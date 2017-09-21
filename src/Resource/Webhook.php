<?php

namespace Moip\Resource;

use stdClass;

class Webhook extends MoipResource
{
    /**
     * Get webhook id.
     *
     * @return string The webhook id.
     */
     public function getId()
     {
         return $this->getIfSet('id');
     }
 
     /**
      * Get resource id.
      *
      * @return string The webhook id.
      */
     public function getResourceId()
     {
         return $this->getIfSet('resourceId');
     }
 
     /**
      * Get event.
      *
      * @return string event.
      */
     public function getEvent()
     {
         return $this->getIfSet('event');
     }
 
     /**
      * Get url.
      *
      * @return string url.
      */
     public function getUrl()
     {
         return $this->getIfSet('url');
     }
 
     /**
      * Get webhook status.
      *
      * @return string webhook status.
      */
     public function getStatus()
     {
         return $this->getIfSet('status');
     }
}