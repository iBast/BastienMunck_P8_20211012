<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RootAdminFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        // user with admin rights
        $admin = new User();
        $hash = $this->encoder->hashPassword($admin, 'root');
        $admin->setEmail('admin@domain.com')
            ->setUsername('root')
            ->setPassword($hash)
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        //saving in DB
        $manager->flush();
    }
}
