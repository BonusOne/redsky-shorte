<?php
/**
 * RedSky Recruitment
 *
 * @author Paweł Liwocha PAWELDESIGN <pawel.liwocha@gmail.com>
 * @copyright Copyright (c) 2021  Paweł Liwocha PAWELDESIGN (https://paweldesign.com)
 */

namespace App\Service;

use Psr\Log\LoggerInterface;
use Swift_Mailer;

class fixerService
{
    private LoggerInterface $logger;
    private Swift_Mailer $mailer;
    private string $baseUrl = 'http://data.fixer.io/api/';
    protected string $accessKey = '36227ff60860b51bddc919a289e5c693';
    private string $encoding = "UTF-8";

    public function __construct(LoggerInterface $logger,Swift_Mailer $mailer)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }

    /**
     * @param $functionName
     * @param string $method
     * @param array $parameters
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function call($functionName, $method = "GET", $parameters = [], $data = [])
    {
        $startTime = microtime(true);

        $curl = curl_init();

        $callURL = $this->baseUrl . $functionName . "?access_key=" . $this->accessKey;

        if (count($parameters) > 0) {
            $callURL .= "&" . http_build_query($parameters);
        }

        if (count($data) > 0) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => $callURL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => $this->encoding,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Content-Type: multipart/form-data",
            ),
        ));

        $response = curl_exec($curl);

        $err = curl_error($curl);
        $errorNumber = curl_errno($curl);
        $curlInfo = curl_getinfo($curl);

        curl_close($curl);

        $duration = round((microtime(true) - $startTime), 4);

        if ($err) {
            if($errorNumber === CURLE_OPERATION_TIMEOUTED){

                try{
                    $message = (new \Swift_Message('RedSky - Fixer - API timeout'))
                        ->setFrom('no-reply@RedSky.com')
                        ->setTo('pawel.liwocha@gmail.com')
                        ->setBody(
                            $method.' - '.$callURL."\n".var_export($data, true)."\n".var_export($err,true)."\n".var_export($curlInfo, true),
                            'text/html');
                    $this->mailer->send($message);
                }catch (\Exception $e){
                    $this->logger->error('[ERROR] Add row - Activity: '. $e->getMessage());
                }
            }
            $this->logger->error($method.' '.$callURL."\n".var_export($data, true)."\n".var_export($err, true)."\n".var_export($curlInfo, true));
            throw new \Exception(var_export($err, true));
        }

        $this->logger->info(
            $method.' '.$callURL.
            "\r\nPOST DATA:\r\n".
            var_export($data, true).
            "\r\nGET DATA:\r\n".
            var_export($parameters, true).
            "\r\nRESPONSE:\r\n".
            var_export(json_decode($response, true), true).
            "\r\nDURATION: $duration\r\nn");

        return json_decode($response, true);
    }
}