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

class AppGetRatesCommand extends ContainerAwareCommand
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
            ->setName('app:get-rates')
            ->setDescription('...')
//            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
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

            if ($coinCount > 0) {
                foreach ($coins as $index => $coin) {
                    $queryCoinStr .= $coin->getShortName() . (($index < $coinCount - 1) ? ',' : '');
                }
            }
            $ratesObj = $this->minApi->getMultiRates($queryCoinStr, 'USD');

            foreach ($ratesObj as $name => $coinRates) {
                $currentCoin = $this->em->getRepository("AppBundle:Coin")->findBy([
                    'shortName' => $name
                ]);

                $output->writeln('create new Log');

                $log = new Log();
                $log->setCreatedAt($now);
                $this->em->persist($log);
                $this->em->flush();

                $logCoinRate = new LogCoinRate();
                $logCoinRate
                    ->setLog($log)
                    ->setRate($coinRates->USD);
                $this->em->persist($logCoinRate);

                $currentCoin[0]->addLoggedRate($logCoinRate);
                $this->em->persist($currentCoin[0]);
            }
            $this->em->flush();

            $output->writeln('Command result.');
        } else {
            $coin = new Coin();
            $coin->setName('BitCoin');
            $coin->setShortName('BTC');
            $coin->setCreatedAt($now);
            $coin->setUpdatedAt($now);
            $this->em->persist($coin);

            $coin = new Coin();
            $coin->setName('Ethereum');
            $coin->setShortName('ETH');
            $coin->setUpdatedAt($now);
            $this->em->persist($coin);

            $coin = new Coin();
            $coin->setName('LiteCoin');
            $coin->setShortName('LTC');
            $coin->setUpdatedAt($now);
            $this->em->persist($coin);

            $coin = new Coin();
            $coin->setName('Peercoin');
            $coin->setShortName('PPC');
            $coin->setUpdatedAt($now);
            $this->em->persist($coin);

            $this->em->flush();
            throw new Exception('There are no Coins in database. BTC and ETH was added to DB.');
        }
    }

}
