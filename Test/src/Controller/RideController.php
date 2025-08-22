<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Form\RideType;
use App\Repository\RideRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/trajet', name: 'ride')]
final class RideController extends AbstractController
{

    public function __construct(private RideRepository $rideRepository)
    {
    }


    #[Route('/', name: '.search.result')]
    public function index(Request $request, RideRepository $repository) : Response
    {
        $page = $request->query->getInt('page', 1);
        $rides = $repository->paginateRide($page);

        return $this->render('ride/index.html.twig', [
            'controller_name' => 'RideController',
            'rides' => $rides
        ]);
    }

    #[Route('/{slug}-{id}', name: '.details', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function rideDetails(Request $request, Ride $ride): Response {
        return $this->render('ride/ride.details.html.twig', [
            'ride' => $ride
        ]);
    }
    
    #[Route('/creer_trajet', name : '.create')]
    public function create(Request $request, EntityManagerInterface $em, FormFactoryInterface $formFactory) {
        $ride = new Ride();
        $form = $formFactory->create(RideType::class, $ride);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($ride);
            $em->flush();
            $this->addFlash('success', 'Trajet créé');
            return $this->redirectToRoute('ride.search.result');
        }
        return $this->render('ride/create.html.twig', [
            'rideForm' => $form
        ]);
    }

    #[Route('/{slug}-{id}/editer', name: '.edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function editRide (Ride $ride, Request $request, EntityManagerInterface $em, FormFactoryInterface $formFactory) {
        $form = $formFactory->create(RideType::class, $ride);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Les modifications ont bien été enregistrée');
            return $this->redirectToRoute('ride.search.result');
        }
        return $this->render('ride/edit.html.twig', [
            'ride' => $ride,
            'rideForm' => $form
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    public function removeRide(Ride $ride, EntityManagerInterface $em) {
        $em->remove($ride);
        $em->flush();
        $this->addFlash('success', 'Tajet supprimé');
        return $this->redirectToRoute('ride.search.result');
    }
}