<?php

namespace App\Controller\Security;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/user")
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
            $this->serializer->serialize($this->getUser(), 'json', ['groups' => 'user'])
        );
    }

    //    /**
    //     * @Route(path="/", name="user_collection")
    //     * @IsGranted("ROLE_ADMIN")
    //     */
    //    public function collection(Request $request, UserRepository $repository): JsonResponse
    //    {
    //        $context = (new RequestContext())->fromRequest($request);
    //
    //        $params = Parameters::fromRequest($request);
    //
    //        $collection = $repository->collection($params);
    //
    //        return JsonResponse::fromJsonString(
    //            $this->serializer->serialize($collection, 'json', ['groups' => ['user', 'collection']])
    //        );
    //    }
    //
    //    /**
    //     * @Route("/new", name="user_create")
    //     * @IsGranted("ROLE_ADMIN")
    //     */
    //    public function create(Request $request): JsonResponse
    //    {
    //        $user = new User();
    //
    //        $this->serializer->deserialize(
    //            $request->getContent(),
    //            User::class,
    //            'json',
    //            [AbstractNormalizer::OBJECT_TO_POPULATE => $user]
    //        );
    //
    //        $this->entityManager->persist($user);
    //        $this->entityManager->flush();
    //
    //        return JsonResponse::fromJsonString(
    //            $this->serializer->serialize($user, 'json', ['groups' => 'user'])
    //        );
    //    }
    //
    //    /**
    //     * @Route("/{user<\d+>}", name="user_read")
    //     * @IsGranted("ROLE_ADMIN")
    //     */
    //    public function read(User $user): JsonResponse
    //    {
    //        return JsonResponse::fromJsonString(
    //            $this->serializer->serialize($user, 'json', ['groups' => 'user'])
    //        );
    //    }
    //
    //    /**
    //     * @Route("/{user<\d+>}/edit", name="user_update")
    //     * @IsGranted("USER_EDIT", subject="user")
    //     */
    //    public function update(Request $request, User $user): JsonResponse
    //    {
    //        $this->serializer->deserialize(
    //            $request->getContent(),
    //            User::class,
    //            'json',
    //            [AbstractNormalizer::OBJECT_TO_POPULATE => $user]
    //        );
    //
    //        $this->entityManager->flush();
    //
    //        return JsonResponse::fromJsonString(
    //            $this->serializer->serialize($user, 'json', ['groups' => 'user'])
    //        );
    //    }
    //
    //    /**
    //     * @Route("/{user<\d+>}/delete", name="user_delete")
    //     * @IsGranted("ROLE_ADMIN")
    //     */
    //    public function delete(User $user): JsonResponse
    //    {
    //        $this->entityManager->remove($user);
    //        $this->entityManager->flush();
    //
    //        return JsonResponse::fromJsonString(
    //            $this->serializer->serialize($user, 'json', ['groups' => 'user'])
    //        );
    //    }
}