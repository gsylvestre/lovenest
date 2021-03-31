<?php

namespace App\Tests\Utils;

use App\Utils\Censurator;
use PHPUnit\Framework\TestCase;

class CensuratorTest extends TestCase
{
    public function testStringWithBadWordsIsPurified(): void
    {
        $censurator = new Censurator();
        $purified = $censurator->purify("pif paf purée pouet");
        $this->assertEquals("pif paf p**** pouet", $purified);

        $purified = $censurator->purify("shit pif paf purée pouet");
        $this->assertEquals("s*** pif paf p**** pouet", $purified);
    }

    public function testStringWithoutBadWordsIsLeftAlone(): void
    {
        $censurator = new Censurator();
        $purified = $censurator->purify("pif paf épê pouet");
        $this->assertEquals("pif paf épê pouet", $purified);
    }
}
