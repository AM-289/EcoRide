<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Profile;
use App\Form\CommentType;
use App\Form\FormListenerFactory;
use App\Form\ProfileType;
use App\Repository\CommentRepository;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use App\Security\Voter\CommentVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Config\TwigConfig;

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
    
    public function __construct(private FormListenerFactory $listenerFactory,
    //private SluggerInterface $slugger
    ){
        
    }

    //)
    #[Route(path: '/{slug}', name: 'profile', requirements: [ 'slug' => '[a-z0-9-]+'])] 
    //#[IsGranted('ROLE_DRIVER')]
    public function driverProfile( ProfileRepository $repository, Security $security, FormFactoryInterface $formFactory, EntityManagerInterface $em, Request $request): Response
    {
        $profile = new Profile();
        $profile->setUser($this->getUser())
            ->setSlug('test-01')
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setCreatedAt(new \DateTimeImmutable());
        //$form = $this->createForm(ProfileType::class, $profile);
        //$form->handleRequest($request);
        //if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($profile);
            $em->flush();
            $this->addFlash('success', 'Voiture créée');
            //return $this->redirectToRoute('user.car.index');
        //}

        //$profile = $repository->findOneBy(['slug' => $slug]);

        return $this->render('profile/profile.html.twig', [
            'profile' => $profile
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