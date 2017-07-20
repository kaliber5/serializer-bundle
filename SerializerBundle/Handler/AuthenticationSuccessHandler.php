<?php

namespace Kaliber5\SerializerBundle\Handler;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Http\HttpUtils;

/**
 * Class AuthenticationSuccessHandler
 *
 * @package Kaliber5\SerializerBundle\Handler
 */
class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * AuthenticationSuccessHandler constructor.
     *
     * @param HttpUtils           $httpUtils
     * @param array               $options
     * @param SerializerInterface $serializer
     */
    public function __construct(HttpUtils $httpUtils, array $options, SerializerInterface $serializer)
    {
        parent::__construct($httpUtils, $options);
        $this->serializer = $serializer;
    }

    /**
     * @param Request        $request
     * @param TokenInterface $token
     * @return Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $data = $this->serializer->serialize($token->getUser(), 'json');
        $response = new JsonResponse();
        $response->setContent($data);

        return $response;
    }
}
