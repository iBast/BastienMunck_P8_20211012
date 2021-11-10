<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class TaskManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $entityManger, RequestStack $requestStack)
    {
        parent::__construct($entityManger, $requestStack);
    }
}
