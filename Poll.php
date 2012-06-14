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
use Bait\PollBundle\Model\VoteManagerInterface;
use Bait\PollBundle\Model\PollInterface;

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
    protected $cookiePrefix;

    /**
     * @var string
     */
    protected $cookieDuration;

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
     * @param VoteManagerInterface $voteManager Vote manager
     */
    public function __construct(
        Request $request,
        EngineInterface $engine,
        ObjectManager $objectManager,
        PollFormFactoryInterface $formFactory,
        PollManagerInterface $pollManager,
        VoteManagerInterface $voteManager,
        $securityContext,
        array $options
    )
    {
        $this->request = $request;
        $this->engine = $engine;
        $this->objectManager = $objectManager;
        $this->formFactory = $formFactory;
        $this->pollManager = $pollManager;
        $this->voteManager = $voteManager;
        $this->securityContext = $securityContext;
        list(
            $this->fieldClass,
            $this->template,
            $this->theme,
            $this->cookiePrefix,
            $this->cookieDuration
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

        if ($this->request->getMethod() === 'POST' && $this->request->request->has($formName) && !$this->voteManager->hasVoted($this->poll)) {
            $this->form->bindRequest($this->request);

            if ($this->form->isValid()) {
                $data = $this->form->getData();

                $votes = array();

                foreach ($data as $fieldId => $value) {
                    $field = str_replace('field_', '', $fieldId);

                    $values = (array) $value;
                    $field = $this->objectManager->getReference($this->fieldClass, $field);

                    foreach ($values as $value) {
                        $vote = $this->voteManager->create($field, $value);
                        $votes[] = $vote;
                    }
                }

                try {
                    $response = new RedirectResponse($this->request->getUri());

                    $isAuthenticated = $this->securityContext->isGranted('IS_AUTHENTICATED_FULLY');
                    $pollType = $this->poll->getType();
                    $doPersist = false;

                    if ((PollInterface::POLL_TYPE_USER === $pollType || PollInterface::POLL_TYPE_MIXED) && $isAuthenticated) {
                        $user = $this->securityContext->getToken()->getUser()->getId();

                        $votes = array_map(function ($vote) use ($user) {
                            $vote->setAuthor($user);
                        }, $votes);

                        $doPersist = true;
                    }

                    if (PollInterface::POLL_TYPE_ANONYMOUS === $pollType || PollInterface::POLL_TYPE_MIXED) {
                        $cookie = new Cookie(sprintf('%svoted_%s', $this->cookiePrefix, $id), true, time() + $this->cookieDuration);
                        $response->headers->setCookie($cookie);

                        $doPersist = true;
                    }

                    if ($doPersist) {
                        $this->voteManager->save($votes);
                    }

                } catch (\Exception $e) {
                    throw $e;
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

        $alreadyVoted = $this->voteManager->hasVoted($this->poll);

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
