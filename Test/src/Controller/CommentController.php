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

#[Route(path: '/comment', name: 'comment.')]
class CommentController extends AbstractController {

    #[Route(path: '/', name: 'index', methods: ['GET', 'POST'])] 
    #[IsGranted(CommentVoter::CREATE)]
    public function driverProfile(CommentRepository $repository, Security $security, FormFactoryInterface $formFactory, EntityManagerInterface $em, Request $request): Response
    {
        $comment = new Comment();
        $comment->setAuthor($this->getUser());
        $form = $formFactory->create(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'Commentaire ajouté');
        }
        return $this->render('comment/comment.html.twig', [
            'commentForm' => $form,
            'comments' =>$repository->findAll()
        ]);
    }

    #[Route('/{id}', name: 'edit', requirements: ['id' =>Requirement::DIGITS], methods: ['GET', 'POST'])]
    #[IsGranted(CommentVoter::EDIT, subject: 'comment')]
    public function edit(Comment $comment, Request $request, EntityManagerInterface $en) {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $en->flush();
            $this->addFlash('success', 'Commentaire modifié');
            return $this->redirectToRoute('comment.index');
        }
        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form
        ]);
    }
}