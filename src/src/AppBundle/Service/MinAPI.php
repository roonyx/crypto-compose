<?php

namespace AppBundle\Service;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MinAPI
{
    const API_URL = 'https://min-api.cryptocompare.com/data/';
    protected $client;
    protected $container;
    protected $session = null;
    protected $oauth = null;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->session = $this->container->get('session');
    }


    protected function req($method, $endpoint)
    {
        $curl = curl_init();
        $url = self::API_URL . $endpoint;

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);

        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $result = json_decode($result);
        curl_close($curl);

        return $this->getStatusInfo($statusCode, $result, $method, $url);
    }


    private function getStatusInfo($statusCode, $result, $method, $url)
    {
        if ($statusCode >= 200 && $statusCode < 300) {//success
            return $result;
        } else {//err
            if (@$result->errorMessages) {
                throw new Exception(implode(",", $result->errorMessages) . " url:" . $url . " method: " . $method);
            }
        }
    }

    public function getRates($coinsStr, $currenciesStr)
    {
        return $this->req('GET', 'price?fsym=' . $coinsStr . '&tsyms=' . $currenciesStr);
    }

    public function getMultiRates($coinsStr, $currenciesStr)
    {
        return $this->req('GET', 'pricemulti?fsyms=' . $coinsStr . '&tsyms=' . $currenciesStr);
    }
}