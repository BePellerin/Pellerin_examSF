<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
// use Symfony\Bridge\Doctrine\ManagerRegistry;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class HomeController extends AbstractController
{
    #[Route('/home', name: 'liste_user')]
    #[IsGranted('ROLE_USER')]
    public function listeArticles(UserRepository $userRepository): Response
    {
        
        $users = $userRepository->findAll();
        foreach ($users as $user) {
            $dateSortie = $user->getDatesortie();
            if ($dateSortie instanceof \DateTimeInterface) {
                $formattedDateSortie = $dateSortie->format('d-m-Y');
                $user->setDatesortie($formattedDateSortie);
            }
        }
        return $this->render('home/index.html.twig', [
            'users' => $users
        ]);
    }


    #[Route('/{id}', name: 'vue_user', requirements: ["id" => "\d+"])]
    #[isGranted("ROLE_USER")]
    public function vueUser(UserRepository $userRepository, $id): Response
    {
        $user = $userRepository->find($id);
        return $this->render('home/vue.html.twig', [
            'user' => $user,
        ]);
    }



    #[Route('/ajout', name: 'ajout_personne')]
    #[isGranted("ROLE_RH")]
    public function create(Request $request): Response
    {
        // Créer une nouvelle instance de l'entité User
        $user = new User();

        // Créer le formulaire en utilisant la méthode buildForm que vous avez fournie
        $form = $this->createForm(UserType::class, $user);

        // Gérer les soumissions de formulaire
        $form->handleRequest($request);

        // Vérifier si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Vous pouvez enregistrer l'utilisateur en base de données ici
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($user);
            // $entityManager->flush();

            // Rediriger vers une autre page, par exemple la page d'accueil
            return $this->redirectToRoute('homepage');
        }

        // Renvoyer la vue avec le formulaire à afficher
        return $this->render('user/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}