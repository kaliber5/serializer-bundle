<?php

namespace Kaliber5\SerializerBundle\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\Pool;

/**
 * Class MediaImageHandler
 * @package Kaliber5\SerializerBundle\Handler
 *
 * support Sonata media thumbnails
 *
 * set type type mediaimage on config
 */
class MediaImageHandler implements SubscribingHandlerInterface
{
    /**
     * @var Pool
     */
    protected $pool;

    /**
     * MediaImageHandler constructor.
     *
     * @param Pool $pool
     */
    public function __construct(Pool $pool)
    {
        $this->pool = $pool;
    }

    /**
     * @return array
     */
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'mediaimage',
                'method'    => 'serializeMediaImageThumbnailsToArray',
            ],
        ];
    }

    /**
     * @param JsonSerializationVisitor $visitor
     * @param MediaInterface           $media
     * @param array                    $type
     * @param Context                  $context
     *
     * @return array
     */
    public function serializeMediaImageThumbnailsToArray(JsonSerializationVisitor $visitor, MediaInterface $media, array $type, Context $context)
    {
        $thumbnails = array();
        $provider = $this->pool
            ->getProvider($media->getProviderName());
        $context = $media->getContext();

        $formats = $this->pool->getFormatNamesByContext($context);

        foreach ($formats as $formatFullName => $formatDefinition) {
            $formatShortName = str_replace($context.'_', '', $formatFullName);
            $thumbnails[$formatShortName] = $provider->generatePublicUrl($media, $formatFullName);
        }

        return $thumbnails;
    }
}
