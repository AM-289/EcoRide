<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Profile;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\FormListenerFactory;
use App\Form\ProfileType;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use App\Security\Voter\CommentVoter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ObjectMapper\Attribute\Map;


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
    
    public function __construct(private FormListenerFactory $listenerFactory){
        
    }

    #[Route(path: '/', name: 'create')] 
    
    public function createProfile(EntityManagerInterface $em, UserRepository $userRepository, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, Request $request, string $slug, int $user_id): Response
    {
        //#[Map(source: \App\Entity\User::class,  expr: 'repository.findBy({"username": username}')] {}
        //$slug = $this->$entityManager->getRepository(User::class)->find($userId);
        //$slug = $this->getUser(getUsername());
        /*$slug = $this->$userRepository->findOneBy(['id' => $Id]);*/
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $profileslug = ($user->getUsername());
        $profile = (new Profile())
                //->setUser($this->getUser())
                //->setCreatedAt(new \DateTimeImmutable())
                //->setUpdatedAt(new \DateTimeImmutable())
                ->setSlug($this->$profileslug);
        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);
        $em->persist($profile);
        $em->flush();

        return $this->redirectToRoute('home');
    }

    #[Route(path: '/{slug}', name: 'profile', requirements: [ 'slug' => '[a-z0-9-]+'])]
    public function showProfile(Profile $profile, CommentRepository $repository, FormFactoryInterface $formFactory, EntityManagerInterface $em, Request $request): Response {
        
        /*$userProfilePage = $this->generateUrl('user.profile', [
            'slug' => $profile->getSlug()
        ]);*/
        //$slug = $this->$profile->getSlug();
        $comment = new Comment();
        $comment->setAuthor($this->getUser())
                ->setProfile($profile);
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