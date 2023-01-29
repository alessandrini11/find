<?php

namespace App\DataFixtures;

use App\Entity\Archive;
use App\Entity\Declaration;
use App\Entity\Document;
use App\Entity\Fund;
use App\Entity\Payement;
use App\Entity\Transaction;
use App\Entity\User;
use App\Entity\Visitor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $encoder
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $users = [];
        $declarations = [];
        $documents = [];
        $transactions = [];
        $archives = [];
        $funds = [];

        for ($i = 0; $i < 10; $i++)
        {
            $user = new User();
            $hash = $this->encoder->hashPassword($user, 'azerty');
            $user
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setEmail($faker->email())
                ->setTelephone($faker->phoneNumber())
                ->setRoles([User::ROLE_USER])
                ->setPassword($hash)
                ->setSex($faker->randomElement([User::MAN, User::WOMAN]))
                ->setIsVerified($faker->randomElement([false, true]))
            ;
            $manager->persist($user);
            $users[] = $user;

            $fund = new Fund();
            $fund->setUser($user);
            $manager->persist($fund);
            $funds[] = $fund;
        }

        for ($i = 0; $i < 20; $i++)
        {
            $transaction = new Transaction();
            $transaction
                ->setFund($funds[mt_rand(0, count($funds) - 1)])
                ->setType($faker->randomElement([Transaction::DEPOSIT, Transaction::WITHDRAWAL]))
            ;

            if($transaction->getType() === Transaction::DEPOSIT){
                $transaction->setMontant(1500);
            } else {
                $transaction->setMontant(50);
            }
            $manager->persist($transaction);
            $transactions[] = $transaction;
        }

        for ($i = 0; $i < 20; $i++){
            $document = new Document();
            $document->setUser($users[mt_rand(0, count($users) - 1)])
                ->setOwner("{$faker->firstName()} {$faker->lastName()}")
                ->setDescription($faker->text(200))
                ->setType($faker->randomElement([
                    Document::MARRIAGE_CERTIFICATE,
                    Document::DEATH_CERTIFICATE,
                    Document::CERTIFICATE
                ]))
                ->setIdNumber("{$faker->postcode()}{$faker->uuid()}");
            $manager->persist($document);
            $documents[] = $document;

        }

        for ($i = 0; $i < 20; $i++){
            $declaration = new Declaration();
            $declaration->setDocument($documents[mt_rand(0, count($documents) - 1)])
                ->setUser($user)
                ->setDescription($faker->text(200))
                ->setReward($faker->randomElement([5000 , 10000, 15000, 50000, 100000, 250000]))
                ->setStatus($faker->randomElement([Declaration::FOUND, Declaration::LOST]))
            ;
            $manager->persist($declaration);
            $declarations[] = $declaration;
        }
        for ($i = 0; $i < 20; $i++)
        {
            $archive = new Archive();
            $archive->setOwner($user)
                ->setActor($users[mt_rand(0, count($users) - 1)])
                ->setDeclaration($declarations[mt_rand(0, count($declarations) - 1)])
                ->setActorValidation($faker->randomElement([true, false]))
                ->setOwnerValidation($faker->randomElement([true, false]))
            ;
            $manager->persist($archive);
            $archives[] = $archive;
        }
        for ($i = 0; $i < 30; $i++)
        {
            $payment = new Payement();
            $payment
                ->setUser($user)
                ->setMontant(500)
            ;
            $manager->persist($payment);
        }
        for ($i = 0; $i < 100; $i++){
            $visitor = new Visitor();
            $visitor->setIp($faker->ipv4());

            for ($i = 0; $i < 3; $i++)
            {
                $visitor->addDeclaration($declarations[mt_rand(0, count($declarations) - 1)]);
            }

            $manager->persist($visitor);
        }


        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
