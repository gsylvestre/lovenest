<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerTest extends WebTestCase
{
    public function testSuggestionPageRedirectIfUserIsNotLoggedId(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/profil/suggestions');

        $this->assertResponseRedirects('/connexion', 302);
    }

    public function testSuggestionPageWorksWithLoggedInUser(): void
    {
        $client = static::createClient();

        $userRepo = static::$container->get(UserRepository::class);
        $user = $userRepo->findOneBy(['email' => 'yo@yo.com']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/profil/suggestions');

        $this->assertResponseIsSuccessful();
    }

    public function testAccountCreationWorks(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/inscription');
        $this->assertResponseIsSuccessful();

        $client->submitForm("C'est parti", [
            'registration_form[username]' => 'toto4',
            'registration_form[email]' => 'toto4@toto.com',
            'registration_form[plainPassword]' => 'tototo',
        ]);

        $this->assertResponseRedirects('/profil/modification', 302);
    }
}
