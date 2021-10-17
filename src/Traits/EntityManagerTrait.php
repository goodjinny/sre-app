<?php

declare(strict_types=1);

namespace App\Traits;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 * EntityManagerTrait.
 */
trait EntityManagerTrait
{
    protected EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     *
     * @required
     */
    public function setEntityManager(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }

    /**
     * Reopen closed entity manager.
     */
    public function reopenClosedEntityManager(): void
    {
        if (!$this->em->isOpen()) {
            $this->em = EntityManager::create($this->em->getConnection(), $this->em->getConfiguration());
        }
    }
}
