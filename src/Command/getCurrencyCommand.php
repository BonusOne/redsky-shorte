<?php
/**
 * RedSky Recruitment
 *
 * @author Paweł Liwocha PAWELDESIGN <pawel.liwocha@gmail.com>
 * @copyright Copyright (c) 2021  Paweł Liwocha PAWELDESIGN (https://paweldesign.com)
 */

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;

class getCurrencyCommand extends Command
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('redsky:get-currency')
            ->setDescription('Get Currency data and return ANG, Median and percentile.')
            ->addArgument('currencyFrom', InputArgument::REQUIRED, 'Add first currency (PLN, EUR).')
            ->addArgument('currencyTo', InputArgument::REQUIRED, 'Add last currency (PLN, EUR).')
            ->addArgument('dateFrom', InputArgument::REQUIRED, 'Add date from (RRRR-MM-DD).')
            ->addArgument('dateTo', InputArgument::REQUIRED, 'Add date to (RRRR-MM-DD).');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param bool $ignoreInterval
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output, $ignoreInterval = false): int
    {
        $currencyFrom = (string)$input->getArgument('currencyFrom');
        $currencyFrom = strtoupper(substr($currencyFrom, 0, 3));
        $currencyTo = (string)$input->getArgument('currencyTo');
        $currencyTo = strtoupper(substr($currencyTo, 0, 3));
        $dateFrom = date("Y-m-d", strtotime($input->getArgument('dateFrom')));
        $dateTo = date("Y-m-d", strtotime($input->getArgument('dateTo')));

        $output->writeln("Building (" . date("Y-m-d H:i:s") . ")");
        $output->writeln("currencyFrom " . $currencyFrom);
        $output->writeln("currencyTo " . $currencyTo);
        $output->writeln("dateFrom " . $dateFrom);
        $output->writeln("dateTo " . $dateTo);

        return Command::SUCCESS;
    }
}
