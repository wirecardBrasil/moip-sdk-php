<?php

namespace Moip\Resource;

use stdClass;

/**
 * Class Balances.
 */
class Balances extends MoipResource
{
    /**
     * Path balances API.
     *
     * @const string
     */
    const PATH = 'balances';

    /**
     * Initialize a new instance.
     */
    public function initialize()
    {
        $this->data = new stdClass();
        $this->data->unavailable = [];
        $this->data->future = [];
        $this->data->current = [];
    }

    /**
     * Populate this instance.
     *
     * @param stdClass $response response object
     *
     * @return mixed|Balances
     */
    protected function populate(stdClass $response)
    {
        $balances = clone $this;
        $balances->data->unavailable = $this->getIfSet('unavailable', $response) ?: [];
        $balances->data->future = $this->getIfSet('future', $response) ?: [];
        $balances->data->current = $this->getIfSet('current', $response) ?: [];

        return $balances;
    }

    /**
     * Get all balances.
     *
     * @return stdClass
     */
    public function get()
    {
        $path = sprintf('/%s/%s', MoipResource::VERSION, self::PATH);

        return $this->getByPath($path, ['Accept' => static::ACCEPT_VERSION]);
    }

    /**
     * Get unavailable balances. Returns an array of objects with the amount and currency.
     *
     * @return array
     */
    public function getUnavailable()
    {
        return $this->data->unavailable;
    }

    /**
     * Get future balances. Returns an array of objects with the amount and currency.
     *
     * @return array
     */
    public function getFuture()
    {
        return $this->data->future;
    }

    /**
     * Get current balances. Returns an array of objects with the amount and currency.
     *
     * @return array
     */
    public function getCurrent()
    {
        return $this->data->current;
    }
}
