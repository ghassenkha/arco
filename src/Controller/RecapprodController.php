<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Besoin;
use App\Entity\Gamme;
use App\Entity\Production;
use App\Entity\Stock;
use App\Entity\Uap;
use mysql_xdevapi\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/Recapprod")
 */
class RecapprodController extends AbstractController
{
    /**
     * @Route("/", name="Recapprod", methods={"GET"})
     */
    public function index(): Response
    {

        $gammeRepository = $this->getDoctrine()->getRepository(Gamme::class);
        $productionRepository = $this->getDoctrine()->getRepository(Production::class);
        $uapRepository = $this->getDoctrine()->getRepository(Uap::class);

        $gamme=$gammeRepository->findAll();
        $Result=[];

        $total=0;
        foreach ($gamme as $g){
            $prod = $productionRepository->findOneBy(['no'=>$g->getNo()]);
            $uap= $uapRepository->findOneBy(['poste'=>$g->getPoste()]);
            if ($prod){
                $total+=$prod->getQt();
                $no=$prod->getNo();
                $qt=$prod->getQt();
                $op=$g->getOp();
                $poste=$g->getPoste();
                $prep=$g->getSetup();
                $run=$g->getRun();
                $qth=$g->getQth();
                $qtmo=$g->getQtemoh();

                array_push($Result,[
                    'no'=>$no,
                    'qt'=>$qt,
                    'op'=>$op,
                    'poste'=>$poste,
                    'prep'=>$prep,
                    'run'=>$run,
                    'qth'=>$qth,
                    'qtmo'=>$qtmo,
                    'section'=>$uap->getSection(),

                ]);
            }
        }dd($Result);






        return $this->render('gamme/index.html.twig', [
            'gammes' => $gammeRepository->findAll(),
        ]);
    }
}