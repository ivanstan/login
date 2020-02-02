<?php

namespace App\Controller\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route(path="/me", name="user_me")
     * @IsGranted("ROLE_USER")
     */
    public function me(SerializerInterface $serializer): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $serializer->serialize($this->getUser(), 'json', ['groups' => 'user'])
        );
    }

    /**
     * @Route("/new", name="user_create")
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = new User();

        $serializer->deserialize($request->getContent(), User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);

        $entityManager->persist($user);
        $entityManager->flush();

        return JsonResponse::fromJsonString(
            $serializer->serialize($user, 'json', ['groups' => 'user'])
        );
    }

    /**
     * @Route("/{user<\d+>}", name="user_read")
     * @IsGranted("ROLE_ADMIN")
     */
    public function read(User $user, SerializerInterface $serializer): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $serializer->serialize($user, 'json', ['groups' => 'user'])
        );
    }

    /**
     * @Route("/{user<\d+>}/edit", name="user_update")
     * @IsGranted("USER_EDIT", subject="user")
     */
    public function update(
        Request $request,
        User $user,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $serializer->deserialize($request->getContent(), User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);

        $entityManager->flush();

        return JsonResponse::fromJsonString(
            $serializer->serialize($user, 'json', ['groups' => 'user'])
        );
    }
}