<?php


namespace App\Controller;

use App\Entity\GitProject;
use App\Entity\User;
use Doctrine\ORM\Query;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use phpDocumentor\Reflection\Types\Object_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UserController extends FOSRestController
{
    /**
     * Get all users
     *
     * @Route("/users", name="get_users", methods={"GET"})
     *
     * @return Object
     */
    public function cgetAction()
    {
        $query = $this->getDoctrine()
            ->getRepository('App\Entity\User')
            ->createQueryBuilder('c')
            ->getQuery();
        $users = $query->getResult(Query::HYDRATE_ARRAY);
        if (!$users) {
            return new JsonResponse([
                'message' => 'User not found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'data' => $users
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Get single user by id
     *
     * @Route("/users/{id}", name="get_user", methods={"GET"})
     *
     * @return array
     */
    public function getUserAction($id)
    {

        $user = $this->getDoctrine()
            ->getRepository('App\Entity\User')
            ->find($id);

        if (!$user) {
            return new JsonResponse([
                'message' => 'User not found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'data' => $user
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Blacklist user
     *
     * @Route("/users/{id}/blacklist", name="blacklist_user", methods={"PATCH"})
     *
     * @return array
     */
    public function patchUserBlacklistAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em
            ->getRepository('App\Entity\User')
            ->find($id);

        if (!$user) {
            return new JsonResponse([
                'message' => 'User not found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        if ($user->getBlackListed()) {
            return new JsonResponse([
                'message' => 'User already in blacklist'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user->setBlackListed(1);

        $em->flush();

        return new JsonResponse([
            'message' => 'User successfully blacklisted'
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Assign role
     *
     * @Route("/users/{id}/assignrole", name="user_assign_role", methods={"GET"})
     *
     * @return array
     */
    public function patchUserAssignroleAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em
            ->getRepository('App\Entity\User')
            ->find($id);

        if (!$user) {
            return new JsonResponse([
                'message' => 'User not found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        if ($user->getRole()) {
            return new JsonResponse([
                'message' => 'User role already assigned'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user->setRole($request->request->get('role'));

        $em->flush();

        return new JsonResponse([
            'message' => 'User role successfully assigned'
        ], JsonResponse::HTTP_OK);
    }


    /**
     * Register User.
     *
     * @FOSRest\Post("/users/register")
     *
     * @return array
     */
    public function postRegisterAction(Request $request)
    {
        $gitProject = $this->getDoctrine()
            ->getRepository('App\Entity\GitProject')
            ->findOneBy(['id' => $request->request->get('git_id')]);

        //check git project exist
        if (!$gitProject) {
            //create git project
            $gitProject = new GitProject();
            $gitProject->setId($request->request->get('git_id'));
            $gitProject->setName($request->request->get('git_name'));
            $gitProject->setGitAddress($request->request->get('git_address'));
            $gitProject->setProjectAddress($request->request->get('git_project_address'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($gitProject);
            $em->flush();
        }

        $user = new User();

        $user->setEmail($request->request->get('email'));
        $user->setUsername($request->request->get('username'));
        $user->setTimezone('Europe/Berlin');
        $user->setActive(false);
        $user->setGithubId($gitProject->getId());

        $errors = $this->validate($user);

//        if (count($errors) > 0) {
//            /*
//             * Uses a __toString method on the $errors variable which is a
//             * ConstraintViolationList object. This gives us a nice string
//             * for debugging.
//             */
//            $errorsString = (string)$errors;
//
//            return new JsonResponse([
//                'message' => 'Validation Errors',
//                'errors' => $errorsString
//            ], JsonResponse::HTTP_BAD_REQUEST);
//        }


        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        if (!$user) {
            return new JsonResponse(
                [
                    'message' => 'Failed to create user'
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        // @todos sendRegistrationEmail
        $this->sendRegistrationEmail($user);

        return new JsonResponse([
            'message' => 'User created successfully'
        ], JsonResponse::HTTP_CREATED);

    }

    /**
     * Send registration email
     * @param $user
     */
    protected function sendRegistrationEmail($user)
    {

    }

}