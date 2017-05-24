<?php

namespace Kaliber5\SerializerBundle\DependencyInjection\Compiler;

use Kaliber5\SerializerBundle\Serializer\JsonApiSerializationVisitor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class SerializerPass
 *
 * @package Kaliber5\SerializerBundle\DependencyInjection\Compiler
 */
class SerializerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $container->getDefinition('jms_serializer.json_serialization_visitor')
            ->setClass(JsonApiSerializationVisitor::class);
        ;
    }
}
