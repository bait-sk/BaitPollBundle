<?php

namespace Bait\PollBundle;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Bait\PollBundle\FormFactory\PollFormFactoryInterface;
use Bait\PollBundle\Model\PollManagerInterface;
use Bait\PollBundle\Model\VoteManagerInterface;

/**
 * Class responsible for poll management.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
class Poll
{
    protected $request;

    protected $engine;

    protected $template;

    protected $entityManager;

    protected $formFactory;

    protected $pollManager;

    protected $voteManager;

    protected $fieldClass;
    public function __construct(
        Request $request,
        EngineInterface $engine,
        ObjectManager $entityManager,
        PollFormFactoryInterface $formFactory,
        PollManagerInterface $pollManager,
        VoteManagerInterface $voteManager,
        $template,
        $fieldClass
    )
    {
        $this->request = $request;
        $this->engine = $engine;
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->pollManager = $pollManager;
        $this->voteManager = $voteManager;
        $this->template = $template;
        $this->fieldClass = $fieldClass;
    }

    public function create($id)
    {
        $poll = $this->pollManager->findOneById($id);

        if (!$poll) {
            throw new NotFoundHttpException(
                sprintf("Poll with id '%s' was not found.", $id)
            );
        }

        $this->form = $this->formFactory->createForm($id);
        $formName = $this->form->getName();

        if ($this->request->getMethod() === 'POST' && $this->request->request->has($formName)) {
            $this->form->bindRequest($this->request);

            if ($this->form->isValid()) {
                $data = $this->form->getData();

                $votes = array();

                foreach ($data as $fieldId => $value) {
                    $field = str_replace('field_', '', $fieldId);

                    $field = $this->entityManager->getReference($this->fieldClass, $field);
                    $vote = $this->voteManager->create($field, $value);

                    $votes[] = $vote;
                }

                $this->voteManager->save($votes);
            }
        }
    }

    public function render($template = null)
    {
        if (!$template) {
            $template = $this->template;
        }

        $viewData = array('form' => $this->form->createView(), 'request' => $this->request);

        return $this->engine->render($this->template, $viewData);
    }
}
