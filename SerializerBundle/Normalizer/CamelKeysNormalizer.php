<?php

namespace Kaliber5\SerializerBundle\Normalizer;

use FOS\RestBundle\Normalizer\ArrayNormalizerInterface;
use FOS\RestBundle\Normalizer\Exception\NormalizationException;
use FOS\RestBundle\Normalizer\CamelKeysNormalizer as BaseCamelKeysNormalizer;

/**
 * CamelKeys Normalizer for FOSRestBundle to support JSON:API encoded payload for POST/PATCH requests
 * Enable this in your config:
 *
 * ```yaml
 * fos_rest:
 *   body_listener:
 *     decoders:
 *       json: kaliber5.decoder.json_api
*      array_normalizer: kaliber5.normalizer.camel_keys
 * ```
 */
class CamelKeysNormalizer extends BaseCamelKeysNormalizer implements ArrayNormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize(array $data)
    {
        $this->normalizeArray($data);

        return $data;
    }

    /**
     * Normalizes a string.
     *
     * @param string $string
     *
     * @return string
     */
    protected function normalizeString($string)
    {
        $string = parent::normalizeString($string);

        if (false === strpos($string, '-')) {
            return $string;
        }

        if (preg_match('/^(-+)(.*)/', $string, $matches)) {
            $underscorePrefix = $matches[1];
            $string = $matches[2];
        } else {
            $underscorePrefix = '';
        }

        $string = preg_replace_callback(
            '/-([a-zA-Z0-9])/',
            function ($matches) {
                return strtoupper($matches[1]);
            },
            $string
        );

        return $underscorePrefix.$string;
    }

    /**
     * Normalizes an array.
     *
     * @param array &$data
     *
     * @throws NormalizationException
     */
    private function normalizeArray(array &$data)
    {
        foreach ($data as $key => $val) {
            $normalizedKey = $this->normalizeString($key);

            if ($normalizedKey !== $key) {
                if (array_key_exists($normalizedKey, $data)) {
                    throw new NormalizationException(
                        sprintf(
                            'The key "%s" is invalid as it will override the existing key "%s"',
                            $key,
                            $normalizedKey
                        )
                    );
                }

                unset($data[$key]);
                $data[$normalizedKey] = $val;
                $key = $normalizedKey;
            }

            if (is_array($val)) {
                $this->normalizeArray($data[$key]);
            }
        }
    }
}