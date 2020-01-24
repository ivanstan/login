<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Security\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const ADMIN_USER_REFERENCE = 'reference_admin_user';

    private const PASSWORD = 'test123';

    public function load(ObjectManager $manager): void
    {
        // Add admin user
        $user = new User();
        $user->setEmail('admin@example.com');
        $user->setRoles([Role::ADMIN]);
        $user->setActive(true);
        $user->setPlainPassword(self::PASSWORD);
        $manager->persist($user);

        $user = new User();
        $user->setEmail('user1@example.com');
        $user->setRoles([Role::USER]);
        $user->setActive(false);
        $user->setPlainPassword(self::PASSWORD);
        $manager->persist($user);

        $manager->flush();

        $this->addReference(self::ADMIN_USER_REFERENCE, $user);
    }
}
