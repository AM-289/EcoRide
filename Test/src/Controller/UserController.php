<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/user', name: 'user.')]
//#[IsGranted(['ROLE_USER'])]
class UserController extends AbstractController {

    #[Route(path: '/{slug}', name: 'test', requirements: [ 'slug' => '[a-z0-9-]+'])]
    public function userProfile(CommentRepository $repository, FormFactoryInterface $formFactory, EntityManagerInterface $em, Request $request){

        $test = 'nope';
        $comment = new Comment();
        $comment->setAuthor($this->getUser());
           // ->setProfile($this->$test);
        $form = $formFactory->create(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'Commentaire ajouté');
            //return $this->redirectToRoute('user.profile', ['slug' => $profile->getSlug()]);
            //return $this->redirectToRoute($request->attributes->get('_route', ['slug' => $profile->getSlug()]));
        }

        $user = $this->getUser();

        return $this->render(
            'profile/profile.html.twig', [
                'user' => $user,
                'comments' =>$repository->findAll(),
                'commentForm' => $form,
            ]
        );
    }

}