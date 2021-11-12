<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\Toolbox\NeedLogin;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    use NeedLogin;

    public function testUsersListForbiddenAcces()
    {
        $client = static::createClient();
        $repo = $client->getContainer()->get('doctrine');
        $this->login($client, $repo->getRepository(User::class)->findOneBy(['username' => 'user']));
        $client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testUsersListAllowedAcces()
    {
        $client = static::createClient();
        $repo = $client->getContainer()->get('doctrine');
        $this->login($client, $repo->getRepository(User::class)->findOneBy(['username' => 'admin']));
        $client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testUserDontSeeButton()
    {
        $client = static::createClient();
        $repo = $client->getContainer()->get('doctrine');
        $this->login($client, $repo->getRepository(User::class)->findOneBy(['username' => 'user']));
        $client->request('GET', '/');
        $this->assertSelectorNotExists('.btn.btn-primary.admin');
    }

    public function testAdminSeeButton()
    {
        $client = static::createClient();
        $repo = $client->getContainer()->get('doctrine');
        $this->login($client, $repo->getRepository(User::class)->findOneBy(['username' => 'admin']));
        $client->request('GET', '/');
        $this->assertSelectorExists('.btn.btn-primary.admin');
    }

    public function testCreateUser()
    {
        $client = static::createClient();
        $repo = $client->getContainer()->get('doctrine');
        $this->login($client, $repo->getRepository(User::class)->findOneBy(['username' => 'user']));
        $client->request('GET', '/');
        $crawler = $client->clickLink('Créer un utilisateur');
        $buttonCrawlerNode = $crawler->selectButton('Ajouter');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'user[username]' => 'newuser',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'new@user.com'
        ]);
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testCreateAdmin()
    {
        $client = static::createClient();
        $repo = $client->getContainer()->get('doctrine');
        $this->login($client, $repo->getRepository(User::class)->findOneBy(['username' => 'admin']));
        $client->request('GET', '/');
        $crawler = $client->clickLink('Créer un utilisateur');
        $buttonCrawlerNode = $crawler->selectButton('Ajouter');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'user[username]' => 'newadmin',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'new@admin.com',
            'user[admin]' => 1
        ]);
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testEditUser()
    {
        $client = static::createClient();
        $repo = $client->getContainer()->get('doctrine');
        $this->login($client, $repo->getRepository(User::class)->findOneBy(['username' => 'admin']));
        $user = $repo->getRepository(User::class)->findOneBy(['username' => 'newadmin']);
        $crawler = $client->request('GET', '/users/' . $user->getId() . '/edit');
        $buttonCrawlerNode = $crawler->selectButton('Modifier');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'user[username]' => 'newadmin',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'new@admin.com'
        ]);
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }
}
