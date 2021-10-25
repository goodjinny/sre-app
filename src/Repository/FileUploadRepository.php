<?php

namespace App\Repository;

use App\Entity\FileUpload;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FileUpload|null find($id, $lockMode = null, $lockVersion = null)
 * @method FileUpload|null findOneBy(array $criteria, array $orderBy = null)
 * @method FileUpload[]    findAll()
 * @method FileUpload[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileUploadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FileUpload::class);
    }

    /**
     * @param string $hashId
     *
     * @return FileUpload|null
     */
    public function findUploadByHashId(string $hashId): ?FileUpload
    {
        return $this->findOneBy([
            'hash' => $hashId
        ]);
    }

    /**
     * @return array|FileUpload[]
     */
    public function getUncompletedUploads(): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.ciUploadId IS NULL')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return array|FileUpload[]
     */
    public function getUnprocessedUploads(): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.isProcessed = false')
            ->getQuery()
            ->getResult()
        ;
    }
}
