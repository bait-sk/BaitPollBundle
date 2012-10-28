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
use Bait\PollBundle\Model\FieldInterface;
use Bait\PollBundle\Model\AnswerManagerInterface;
use Bait\PollBundle\Model\AnswerGroupManagerInterface;
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
     * @var AnswerManagerInterface
     */
    protected $answerManager;

    /**
     * @var AnswerGroupManagerInterface
     */
    protected $answerGroupManager;

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
     * @param AnswerManagerInterface $answerManager Answer manager
     * @param AnswerGroupManagerInterface $answerGroupManager Answer group manager
     */
    public function __construct(
        Request $request,
        EngineInterface $engine,
        ObjectManager $objectManager,
        PollFormFactoryInterface $formFactory,
        PollManagerInterface $pollManager,
        FieldManager $fieldManager,
        AnswerManagerInterface $answerManager,
        AnswerGroupManagerInterface $answerGroupManager,
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
        $this->answerManager = $answerManager;
        $this->answerGroupManager = $answerGroupManager;
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

        if (!$this->poll || $this->poll->getDeletedAt()) {
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

        if ($this->request->getMethod() === 'POST' && $this->request->request->has($formName) && !$this->answerGroupManager->hasAnswered($this->poll)) {
            $this->form->bindRequest($this->request);

            if ($this->form->isValid()) {
                $data = $this->form->getData();

                $answers = array();

                $answerGroup = $this->answerGroupManager->create($this->poll);

                foreach ($data as $fieldId => $userAnswer) {
                    $fieldId = str_replace('field_', '', $fieldId);
                    $field = $this->objectManager->getReference($this->fieldClass, $fieldId);
                    if ($field->getType() === FieldInterface::TYPE_FILE) {
                        $userAnswers = (array) $userAnswer->getClientOriginalName();
                    } else {
                        $userAnswers = (array) $userAnswer;
                    }


                    foreach ($userAnswers as $userAnswer) {
                        $answer = $this->answerManager->create($field, $userAnswer, $answerGroup);
                        $answers[] = $answer;
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

                // Checks what multi-answer prevention mechanisms should be triggered
                // and error that might occur.
                $doPersist = false;

                if ($this->poll instanceof SignedPollInterface) {
                    $isAuthenticated = $this->securityContext->isGranted('IS_AUTHENTICATED_FULLY');

                    if (in_array($pollType, array(PollInterface::POLL_TYPE_USER, PollInterface::POLL_TYPE_MIXED)) && $isAuthenticated) {
                        $user = $this->securityContext->getToken()->getUser();
                        $answerGroup->setAuthor($user);

                        $doPersist = true;
                    }
                } else {
                    if (in_array($pollType, array(PollInterface::POLL_TYPE_USER, PollInterface::POLL_TYPE_MIXED))) {
                        throw new \Exception(sprintf('Poll type is "%s", but your Poll class (%s) doesn\'t implement Bait\PollBundle\Model\SignedPollInterface', $pollType, $this->pollClass));
                    }
                }

                if (in_array($pollType, array(PollInterface::POLL_TYPE_ANONYMOUS, PollInterface::POLL_TYPE_MIXED))) {
                    $cookie = new Cookie(sprintf('%sanswered_%s', $this->cookiePrefix, $id), true, time() + $this->cookieDuration);
                    $response->headers->setCookie($cookie);

                    $doPersist = true;
                }

                // Checks if any upload occurred/should have occurred
                // and handles it
                if ($this->fieldManager->hasUploadFields($this->poll)) {
                    if ("" == $this->uploadDir) {
                        throw new \Exception("You should configure the bait_poll.upload_dir directive in your config.yml");
                    }

                    if (!is_writable($this->uploadDir . '/')) {
                        throw new \Exception(sprintf('"%s" is not a writable folder for uploads.', $this->uploadDir));
                    }

                    $answerGroup = $this->answerGroupManager->save($answerGroup);
                    $folder = $this->uploadDir . '/' . $this->poll->getId() . '/answergroups/' . $answerGroup->getId();
                    mkdir($folder, 0777, true);

                    foreach ($data as $fieldName => $field) {
                        if ($field instanceof UploadedFile) {
                            $field->move($folder, $fieldName . '.' . $field->guessExtension());
                        }
                    }
                }

                // If everything went ok, save all answers
                if ($doPersist) {
                    $this->answerGroupManager->save($answerGroup);
                    $this->answerManager->save($answers);
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

        $alreadyAnswered = $this->answerGroupManager->hasAnswered($this->poll);

        $viewData = array(
            'form' => $this->form->createView(),
            'theme' => $theme,
            'request' => $this->request,
            'alreadyAnswered' => $alreadyAnswered,
            'hasUploadFields' => $this->fieldManager->hasUploadFields($this->poll)
        );

        if ($this->poll->isAnswersVisible()) {
            $fields = $this->poll->getFields();
            $results = array();

            foreach ($fields as $field) {
                if ($field->isStandalone() && $field->hasChildren()) {
                    $children = $field->getChildren();

                    $answersCount = $this->answerManager->countVotesOf($children);
                    $results[$field->getTitle()] = $answersCount;
                }
            }

            $viewData['results'] = $results;
        }

        return $this->engine->render($template, $viewData);
    }
}
