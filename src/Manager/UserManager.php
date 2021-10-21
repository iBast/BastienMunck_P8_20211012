<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager extends AbstractManager
{
    private $encoder;

    public function __construct(EntityManagerInterface $entityManger, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($entityManger);
        $this->encoder = $encoder;
    }

    public function encode(User $user, $password)
    {
        $hash = $this->encoder->encodePassword($user, $password);
        $user->setPassword($hash);
    }
}
