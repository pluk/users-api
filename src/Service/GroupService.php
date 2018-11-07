<?php

namespace App\Service;

use App\Entity\Group;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class GroupService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $groupRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->groupRepository = $entityManager->getRepository(Group::class);
    }

    public function findByID(int $id): ?Group
    {
        return $this->groupRepository->find($id);
    }

    public function findByName(string $name): ?Group
    {
        $queryBuilder = $this->entityManager
            ->createQueryBuilder()
            ->select('g')
            ->from(Group::class, 'g')
            ->where('g.name = :name')->setParameter('name', $name)
            ->getQuery();

        return $queryBuilder->getOneOrNullResult();
    }

    public function create(string $name)
    {
        if ($this->findByName($name)) {
            throw new \Exception(
                sprintf('Group with name=%s already exists', $name)
            );
        }

        $group = new Group($name);
        $this->entityManager->persist($group);
        $this->entityManager->flush();

        return $group;
    }

    public function update(array $groupParams, Group $group): Group
    {
        if (!empty($groupParams['name'])) {
            $group->setName($groupParams['name']);
        }

        $this->entityManager->persist($group);
        $this->entityManager->flush();

        return $group;
    }

    public function find(array $params = []): array
    {
        return $this->groupRepository->findAll();
    }
}