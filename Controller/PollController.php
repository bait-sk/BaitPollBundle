<?php

namespace Bait\PollBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PollController extends Controller
{
    public function showAction($id)
    {
        $form = $this->container->get('bait_poll.form_factory')->createForm($id);

        return $this->render('BaitPollBundle:Poll:show.html.twig', array(
            'id' => $id,
            'form' => $form->createView(),
        ));
    }

    public function voteAction(Request $request, $id)
    {
        $form = $this->container->get('bait_poll.form_factory')->createForm($id);
        $form->bindRequest($request);

        return $this->forward('BaitPollBundle:Poll:show', array('request' => $request, 'id' => $id));
    }
}
