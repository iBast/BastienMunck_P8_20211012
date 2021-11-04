<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\Toolbox\NeedLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    use NeedLogin;

    public function testLoginUser()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $buttonCrawlerNode = $crawler->selectButton('Se connecter');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            '_username' => 'user',
            '_password' => 'password',
        ]);
        $client->followRedirect();
        $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !');
    }

    public function testLoginBadCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $buttonCrawlerNode = $crawler->selectButton('Se connecter');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            '_username' => 'user',
            '_password' => 'wrong',
        ]);
        $client->followRedirect();
        $this->assertSelectorTextContains('.alert.alert-danger', 'Invalid credentials.');
    }

    public function testLogout()
    {
        $client = static::createClient();
        $repo = $client->getContainer()->get('doctrine');
        $this->login($client, $repo->getRepository(User::class)->findOneBy(['username' => 'user']));

        $client->request('GET', '/logout');
        $crawler = $client->followRedirect();
        $this->assertSame($crawler->getUri(), 'http://localhost/');
    }
}
