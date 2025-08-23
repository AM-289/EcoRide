<?php

namespace App\Controller\API;

use App\DTO\PaginationDTO;
use App\Entity\Ride;
use App\Repository\RideRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class RidesController extends AbstractController {
    
    #[Route("/api/rides", methods: "GET")]
    public function index(RideRepository $repository, #[MapQueryString()] PaginationDTO $paginationDTO) {
        $rides = $repository->paginateRide($paginationDTO->page);
        return $this->json($rides, 200, [], [
            'groups' => ['rides.index']
        ]);
    }

    #[Route("/api/rides/{id}", requirements: ['id' => Requirement::DIGITS])]
    public function show(Ride $ride) {
        return $this->json($ride, 200, [], [
            'groups' => ['rides.index', 'rides.show']
        ]);
    }

    #[Route("/api/rides", methods: ['POST'])]
    public function create(Request $request, 
    //MapRequestPayload -> paylood of json is going to be injected into ride
    #[MapRequestPayload(
        serializationContext: [
            //control wath the user can enter
            'groups' => ['rides.create']
        ]
    )]
    Ride $ride, 
    EntityManagerInterface $em ) {
        $em->persist($ride);
        $em->flush();
        return $this->json($ride, 200, [], [
            'groups' => ['rides.index', 'rides.show']
        ]);
    }
        
}