<?php

namespace App\Controller\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/user")
 */
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

    /**
     * @Route("/{user<\d+>}", name="user")
     * @IsGranted("ROLE_ADMIN")
     */
    public function user(User $user, SerializerInterface $serializer): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $serializer->serialize($user, 'json', ['groups' => 'user'])
        );
    }

    /**
     * @Route("/{user<\d+>}/edit", name="user_edit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, User $user, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $serializer->deserialize($request->getContent(), User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);

        $entityManager->flush();

        return JsonResponse::fromJsonString(
            $serializer->serialize($user, 'json', ['groups' => 'user'])
        );
    }
}