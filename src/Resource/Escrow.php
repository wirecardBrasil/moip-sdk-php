<?php

namespace Moip\Resource;

use Requests;
use stdClass;

/**
 * Class Escrow.
 */
class Escrow extends MoipResource
{
    /**
     * @const string
     */
    const PATH = 'escrows';

    /**
     * Initializes new instances.
     */
    protected function initialize()
    {
        $this->data = new stdClass();
    }

    /**
     * Set id MoIP escrow.
     *
     *
     * @return \Moip\Resource\Escrow
     */
    public function setId($id)
    {
        $this->data->id = $id;

        return $this;
    }

    /**
     * Get id MoIP escrow.
     *
     *
     * @return \Moip\Resource\Escrow
     */
    public function getId()
    {
        return $this->getIfSet('id');
    }

    /**
     * Get escrow status.
     *
     * @return string Escrow status. Possible values HOLD_PENDING, HELD, RELEASED
     */
    public function getStatus()
    {
        return $this->getIfSet('status');
    }

    /**
     * get creation time.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->data->createdAt;
    }

    /**
     * Returns when the last update occurred.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->data->updatedAt;
    }

    /**
     * Get escrow description.
     *
     * @return string Escrow description.
     */
    public function getDescription()
    {
        return $this->data->description;
    }

    /**
     * Release a escrow payment.
     *
     * @return Payment
     */
    public function release()
    {
        $path = sprintf('/%s/%s/%s/%s', MoipResource::VERSION, self::PATH, $this->getId(), 'release');

        $response = $this->httpRequest($path, Requests::POST, []);

        return $this->populate($response);
    }

    /**
     * Mount escrow structure.
     *
     * @param \stdClass $response
     *
     * @return Escrow
     */
    protected function populate(stdClass $response)
    {
        $escrow = clone $this;

        $escrow->data->id = $this->getIfSet('id', $response);
        $escrow->data->status = $this->getIfSet('status', $response);
        $escrow->data->description = $this->getIfSet('description', $response);
        $escrow->data->amount = $this->getIfSet('amount', $response);
        $escrow->data->_links = $this->getIfSet('_links', $response);
        $escrow->data->createdAt = $this->getIfSetDateTime('createdAt', $response);
        $escrow->data->updatedAt = $this->getIfSetDateTime('updatedAt', $response);

        return $escrow;
    }
}
