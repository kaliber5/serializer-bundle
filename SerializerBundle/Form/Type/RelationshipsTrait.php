<?php
/**
 * Created by PhpStorm.
 * User: andreasschacht
 * Date: 06.09.16
 * Time: 18:36
 */

namespace Kaliber5\SerializerBundle\Form\Type;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * Trait RelationshipsTrait
 *
 * this trait moves the primary keys of relationships-attributes from a JSONAPI-Response
 * up to the form data and removes them from the submitted data
 *
 * @package Kaliber5\SerializerBundle\Form\Type
 */
trait RelationshipsTrait
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    /**
     * Got Payment Method from the relationship
     *
     * @param FormEvent $formEvent
     */
    public function preSubmit(FormEvent $formEvent)
    {
        $data = $formEvent->getData();
        if (isset($data['relationships'])) {
            foreach ($data['relationships'] as $relationship => $relData) {
                if (isset($relData['data']['id'])) {
                    $data[$relationship] = $relData['data']['id'];
                }
            }
            unset($data['relationships']);
            $formEvent->setData($data);
        }
    }
}
