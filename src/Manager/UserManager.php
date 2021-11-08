<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager extends AbstractManager
{
    private $encoder;

    public function __construct(EntityManagerInterface $entityManger, RequestStack $requestStack, UserPasswordHasherInterface $encoder)
    {
        parent::__construct($entityManger, $requestStack);
        $this->encoder = $encoder;
    }

    public function encode(User $user, $password)
    {
        $hash = $this->encoder->hashPassword($user, $password);
        $user->setPassword($hash);
    }

    public function setRole($form, User $user)
    {
        $isAdmin = false;
        if ($form->has('admin')) {
            $isAdmin = $form->get('admin')->getData();
        }
        if ($isAdmin === true) {
            $user->setRoles(['ROLE_ADMIN']);
            return $user;
        }
    }
}
