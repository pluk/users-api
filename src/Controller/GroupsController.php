<?php

namespace App\Controller;

use App\Service\GroupService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class GroupsController extends AbstractController
{
    /**
     * @var GroupService
     */
    private $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    public function show(Request $request, int $id): Response
    {
        $group = $this->groupService->findByID((int) $id);

        if (!$group) {
            throw new NotFoundHttpException(sprintf('Group with id = %d not found', $id));
        }

        return $this->json($group);
    }

    public function find(Request $request): Response
    {
        $groups = $this->groupService->find();

        return $this->json($groups);
    }

    public function create(Request $request): Response
    {
        $body = json_decode($request->getContent(), true);

        if (!isset($body['name'])) {
            throw new BadRequestHttpException('name is required');
        }

        try {
            $group = $this->groupService->create($body['name']);
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return $this->json($group);
    }

    public function update(Request $request, int $id): Response
    {
        $group = $this->groupService->findByID($id);

        if (!$group) {
            throw new NotFoundHttpException(sprintf('User with id = %d not found', $id));
        }

        $body = json_decode($request->getContent(), true);

        try {
            $group = $this->groupService->update($body, $group);
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return $this->json($group);
    }
}
