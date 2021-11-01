<?php

namespace App\Controller;


use App\Entity\Achat;
use App\Entity\Besoin;
use App\Entity\Stock;
use App\Repository\BesoinRepository;
use App\Repository\PeriodRepository;
use PhpParser\Node\Stmt\Else_;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\EclatService;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/")
 */
class CasemploiController extends AbstractController
{
    /**
     * @Route("/CasEmp", name="CasEmp", methods={"GET","POST"})
     */
    public function index(EclatService $EclatService): Response
    {
        $em = $this->getDoctrine()->getManager();

        $achats = $EclatService->Repture();

        $q = $em->createQuery("select b.no, b.somme as qt
                               from App\Entity\Besoin b 
                               where b.somme > 0");
        $besoin = $q->getResult();
        $s = $this->getDoctrine()->getRepository(Stock::class);
        $ss = $s->findAll();

        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers);


        $stock = $serializer->normalize($ss);
        $Verif = $EclatService->VerifStock($stock, $besoin);


        $besoin = $Verif['besoin'];


        $result = [];
        foreach ($achats as $a) {
            $e = $EclatService->CasEmp($a);
            foreach ($e as $t) {
                foreach ($besoin as $b){
                    if ($t['no']==$b['no'] ) {

                        $qt=$b['qt']*$t['coeff'];
                        array_push($result,
                            [
                                'origine'=>$t['no'],
                                'besoin'=>$b['qt'],
                                "qt"=>$qt,
                                "achat"=>$t['achat'],
                                'totalcmp'=>$a['qt'],
                                'acmd'=>-$a['acmd'],
                                'stock'=>$a['stock']
                            ]);
                    }
                }

            }
        }
//        foreach ($result as $r) {
//            $origine= $r['origine'];
//            foreach ($result as $comp){
//                if ($origine== $comp['origine']){
//                    $comp['qt']=;
//                    $comp['stock']=;
//
//                }
//            }
//        }
        return $this->render('RecapRepture/index.html.twig', [
            'Results' => $result,
        ]);

    }
}