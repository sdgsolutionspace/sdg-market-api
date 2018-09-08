<?php


namespace App\Controller;

use Doctrine\ORM\Query;
use App\Entity\{GitProject, User};

use FOS\RestBundle\Routing\ClassResourceInterface;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\Annotations\RequestParam as RequestParam;
use Symfony\Component\HttpFoundation\{Request, Response, JsonResponse};
use FOS\RestBundle\Controller\Annotations\RouteResource;


/**
 * @RouteResource("Project")
 */
class ProjectController extends FOSRestController implements ClassResourceInterface
{

    /**
     * Get collection of Projects
     *
     * @return JsonResponse
     */
    public function cgetAction()
    {
        $query = $this->getDoctrine()
            ->getRepository('App\Entity\GitProject')
            ->createQueryBuilder('c')
            ->getQuery();
        $projects = $query->getResult(Query::HYDRATE_ARRAY);
        return new JsonResponse([
            'data' => $projects
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Register Project.
     *
     * @return JsonResponse
     */
    public function postAction(Request $post)
    {
        $gitProject = new GitProject();
        $request = $post->request;
        $gitProject->setName($request->get('git_name'));
        $gitProject->setGitAddress($request->get('git_address'));
        $gitProject->setProjectAddress($request->get('git_project_address'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($gitProject);
        $em->flush();

        $serializer = SerializerBuilder::create()->build();
        $gitProjectArray = $serializer->toArray($gitProject);

        return new JsonResponse($gitProjectArray, Response::HTTP_CREATED);

    }

    /**
     *
     * @return JsonResponse
     */
    public function getAction($id)
    {
        $repository = $this->getDoctrine()->getRepository(GitProject::class);
        $project = $repository->find($id);

        if (!$project) {
            return new JsonResponse([
                'message' => 'Project not found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'message' => 'Check out this project: ' . $project->getName()
        ], JsonResponse::HTTP_OK);

    }

    /**
     * @Route("/project/{id}", name="update_project", methods={"PUT"})
     * @return JsonResponse
     */
    public function updateAction($id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $project = $entityManager->getRepository(GitProject::class)->find($id);

        if (!$project) {
            return new JsonResponse([
                'message' => 'Project not found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $request = $request->request;

        if ($request->get('git_name')) {
            $project->setName($request->get('git_name'));
        }
        if ($request->get('git_address')) {
            $project->setGitAddress($request->get('git_address'));
        }
        if ($request->get('git_project_address')) {
            $project->setProjectAddress($request->get('git_project_address'));
        }

        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Project updated successfully'
        ], JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/project/{id}", name="delete_project", methods={"DELETE"})
     * @return Object
     */
    public function deleteAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $project = $entityManager->getRepository(GitProject::class)->find($id);

        if (!$project) {
            return new JsonResponse([
                'message' => 'Project not found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $name = $project->getName();
        $entityManager->remove($project);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Deleted this project: ' . $name
        ], JsonResponse::HTTP_OK);

    }

}