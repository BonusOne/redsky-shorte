<?php

namespace App\Tests;

use App\Service\calcService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class calcServiceTest extends KernelTestCase
{

    public function testCalcExecute(){

        self::bootKernel();
        $container = static::getContainer();

        $calcService = $container->get(calcService::class);

        $data = [
            0 => 1,
            1 => 3,
            2 => 6,
            3 => 8.5,
            4 => 15
        ];

        $this->assertEquals(6.7, $calcService->avgData($data));
        $this->assertEquals(6, $calcService->medianData($data));
        $this->assertEquals(8.5, $calcService->percentileData(75, $data));
        $this->assertEquals(15, $calcService->percentileData(95, $data));
    }
}