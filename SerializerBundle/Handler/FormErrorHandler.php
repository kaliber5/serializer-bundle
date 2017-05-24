<?php

namespace Kaliber5\SerializerBundle\Handler;

use JMS\Serializer\Handler\FormErrorHandler as JMSFormErrorHandler;
use JMS\Serializer\JsonSerializationVisitor;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class FormErrorHandler
 *
 * A Form Error Handler fro JSONApi-Error Response
 *
 * @package Kaliber5\SerializerBundle\Handler
 */
class FormErrorHandler extends JMSFormErrorHandler
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * FormErrorHandler constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct($translator);
        $this->translator = $translator;
    }

    /**
     * @param JsonSerializationVisitor $visitor
     * @param Form                     $data
     * @param array                    $type
     *
     * @return \ArrayObject
     */
    public function serializeFormToJson(JsonSerializationVisitor $visitor, Form $data, array $type)
    {
        $isRoot = null === $visitor->getRoot();

        $form = new \ArrayObject();
        $errors = [];
        foreach ($data->getErrors(true) as $error) {
            /** @var FormError $error */
            $errors[] = [
                'title'  => "{$error->getOrigin()->getPropertyPath()} is invalid",
                'detail' => $this->getErrorMessage($error)
            ];
        }

        if ($errors) {
            $form['errors'] = $errors;
        }

        if ($isRoot) {
            $visitor->setRoot($form);
        }

        return $form;
    }

    private function getErrorMessage(FormError $error)
    {
        if (null !== $error->getMessagePluralization()) {
            return $this->translator->transChoice(
                $error->getMessageTemplate(),
                $error->getMessagePluralization(),
                $error->getMessageParameters(),
                'validators'
            );
        }

        return $this->translator->trans($error->getMessageTemplate(), $error->getMessageParameters(), 'validators');
    }
}
