<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Team;
use App\Entity\User;
use App\Entity\TypeBien;
use App\Entity\TypeTransaction;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $transactionTypes = ['location', 'vente', 'location saisonnière', 'viager'];
        foreach ($transactionTypes as $transactionType) {
            $this->createTransactionType($transactionType, $manager);
        }

        $bienTypes = ['propriété', 'villa', 'maison de ville', 'appartement', 'bureau', 'exploitation agricole', 'terrain'];
        foreach ($bienTypes as $bienType) {
            $this->createBienType($bienType, $manager);
        }

        $admin = new Team;
        $password = $this->passwordHasher->hashPassword($admin, 'Simplon@2023');
        $admin->setGender('M.');
        $admin->setFirstname('Xavier');
        $admin->setLastname('Tezza');
        $admin->setEmail('xavier.tezza@comnstay.fr');
        $admin->setPassword($password);
        $admin->setRoles(['ROLE_SUPER_ADMIN']);
        $manager->persist($admin);

        for ($i = 0; $i < 10; $i++) {
            $user = new User;
            $password = $this->passwordHasher->hashPassword($user, 'Simplon@2023');
            $user->setGender($faker->title());
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setEmail($faker->email());
            $user->setPassword($password);
            $user->setRoles(['ROLE_SUPER_ADMIN']);
            $user->setAddress($faker->streetAddress());
            $user->setZipcode($faker->postcode());
            $user->setTown($faker->city());
            $user->setCountry($faker->country());
            $user->setPhone($faker->phoneNumber());
            $user->setMobile($faker->phoneNumber());
            $manager->persist($user);
        }

        $manager->flush();
    }

    public function createTransactionType(string $name, ObjectManager $manager)
    {
        $transactionType = new TypeTransaction;
        $transactionType->setName($name);
        $transactionType->setQte(0);
        $manager->persist($transactionType);
        return $transactionType;
    }

    public function createBienType(string $name, ObjectManager $manager)
    {
        $bienType = new TypeBien;
        $bienType->setName($name);
        $bienType->setQte(0);
        $manager->persist($bienType);
        return $bienType;
    }
}
