<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
        protected $slugger;
        protected $hasher;

    public function __construct(SluggerInterface $slugger, UserPasswordHasherInterface $hasher){
            $this->slugger = $slugger;
            $this->hasher = $hasher;
        }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));

        $admin = new User;
        
        $hash = $this->hasher->hashPassword($admin, "password");
        
        $admin->setEmail("admin@gmail.com")
            ->setFullName("admin")
            ->setPassword($hash)
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);


        for ($u = 0; $u < 5; $u++) {
            $user = new User();

            $hash = $this->hasher->hashPassword($user, "password");

            $user->setEmail("user$u@gmail.com")
                ->setFullName($faker->name())
                ->setPassword($hash);

            $manager->persist($user);
        }




        $manager->flush();
    }
}
