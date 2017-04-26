<?php

namespace Moip\Helper;

use stdClass;

/**
 * Class that represents the HATEOAS structure of the API resources.
 */
class Links
{
    /**
     * @var array[string]Link Map a link name to it's representation.
     */
    private $links;

    /**
     * @var \stdClass
     */
    private $checkout;

    /**
     * Links constructor.
     *
     * @param stdClass $links "_link" returned from the API, if there isn't any.
     */
    public function __construct(stdClass $links)
    {
        $this->links = $links;
        $this->generateMethods();
    }

    /**
     * @return mixed
     */
    public function getSelf()
    {
        return $this->links->self->href;
    }

    /**
     * @param null|string $pay
     *
     * @return mixed
     */
    public function getCheckout($pay)
    {
        return $this->links->checkout->$pay->redirectHref;
    }

    /**
     * @return mixed
     */
    public function getAllCheckout()
    {
        return $this->checkout;
    }

    private function generateMethods()
    {
        $this->checkout = new stdClass();

        foreach ($this->links->checkout as $method => $link) {
            $this->checkout->$method = $link->redirectHref;
        }
    }
}
