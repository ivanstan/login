<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Security\Role;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    protected function supports($attribute, $subject): bool
    {
        return $attribute === 'USER_EDIT' && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof \App\Entity\User) {
            return false;
        }

        if ($user->hasRole(Role::ADMIN)) {
            return true;
        }

        $request = $this->requestStack->getMasterRequest();

        if (!$request) {
            return false;
        }

        switch ($attribute) {
            case 'USER_EDIT':
                $userDataFromPayload = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
                $userId = $userDataFromPayload['id'] ?? null;
                /** @var User $userFromUrl */
                $userFromUrl = $request->attributes->get('user');
                // user can edit self
                if (($user->getId() === $userId) && ($userId === $userFromUrl->getId())) {
                    return true;
                }
                break;
        }

        return false;
    }
}
