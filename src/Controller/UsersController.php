<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UsersController extends AbstractController
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function show(Request $request, int $id): Response
    {
        $user = $this->userService->findByID((int) $id);

        if (!$user) {
            throw new NotFoundHttpException(sprintf('User with id = %d not found', $id));
        }

        return $this->json($user);
    }

    public function find(Request $request): Response
    {
        $users = $this->userService->find();

        return $this->json($users);
    }

    public function create(Request $request): Response
    {
        $body = json_decode($request->getContent(), true);

        if (!isset($body['email'])) {
            throw new BadRequestHttpException('email is required');
        }

        if (!isset($body['first_name'])) {
            throw new BadRequestHttpException('first_name is required');
        }

        if (!isset($body['last_name'])) {
            throw new BadRequestHttpException('last_name is required');
        }

        try {
            $user = $this->userService->create(
                $body['first_name'],
                $body['last_name'],
                $body['email']
            );
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return $this->json($user);
    }

    public function update(Request $request, int $id): Response
    {
        $user = $this->userService->findByID($id);

        if (!$user) {
            throw new NotFoundHttpException(sprintf('User with id = %d not found', $id));
        }

        $body = json_decode($request->getContent(), true);

        try {
            $user = $this->userService->update($body, $user);
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return $this->json($user);
    }
}
