<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class TaskManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $entityManger, SessionInterface $session)
    {
        parent::__construct($entityManger, $session);
    }
}
