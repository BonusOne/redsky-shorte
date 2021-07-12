<?php
/**
 * RedSky Recruitment
 *
 * @author Paweł Liwocha PAWELDESIGN <pawel.liwocha@gmail.com>
 * @copyright Copyright (c) 2021  Paweł Liwocha PAWELDESIGN (https://paweldesign.com)
 */

namespace App\Service;

class calcService
{

    /**
     * @param array $data
     * @return float
     */
    public function avgData(array $data): float
    {
        return (array_sum($data) / count($data));
    }

    /**
     * @param array $data
     * @return float
     */
    public function medianData(array $data): float
    {
        sort($data);
        $count= sizeof($data);
        if($count % 2 !=0){
            $value = round($count/2)-1;
            $result = $data[$value];
        } else {
            $valueOne = $count/2;
            $valueTwo = $valueOne-1;
            $result = (($data[$valueOne] + $data[$valueTwo]) / 2);
        }
        return floatval($result);
    }

    /**
     * @param int $percentile
     * @param array $data
     * @return float
     */
    public function percentileData(int $percentile, array $data): float {
        sort($data);
        $index = (($percentile / 100) * count($data));
        if (floor($index) == $index) {
            $result = ($data[$index-1] + $data[$index])/2;
        }
        else {
            $result = $data[floor($index)];
        }
        return floatval($result);
    }
}