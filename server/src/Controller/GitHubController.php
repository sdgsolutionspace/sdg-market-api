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
use Github\Client;
use GuzzleHttp\RequestOptions;
use JMS\Serializer\Serializer;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\Github;
use League\OAuth2\Client\Provider\GithubResourceOwner;
use function PHPSTORM_META\type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GitHubController extends Controller
{
    /**
     * After going to GitHub, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml.
     *
     * @Route("/connect/github/check", name="connect_github_check")
     *
     * @param ClientRegistry $clientRegistry
     *
     * @return Response|JsonResponse
     *
     * @throws IdentityProviderException
     */
    public function connectCheckAction(Request $request)
    {
        $github = new Github();

        $code = $request->get('code');
        $state = $request->get('state');

        $client = new \GuzzleHttp\Client();
        $response = $client->post($github->getBaseAccessTokenUrl([]), [
            'headers' => [
                'Accept'     => 'application/json',
            ],
            RequestOptions::JSON => [
                'client_id' => getenv('OAUTH_GITHUB_CLIENT_ID'),
                'client_secret' => getenv('OAUTH_GITHUB_CLIENT_SECRET'),
                'code' => $code,
                'state' => $state
            ]

        ]);

        try {
            $responseData = json_decode($response->getBody()->getContents(), true);
            if(isset($responseData['error']) || !isset($responseData['access_token'])){
                return new JsonResponse($responseData, 500);
            }
            $accessToken = $responseData['access_token'];

            $githubApi = new Client();
            $githubApi->authenticate($accessToken, null, Client::AUTH_HTTP_TOKEN);
            $apiUser = $githubApi->currentUser()->show();

            $apiUser = new GithubResourceOwner($apiUser);

            $dbUser = $this->syncUser($apiUser, $accessToken);
            $jwtManager = $this->container->get('lexik_jwt_authentication.jwt_manager');

            return new JsonResponse(['token' => $jwtManager->create($dbUser)]);
        }catch (\Exception $ex){
            return new JsonResponse($ex->getMessage());
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
