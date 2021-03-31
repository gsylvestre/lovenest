<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

class UserRoleUpdater
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;
    /*** @var FlashBagInterface */
    private FlashBagInterface $flashBag;
    /*** @var TokenStorageInterface */
    private $tokenStorage;

    public function __construct(
        EntityManagerInterface $entityManager,
        FlashBagInterface $flashBag,
        TokenStorageInterface $tokenStorage
    )
    {
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
        $this->tokenStorage = $tokenStorage;
    }

    public function update(User $user)
    {
        $this->entityManager->refresh($user);
        if (
            $user->getProfilePicture()->count() >= 1 &&
            !empty($user->getProfile()) &&
            !empty($user->getSearchCriterias())
        )
        {
            $user->setRoles(["ROLE_USER"]);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            //on recrée une token pour l'utilisateur, sinon il est déconnecté !
            $token = new PostAuthenticationGuardToken($user, 'main', $user->getRoles());
            $this->tokenStorage->setToken($token);

            $this->flashBag->add('success', 'Votre profil est maintenant complet !');
        }
    }
}