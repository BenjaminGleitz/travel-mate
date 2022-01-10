<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/event/", name="event_")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    /**
     * @Route("/{id}", name="show", requirements={"id":"\d+"})
     */
    public function show(int $id, EventRepository $eventRepository): Response
    {


        $eventById = $eventRepository->find($id);

        return $this->render('event/show.html.twig', [
            'controller_name' => 'EventController',
            'event' => $eventById,
        ]);
    }
}
