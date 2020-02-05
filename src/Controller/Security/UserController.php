<?php

namespace App\Controller\Security;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/users")
 */
class UserController extends AbstractController
{
    protected SerializerInterface $serializer;
    protected EntityManagerInterface $entityManager;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route(path="/me", name="user_me")
     * @IsGranted("ROLE_USER")
     */
    public function me(): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $this->serializer->serialize($this->getUser(), 'json', ['groups' => 'user_read'])
        );
    }
}