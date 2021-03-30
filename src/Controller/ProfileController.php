<?php

namespace App\Controller;

use App\Entity\ProfilePicture;
use App\Form\ProfilePictureType;
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
     * @Route("/profil/photo", name="profile_picture")
     */
    public function profilePicture(Request $request, EntityManagerInterface $entityManager): Response
    {
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
                ->bestFit(1000, 1000)
                ->toFile($this->getParameter('upload_dir') . "/big/$newFilename")
                ->bestFit(300, 300)
                ->toFile($this->getParameter('upload_dir') . "/small/$newFilename");

            //hydrate et sauvegarde les données de l'image
            //todo: on devrait ici associer cette image à l'utilisateur connecté !
            $profilePicture->setFilename($newFilename);
            $profilePicture->setDateCreated(new \DateTime());

            $entityManager->persist($profilePicture);
            $entityManager->flush();
        }

        return $this->render('profile/picture.html.twig', [
            'pictureForm' => $pictureForm->createView()
        ]);
    }
}
