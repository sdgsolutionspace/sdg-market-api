<?php

namespace App\Controller;

use App\Entity\GitProject;
use App\Entity\User;
use App\Form\Type\UserType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as FOSRest;

/**
 * Class UserController.
 *
 * @FOSRest\NamePrefix(value="api_v1_users_")
 */
class UserController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get all users.
     *
     * @return object
     */
    public function cgetAction()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $users;
    }

    /**
     * Get single user by id.
     *
     * @param User $user
     *
     * @return null|object
     */
    public function getAction(User $user)
    {
        return $user;
    }

    /**
     * Blacklist user.
     *
     * @param User $user
     *
     * @return User
     */
    public function blacklistAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $user->setBlackListed(1);
        $em->flush();

        return $user;
    }

    /**
     * Assign role.
     *
     * @param User $user
     * @param $role
     *
     * @return User
     */
    public function assignRoleAction(User $user, $role)
    {
        $em = $this->getDoctrine()->getManager();
        $user->setRole($role);
        $em->flush();

        return $user;
    }

    /**
     * Register User.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function postAction(Request $request)
    {
        $gitProject = $this->getDoctrine()
            ->getRepository(GitProject::class)
            ->findOneBy(['id' => $request->request->get('git_id')]);

        $gitProject = $this->getOrCreateGitProject($request, $gitProject);
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user, ['csrf_protection' => false]);

        $data = $request->request->all();
        $userForm->submit($data);
        if ($userForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user->setTimezone('Europe/Berlin');
            $user->setActive(false);
            $user->setGithubId($gitProject->getId());
            $em->persist($user);
            $em->flush();

            return $userForm;
        } else {
            return $userForm;
        }
    }

    public function deleteAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return ['success' => 'OK'];
    }

    /**
     * Send registration email.
     *
     * @param $user
     */
    protected function sendRegistrationEmail($user)
    {
    }

    /**
     * @param Request $request
     * @param $gitProject
     * @return GitProject
     */
     private function getOrCreateGitProject(Request $request, $gitProject): GitProject
     {
         if (!$gitProject) {
             //create git project
             $gitProject = new GitProject();
             //$gitProject->setId($request->request->get('git_id'));
             $gitProject->setName($request->request->get('git_name'));
             $gitProject->setGitAddress($request->request->get('git_address'));
             $gitProject->setProjectAddress($request->request->get('git_project_address'));
         }

         return $gitProject;
     }
}
