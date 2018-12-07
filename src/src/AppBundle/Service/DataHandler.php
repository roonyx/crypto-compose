<?php

namespace AppBundle\Service;

use AppBundle\Entity\Link;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class DataHandler
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var null|object
     */
    protected $session = null;

    /**
     * @var null|object
     */
    protected $doctrine = null;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->session = $this->container->get('session');
        $this->doctrine = $this->container->get('doctrine');
    }


    /**
     * @param Request $request
     * @param string $name
     * @param string $format
     * @param \DateTime $defaultTime
     *
     * @return string
     */
    public function getFilterDate($request, $name, $format, $defaultTime)
    {
        $dateTime = (!empty($request->get($name))) ?
            $request->get($name) :
            ((!empty($this->session->get($name))) ?
                $this->session->get($name) :
                $defaultTime->format($format));

        return new \DateTime($dateTime);
    }

    /**
     * @param Request $request
     * @param string $name
     * @param string $format
     * @param \DateTime $defaultTime
     *
     * @return string
     */
    public function getFilterCoinId($request, $name)
    {
        $coinId = (!empty($request->get($name))) ?
            $request->get($name) :
            ((!empty($this->session->get('queryId'))) ?
                $this->session->get('queryId') :
                1);
        $this->session->set('queryId', $coinId);

        return $coinId;
    }

    /**
     * @param string $link
     *
     * @return array
     */
    public function getLogCoinRatesParsed($linkId)
    {
        $logCoinRates = $this->doctrine
            ->getRepository('AppBundle\Entity\LogCoinRate')
            ->getLogCoinRatesByLinkId($linkId);

        return $this->handleLogCoinRates($logCoinRates);
    }

    /**
     * @param array $logCoinRates
     *
     * @return array
     */
    public function getLogCoinRatesByLogCoinRatesParsed($logCoinRates)
    {
        return $this->handleLogCoinRates($logCoinRates);
    }

    /**
     * @param array $logCoinRates
     *
     * @return array
     */
    private function handleLogCoinRates ($logCoinRates) {
        $logCoinRatesArr = [];
        foreach ($logCoinRates as $logCoinRate) {
            $logCoinRatesArr[$logCoinRate->getLog()->getCreatedAt()->format('Y-m-d H:i:s')][] = [
                'rate' => $logCoinRate->getRate(),
                'coin' => $logCoinRate->getCoin()->getName(),
                'date' => $logCoinRate->getLog()->getCreatedAt()->format('Y-m-d')
            ];
        }

        return $logCoinRatesArr;
    }

}