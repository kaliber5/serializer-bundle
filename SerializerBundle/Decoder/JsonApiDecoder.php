<?php
/**
 * Created by PhpStorm.
 * User: andreasschacht
 * Date: 06.09.16
 * Time: 17:02
 */

namespace Kaliber5\SerializerBundle\Decoder;

/**
 * Decoder for FOSRestBundle to support JSON:API encoded payload for POST/PATCH requests
 * Enable this in your config:
 *
 * ```yaml
 * fos_rest:
 *   body_listener:
 *     decoders:
 *       json: k5serializer.decoder.json_api
 * ```
 */
class JsonApiDecoder
{

    /**
     * @param string $data
     *
     * @return mixed
     */
    public function decode($data)
    {
        // @todo this probably needs refactoring to use JMSSerializer decoding to support relations!?
        $decoded = @json_decode($data, true);
        $attributes = $decoded['data']['attributes'];
        if (isset($decoded['data']['relationships'])) {
            $attributes['relationships'] = $decoded['data']['relationships'];
        }

        return $attributes;
    }
}