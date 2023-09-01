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

// $htmlTag = $repository->find(1);
// $cssTag = $repository->find(2);
// $jslTag = $repository->find(3);

// éléments de code réutiliser dans vos boucles
// $html = $tags[0];
// $html->getName();
// $tags[0]->getName();



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
        $this->loadStudents();
    }

    public function loadProjects()
    {
        $repoTags = $this->manager->getRepository(Tag::class);
        $tags = $repoTags->findAll();
        // $this->faker->randomElement($tags);

        // on récupère un tag a partir de son id
        $htmlTag = $repoTags->find(1);
        $cssTag = $repoTags->find(2);

        // on récupère 
        $jsTag = $tags[2];

        $datas = [
            [
                'name' => 'P11',
                'description' => null,
                'clientName' => 'Toto',
                'startDate' => new DateTime('2022-01-01'),
                'checkpointDate' => new DateTime('2022-06-01'),
                'deliveryDate' => new DateTime('2023-01-01'),
                'tags' => [$htmlTag],
            ],
            [
                'name' => 'P12',
                'description' => null,
                'clientName' => 'Titi',
                'startDate' => new DateTime('2022-01-01'),
                'checkpointDate' => new DateTime('2022-06-01'),
                'deliveryDate' => new DateTime('2023-01-01'),
                'tags' => [$cssTag],
            ],
            [
                'name' => 'P13',
                'description' => null,
                'clientName' => 'Tata',
                'startDate' => new DateTime('2022-01-01'),
                'checkpointDate' => new DateTime('2022-06-01'),
                'deliveryDate' => new DateTime('2023-01-01'),
                'tags' => [$jsTag],
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

            foreach ($data['tags'] as $tag) {
                $project->addTag($tag);
            }

            $this->manager->persist($project);
        }

        $this->manager->flush();

        // données dynamique

        for ($i = 0; $i < 30; $i++) {
            $project = new Project();
            $words = random_int(1, 2);
            $project->setName($this->faker->sentence($words));
            $words = random_int(2, 10);
            $project->setDescription($this->faker->optional(0.7)->sentence($words));
            $project->setClientName($this->faker->name());
            $startDate = $this->faker->dateTimeBetween('-1 year', '-8months');
            $project->setStartDate($startDate);
            $checkpointDate = $this->faker->dateTimeBetween('-7 months', '-2months');
            $project->setCheckpointDate($checkpointDate);
            $deliveryDate = $this->faker->dateTimeBetween('-1 months', 'now');
            $project->setDeliveryDate($deliveryDate);

            // on choisit le nombre de tags au hasard entre 1 et 4
            $tagsCount = random_int(1, 4);
            // on choisit des tags au hasard depuis la liste complète
            $shortList = $this->faker->randomElements($tags, $tagsCount);

            // on passe revue chaque tag de la short list
            foreach ($shortList as $tag) {
                // on associe un tag avec le projet
                $project->addTag($tag);
            }

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

    public function loadStudents(): void
    {
        $repoSchoolYear = $this->manager->getRepository(SchoolYear::class);
        $schoolYears = $repoSchoolYear->findAll();
        $repoTag = $this->manager->getRepository(Tag::class);
        $tags = $repoTag->findAll();
        $repoProject = $this->manager->getRepository(Project::class);
        $projects = $repoProject->findAll();

        $siteVitrine = $repoProject->find(1);
        $wordPress = $repoProject->find(2);
        $apiRest = $repoProject->find(3);

        $htmlTag = $repoTag->find(1);
        $cssTag = $repoTag->find(2);
        $jsTag = $repoTag->find(3);

        // Données statiques
        $datas = [
            [
                'email' => 'alice@example.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
                'firstName' => 'Foo',
                'lastName' => 'Example',
                'schoolYear' => $schoolYears[0],
                'projects' => [$siteVitrine],
                'tags' => [$htmlTag],
            ],
            [
                'email' => 'bob@example.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
                'firstName' => 'Bar',
                'lastName' => 'Example',
                'schoolYear' => $schoolYears[1],
                'projects' => [$wordPress],
                'tags' => [$cssTag, $htmlTag],
            ],
            [
                'email' => 'charlie@example.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
                'firstName' => 'Baz',
                'lastName' => 'Example',
                'schoolYear' => $schoolYears[2],
                'projects' => [$apiRest],
                'tags' => [$jsTag],
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

            $student = new Student();
            $student->setFirstName($data['firstName']);
            $student->setLastName($data['lastName']);
            $student->setSchoolYear($data['schoolYear']);
            $student->setUser($user);

            // récupération du premier projet de la liste du student
            $project = $data['projects'][0];
            $student->addProject($project);


            foreach ($data['tags'] as $tag) {
                $student->addTag($tag);
            }
            // $tag = $data['tags'][0];
            // $student->addTag($tag);


            $this->manager->persist($student);
        }


        $this->manager->flush();

        // flush = push du user dans la base de donner

        // // données dynamiques
        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user->setEmail($this->faker->unique()->safeEmail());
            $password = $this->hasher->hashPassword($user, $data['password']);
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);

            $student = new Student();
            $student->setFirstname($this->faker->firstName());
            $student->setLastname($this->faker->lastName());

            $schoolYear = $this->faker->randomElement($schoolYears);
            $student->setSchoolYear($schoolYear);

            $project = $this->faker->randomElement($projects);
            $student->addProject($project);

            $tagsCount = random_int(1, 4);
            $shortList = $this->faker->randomElements($tags, $tagsCount);
            foreach ($shortList as $tag) {
                $student->addTag($tag);
            }

            $student->setUser($user);



            $this->manager->persist($user);
        }
        $this->manager->flush();
    }
}
