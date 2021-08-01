<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Besoin;
use App\Entity\Production;
use App\Entity\Stock;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/Recap")
 */
class RecapController extends AbstractController
{
    /**
     * @Route("/", name="Recapcbn", methods={"GET"})
     */
    public function index( ): Response
    {

        $stockRepository = $this->getDoctrine()->getRepository(Stock::class);



        $productionRepository = $this->getDoctrine()->getRepository(Production::class);
        $prod = $productionRepository->findAll();
        $nbof = 0;
        $totalprod=0;
        foreach ($prod as $p) {
            $nbof++;
            $totalprod+=$p->getQt();
        }

        $achatRepository = $this->getDoctrine()->getRepository(Achat::class);
        $achat = $achatRepository->findAll();
        $nbachat = 0;
        $totalachat=0;
        $nbarepture=0;


        foreach ($achat as $a) {
            $totalachat+=$a->getQt();
            $nbachat++;
            $stock = $stockRepository->findoneBy(['no' => $a->getNo()]);
            if ($stock) {
                $stock = $stock->getQt();
            } else $stock = 0;
            if ($stock < $a->getQt()) {
                $nbarepture++;
            }
        }
        $besoinRepository = $this->getDoctrine()->getRepository(Besoin::class);
        $besoin = $besoinRepository->findAll();
        $nbpf = 0;
        $totalpf=0;
        foreach ($besoin as $b) {
            if ($b->getSomme()>0){
                $nbpf++;
                $totalpf+=$b->getSomme();
            }

        }


        return $this->render('recap/recap.html.twig', [
            'nbof' => $nbof,
            'totalprod' => $totalprod,
            'nbachat' => $nbachat,
            'totalachat' => $totalachat,
            'nbpf' => $nbpf,
            'totalpf' => $totalpf,
            'repture'=>$nbarepture
        ]);
    }
}