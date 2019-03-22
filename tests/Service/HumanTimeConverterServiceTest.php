<?php

namespace App\Tests\Service;

use App\Service\HumanTimeConverterService;
use PHPUnit\Framework\TestCase;

class HumanTimeConverterServiceTest extends TestCase
{
    public function testConvert()
    {
        $converter = new HumanTimeConverterService();
        $result  =$converter->convert(60);

        $this->assertEquals(1, $result);
    }

}
