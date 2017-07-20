<?php

namespace Kaliber5\SerializerBundle\EntryPoint;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

/**
 * Class Api
 *
 * @package Kaliber5\SerializerBundle\EntryPoint
 */
class Api implements AuthenticationEntryPointInterface
{

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse(array("message" => "Unauthorized"), 403);
    }
}
