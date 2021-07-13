<?php

namespace App\Tests;

use App\Command\getCurrencyCommand;
use App\Service\calcService;
use App\Service\fixerService;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class getCurrencyCommandTest extends KernelTestCase
{

    public function testExecute(){
        $fixer = $this->getMockBuilder(fixerService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $calc = $this->getMockBuilder(calcService::class)
            ->getMock();

        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new getCurrencyCommand($fixer, $calc));

        $command = $application->find('redsky:get-currency');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'currencyFrom' => 'EUR',
            'currencyTo' => 'USD',
            'dateFrom' => '2021-01-01',
            'dateTo' => '2021-01-03'
        ));

        $output = $commandTester->getDisplay();
        $this->assertJson('{"currency":"EUR\/USD","avg":1.22,"med":1.22,"75p":1.23,"95p":1.23}',$output);
    }
}