<?php

/*
 * This file is part of the BaitPollBundle package.
 *
 * (c) BAIT s.r.o. <http://www.bait.sk/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bait\PollBundle;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Bait\PollBundle\FormFactory\PollFormFactoryInterface;
use Bait\PollBundle\Model\PollManagerInterface;
use Bait\PollBundle\Model\FieldManager;
use Bait\PollBundle\Model\VoteManagerInterface;
use Bait\PollBundle\Model\VoteGroupManagerInterface;
use Bait\PollBundle\Model\PollInterface;
use Bait\PollBundle\Model\SignedPollInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class responsible for poll management.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
class Poll
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var EngineInterface
     */
    protected $engine;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var PollFormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var PollManagerInterface
     */
    protected $pollManager;

    /**
     * @var VoteManagerInterface
     */
    protected $voteManager;

    /**
     * @var VoteGroupManagerInterface
     */
    protected $voteGroupManager;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var string
     */
    protected $fieldClass;

    /**
     * @var string
     */
    protected $pollClass;

    /**
     * @var string
     */
    protected $cookiePrefix;

    /**
     * @var string
     */
    protected $cookieDuration;

    /**
     * @var string
     */
    protected $uploadDir;

    /**
     * @var string
     */
    protected $theme;

    /**
     * @var boolean
     */
    protected $isActive;

    /**
     * Constructs Poll service.
     *
     * @param Request $request Current request
     * @param EngineInterface $engine Templating engine
     * @param ObjectManager $objectManager Doctrine's object manager
     * @param PollFormFactoryInterface $formFactory Poll form factory
     * @param PollManagerInterface $pollManager Poll manager
     * @param FieldManager $fieldManager Field manager
     * @param VoteManagerInterface $voteManager Vote manager
     * @param VoteGroupManagerInterface $voteGroupManager Vote group manager
     */
    public function __construct(
        Request $request,
        EngineInterface $engine,
        ObjectManager $objectManager,
        PollFormFactoryInterface $formFactory,
        PollManagerInterface $pollManager,
        FieldManager $fieldManager,
        VoteManagerInterface $voteManager,
        VoteGroupManagerInterface $voteGroupManager,
        $securityContext,
        array $options
    )
    {
        $this->request = $request;
        $this->engine = $engine;
        $this->objectManager = $objectManager;
        $this->formFactory = $formFactory;
        $this->pollManager = $pollManager;
        $this->fieldManager = $fieldManager;
        $this->voteManager = $voteManager;
        $this->voteGroupManager = $voteGroupManager;
        $this->securityContext = $securityContext;
        list(
            $this->fieldClass,
            $this->pollClass,
            $this->template,
            $this->theme,
            $this->cookiePrefix,
            $this->cookieDuration,
            $this->uploadDir
        ) = $options;
        $this->isActive = true;
    }

    /**
     * Creates form and validates it or saves data in case some data
     * were already submitted.
     *
     * @param mixed $id Id of poll to be created
     *
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function create($id, Response &$response)
    {
        $this->id = $id;

        $this->poll = $this->pollManager->findOneById($id);

        if (!$this->poll) {
            throw new NotFoundHttpException(
                sprintf("Poll with id '%s' was not found.", $id)
            );
        }

        if (!$this->poll->isActive()) {
            $this->isActive = false;

            return;
        }

        $this->form = $this->formFactory->create($id);
        $formName = $this->form->getName();

        if ($this->request->getMethod() === 'POST' && $this->request->request->has($formName) && !$this->voteGroupManager->hasVoted($this->poll)) {
            $this->form->bindRequest($this->request);

            if ($this->form->isValid()) {
                $data = $this->form->getData();

                $votes = array();

                $voteGroup = $this->voteGroupManager->create($this->poll);

                foreach ($data as $fieldId => $answer) {
                    $fieldId = str_replace('field_', '', $fieldId);

                    $answers = (array) $answer;
                    $field = $this->objectManager->getReference($this->fieldClass, $fieldId);

                    foreach ($answers as $answer) {
                        $vote = $this->voteManager->create($field, $answer, $voteGroup);
                        $votes[] = $vote;
                    }
                }

                $response = new RedirectResponse($this->request->getUri());

                $pollType = $this->poll->getType();

                // Checks if poll type is proper one, defined in PollInterface
                $fieldClassReflection = new \ReflectionClass($this->pollClass);
                $fieldConstants = $fieldClassReflection->getConstants();

                if (!in_array($pollType, $fieldConstants)) {
                    throw new \Exception(sprintf('"%s" is incorrect poll type.', $pollType));
                }

                // Checks what multi-vote prevention mechanisms should be triggered
                // and error that might occur.
                $doPersist = false;

                if ($this->poll instanceof SignedPollInterface) {
                    $isAuthenticated = $this->securityContext->isGranted('IS_AUTHENTICATED_FULLY');

                    if (in_array($pollType, array(PollInterface::POLL_TYPE_USER, PollInterface::POLL_TYPE_MIXED)) && $isAuthenticated) {
                        $user = $this->securityContext->getToken()->getUser();
                        $voteGroup->setAuthor($user);

                        $doPersist = true;
                    }
                } else {
                    if (in_array($pollType, array(PollInterface::POLL_TYPE_USER, PollInterface::POLL_TYPE_MIXED))) {
                        throw new \Exception(sprintf('Poll type is "%s", but your Poll class doesn\'t (%s) implement Bait\PollBundle\Model\SignedPollInterface', $pollType, $this->pollClass));
                    }
                }

                if (in_array($pollType, array(PollInterface::POLL_TYPE_ANONYMOUS, PollInterface::POLL_TYPE_MIXED))) {
                    $cookie = new Cookie(sprintf('%svoted_%s', $this->cookiePrefix, $id), true, time() + $this->cookieDuration);
                    $response->headers->setCookie($cookie);

                    $doPersist = true;
                }

                // Checks if any upload occurred/should have occurred
                // and handles it

                if ($this->fieldManager->hasUploadFileds($this->poll)) {
                    if ("" == $this->uploadDir) {
                        throw new \Exception("You should configure the bait_poll.upload_dir directive in your config.yml");
                    }
                    if (!is_writable($this->uploadDir . '/')) {
                        throw new \Exception(sprintf('"%s" is not a writable folder for uploads.', $this->uploadDir));
                    }

                    $voteGroup = $this->voteGroupManager->save($voteGroup);
                    $folder = $this->uploadDir . '/' . $voteGroup->getId();
                    mkdir($folder);

                    foreach ($data as $fieldName => $field) {
                        if ($field instanceof UploadedFile) {
                            $field->move($folder, $fieldName . '.' . $field->guessExtension());
                        }
                    }
                }

                // If everything went ok, save all votes
                if ($doPersist) {
                    $this->voteGroupManager->save($voteGroup);
                    $this->voteManager->save($votes);
                }
            }
        }
    }

    /**
     * Renders poll form into given template.
     *
     * @param string $template Path to poll template
     *
     * @return string
     */
    public function render($template = null, $theme = null)
    {
        if (!$this->isActive) {
            return null;
        }

        if (!$template) {
            $template = $this->template;
        }

        if (!$theme) {
            $theme = $this->theme;
        }

        $alreadyVoted = $this->voteGroupManager->hasVoted($this->poll);

        $viewData = array(
            'form' => $this->form->createView(),
            'theme' => $theme,
            'request' => $this->request,
            'alreadyVoted' => $alreadyVoted
        );

        if ($alreadyVoted) {
            $fields = $this->poll->getFields();
            $fieldCount = array();

            foreach ($fields as $field) {
                $fieldCount[$field->getId()] = $this->voteManager->countByField($field);
            }

            $viewData['results'] = $fieldCount;
        }

        return $this->engine->render($template, $viewData);
    }
}
