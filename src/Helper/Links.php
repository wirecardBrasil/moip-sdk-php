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
     * @param null|string $link
     *
     * @return mixed
     */
    public function getLink($link)
    {
        if (isset($this->links->$link->redirectHref)) {
            return $this->links->$link->redirectHref;
        }

        if (isset($this->links->$link->href)) {
            return $this->links->$link->href;
        }

        return $this->links->$link;
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

        if (isset($this->links->checkout)) {
            foreach ($this->links->checkout as $method => $link) {
                $this->checkout->$method = $link->redirectHref;
            }
        }
    }
}
