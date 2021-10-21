<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractManager
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
