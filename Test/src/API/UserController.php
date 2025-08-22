<?php 

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;


class UserController extends AbstractController{
    
    #[Route('/api/me')]
    //#[IsGranted("ROLE_USER")]
    public function me() {
        return $this->json(['message'=> 'hi']);
        //return $this->json($this->getUser());
    }
}