<?php

namespace Moip\Tests\Resource;

use Moip\Tests\TestCase;

class KeyTest extends TestCase
{
    public function testGetKey()
    {
        $this->mockHttpSession($this->body_keys);

        $keys = $this->moip->keys()->get();

        $this->assertNotNull($keys->getBasicAuth()->token);
        $this->assertNotNull($keys->getBasicAuth()->secret);
        $this->assertNotNull($keys->getEncryption());
    }
}
