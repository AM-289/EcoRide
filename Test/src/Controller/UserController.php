<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use App\Security\Voter\CommentVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Config\TwigConfig;

#[Route(path: '/profile', name: 'user.')]
//#[IsGranted(['ROLE_USER'])]
class UserController extends AbstractController {
   /* #[Route(path: '/{slug}', name: '...profile')]
    public function userProfile(UserRepository $repository, Security $security): Response
    {
        $userId = $security->getUser()->getUserIdentifier();
        $user = $repository->findAll($userId);

        return $this->render('profile/user.html.twig', [
            'user' => $user,
        ]);
    }*/
    
    //requirements: [ 'slug' => '[a-z0-9-]+'])
    #[Route(path: '/', name: 'profile')] 
    //#[IsGranted('ROLE_DRIVER')]
    public function driverProfile(CommentRepository $repository, Security $security, FormFactoryInterface $formFactory, EntityManagerInterface $em, Request $request): Response
    {
        //$userId = $security->getUser()->getUserIdentifier();
        //$user = $repository->findAll($userId);

        $comment = new Comment();
        $form = $formFactory->create(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'Trajet créé');
        }
        return $this->render('profile/driver.html.twig', [
            //'user' => $user,
            'commentForm' => $form,
            'comments' =>$repository->findAll()
        ]);
    }

    #[Route('/{id}', name: 'comment.edit', requirements: ['id' =>Requirement::DIGITS], methods: ['GET', 'POST'])]
    #[IsGranted(CommentVoter::EDIT, subject: 'comment')]
    public function edit(Comment $comment, Request $request, EntityManagerInterface $en) {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $en->flush();
            $this->addFlash('success', 'Commentaire modifié');
            return $this->redirectToRoute('user.profile');
        }
        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form
        ]);
    }

    /*#[Route(path: '/{slug}/editer', name: 'user.edit', requirements: [ 'slug' => '[a-z0-9-]+'])]
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
    }*/
}