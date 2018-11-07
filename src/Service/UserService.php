<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class UserService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $userRepository;

    /**
     * @var GroupService
     */
    private $groupService;

    public function __construct(
        EntityManager $entityManager,
        GroupService $groupService
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository(User::class);
        $this->groupService = $groupService;
    }

    public function findByID(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function findByEmail(string $email): ?User
    {
        $queryBuilder = $this->entityManager
            ->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.email = :email')->setParameter('email', $email)
            ->getQuery();

        return $queryBuilder->getOneOrNullResult();
    }

    public function create(string $firstName, string $lastName, string $email)
    {
        if ($this->findByEmail($email)) {
            throw new \Exception(
                sprintf('User with email=%s already exists', $email)
            );
        }

        $user = new User($firstName, $lastName, $email);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function update(array $userParams, User $user): User
    {
        if (!empty($userParams['email'])) {
            if ($this->findByEmail($userParams['email'])) {
                throw new \Exception(
                    sprintf(
                        'User with email=%s already exists',
                        $userParams['email']
                    )
                );
            }

            $user->setEmail($userParams['email']);
        }

        if (!empty($userParams['first_name'])) {
            $user->setFirstName($userParams['first_name']);
        }

        if (!empty($userParams['last_name'])) {
            $user->setLastName($userParams['last_name']);
        }

        if (isset($userParams['state'])) {
            switch ((int) $userParams['state']) {
                case User::USER_STATE_INACTIVE:
                    $user->deactivate();
                    break;
                case User::USER_STATE_ACTIVE:
                    $user->activate();
                    break;
                default:
                    break;
            }
        }

        if (!empty($userParams['group_id'])) {
            $group = $this->groupService->findByID((int) $userParams['group_id']);
            if (!$group) {
                throw new \Exception(
                    sprintf(
                        'Group with id=%d not found',
                        $userParams['group_id']
                    )
                );
            }

            $user->setGroup($group);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function find(array $params = []): array
    {
        return $this->userRepository->findAll();
    }
}