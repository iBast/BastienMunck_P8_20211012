<?php

namespace App\Tests\Entity;

use DateTime;
use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    private $validator;

    public function __contruct(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function getTaskEntity(): Task
    {
        return (new Task())
            ->setTitle('Task title')
            ->setContent('Task content')
            ->setCreatedAt(new DateTime());
    }

    public function assertHasErrors(Task $task, int $number)
    {
        self::bootKernel();
        $errors = $this->validator->validate($task);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() .  ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(',', $messages));
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->getTaskEntity(), 0);
    }

    public function testGetAnonymousUser()
    {
        $this->assertSame('Anonymous user', $this->getTaskEntity()->getCreatedBy()->getUsername());
    }

    public function testBlankTitle()
    {
        $this->assertHasErrors($this->getTaskEntity()->setTitle(''), 1);
    }

    public function testGetTitle()
    {
        $this->assertSame('Task title', $this->getTaskEntity()->getTitle());
    }

    public function testBlankContent()
    {
        $this->assertHasErrors($this->getTaskEntity()->setContent(''), 1);
    }

    public function testGetContent()
    {
        $this->assertSame('Task content', $this->getTaskEntity()->getContent());
    }

    public function testGetCreatedAt()
    {
        $date = new DateTime();
        $this->assertSame($date, $this->getTaskEntity()->setCreatedAt($date)->getCreatedAt());
    }

    public function testIsNotDoneByDefault()
    {
        $this->assertSame(false, $this->getTaskEntity()->isDone());
    }

    public function testToggle()
    {
        $task = $this->getTaskEntity();
        $task->toggle(!$task->isDone());
        $this->assertSame(true, $task->isDone());
    }
}
