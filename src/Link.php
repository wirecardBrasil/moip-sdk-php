<?php

namespace Moip;

/**
 * Class Link represents a single link from the resource HATEOAS structure.
 */
class Link
{
    /**
     * @var string link href. Currently it's taken from the href or redirectHref property.
     */
    private $href;

    /**
     * @var string|null link title, can be null.
     */
    private $title = null;

    /**
     * @var string link name it's used as key in the Links class.
     */
    private $name;

    /**
     * Link constructor.
     *
     * @param string    $name.
     * @param \stdClass $obj.
     *
     * @throws \InvalidArgumentException if there isn't a href property on the $obj.
     */
    public function __construct($name, $obj)
    {
        $this->name = $name;

        $hasHref = empty($obj->href);
        if ($hasHref && empty($obj->redirectHref)) {
            throw new \InvalidArgumentException('Link is malformed. No href property');
        }

        $this->href = !$hasHref ? $obj->href : $obj->redirectHref;

        if (!empty($obj->title)) {
            $this->title = $obj->title;
        }
    }

    /**
     * Returns link location.
     *
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * Return link title, if any.
     *
     * @return null|string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns link name e.g: "self". This is also the key used in the Links class.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
