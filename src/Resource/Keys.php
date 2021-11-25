<?php

namespace Moip\Resource;

use stdClass;

/**
 * Class Keys.
 */
class Keys extends MoipResource
{
    /**
     * @const string
     */
    const PATH = 'keys';

    /**
     * Initializes new instances.
     */
    public function initialize()
    {
        $this->data = new stdClass();
    }

    /**
     * Mount keys structure.
     *
     * @param \stdClass $response
     *
     * @return $this
     */
    protected function populate(stdClass $response)
    {
        $keys = clone $this;

        $resp = $response->keys;
        $keys->data->basicAuth = $this->getIfSet('basicAuth', $resp);
        $keys->data->encryption = $this->getIfSet('encryption', $resp);

        return $keys;
    }

    /**
     * Get encryption.
     *
     * @return string
     */
    public function getEncryption()
    {
        return $this->getIfSet('encryption');
    }

    /**
     * Get Basic Auth.
     *
     * @return stdClass
     */
    public function getBasicAuth()
    {
        return $this->getIfSet('basicAuth');
    }

    /**
     * Get keys.
     *
     * @return stdClass
     */
    public function get()
    {
        return $this->getByPath(sprintf('/%s/%s', MoipResource::VERSION, self::PATH));
    }
}
