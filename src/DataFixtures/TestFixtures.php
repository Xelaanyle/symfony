<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Project;
use App\Entity\SchoolYear;
use App\Entity\Student;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// $repotags = $this->manager->getRepository(Tag::class);
// $ltags = $repotags->finAll();
// $this->faker->randomElement($ltags);

// $repoproject = $this->manager->getRepository(Project::class);
// $lproject = $repoproject->findAll();
// $this->faker->randomElements($lproject)



class TestFixtures extends Fixture implements FixtureGroupInterface
{
    private $faker;
    private $hasher;
    private $manager;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = FakerFactory::create('fr_FR');
        $this->hasher = $hasher;
    }

    public static function getGroups(): array
    {
        return ['test'];
    }

    public function load(ObjectManager $manager): void
    {

        $this->manager = $manager;
        $this->loadTags();
        $this->loadProjects();
        $this->loadSchoolYears();
        $this->loadUsers();
        $this->loadStudents();
    }

    public function loadStudents()
    {
        $reposchoolyear = $this->manager->getRepository(SchoolYear::class);
        $lschoolyear = $reposchoolyear->findAll();

        $datas = [
            [
                'firstName' => 'Foo',
                'lastName' => 'Toto',
                'schoolYearId' => $lschoolyear[0],
            ],
            [
                'firstName' => 'Bar',
                'lastName' => 'Tata',
                'schoolYearId' => $lschoolyear[2],
            ],
            [
                'firstName' => 'Baz',
                'lastName' => 'Titi',
                'schoolYearId' => $lschoolyear[3],
            ],
        ];

        // données statique 

        foreach ($datas as $data) {
            $student = new Student();
            $student->setFirstName($data['firstName']);
            $student->setLastName($data['lastName']);
            $student->setSchoolYear($data['schoolYearId']);

            $this->manager->persist($student);
        }

        $this->manager->flush();

        // données dynamique

        for ($i = 0; $i < 10; $i++) {
            $student = new Student();
            $words = random_int(1, 3);
            $student->setFirstName($this->faker->sentence($words));
            $student->setLastName($this->faker->sentence($words));
            $student->setSchoolYear($this->faker->randomElement($lschoolyear));

            $this->manager->persist($student);
        }

        $this->manager->flush();
    }

    public function loadProjects()
    {
        $datas = [
            [
                'name' => 'P11',
                'description' => null,
                'clientName' => 'Toto',
                'startDate' => null,
                'checkpointDate' => null,
                'deliveryDate' => null,
            ],
            [
                'name' => 'P12',
                'description' => null,
                'clientName' => 'Titi',
                'startDate' => null,
                'checkpointDate' => null,
                'deliveryDate' => null,
            ],
            [
                'name' => 'P13',
                'description' => null,
                'clientName' => 'Tata',
                'startDate' => null,
                'checkpointDate' => null,
                'deliveryDate' => null,
            ],
        ];

        // données statique 

        foreach ($datas as $data) {
            $project = new Project();
            $project->setName($data['name']);
            $project->setDescription($data['description']);
            $project->setClientName($data['clientName']);
            $project->setStartDate($data['startDate']);
            $project->setCheckpointDate($data['checkpointDate']);
            $project->setDeliveryDate($data['deliveryDate']);

            $this->manager->persist($project);
        }

        $this->manager->flush();

        // données dynamique

        for ($i = 0; $i < 10; $i++) {
            $project = new Project();
            $words = random_int(1, 2);
            $project->setName($this->faker->sentence($words));
            $words = random_int(2, 3);
            $project->setDescription($this->faker->sentence($words));
            $words = random_int(3, 10);
            $project->setClientName($this->faker->sentence($words));
            $startDate = $this->faker->dateTimeBetween('-1 year', '- 3months');
            $project->setStartDate($startDate);
            $checkpointDate = $this->faker->dateTimeBetween('- 6 months', '-1months');
            $project->setCheckpointDate($checkpointDate);
            $deliveryDate = $this->faker->dateTimeBetween('- 1 months', 'now');
            $project->setDeliveryDate($deliveryDate);

            $this->manager->persist($project);
        }

        $this->manager->flush();
    }

    public function loadSchoolYears()
    {
        $datas = [
            [
                'name' => 'Alan turing',
                'description' => null,
                'startDate' => new DateTime('2022-01-01'),
                'endDate' => new DateTime('2022-12-31'),
            ],
            [
                'name' => 'John Von Neuman',
                'description' => null,
                'startDate' => new DateTime('2022-01-01'),
                'endDate' => new DateTime('2022-12-31'),
            ],
            [
                'name' => 'Brendan Eich',
                'description' => null,
                'startDate' => new DateTime('2022-01-01'),
                'endDate' => new DateTime('2022-12-31'),
            ],
        ];

        // données statique 

        foreach ($datas as $data) {
            $schoolyear = new SchoolYear();
            $schoolyear->setName($data['name']);
            $schoolyear->setDescription($data['description']);
            $schoolyear->setStartDate($data['startDate']);
            $schoolyear->setEndDate($data['endDate']);

            $this->manager->persist($schoolyear);
        }

        $this->manager->flush();

        // données dynamique

        for ($i = 0; $i < 10; $i++) {
            $schoolyear = new SchoolYear();
            $words = random_int(2, 4);
            $schoolyear->setName($this->faker->unique()->sentence($words));
            $words = random_int(2, 10);
            $schoolyear->setDescription($this->faker->optional(0.7)->sentence($words));
            $startDate = $this->faker->dateTimeBetween('-1 year', '- 6months');
            $schoolyear->setStartDate($startDate);
            $endDate = $this->faker->dateTimeBetween('- 3 months', 'now');
            $schoolyear->setEndDate($endDate);

            $this->manager->persist($schoolyear);
        }
        $this->manager->flush();
    }

    public function loadTags()
    {
        // données statique
        $datas = [
            [
                'name' => 'HTML',
                'description' => null,
            ],
            [
                'name' => 'JavaScript',
                'description' => null,
            ],
            [
                'name' => 'CSS',
                'description' => null,
            ],
        ];
        foreach ($datas as $data) {
            $tag = new Tag();
            $tag->setName($data['name']);
            $tag->setDescription($data['description']);

            $this->manager->persist($tag);
        }
        $this->manager->flush();

        //données dynamique
        for ($i = 0; $i < 10; $i++) {
            $tag = new Tag();
            $words = random_int(1, 3);
            $tag->setName($this->faker->unique()->sentence($words));
            $words = random_int(8, 15);
            $tag->setDescription($this->faker->sentence($words));

            $this->manager->persist($tag);
        }
        $this->manager->flush();
    }

    public function loadUsers(): void
    {
        // Données statiques
        $datas = [
            [
                'email' => 'alice@example.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
            ],
            [
                'email' => 'bob@example.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
            ],
            [
                'email' => 'charlie@example.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
            ],
        ];

        foreach ($datas as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $password = $this->hasher->hashPassword($user, $data['password']);
            $user->setPassword($password);
            $user->setRoles($data['roles']);
            // Persist sert a stocker l'user dans la base de donner
            $this->manager->persist($user);
        }

        // flush = push du user dans la base de donner
        $this->manager->flush();

        // données dynamiques
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($this->faker->unique()->safeEmail());
            $password = $this->hasher->hashPassword($user, $data['password']);
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);
            // Persist sert a stocker l'user dans la base de donner
            $this->manager->persist($user);
        }
        $this->manager->flush();
        // flush = push du user dans la base de donner
    }
}
