<?php

namespace App\Controller;

use DateTime;
use Exception;
use App\Entity\Project;
use App\Entity\Student;
use App\Entity\SchoolYear;
use App\Entity\Tag;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/test')]
class TestController extends AbstractController
{
    #[Route('/tag', name: 'app_test_tag')]
    public function tag(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $repository = $em->getRepository(Tag::class);

        // création d'un nouvel objet
        $foo = new Tag;
        $foo->setName('Foo');
        $foo->setDescription('Foo bar baz');
        $em->persist($foo);

        try {
            $em->flush();
        } catch (Exception $e) {
            // géréer le message d'erreur
            dump($e->getMessage('Erreur'));
        };

        // récupération de l'objet dont l'id est 1
        $tag = $repository->find(1);

        // récupération de l'objet dont l'id est 15
        $tag15 = $repository->find(15);

        // suppression de l'objet s'il existe
        if ($tag15) {
            // supression de l'objet
            $em->remove($tag15);
            $em->flush();
        }

        // pas la peine d'appeler persist si l'objet existe dans la bdd
        $em->flush();

        // récupération d'un tag dont le nom est CSS
        $cssTag = $repository->findOneBy([
            // critère de recherche
            'name' => 'CSS',
        ]);

        // récupération dont tous les tag dont la desciption est null
        $nullDescriptionTags = $repository->findBy([
            // critère de recherche
            'description' => null,
        ],[
            // critères de tri
            'name' => 'ASC'
        ]);
        // ou
        $nullDescriptionTags = $repository->findByNullDescription();

        
        // récupération de tous les tags avec description

        $notNullDescriptionTags = $repository->findByNotNullDescription();

        // récupération de la liste complète des objets
        $tags = $repository->findAll();

        // récupération des tags qui contiennent certinas mot-clés
        $keywordTags1 = $repository->findByKeyword('HTML');
        $keywordTags2 = $repository->findByKeyword('ipsum');

        // récupération de tags a partir d'une school year 
        $reposchoolYears = $em->getRepository(schoolYear::class);
        $schoolYear = $reposchoolYears->find(1);
        $schoolYearTags = $repository->findBySchoolYear($schoolYear);

        // mise à jour des relations d'un tag

        $studentRepository = $em->getRepository(Student::class);
        $student = $studentRepository->find(2);
        $tag1 = $repository->find(1);
        $tag1->addStudent($student);
        $em->flush();


        // récupération de l'objet dont l'id est 4
        $tag4 = $repository->find(4);

        // modification d'un objet
        $tag4->setName('Python');
        $tag4->setDescription(null);

        // association du tag 4 au student 1
        $student->addTag($tag4);
        $em->flush();

        $title = 'Test des tags';

        return $this->render('test/tag.html.twig', [
            'title' => $title,
            'tags' => $tags,
            'tag' => $tag,
            'cssTag' => $cssTag,
            'nullDescriptionTags' => $nullDescriptionTags,
            'notNullDescriptionTags' => $notNullDescriptionTags,
            'keywordTags1' => $keywordTags1,
            'keywordTags2' => $keywordTags2,
            'schoolYearId' => $schoolYearTags,
            'tag1' => $tag1,
        ]);
    }

    #[Route('/school-year', name: 'app_test_school-year')]
    public function project(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $repository = $em->getRepository(Project::class);

        $p11 = new SchoolYear;
        $p11->setName('Promo 11');
        $p11->setDescription('Formation de la promo 11');
        $p11->setStartDate(new DateTime('2023-01-01'));
        $p11->setEndDate(new DateTime('2023-06-01'));
        $em->persist($p11);
        try {
            $em->flush();
        } catch (Exception $e) {
            // géréer le message d'erreur
            dump($e->getMessage('Erreur'));
        };

        $schoolYears = $repository->findAll();
        $schoolYear = $repository->find(1);
        $schoolYear14 = $repository->find(14);

        if ($schoolYear14) {
            // supression de l'objet
            $em->remove($schoolYear14);
            $em->flush();
        }

        $title = 'Test des school years';

        return $this->render('test/school-year.html.twig', [
            'title' => $title,
            'schoolYear' => $schoolYear,
            'schoolYears' => $schoolYears,
        ]);
    }

    #[Route('/project', name: 'app_test_project')]
    public function schoolYear(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $repository = $em->getRepository(Project::class);

        $project = new Project;
        $project->setName('Promo 11');
        $project->setDescription('Formation de la promo 11');
        $project->setClientName('Alexandre');
        $project->setStartDate(new DateTime('2023-01-01'));
        $project->setCheckPointDate(new DateTime('2023-06-01'));
        $project->setDeliveryDate(new DateTime('2023-07-01'));
        $em->persist($project);

        try {
            $em->flush();
        } catch (Exception $e) {
            // géréer le message d'erreur
            dump($e->getMessage('Erreur'));
        };

        $projects = $repository->findAll();
        $project = $repository->find(1);
        $project14 = $repository->find(14);

        if ($project14) {
            // supression de l'objet
            $em->remove($project14);
            $em->flush();
        }

        $title = 'Test des school years';

        return $this->render('test/project.html.twig', [
            'title' => $title,
            'project' => $project,
            'projects' => $projects,
        ]);
    }
}
