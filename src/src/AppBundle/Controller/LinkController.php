<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Link;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\LogCoinRate;


class LinkController extends Controller
{

    /** @var EntityManager */
    private $em;

    /**
     * @var \AppBundle\Service\DataHandler
     */
    protected $dataHandler;

    /**
     * @Route("/", name="composepage")
     */
    public function indexAction(Request $request)
    {
        $this->dataHandler = $this->container->get("dataHandler");

        $coinId = $this->dataHandler->getFilterCoinId($request, 'querySelect');

        $now = new \DateTime();
        $toDateTime = $this->dataHandler->getFilterDate($request, 'toDateTime', 'Y-m-d 23:59:59', $now);
        $fromDateTime = $this->dataHandler->getFilterDate($request, 'fromDateTime', 'Y-m-d 00:00:00', $now->modify('-2 day'));

        $this->get('session')->set('queryId', $coinId);

        $coins = $this->getDoctrine()
            ->getRepository('AppBundle\Entity\Coin')
            ->findAll([
                ''
            ], [
                'id' => 'ASC',
            ]);

        $logCoinRates = $this->getDoctrine()
            ->getRepository('AppBundle\Entity\LogCoinRate')
            ->checkDateRange($fromDateTime, $toDateTime, $coinId);

        $logCoinRatesArr = $this->dataHandler->getLogCoinRatesByLogCoinRatesParsed($logCoinRates);

        if ($request->get("generateLinkFlag")) {
            $link = $this->createLink($now, $logCoinRates);
            return $this->redirectToRoute('showlink', [
                'linkId' => $link->getShortUrlId()
            ]);
        }

        return $this->render('@App/default/index.html.twig', [
            'coins' => $coins,
            'logCoinRate' => json_encode($logCoinRatesArr, JSON_FORCE_OBJECT),
            'queryId' => $coinId,
            'fromDateTime' => $fromDateTime->format('m/d/Y'),
            'toDateTime' => $toDateTime->format('m/d/Y'),
        ]);
    }

    /**
     * @Route("/{linkId}", name="showlink")
     */
    public function showAction($linkId)
    {
        $this->em = $this->getDoctrine()->getManager();
        $link = $this->getDoctrine()
            ->getRepository('AppBundle\Entity\Link')
            ->findBy([
                'shortUrlId' => $linkId
            ]);

        if (count($link) > 0) {
            $link = $link[0];
            $link->incrementImpCount();
            $this->em->persist($link);
            $this->em->flush();

            $this->dataHandler = $this->container->get("dataHandler");
            $logCoinRatesArr = $this->dataHandler->getLogCoinRatesParsed($linkId);

            return $this->render('@App/default/show.html.twig', [
                'logCoinRate' => json_encode($logCoinRatesArr, JSON_FORCE_OBJECT),
            ]);

        } else {
            return $this->redirectToRoute('composepage');
        }

    }

    /**
     * @Route("/about/", name="about")
     */
    public function aboutAction()
    {
        return $this->render('@App/default/about.html.twig');
    }

    /**
     * @param \DateTime $now
     * @param array $logCoinRates
     * @return Link $link
     */
    private function createLink($now, $logCoinRates)
    {
        $this->em = $this->getDoctrine()->getManager();
        $link = new Link();
        foreach ($logCoinRates as $logCoinRate) {
            $link->addLog($logCoinRate->getLog());
        }
        $link->setShortUrlId(uniqid());
        $link->setImpCount(0);
        $link->setCreatedAt($now);
        $this->em->persist($link);
        $this->em->flush();

        return $link;
    }
}
