<?php

namespace Kaliber5\SerializerBundle\Hateoas;

use Hateoas\Configuration\Route;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\Factory\PagerfantaFactory as BasePagerfantaFactory;
use Pagerfanta\Pagerfanta;

/**
 * Class PagerfantaFactory
 */
class PagerfantaFactory extends BasePagerfantaFactory
{

    /**
     * @param Pagerfanta $pager  The pager
     * @param Route      $route  The collection's route
     * @param mixed      $inline Most of the time, a custom `CollectionRepresentation` instance
     *
     * @return PaginatedRepresentation
     */
    public function createRepresentation(Pagerfanta $pager, Route $route, $inline = null)
    {
        if (null === $inline) {
            $inline = new CollectionRepresentation($pager->getCurrentPageResults());
        }

        return new PaginatedRepresentation(
            $inline,
            $route->getName(),
            $route->getParameters(),
            $pager->getCurrentPage(),
            $pager->getMaxPerPage(),
            $pager->getNbPages(),
            $this->getPageParameterName(),
            $this->getLimitParameterName(),
            $route->isAbsolute(),
            $pager->getNbResults()
        );
    }
}
