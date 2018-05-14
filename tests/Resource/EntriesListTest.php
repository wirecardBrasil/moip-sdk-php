<?php

namespace Moip\Tests\Resource;

use Moip\Tests\TestCase;

class EntriesListTest extends TestCase
{
    public function testShouldGetEntriesListUnfiltered()
    {
        $this->mockHttpSession($this->body_entries_list);

        $entries = $this->moip->entries()->getList();

        $this->assertEquals(67958700, $entries->getSummary()->amount);
        $this->assertEquals('https://sandbox.moip.com.br/v2/entries?offset=0&limit=20', $entries->getLinks()->previous->href);
        $this->assertNotNull($entries->getEntries());
        $this->assertEquals('ENT-PA0KQG8CCI4O', $entries->getEntries()[0]->id);
    }
}