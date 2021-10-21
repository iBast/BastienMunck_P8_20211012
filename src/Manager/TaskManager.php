<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;

class TaskManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $entityManger)
    {
        parent::__construct($entityManger);
    }
}
