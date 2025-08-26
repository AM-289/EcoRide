<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Config\TwigConfig;

#[Route(path: '/profile', name: 'user.')]
#[IsGranted(['ROLE_USER'])]
class ProfileController extends AbstractController {
    #[Route(path: '/{slug}', name: '...profile')]
    public function userProfile(UserRepository $repository, Security $security): Response
    {
        $userId = $security->getUser()->getUserIdentifier();
        $user = $repository->findAll($userId);

        return $this->render('profile/user.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route(path: '/{slug}', name: 'profile', requirements: [ 'slug' => '[a-z0-9-]+'])]
    #[IsGranted('ROLE_DRIVER')]
    public function driverProfile(UserRepository $repository, Security $security, TwigConfig $twig): Response
    {
        $userId = $security->getUser()->getUserIdentifier();
        $user = $repository->findAll($userId);

        

        return $this->render('profile/driver.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route(path: '/{slug}/editer', name: 'user.edit', requirements: [ 'slug' => '[a-z0-9-]+'])]
    #[IsGranted('ROLE_USER')]
    public function userProfileEdit(UserRepository $repository, Security $security): Response
    {
        $userId = $security->getUser()->getUserIdentifier();
        $user = $repository->findAll($userId);

        return $this->render('profile/edit.user.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route(path: '/{slug}/editer', name: 'profile.edit', requirements: [ 'slug' => '[a-z0-9-]+'])]
    #[IsGranted('ROLE_DRIVER')]
    public function driverProfileEdit(UserRepository $repository, Security $security): Response
    {
        $userId = $security->getUser()->getUserIdentifier();
        $user = $repository->findAll($userId);

        return $this->render('profile/edit.driver.html.twig', [
            'user' => $user,
        ]);
    }
}