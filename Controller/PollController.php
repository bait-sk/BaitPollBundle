<?php

namespace Bait\PollBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PollController extends Controller
{
    public function showAction(Request $request, Request $parentRequest, $id)
    {
        $form = $this->container->get('bait_poll.form_factory')->createForm($id);

        if ($request->getMethod() === 'POST') {
            $form->bindRequest($request);
        }

        return $this->render('BaitPollBundle:Poll:show.html.twig', array(
            'id' => $id,
            'form' => $form->createView(),
            'parentRequest' => $parentRequest,
        ));
    }

    public function voteAction(Request $request, $id)
    {
        $form = $this->container->get('bait_poll.form_factory')->createForm($id);
        $form->bindRequest($request);

        return $this->forward('BaitPollBundle:Poll:show', array('request' => $request, 'id' => $id));
    }
}
