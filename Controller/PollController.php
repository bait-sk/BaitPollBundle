<?php

namespace Bait\PollBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PollController extends Controller
{
    public function showAction(Request $request, Request $parentRequest, $id)
    {
        $form = $this->container->get('bait_poll.form_factory')->createForm($id);
        $formName = $form->getName();

        if ($request->getMethod() === 'POST' && $request->request->has($formName)) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $voteManager = $this->container->get('bait_poll.vote.manager');
                $poll = $this->container->get('bait_poll.poll.manager')->findOneById($id);

                $data = $form->getData();

                $votes = array();

                foreach ($data as $fieldId => $value) {
                    $field = str_replace('field_', '', $fieldId);

                    $field = $this->get('doctrine.orm.entity_manager')->getReference('AcmeDemoBundle:PollField', $field);
                    $vote = $voteManager->create($field, $value);

                    $votes[] = $vote;
                }

                $voteManager->save($votes);
            }
        }

        return $this->render('BaitPollBundle:Poll:show.html.twig', array(
            'id' => $id,
            'form' => $form->createView(),
            'parentRequest' => $parentRequest,
        ));
    }
}
