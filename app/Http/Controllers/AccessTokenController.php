<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 21/09/2018
 * Time: 09:51 AM
 */

namespace App\Http\Controllers;


use GuzzleHttp\Exception\ClientException;
use Laravel\Passport\Http\Controllers\AccessTokenController as PassportAccessTokenController;
use Laravel\Passport\Http\Controllers\HandlesOAuthErrors;
use Laravel\Passport\TokenRepository;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;
use Lcobucci\JWT\Parser as JwtParser;
use Zend\Diactoros\Response as Psr7Response;


class AccessTokenController extends PassportAccessTokenController
{
    /**
     * Authorize a client to access the user's account.
     *
     * @param ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws OAuthServerException
     */
    public function issueToken(ServerRequestInterface $request)
    {
        try {
            return $this->server->respondToAccessTokenRequest($request, new Psr7Response);
        } catch (ClientException $exception) {
            $error = json_decode($exception->getResponse()->getBody());

            throw OAuthServerException::invalidRequest('access_token', object_get($error, 'error.message'));
        }
    }
}
