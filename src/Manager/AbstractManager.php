<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractManager
{
    protected $entityManager;
    protected $requestStack;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    public function save($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function saveAndFlash($entity, $message, $type = 'success')
    {
        $this->save($entity);
        $session = $this->requestStack->getSession();
        $session->getFlashBag()->add($type, $message);
    }

    public function remove($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
}
