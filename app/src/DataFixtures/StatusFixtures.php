<?php

namespace App\DataFixtures;

use App\Entity\Status;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class StatusFixtures.
 */
class StatusFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
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
        $statuses = ["solved", "unsolved"];
        $this->createMany(2, 'statuses', function ($i) use ($statuses) {
            $status = new Status();
            $status->setTitle($statuses[$i]);

            return $status;
        });

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
