<?php
/**
 * Created by PhpStorm.
 * User: andreasschacht
 * Date: 28.04.16
 * Time: 18:03
 */

namespace Kaliber5\SerializerBundle\Serializer;

use Mango\Bundle\JsonApiBundle\Serializer\JsonApiSerializationVisitor as MangoJsonApiSerializationVisitor;

/**
 * Class JsonApiSerializationVisitor
 *
 * Added Error handling
 *
 * @package Kaliber5\SerializerBundle\Serializer
 */
class JsonApiSerializationVisitor extends MangoJsonApiSerializationVisitor
{

    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        if (false === $this->isJsonApiDocument) {
            return parent::getResult();
        }

        $root = $this->getRoot();

        if (isset($root['data']) && array_key_exists('errors', $root['data'])) {
            if ($this->showVersionInfo) {
                $root['jsonapi'] = [
                    'version' => '1.0',
                ];
            }
            $this->setRoot($root['data']);
            $this->isJsonApiDocument = false;
        }
        return parent::getResult();
    }
}
