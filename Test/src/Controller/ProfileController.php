<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Profile;
use App\Form\CommentType;
use App\Form\FormListenerFactory;
use App\Security\Voter\CommentVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route(path: '/profile', name: 'user.')]
//#[IsGranted(['ROLE_USER'])]
class ProfileController extends AbstractController {
   /* #[Route(path: '/{slug}', name: '...profile')]
    public function userProfile(UserRepository $repository, Security $security): Response
    {
        $userId = $security->getUser()->getUserIdentifier();
        $user = $repository->findAll($userId);

        return $this->render('profile/user.html.twig', [
            'user' => $user,
        ]);
    }*/
    
    public function __construct(private FormListenerFactory $listenerFactory,){
        
    }

    #[Route(path: '/', name: 'create')] 
    public function createProfile(EntityManagerInterface $em): Response
    {
        $profile = (new Profile())
                ->setUser($this->getUser())
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable())
                ->setSlug($this->listenerFactory->autoSlug('username'));
        
            $em->persist($profile);
            $em->flush();

        return $this->redirectToRoute('home');
    }

    #[Route(path: '/{slug}', name: 'profile', requirements: [ 'slug' => '[a-z0-9-]+'])]
    public function showProfile(){
        
        //$profile = $this->getProfile();

        return $this->render(
            'profile/profile.html.twig'
        );
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