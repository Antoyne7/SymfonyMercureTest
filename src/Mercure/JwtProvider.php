<?php


namespace App\Mercure;


use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class JwtProvider
{

    private $secret;
    public function __construct(string $secret, TokenGeneratorInterface $tokenGenerator)
    {
//        $tokenGenerator->generateToken()
        $this->secret = new Key($secret);
    }

    public function __invoke() : string
    {
        $signer = new Sha256();
        return (new Builder())
            ->withClaim('mercure', ['publish' => ['*']])
            ->getToken($signer,$this->secret);

    }

}