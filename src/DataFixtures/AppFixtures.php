<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        //simple user
        $user = new User();
        $hash = $this->encoder->hashPassword($user, 'password');
        $user->setEmail('user@domain.com')
            ->setUsername('user')
            ->setPassword($hash);
        $manager->persist($user);

        // user with admin rights
        $admin = new User();
        $hash = $this->encoder->hashPassword($admin, 'password');
        $admin->setEmail('admin@domain.com')
            ->setUsername('admin')
            ->setPassword($hash)
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        //Task with a regristrated user
        $task = new Task();
        $task->setTitle('Task with registrated user')
            ->setContent('Content')
            ->setCreatedAt(new DateTime())
            ->setCreatedBy($user);
        $manager->persist($task);

        //Task created by admin
        $task = new Task();
        $task->setTitle('Task created by admin')
            ->setContent('Content')
            ->setCreatedAt(new DateTime())
            ->setCreatedBy($admin);
        $manager->persist($task);

        //Task without user
        $task = new Task();
        $task->setTitle('Task not linked to a user')
            ->setContent('Content')
            ->setCreatedAt(new DateTime());
        $manager->persist($task);

        //Task done
        $task = new Task();
        $task->setTitle('Task done')
            ->setContent('Content')
            ->setCreatedAt(new DateTime());
        $task->toggle(!$task->isDone());
        $manager->persist($task);

        //saving in DB
        $manager->flush();
    }
}
