<?php

namespace spec\Kaliber5\SerializerBundle\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\JsonSerializationVisitor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sonata\MediaBundle\Model\Media;
use Sonata\MediaBundle\Provider\MediaProviderInterface;
use Sonata\MediaBundle\Provider\Pool;

class MediaImageHandlerSpec extends ObjectBehavior
{
    function let(Pool $pool)
    {
        $this->beConstructedWith($pool);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Kaliber5\SerializerBundle\Handler\MediaImageHandler');
    }

    function it_subscribes_methods_for_media_image()
    {
        $methods = $this::getSubscribingMethods();
        $methods->shouldBeArray();
        $methods->shouldHaveCount(1);
        $methods[0]->shouldHaveKeyWithValue('format', 'json');
        $methods[0]->shouldHaveKeyWithValue('method', 'serializeMediaImageThumbnailsToArray');
        $methods[0]->shouldHaveKeyWithValue('direction', 1);
        $methods[0]->shouldHaveKeyWithValue('type', 'mediaimage');
    }

    function it_serialize_media_image_to_thumbnail_array(JsonSerializationVisitor $visitor, Media $media, Context $context, MediaProviderInterface $mediaProvider, Pool $pool)
    {
        $mediacontext = 'default';
        $media->getContext()->willReturn($mediacontext);
        $media->getProviderName()->willReturn($mediacontext);
        $pool->getProvider($mediacontext)->willReturn($mediaProvider);
        $pool->getFormatNamesByContext($mediacontext)->willReturn(array(
            $mediacontext.'_small'=>'foo',
            $mediacontext.'_large' => 'bar'
        ));
        $mediaProvider->generatePublicUrl($media, $mediacontext.'_small')->willReturn('http://img.de/small.jpg');
        $mediaProvider->generatePublicUrl($media, $mediacontext.'_large')->willReturn('http://img.de/large.jpg');

        $imageArray = $this->serializeMediaImageThumbnailsToArray($visitor, $media, array(), $context);
        $imageArray->shouldBeArray();
        $imageArray->shouldHaveKeyWithValue('small', 'http://img.de/small.jpg');
        $imageArray->shouldHaveKeyWithValue('large', 'http://img.de/large.jpg');

    }
}
