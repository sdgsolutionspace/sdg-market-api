<?php
/**
 * Created by PhpStorm.
 * User: Nikolaos Doulgeridis
 * Website: http://www.nickdoulgeris.com
 * Email: nickdoulgeridis@gmail.com
 * Date: 10/14/18
 * Time: 4:35 PM
 */

namespace App\EventListener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class JWTCreatedListener
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @param TokenStorage $tokenStorage
     */
    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $payload = $event->getData();
        /** @var User $user */
        $user = $event->getUser();
        $payload['user']['id'] = $user->getId();
        $payload['user']['username'] = $user->getUsername();
        $payload['user']['email'] = $user->getEmail();
        $payload['user']['timezone'] = $user->getTimezone();
        $payload['user']['roles'] = $user->getRoles();
        $payload['user']['name'] = $user->getName();
        $payload['user']['github_id'] = $user->getGithubId();
        $payload['user']['blacklisted'] = $user->getBlackListed();
        $event->setData($payload);
    }
}