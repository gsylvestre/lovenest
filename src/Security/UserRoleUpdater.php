<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class UserRoleUpdater
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;
    /**
     * @var FlashBagInterface
     */
    private FlashBagInterface $flashBag;

    public function __construct(EntityManagerInterface $entityManager, FlashBagInterface $flashBag)
    {
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
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

            $this->flashBag->add('success', 'Votre profil est maintenant complet !');
        }
    }
}