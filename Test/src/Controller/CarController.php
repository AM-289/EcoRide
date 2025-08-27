<?php

namespace App\Controller;

use App\Form\CarType;
use App\Entity\Car;
use App\Repository\CarRepository;
use App\Security\Voter\CarVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route("/user/car", name: 'user.car.')]
//#[IsGranted('ROLE_USER')]
class CarController extends AbstractController {

    #[Route(name: 'index')]
    public function index(CarRepository $repository, Security $security) {
        $userID = $security->getUser()->getUserIdentifier();
        
        return $this->render('car/index.html.twig', [
            /*'cars' =>$repository->findAll()*/
            'cars' =>$repository->findAllWithCount()
        ]);

    }

    #[Route('/create', name: 'create')]
    #[IsGranted('ROLE_DRIVER')]
    public function create(Request $request, EntityManagerInterface $en) {
        $car = new Car();
        $car->setDriver($this->getUser());
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $en->persist($car);
            $en->flush();
            $this->addFlash('success', 'Voiture créée');
            return $this->redirectToRoute('user.car.index');
        }
        return $this->render('car/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'edit', requirements: ['id' =>Requirement::DIGITS], methods: ['GET', 'POST'])]
    #[IsGranted(CarVoter::EDIT, subject: 'car')]
    public function edit(Car $car, Request $request, EntityManagerInterface $en) {
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $en->flush();
            $this->addFlash('success', 'Voiture modifiée');
            return $this->redirectToRoute('user.car.index');
        }
        return $this->render('car/edit.html.twig', [
            'car' => $car,
            'form' => $form
        ]);
    }
    
    #[Route('/{id}/remove', name: 'remove', requirements: ['id' =>Requirement::DIGITS], methods: 'DELETE')]
    #[IsGranted(CarVoter::EDIT, subject: 'car')]
    public function remove(Car $car, EntityManagerInterface $en) {
        $en->remove($car);
        $en->flush();
        $this->addFlash('success', 'Voiture supprimée');
        return $this->redirectToRoute('car.index');
    }
}