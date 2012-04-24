<?php

namespace Bait\PollBundle;

/**
 * Class responsible for poll management.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
class Poll
{
    protected $request;

    protected $formFactory;

    protected $pollManager;

    protected $voteManager;

    public function __construct($request, $formFactory, $pollManager, $voteManager)
    {
        $this->request = $request;
        $this->formFactory = $formFactory;
        $this->pollManager = $pollManager;
        $this->voteManager = $voteManager;
    }

    public function create($id)
    {
        $poll = $this->pollManager->findOneById($id);

        if (!$poll) {
            throw new \Exception();
        }

        $form = $this->formFactory->createForm($id);
        $formName = $form->getName();

        if ($this->request->getMethod() === 'POST' && $this->request->request->has($formName)) {
            $form->bindRequest($this->request);

            if ($form->isValid()) {
                $data = $form->getData();

                $votes = array();

                foreach ($data as $fieldId => $value) {
                    $field = str_replace('field_', '', $fieldId);

                    $field = $this->get('doctrine.orm.entity_manager')->getReference('AcmeDemoBundle:PollField', $field);
                    $vote = $this->voteManager->create($field, $value);

                    $votes[] = $vote;
                }

                $this->voteManager->save($votes);
            }
        }
    }

    public function createView()
    {

    }
}
