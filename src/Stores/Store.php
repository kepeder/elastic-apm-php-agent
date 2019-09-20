<?php

namespace Kepeder\Stores;

use Kepeder\Events\EventBean;

/**
 *
 * Registry for captured the Events
 *
 */
class Store implements \JsonSerializable
{
    /**
     * Set of Events
     *
     * @var array of \Kepeder\Events\EventBean
     */
    protected $store = [];

    /**
     * Get all Registered Errors
     *
     * @return array of \Kepeder\Events\EventBean
     */
    public function theList()
    {
        return $this->store;
    }

    /**
     * Is the Store Empty ?
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->store);
    }

    /**
     * Empty the Store
     *
     * @return void
     */
    public function reset()
    {
        $this->store = [];
    }

    /**
     * Serialize the Events Store
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->store;
    }
}
