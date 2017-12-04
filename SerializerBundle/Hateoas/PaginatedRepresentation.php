<?php

namespace Kaliber5\SerializerBundle\Hateoas;

use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation as BasePaginatedRepresentation;
use Webmozart\Assert\Assert;

/**
 * Class PaginatedRepresentation
 */
class PaginatedRepresentation extends BasePaginatedRepresentation implements \IteratorAggregate
{
    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        Assert::isInstanceOf($this->getInline(), CollectionRepresentation::class);

        return new \ArrayIterator($this->getInline()->getResources());
    }
}
