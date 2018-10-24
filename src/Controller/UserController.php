<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\GitProject;
use App\Form\Type\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Transaction;

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
     * @Security("has_role('ROLE_ADMIN')")
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
     *
     * @param User $username
     *
     * @return null|object
     */
    public function getAction($username, EntityManagerInterface $em)
    {
        return $em->getRepository('App:User')->findOneBy([
            'username' => $username,
        ]);
    }

    /**
     * @return mixed
     */
    public function getMeAction()
    {
        $em = $this->getDoctrine()->getManager();

        if ($this->getUser()) {
            $user = $em->getRepository(User::class)->findOneBy(['username' => $this->getUser()->getUsername()]);
        }

        if (!$user) {
            return null;
        }

        return $em->getRepository('App:User')->findUserWithSold($user);
    }

    /**
     * Blacklist user.
     *
     * @Security("has_role('ROLE_ADMIN')")
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
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param User $user
     * @param $role
     *
     * @return User
     */
    public function assignRoleAction(User $user, $role)
    {
        $em = $this->getDoctrine()->getManager();
        $user->addRole($role);
        $em->flush();

        return $user;
    }

    /**
     * Register User.
     *
     * @Security("has_role('ROLE_ADMIN')")
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
            // Create the SDG offer
            $sdgOffer = new Transaction();
            $sdgOffer
                ->setToUser($user)
                ->setNbSdg(200)
                ->setTransactionLabel(Transaction::SUBSCRIPTION_SDG_CREDIT);
            $em->persist($sdgOffer);

            $em->flush();

            return $userForm;
        } else {
            return $userForm;
        }
    }

    /**
     * @Get("/user/refresh-token")
     *
     * @return array
     */
    public function userTokenRefreshAction()
    {
        $jwtManager = $this->container->get('lexik_jwt_authentication.jwt_manager');

        return ['token' => $jwtManager->create($this->getUser())];
    }

    /**
     * @param Request $request
     * @param $gitProject
     *
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
