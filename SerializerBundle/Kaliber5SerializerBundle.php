<?php

namespace Kaliber5\SerializerBundle;

use Kaliber5\SerializerBundle\DependencyInjection\Compiler\SerializerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class Kaliber5SerializerBundle
 *
 * @package Kaliber5\SerializerBundle
 */
class Kaliber5SerializerBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SerializerPass());
    }
}
