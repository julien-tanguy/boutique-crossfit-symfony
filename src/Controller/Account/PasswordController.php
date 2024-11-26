<?php

namespace App\Controller\Account;

use App\Entity\User;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class PasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/modifier-mot-de-passe', name: 'app_account_modify_pwd')]
    public function index(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        // Injecter l'utilisateur connecté dans la variable $user
        $user = $this->getuser();

        if ($user instanceof User) {
            // Créer le formulaire : 
            $form = $this->createForm(ChangePasswordType::class, $user, [
                'hasher' => $hasher
            ]);
            // Lui demander d'ecouter la requete :
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->flush();
                $this->addFlash('success', 'Votre mot de passe a été modifié.');
            }
            return $this->render('account/password/index.html.twig', [
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('app_home');
        }
    }
}
