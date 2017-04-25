<?php

namespace Moip;

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
     * Links constructor.
     *
     * @param \stdClass $links "_link" returned from the API, if there isn't any.
     */
    public function __construct(\stdClass $links)
    {
        $this->parseLinks($links);
    }

    /**
     * @param \stdClass $links
     */
    private function parseLinks(\stdClass $links)
    {
        foreach (get_object_vars($links) as $property => $value) {
            if ($property == 'checkout') { // nested links? eg: checkout=>payOnlineBankDebitItau,payCreditCard...
                $this->parseLinks($value);
            } else {
                $this->links[$property] = new Link($property, $value);
            }
        }
    }

    /**
     * Returns all the links from the HATEOAS structure.
     *
     * @return array[string]Link.
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param $name string Links name, e.g: "self".
     *
     * @return null|Link Link object of the corresponding $name or null if the link doesn't exist.
     */
    public function getLink($name)
    {
        if (!isset($this->links[$name])) {
            return;
        }

        return $this->links[$name];
    }
}
