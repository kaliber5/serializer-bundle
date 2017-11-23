<?php

namespace Kaliber5\SerializerBundle\Handler;

use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationFailureHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthenticationFailureHandler
 *
 * @package Kaliber5\SerializerBundle\Handler
 */
class AuthenticationFailureHandler extends DefaultAuthenticationFailureHandler
{

    /**
     * AuthenticationFailureHandler constructor.
     *
     * @param HttpKernelInterface  $httpKernel
     * @param HttpUtils            $httpUtils
     * @param array                $options
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        HttpKernelInterface $httpKernel,
        HttpUtils $httpUtils,
        array $options,
        LoggerInterface $logger = null
    ) {
        parent::__construct($httpKernel, $httpUtils, $options, $logger);
    }

    /**
     * Returns a JsonResponse with code 401 for xml requests
     *
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // Return JSON when client requested this
        // More reliable that $request->isXmlHttpRequest()
        if (in_array('application/json', $request->getAcceptableContentTypes())) {
            $result = ['success' => false, 'message' => $exception->getMessage()];
            $response = new JsonResponse($result, 401);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        } else {
            return parent::onAuthenticationFailure($request, $exception);
        }
    }
}
