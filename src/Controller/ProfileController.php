<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\ProfilePicture;
use App\Entity\SearchCriterias;
use App\Entity\User;
use App\Form\ProfilePictureType;
use App\Form\ProfileType;
use App\Form\SearchCriteriasType;
use App\Repository\UserRepository;
use claviska\SimpleImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\ByteString;

class ProfileController extends AbstractController
{

    /**
     * @Route("/profil/modification", name="profile_edit")
     */
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $profile = $user->getProfile();

        //si l'utilisateur n'a pas encore de profil, on en crée un tout neuf
        if (!$profile){
            $profile = new Profile();
            $profile->setUser($user);
            $profile->setDateCreated(new \DateTime());
        }

        $profileForm = $this->createForm(ProfileType::class, $profile);
        $profileForm->handleRequest($request);

        if ($profileForm->isSubmitted() && $profileForm->isValid()){
            $entityManager->persist($profile);
            $entityManager->flush();

            $this->addFlash("success", "Votre profil a bien été enregistré !");
            return $this->redirectToRoute('profile_picture');
        }

        return $this->render('profile/edit.html.twig', [
            'profileForm' => $profileForm->createView()
        ]);
    }

    /**
     * @Route("/criteres-de-recherche/modification", name="profile_criterias_edit")
     */
    public function criteriasEdit(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $searchCriterias = $user->getSearchCriterias();

        //si l'utilisateur n'a pas encore de critères, on en crée un tout neuf
        if (!$searchCriterias){
            $searchCriterias = new SearchCriterias();
            $searchCriterias->setUser($user);
        }

        $searchCriteriasForm = $this->createForm(SearchCriteriasType::class, $searchCriterias);
        $searchCriteriasForm->handleRequest($request);

        if ($searchCriteriasForm->isSubmitted() && $searchCriteriasForm->isValid()){
            $entityManager->persist($searchCriterias);
            $entityManager->flush();

            $this->addFlash("success", "Vos critères ont bien été enregistrés !");
            return $this->redirectToRoute('profile_view', ['username' => $user->getUsername()]);
        }

        return $this->render('profile/criterias_edit.html.twig', [
            'searchCriteriasForm' => $searchCriteriasForm->createView()
        ]);
    }


    /**
     * @Route("/profil/photo", name="profile_picture")
     */
    public function profilePicture(Request $request, EntityManagerInterface $entityManager): Response
    {
        //todo: gérer l'image existante

        /** @var User $user */
        $user = $this->getUser();

        $profilePicture = new ProfilePicture();
        $pictureForm = $this->createForm(ProfilePictureType::class, $profilePicture);

        $pictureForm->handleRequest($request);
        if ($pictureForm->isSubmitted() && $pictureForm->isValid()){
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $pictureForm->get('pic')->getData();

            //génère un nom de fichier sécuritaire
            $newFilename = ByteString::fromRandom(30) . "." . $uploadedFile->guessExtension();

            //déplace le fichier dans mon répertoire public/ avant sa destruction
            //upload_dir est défini dans config/services.yaml
            try {
                $uploadedFile->move($this->getParameter('upload_dir'), $newFilename);
            } catch (\Exception $e){
                dd($e->getMessage());
            }

            //redimensionne l'image avec SimpleImage
            $simpleImage = new SimpleImage();
            $simpleImage->fromFile($this->getParameter('upload_dir') . "/$newFilename")
                ->bestFit(400, 400)
                ->toFile($this->getParameter('upload_dir') . "/big/$newFilename")
                ->thumbnail(140, 140)
                ->toFile($this->getParameter('upload_dir') . "/small/$newFilename");

            //détruit l'originale
            unlink($this->getParameter('upload_dir') . "/$newFilename");

            //hydrate et sauvegarde les données de l'image
            $profilePicture->setFilename($newFilename);
            $profilePicture->setDateCreated(new \DateTime());
            //associe cette image à l'utilisateur connecté !
            $profilePicture->setUser($user);

            $entityManager->persist($profilePicture);
            $entityManager->flush();

            $this->addFlash('success', 'Photo ajoutée !');
            return $this->redirectToRoute('profile_criterias_edit');
        }

        return $this->render('profile/picture.html.twig', [
            'pictureForm' => $pictureForm->createView()
        ]);
    }

    /**
     * @Route("/profil/voir/{username}", name="profile_view", requirements={"username": "[a-zA-Z0-9_]+"})
     */
    public function view(string $username, UserRepository $userRepository)
    {
        $foundUser = $userRepository->findOneBy(['username' => $username]);
        if (!$foundUser){
            throw $this->createNotFoundException("Ce profil n'existe pas !");
        }

        return $this->render('profile/view.html.twig', [
            'foundUser' => $foundUser
        ]);
    }

    /**
     * @Route("/profil/suggestions", name="profile_list")
     */
    public function list(UserRepository $userRepository)
    {
        /** @var User $user */
        $user = $this->getUser();

        $results = $userRepository->findSuggestionsForUser($user);

        return $this->render('profile/list.html.twig', [
            'users' => $results
        ]);
    }
}
