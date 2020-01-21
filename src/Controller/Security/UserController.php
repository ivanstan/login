<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    /**
     * @Route(name="user_me", path="/me")
     */
    public function me(SerializerInterface $serializer): JsonResponse
    {
        if ($this->getUser() === null) {
            throw new AccessDeniedHttpException();
        }

        return JsonResponse::fromJsonString(
            $serializer->serialize($this->getUser(), 'json', ['groups' => 'user'])
        );
    }
}