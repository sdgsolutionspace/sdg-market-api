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

use Github\Client;
use App\Entity\User;
use App\Entity\Transaction;
use GuzzleHttp\RequestOptions;
use League\OAuth2\Client\Provider\Github;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use League\OAuth2\Client\Provider\GithubResourceOwner;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GitHubController extends Controller
{
    /**
     * After going to GitHub, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml.
     *
     * @Route("/api/v1/connect/github/check", name="github_auth_check")
     *
     * @param Request $request
     *
     * @return Response|JsonResponse
     */
    public function connectCheckAction(Request $request)
    {
        try {
            $github = new Github();

            $code = $request->get('code');
            $state = $request->get('state');

            $client = new \GuzzleHttp\Client();
            $response = $client->post($github->getBaseAccessTokenUrl([]), [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                RequestOptions::JSON => [
                    'client_id' => getenv('OAUTH_GITHUB_CLIENT_ID'),
                    'client_secret' => getenv('OAUTH_GITHUB_CLIENT_SECRET'),
                    'code' => $code,
                    'state' => $state,
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);
            if (isset($responseData['error']) || !isset($responseData['access_token'])) {
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
        } catch (\Exception $ex) {
            return new JsonResponse($ex->getMessage(), 500);
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
            $dbUser->setName($apiUser->getName() ? $apiUser->getName() : 'None');
            $dbUser->setGithubId($apiUser->getId());
            $dbUser->setEmail($this->getPrimaryEmail($accessToken));

            if (!$dbUser->getEmail()) {
                throw new \Exception('Unable to retrieve your email address from Github');
            }

            $dbUser->setUsername($apiUser->getNickname());
            $dbUser->setTimezone('Europe/Paris');
            $em->persist($dbUser);
            // Create the SDG offer
            $sdgOffer = new Transaction();
            $sdgOffer
                ->setToUser($dbUser)
                ->setNbSdg(10000)
                ->setTransactionLabel(Transaction::SUBSCRIPTION_SDG_CREDIT);
            $em->persist($sdgOffer);
        } else {
            $dbUser->setEmail($this->getPrimaryEmail($accessToken));
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

        return array_pop($email)['email'];
    }
}
