<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Task;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        
        $user->setEmail('test@hello.ua');
        $user->setUsername('hello'); 
        $manager->persist($user);

        $task = new Task('Wake up', 'Wake up in the morning', new \DateTime('NOW'));
        $task->setOwner($user);
        $manager->persist($task);

        $manager->flush();
    }
}
