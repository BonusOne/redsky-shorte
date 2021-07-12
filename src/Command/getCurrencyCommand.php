<?php
/**
 * RedSky Recruitment
 *
 * @author Paweł Liwocha PAWELDESIGN <pawel.liwocha@gmail.com>
 * @copyright Copyright (c) 2021  Paweł Liwocha PAWELDESIGN (https://paweldesign.com)
 */

namespace App\Command;

use Symfony\Component\Console\Command\Command;
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
            ->setDescription('Build buffered statistics to save in database.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param bool $ignoreInterval
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output, $ignoreInterval = false): int
    {

        $output->writeln("Building (" . date("Y-m-d H:i:s") . ")");

        return 1;
    }
}
