<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture implements ORMFixtureInterface
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        //two type of roles, for checking access

        $roleAdmin = $this->addRole($manager, 'ROLE_ADMIN');
        $roleUser = $this->addRole($manager, 'ROLE_USER');

        $this->addUser($manager, $roleAdmin, 'admin', '1111');
        $this->addUser($manager, $roleUser, 'user', '1111');

        $manager->flush();
    }

    private function addRole(ObjectManager $manager, $type)
    {
        $role = new Role();
        $role->setType($type);
        $manager->persist($role);
        return $role;
    }

    private function addUser(ObjectManager $manager, Role $role, $login, $password)
    {
        $user = new User();
        $user->setLogin($login);
        $user->setRole($role);

        $password = $this->encoder->encodePassword($user, $password);
        $user->setPassword($password);

        $manager->persist($user);
    }
}