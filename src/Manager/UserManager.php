<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager extends AbstractManager
{
    private $encoder;

    public function __construct(EntityManagerInterface $entityManger, SessionInterface $session, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($entityManger, $session);
        $this->encoder = $encoder;
    }

    public function encode(User $user, $password)
    {
        $hash = $this->encoder->encodePassword($user, $password);
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
