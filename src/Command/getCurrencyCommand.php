<?php
/**
 * RedSky Recruitment
 *
 * @author Paweł Liwocha PAWELDESIGN <pawel.liwocha@gmail.com>
 * @copyright Copyright (c) 2021  Paweł Liwocha PAWELDESIGN (https://paweldesign.com)
 */

namespace App\Command;

use App\Service\calcService;
use App\Service\fixerService;
use DateInterval;
use DatePeriod;
use DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class getCurrencyCommand extends Command
{
    private fixerService $fixer;
    private calcService $calc;

    public function __construct(fixerService $fixer, calcService $calc)
    {
        $this->fixer = $fixer;
        $this->calc = $calc;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('redsky:get-currency')
            ->setDescription('Get Currency data and return ANG, Median and percentile.')
            ->addArgument('currencyFrom', InputArgument::REQUIRED, 'Add first currency (EUR) - Free plan has only EUR in base currency.')
            ->addArgument('currencyTo', InputArgument::REQUIRED, 'Add last currency (USD).')
            ->addArgument('dateFrom', InputArgument::REQUIRED, 'Add date from (RRRR-MM-DD).')
            ->addArgument('dateTo', InputArgument::REQUIRED, 'Add date to (RRRR-MM-DD).');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param bool $ignoreInterval
     * @return int
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output, $ignoreInterval = false): int
    {
        $currencyFrom = (string)$input->getArgument('currencyFrom');
        $currencyFrom = strtoupper(substr($currencyFrom, 0, 3));
        $currencyTo = (string)$input->getArgument('currencyTo');
        $currencyTo = strtoupper(substr($currencyTo, 0, 3));
        $dateFrom = date("Y-m-d", strtotime($input->getArgument('dateFrom')));
        $dateTo = date("Y-m-d", strtotime($input->getArgument('dateTo')));

        if($currencyFrom == 'EUR') {

            $startLoop = new DateTime($dateFrom);
            $endLoop = new DateTime($dateTo);
            $endLoop = $endLoop->modify('+1 day');

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($startLoop, $interval, $endLoop);

            $parameters = ['base' => $currencyFrom, 'symbols' => $currencyTo];
            $dataFixer = array();

            foreach ($period as $dt) {
                $result = $this->fixer->call($dt->format("Y-m-d"), 'GET', $parameters);
                if (array_key_exists($currencyTo, $result['rates'])) {
                    $dataFixer[] = $result['rates'][$currencyTo];
                } else {
                    $output->writeln("Error, Fixer don't return value for currency: " . $currencyTo);
                    return Command::FAILURE;
                }
            }

            $dataAvg = $this->calc->avgData($dataFixer);
            $dataMedian = $this->calc->medianData($dataFixer);
            $data75Percentile = $this->calc->percentileData(75, $dataFixer);
            $data95Percentile = $this->calc->percentileData(95, $dataFixer);

            $resultJson = json_encode([
                'currency' => $currencyFrom.'/'.$currencyTo,
                'avg' => round($dataAvg, 2),
                'med' => round($dataMedian, 2),
                '75p' => round($data75Percentile, 2),
                '95p' => round($data95Percentile, 2)
            ]);

            file_put_contents('public/fixerData.txt', date("Y-m-d H:i:s").' [SUCCESS] Result: '.print_r($resultJson, true)."\r\n", FILE_APPEND);

            $output->writeln($resultJson);

            return Command::SUCCESS;
        } else {
            $output->writeln("Error, Free plan has only EUR in base currency. Your currency: " . $currencyFrom);
            return Command::FAILURE;
        }
    }
}
