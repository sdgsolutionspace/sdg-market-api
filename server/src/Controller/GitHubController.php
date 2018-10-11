<?php

/**
 * Created by PhpStorm.
 * User: Nikolaos Doulgeridis
 * Website: http://www.nickdoulgeris.com
 * Email: nickdoulgeridis@gmail.com
 * Date: 10/10/18
 * Time: 7:48 PM.
 */

namespace App\Controller;

use App\Entity\User;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GithubResourceOwner;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Github\Client;

class GitHubController extends Controller
{
    /**
     * Link to this controller to start the "connect" process.
     *
     * @Route("/connect/github", name="connect_github_start")
     *
     * @param ClientRegistry $clientRegistry
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
        // will redirect to GitHub!
        return $clientRegistry
            ->getClient('github')
            ->redirect(['user', 'user:email', 'repo']);
    }

    /**
     * After going to GitHub, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml.
     *
     * @Route("/connect/github/check", name="connect_github_check")
     *
     * @param ClientRegistry $clientRegistry
     *
     * @return JsonResponse
     *
     * @throws IdentityProviderException
     */
    public function connectCheckAction(ClientRegistry $clientRegistry)
    {
        /** @var \KnpU\OAuth2ClientBundle\Client\Provider\GithubClient $client */
        $client = $clientRegistry->getClient('github');
        try {
            /** @var \League\OAuth2\Client\Provider\Github $apiUser */
            $accessToken = $client->getAccessToken();
            $apiUser = $client->fetchUserFromToken($accessToken);
            $dbUser = $this->syncUser($apiUser, $accessToken);
            $jwtManager = $this->container->get('lexik_jwt_authentication.jwt_manager');

            return new JsonResponse(['token' => $jwtManager->create($dbUser)]);
        } catch (IdentityProviderException $e) {
            throw $e;
        }
    }

    /**
     * @param GithubResourceOwner $apiUser
     * @param $accessToken
     *
     * @return User|null|object
     */
    private function syncUser(GithubResourceOwner $apiUser, $accessToken)
    {
        $em = $this->getDoctrine()->getManager();
        $dbUser = $em->getRepository(User::class)->findOneBy(['githubId' => $apiUser->getId()]);
        if ($dbUser === null) {
            $dbUser = new User();
            $dbUser->setName($apiUser->getName());
            $dbUser->setGithubId($apiUser->getId());
            $dbUser->setEmail($this->getPrimaryEmail($accessToken));
            $dbUser->setUsername($apiUser->getNickname());
            $dbUser->setTimezone('Europe/Paris');
        }
        $dbUser->setAccessToken($accessToken);
        $em->persist($dbUser);
        $em->flush();

        return $dbUser;
    }

    /**
     * @param $accessToken
     *
     * @return string
     */
    private function getPrimaryEmail($accessToken): string
    {
        $githubApi = new Client();
        $githubApi->authenticate($accessToken, null, Client::AUTH_HTTP_TOKEN);
        $email = array_filter($githubApi->currentUser()->emails()->all(), function ($email) {
            return $email['primary'] === true;
        });

        return $email[0]['email'];
    }
}
