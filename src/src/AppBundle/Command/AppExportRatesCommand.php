<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Coin;
use AppBundle\Entity\Log;
use AppBundle\Entity\LogCoinRate;
use Symfony\Component\Config\Definition\Exception\Exception;

class AppExportRatesCommand extends ContainerAwareCommand
{
    /**
     * @var \AppBundle\Service\MinAPI
     */
    protected $minApi;

    private $container;

    private $doctrine;

    /** @var EntityManager */
    private $em;

    protected function configure()
    {
        $this
            ->setName('app:export-rates')
            ->setDescription('Exports history for the past year')
            ->addOption(
                'start',
                null,
                InputOption::VALUE_OPTIONAL,
                'Exports rates from date'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->container = $this->getContainer();
        $this->doctrine = $this->container->get('doctrine');
        $this->minApi = $this->container->get('minApi');
        $this->em = $this->doctrine->getManager();

        // Get all coins
        $coins = $this->em->getRepository("AppBundle:Coin")->findAll();

        $now = new \DateTime();
        if (count($coins) > 0) {
            $queryCoinStr = '';
            $coinCount = count($coins);

            $ratesObj = null;

            if ($coinCount > 0) {
                foreach ($coins as $index => $coin) {
                    $queryCoinStr .= $coin->getShortName() . (($index < $coinCount - 1) ? ',' : '');
                    $ratesObj[$coin->getShortName()] = $this->minApi->getHistoryDailyRates($coin->getShortName(), 'USD', $now->getTimestamp(), 360);
                }
            }

            foreach ($ratesObj as $coinShortName => $coinRates) {
                $currentCoin = $this->em->getRepository("AppBundle:Coin")->findBy([
                    'shortName' => $coinShortName
                ]);

                foreach ($coinRates->Data as $coinRateData) {
                    $time = $now;
                    $time->setTimestamp($coinRateData->time);
                    $isLogExists = $this->em
                        ->getRepository('AppBundle:LogCoinRate')
                        ->checkLogExistsByDateAndCoin($time, $coinShortName);

                    if (count($isLogExists) == 0) {
                        $output->writeln(
                            'create new Log for ' .
                            $coinShortName .
                            ' - ' .
                            $time->format('m/d/Y')
                        );

                        $log = new Log();
                        $log->setCreatedAt($time);
                        $this->em->persist($log);
                        $this->em->flush();

                        $logCoinRate = new LogCoinRate();
                        $logCoinRate
                            ->setLog($log)
                            ->setRate($coinRateData->open);
                        $this->em->persist($logCoinRate);

                        $currentCoin[0]->addLoggedRate($logCoinRate);
                        $this->em->persist($currentCoin[0]);
                    }
                }
            }
            $this->em->flush();

            $output->writeln('Command result.');
        } else {
            throw new Exception('There are no Coins in database.');
        }
    }

}
