<?php


namespace App\Service;


use App\Entity\Utilisateur;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class MercureCookieGenerator
{
    private $secret;

    /**
     * MercureCookieGenerator constructor.
     * @param string $secret
     */
    public function __construct(string $secret, LoggerInterface $logger)
    {
        $logger->log(LogLevel::INFO,"Construction du generateur de cookie");

        $this->secret = new Key($secret);
    }

    public function generate(Utilisateur $user)
    {
        $signer = new Sha256();
        $token = (new Builder())
            ->withClaim('mercure', ['subscribe' => ["ping/{$user->getId()}"]]) // ,"http://monsite.com/user/{$user->getId()}"
            ->getToken($signer, $this->secret);
        return "mercureAuthorization={$token}; Path=/.well-known/mercure; HttpOnly;";
    }
}