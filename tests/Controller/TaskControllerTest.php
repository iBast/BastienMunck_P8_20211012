<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Tests\Toolbox\NeedLogin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
    use NeedLogin;

    public function testCreateTask()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['username' => 'user']));
        $crawler = $client->request('GET', '/tasks/create');
        $buttonCrawlerNode = $crawler->selectButton('Ajouter');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'task[title]' => 'Task title',
            'task[content]' => 'Task content'
        ]);
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testEditTask()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['username' => 'user']));
        $task = $doctrine->getRepository(Task::class)->findOneBy(['title' => 'Task title']);
        $crawler = $client->request('GET', '/tasks/' . $task->getId() . '/edit');
        $buttonCrawlerNode = $crawler->selectButton('Modifier');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'task[title]' => 'Title',
            'task[content]' => 'Task content'
        ]);
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testDeleteTask()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['username' => 'user']));
        $task = $doctrine->getRepository(Task::class)->findOneBy(['title' => 'Title']);
        $client->request('GET', '/tasks/' . $task->getId() . '/delete');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testDoneList()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['username' => 'user']));
        $client->request('GET', '/tasks/done');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testToDoList()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['username' => 'user']));
        $client->request('GET', '/tasks/todo');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testToggle()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['username' => 'user']));
        $task = $doctrine->getRepository(Task::class)->findOneBy(['title' => 'Task not linked to a user']);
        $client->request('GET', '/tasks/' . $task->getId() . '/toggle');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testDeleteNotOwnedTask()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['username' => 'user']));
        $task = $doctrine->getRepository(Task::class)->findOneBy(['title' => 'Task created by admin']);
        $client->request('GET', '/tasks/' . $task->getId() . '/delete');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testDeleteTaskByAdmin()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['username' => 'admin']));
        $task = $doctrine->getRepository(Task::class)->findOneBy(['title' => 'Task with registrated user']);
        $client->request('GET', '/tasks/' . $task->getId() . '/delete');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }
}
