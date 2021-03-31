<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginPageIsWorking(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/connexion');

        $this->assertResponseIsSuccessful();

        $client->submitForm("Connexion !", [
            'username' => 'yo',
            'password' => 'yoyoyo',
        ]);

        $this->assertResponseRedirects();
    }

    public function testAccountCreationWorks(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/inscription');
        $this->assertResponseIsSuccessful();

        $client->submitForm("C'est parti", [
            'registration_form[username]' => 'toto_4444444',
            'registration_form[email]' => 'toto4444444@toto.com',
            'registration_form[plainPassword]' => 'tototo',
        ]);

        $this->assertResponseRedirects('/profil/modification', 302);
    }
}
