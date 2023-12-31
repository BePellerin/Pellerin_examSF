<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
// use App\Form\UserEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
// use Symfony\Component\HttpFoundation\File\UploadedFile;
// use App\Service\FileUploader;

// #[isGranted("ROLE_RH")]
#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/index', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    #[Route('/{id}/edit', name: 'app_user_edit', requirements: ["id" => "\d+"], methods: ['GET', 'POST'])]
    #[isGranted("ROLE_RH")]
    public function new(User $user = null, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $template= "user/edit.html.twig";
        if ($user === null) {
            $user = new User();
            $template = "user/new.html.twig";
        }
        
        $r = $user->getId() === null ? true : false;
        $form = $this->createForm(UserType::class, $user, ["requiredImg"=>$r]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $form->get('picture')->getData();
            if ($picture) {
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $picture->guessExtension();
                try {
                    $picture->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $errorMessage = 'Il y a eu une erreur pendant le téléchargement ! Recommencez ;)';
                    return $this->renderForm('user/new.html.twig', [
                        'user' => $user,
                        'form' => $form,
                        'error' => $errorMessage,
                    ]);
                }
                $user->setPicture($newFilename);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }
       
            return $this->renderForm($template, [
                'user' => $user,
                'form' => $form,
            ]);
        
    }


    #[Route('/{id}', name: 'app_user_show', requirements: ["id" => "\d+"], methods: ['GET'])]
    public function show(User $user, $id): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    #[isGranted("ROLE_RH")]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
