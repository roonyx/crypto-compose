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
//        dump($coins);die;

        $queryCoinStr = '';
        $coinCount = count($coins);

        if($coinCount > 0) {
            foreach ($coins as $index => $coin) {
                $queryCoinStr .= $coin->getShortName().(($index < $coinCount-1)?',':'');

                $coin->getShortName();
            }
        }
        $ratesObj = $this->minApi->getMultiRates($queryCoinStr,'USD');

        foreach ($ratesObj as $name => $coinRates) {
            $currentCoin = $this->em->getRepository("AppBundle:Coin")->findBy([
                'shortName' => $name
            ]);

            $now = new \DateTime();

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
    }

}
