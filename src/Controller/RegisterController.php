<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        // Créer le form grace a la class RegisterUserType :
        $form = $this->createForm(RegisterUserType::class, $user);
        // Écoute la request avant d'aller plus loin : 
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // Fige la data et la prepare a etre creer :
            $entityManager->persist($user);
            // Prend l'objet que tu a figer et enregistre le en bdd
            $entityManager->flush();
            // Creation message de validation : 
            $this->addFlash('success', 'Bienvenue dans la team :)');
            // Rediriger vers loggin :
            return $this->redirectToRoute('app_login');
        }
        return $this->render('register/index.html.twig',[
            // Créer la vue du formulaire et la passer en paramétre a ma vue :
            'form' => $form->createView()
        ]);
    }
}
