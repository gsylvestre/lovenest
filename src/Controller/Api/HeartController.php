<?php

namespace App\Controller\Api;

use App\Entity\Heart;
use App\Entity\User;
use App\Repository\HeartRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class HeartController extends AbstractController
{
    /**
     * @Route("/api/heart/{id}", name="api_heart_retrieve", methods={"GET"})
     */
    public function retrieve(int $id, HeartRepository $heartRepository, SerializerInterface $serializer): Response
    {
        $heart = $heartRepository->find($id);
        return $this->json($heart, 200, [], ['groups' => 'heart_read']);
    }

    /**
     * @Route("/api/heart/", name="api_heart_retrieve_all", methods={"GET"})
     */
    public function retrieveAll(HeartRepository $heartRepository, SerializerInterface $serializer): Response
    {
        $heart = $heartRepository->findAll();
        return $this->json($heart, 200, [], ['groups' => 'heart_read']);
    }


    /**
     * @Route("/api/heart/", name="api_heart_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        //extrait les données du json reçu
        $receivedJson = $request->getContent();
        $receivedDatas = json_decode($receivedJson);

        //retrouve l'utilisateur qui se font liké (on a reçu son id)
        $sentTo = $userRepository->find($receivedDatas->sentToId);

        if (!$sentTo){
            return $this->json([
                "status" => "error",
                "message" => "Cet utilisateur n'existe pas !"
            ], 400);
        }

        $heart = new Heart();
        $heart->setSentTo($sentTo);
        $heart->setSentDate(new \DateTime());
        $heart->setInitiatedBy($user);
        $heart->setIsReciprocal(false);

        $entityManager->persist($heart);
        $entityManager->flush();

        return $this->json([
            "status" => "ok",
            "message" => "Coeur ajouté !",
            "data" => $heart
        ], 201, [], ["groups" => "heart_read"]);
    }

    /**
     * @Route("/api/heart/{id}", name="api_heart_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {

    }


    /**
     * @Route("/api/heart/{id}", name="api_heart_replace", methods={"PUT"})
     */
    public function replace(int $id): Response
    {

    }
}
