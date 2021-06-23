<?php
/**
 * Bug fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Bug;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class BugFixtures.
 *
 * @codeCoverageIgnore
 */
class BugFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
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
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(50, 'bugs', function ($i) {
            $bug = new Bug();
            $bug->setTitle($this->faker->text(64));
            $bug->setDescription($this->faker->text(256));
            $bug->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $bug->setUpdatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $bug->setCategory($this->getRandomReference('categories'));
            $bug->setAuthor($this->getRandomReference('users'));
            $bug->setStatus($this->getRandomReference('statuses'));
            $bug->addTag($this->getRandomReference('tags'));
            $bug->addTag($this->getRandomReference('tags'));
            $bug->addTag($this->getRandomReference('tags'));

            return $bug;
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
        return [CategoryFixtures::class, UserFixtures::class, StatusFixtures::class, TagFixtures::class];
    }
}
