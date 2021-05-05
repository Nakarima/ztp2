<?php

namespace App\DataFixtures;

use App\Entity\Bug;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class BugFixtures.
 */
class BugFixtures extends AbstractBaseFixtures
{
    /**
     * Faker.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Persistence object manager.
     *
     * @var \Doctrine\Persistence\ObjectManager
     */
    protected $manager;

    /**
     * Load.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->faker = Factory::create();
        $this->manager = $manager;

        for ($i = 0; $i < 10; ++$i) {
            $bug = new Bug();
            $bug->setTitle($this->faker->text(64));
            $bug->setDescription($this->faker->text(256));
            $bug->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $bug->setUpdatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $this->manager->persist($bug);
        }

        $manager->flush();
    }
}
