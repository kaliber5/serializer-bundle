<?php

namespace Kaliber5\SerializerBundle\Handler;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

/**
 * Class LogoutHandler
 *
 * @package Kaliber5\SerializerBundle\Handler
 */
class LogoutHandler implements LogoutSuccessHandlerInterface
{
    /**
     * @var \Symfony\Component\Security\Http\HttpUtils
     */
    protected $httpUtils;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * LogoutHandler constructor.
     *
     * @param HttpUtils        $httpUtils
     * @param SessionInterface $session
     */
    public function __construct(HttpUtils $httpUtils, SessionInterface $session)
    {
        $this->httpUtils = $httpUtils;
        $this->session = $session;
    }

    /**
     * Creates a Response object to send upon a successful logout.
     *
     * @param Request $request
     *
     * @return Response never null
     */
    public function onLogoutSuccess(Request $request)
    {
        $this->session->clear();

        return new JsonResponse(array("logout" => true));
    }
}
