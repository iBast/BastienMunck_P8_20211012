<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserTest extends KernelTestCase
{
    public function getUserEntity(): User
    {
        return (new User())
            ->setEmail('user@email.com')
            ->setUsername('test')
            ->setPassword('password');
    }

    public function assertHasErrors(User $user, int $number = 0)
    {
        $errors = static::getContainer()->get('validator')->validate($user);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->getUserEntity(), 0);
    }

    public function testBlankUsername()
    {
        $this->assertHasErrors($this->getUserEntity()->setUsername(''), 1);
    }

    public function testInvalidEmail()
    {
        $this->assertHasErrors($this->getUserEntity()->setEmail('falseemail'), 1);
    }

    public function testGetEmail()
    {
        $this->assertSame('user@email.com', $this->getUserEntity()->getEmail());
    }

    public function testBlankEmail()
    {
        $this->assertHasErrors($this->getUserEntity()->setEmail(''), 1);
    }

    public function testGetPassword()
    {
        $this->assertSame('password', $this->getUserEntity()->getPassword());
    }

    public function testRoleUser()
    {
        $this->assertSame(['ROLE_USER'], $this->getUserEntity()->getRoles());
    }

    public function testSetRole()
    {
        $user = $this->getUserEntity()->setRoles(['ROLE_ADMIN']);
        $this->assertSame(['ROLE_ADMIN', 'ROLE_USER'], $user->getRoles());
    }

    public function testAddTask()
    {
        $user = $this->getUserEntity();
        $task = new Task;
        $user->addTask($task);
        $this->assertCount(1, $user->getTasks());
        $this->assertSame($task->getCreatedBy(), $user);
    }

    public function testRemoveTask()
    {
        $user = $this->getUserEntity();
        $task = new Task;
        $user->addTask($task);
        $this->assertCount(1, $user->getTasks());
        $this->assertSame($task->getCreatedBy(), $user);
        $user->removeTask($task);
        $this->assertCount(0, $user->getTasks());
        $this->assertSame('Anonymous user', $task->getCreatedBy()->getUsername());
    }
}
