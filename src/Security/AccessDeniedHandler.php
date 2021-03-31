<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use App\Entity\User;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $urlGenerator;

    /**
     * @var Security
     */
    private Security $security;
    /**
     * @var FlashBagInterface
     */
    private FlashBagInterface $flashBag;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        Security $security,
        FlashBagInterface $flashBag
    )
    {
        $this->urlGenerator = $urlGenerator;
        $this->security = $security;
        $this->flashBag = $flashBag;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        if ($this->security->isGranted("ROLE_USER_INCOMPLETE")) {
            $this->flashBag->add('warning', 'Merci de compléter votre profil !');

            /** @var User $user */
            $user = $this->security->getUser();

            if (!$user->getProfile()){
                return new RedirectResponse($this->urlGenerator->generate('profile_edit'));
            }
            if ($user->getProfilePicture()->count() < 1){
                return new RedirectResponse($this->urlGenerator->generate('profile_picture'));
            }
            if (!$user->getSearchCriterias()){
                return new RedirectResponse($this->urlGenerator->generate('profile_criterias_edit'));
            }
        }

        $content = "you shall not pass";
        return new Response($content, 403);
    }
}