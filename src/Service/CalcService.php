<?php
/**
 * RedSky Recruitment
 *
 * @author Paweł Liwocha PAWELDESIGN <pawel.liwocha@gmail.com>
 * @copyright Copyright (c) 2021  Paweł Liwocha PAWELDESIGN (https://paweldesign.com)
 */

namespace App\Service;

use Psr\Log\LoggerInterface;

class CalcService
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

}