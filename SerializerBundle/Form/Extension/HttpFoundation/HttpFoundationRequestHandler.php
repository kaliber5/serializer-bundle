<?php

namespace Kaliber5\SerializerBundle\Form\Extension\HttpFoundation;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\RequestHandlerInterface;
use Symfony\Component\Form\Util\ServerParams;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class HttpFoundationRequestHandler
 *
 * This class injects the the field values without compare forms name
 */
class HttpFoundationRequestHandler implements RequestHandlerInterface
{
    /**
     * @var ServerParams
     */
    private $serverParams;

    /**
     * {@inheritdoc}
     */
    public function __construct(ServerParams $serverParams = null)
    {
        $this->serverParams = $serverParams ?: new ServerParams();
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(FormInterface $form, $request = null)
    {
        if (!$request instanceof Request) {
            throw new UnexpectedTypeException($request, 'Symfony\Component\HttpFoundation\Request');
        }

        $name = $form->getName();
        $method = $request->getMethod();

        // For request methods that must not have a request body we fetch data
        // from the query string. Otherwise we look for data in the request body.
        if ('GET' === $method || 'HEAD' === $method || 'TRACE' === $method) {
            if ('' === $name) {
                $data = $request->query->all();
            } else {
                // Don't submit GET requests if the form's name does not exist
                // in the request
                if (!$request->query->has($name)) {
                    return;
                }

                $data = $request->query->get($name);
            }
        } else {
            // Mark the form with an error if the uploaded size was too large
            // This is done here and not in FormValidator because $_POST is
            // empty when that error occurs. Hence the form is never submitted.
            if ($this->serverParams->hasPostMaxSizeBeenExceeded()) {
                // Submit the form, but don't clear the default values
                $form->submit(null, false);

                $form->addError(
                    new FormError(
                        call_user_func($form->getConfig()->getOption('upload_max_size_message')),
                        null,
                        ['{{ max }}' => $this->serverParams->getNormalizedIniPostMaxSize()]
                    )
                );

                return;
            }

            if ('' !== $name  && ($request->request->has($name) || $request->files->has($name))) {
                $default = $form->getConfig()->getCompound() ? [] : null;
                $params = $request->request->get($name, $default);
                $files = $request->files->get($name, $default);
            } else {
                $params = $request->request->all();
                $files = $request->files->all();
            }

            if (is_array($params) && is_array($files)) {
                $data = array_replace_recursive($params, $files);
            } else {
                $data = $params ?: $files;
            }
        }
        $form->submit($data, 'PATCH' !== $method);
    }
}
