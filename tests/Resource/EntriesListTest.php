<?php

namespace Moip\Tests\Resource;

use Moip\Tests\TestCase;

class EntriesListTest extends TestCase
{
    public function testShouldGetEntriesListUnfiltered()
    {
        $this->mockHttpSession($this->body_entries_list);

        $entries = $this->moip->entries()->getList();

        $this->assertNotNull($entries->getEntries());
    }
}