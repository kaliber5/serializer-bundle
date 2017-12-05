<?php

namespace Kaliber5\SerializerBundle\Hateoas;

use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation as BasePaginatedRepresentation;
use Webmozart\Assert\Assert;

/**
 * Class PaginatedRepresentation
 */
class PaginatedRepresentation extends BasePaginatedRepresentation implements \IteratorAggregate, \Countable
{
    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->ensureCollection()->getResources());
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->ensureCollection()->getResources());
    }

    /**
     * @return CollectionRepresentation
     */
    protected function ensureCollection(): CollectionRepresentation
    {
        Assert::isInstanceOf($this->getInline(), CollectionRepresentation::class);

        return $this->getinline();
    }
}
