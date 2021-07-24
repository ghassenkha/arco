<?php

namespace App\Controller;

use App\Entity\Besoin;
use App\Entity\Period;
use App\Entity\Eclat;
use App\Entity\Production;
use App\Entity\Achat;
use App\Form\BesoinType;
use App\Form\PeriodType;
use App\Repository\BesoinRepository;
use App\Repository\ProductionRepository;
use App\Repository\AchatRepository;
use App\Repository\EclatRepository;
use App\Repository\NomenclatureRepository;
use App\Repository\PeriodRepository;
use App\Repository\StockRepository;
use App\Service\EclatService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Service\SommeService;


/**
 * @Route("/besoin")
 */
class BesoinController extends AbstractController
{
    /**
     * @Route("/", name="besoin_index", methods={"GET","POST"})
     */
    public function index(BesoinRepository $besoinRepository, Request $request, PeriodRepository $periodRepository): Response
    {
        $period = $periodRepository->findAll();
        $besoin = $besoinRepository->findAll();
        $em = $this->getDoctrine()->getManager();


        $form = $this->createForm(PeriodType::class, $period[0]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('besoin_calculate', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('besoin/index.html.twig', [
            'besoins' => $besoin,
            'form' => $form,
            'period' => $period[0]
        ]);
    }


    /**
     * @Route("/besoin_calculate", name="besoin_calculate")
     */
    public function UpdateSomme(BesoinRepository $besoinRepository, PeriodRepository $periodRepository, SommeService $SommeService)
    {
        $period = $periodRepository->findAll();
        $lastweek = intval($period[0]->getWeek()); //convert string to int

        $em = $this->getDoctrine()->getManager();

        $besoin = $besoinRepository->findAll();

        foreach ($besoin as $b) {
            $somme = $SommeService->SommeCalcule($b, $lastweek);  //Calcul

            $qb = $em->createQueryBuilder('b')                    // Updating
            ->update('App\Entity\Besoin', 'b')
                ->set('b.somme', '?1')
                ->where('b.id = ?2')
                ->setParameter(1, $somme)
                ->setParameter(2, $b->getId())
                ->getQuery()
                ->getResult();
        }

        return $this->redirectToRoute('besoin_index');

    }

    /**
     * @Route("/besoin_import", name="besoin_import")
     */
    public function xslx(PeriodRepository $periodRepository, SommeService $SommeService)
    {
        $fileFolder = __DIR__ . '/../../public/uploads/';  //choose the folder in which the uploaded file will be stored
//
        $filePathName = 'Besoin.xlsx';

        $spreadsheet = IOFactory::load($fileFolder . $filePathName); // Here we are able to read from the excel file
        $row = $spreadsheet->getActiveSheet()->removeRow(1); // I added this to be able to remove the line off title
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true); // here, the read data is turned into an array
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();        //delete all date from besoin
        $qb->delete('App\Entity\Besoin', 's')
            ->getQuery()
            ->getResult();

        $alphas = range('A', 'Z'); //table alphabet A-Z

        $period = $periodRepository->findAll();
        $lastweek = intval($period[0]->getWeek()); //convert string to int

        function mb_str_replace($needle, $replacement, $haystack)  //////supprimer les ','
        {
            return implode($replacement, mb_split($needle, $haystack));
        }

        foreach ($sheetData as $Row) {
            $no = $Row['A'];
            $desc = $Row['B'];

            if (strlen($no)) {  //// to stop the insertion of null line
                $besoin = new Besoin();
                $besoin->setNo($no);
                $besoin->setDescription('fsd');
                $somme = 0;
                for ($i = 1; $i <= 16; $i++) {   //16 ---> max Periode

                    ${'s' . $i} = intval(mb_str_replace(',', '', $Row[$alphas[$i + 1]])); // i+1 reglage alphabet A-Z // to int // to replace , error
                    if (!${'s' . $i}) {
                        ${'s' . $i} = 0;
                    }
                    $besoin->{"setS" . $i}(${'s' . $i}); // insert rows in besoin
                }

                $somme = $SommeService->SommeCalcule($besoin, $lastweek); // calculer la somme selon la periode

                $besoin->setSomme($somme);
                $em->persist($besoin);

                $em->flush();
                $em->clear(); // Detaches all objects from Doctrine!

            }
            // here Doctrine checks all the fields of all fetched data and make a transaction to the database.
            $em->flush();
            $em->clear();

        }
        return $this->redirectToRoute('besoin_index');

//        return $this->redirectToRoute('article_index'
//        );

    }
    public function sumGB($table){
        $data = array();
        $result = array();
        foreach ($table as $a)
            array_push($data,["no"=> $a->getNo(),"qt"=>$a->getQt()]);

        // predefine array
        $data_summ = array();
        foreach ( $data as $value ) {
            $data_summ[ $value['no'] ] = 0;
        }
        foreach ( $data as $list ) {

            $data_summ[ $list['no'] ] += $list['qt'];
        }
        foreach ($data_summ as $no => $v){
            array_push($result,["no"=> $no,"qt"=>$v]);
        }
        return $result;
    }

    /**
     * @Route("/eclat", name="elcat")
     */
    public function eclatement(NomenclatureRepository $Nomc, EclatService $EclatService,StockRepository $s): Response
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();                                //Reset Table Eclat
        $qb->delete('App\Entity\Eclat', 's')
            ->getQuery()
            ->getResult();
                                                                        //Reset Table Achat
        $qb->delete('App\Entity\Achat', 's')
            ->getQuery()
            ->getResult();
                                                                        //get all Besoin
        $q = $em->createQuery("select b.no, b.somme
                               from App\Entity\Besoin b 
                               where b.somme > 0");
        $besoin = $q->getResult();

        $qb = $em->createQueryBuilder();                            //Reset Table Product
        $qb->delete('App\Entity\Production', 's')
            ->getQuery()
            ->getResult();

        foreach ($besoin as $b) {                                ///// insert besoin as Production
            $prod = new Production();
            $prod->setNo($b['no']);
            $prod->setQt($b['somme']);
            $em->persist($prod);
            $em->flush();
            $em->clear();

            $prod = new Eclat();                                ///// insert besoin To Eclat
            $prod->setNo($b['no']);
            $prod->setQt($b['somme']);
            $em->persist($prod);
            $em->flush();
            $em->clear();
        }






        while ($besoin ){

            $q = $em->createQuery("select p
                               from App\Entity\Eclat p 
                               ");
            $besoin = $q->getResult();
                                                            //// Eclatement Besoin
            $EclatResult = $EclatService->Eclat($Nomc, $besoin,$s);

            $achat = $EclatResult['achat'];

            $data_summ = $this->sumGB($achat);
            foreach ($achat as $b) {
                $em->persist($b);
                $em->flush();
                $em->clear();
            }

            /////return Product eclaté à besoin pour reclaculer
            $besoin = $EclatResult['prod'];
            $besoin = $this->sumGB($besoin);

            if (sizeof($besoin) > 0) {


//                $qb = $em->createQueryBuilder();             //Reset Table Eclat For New Insert
//                $qb->delete('App\Entity\Eclat', 's')
//                    ->getQuery()
//                    ->getResult();
//                foreach ($besoin as $prod) {                  //Insert Result Prod To Eclat Table
//                    $em->persist($prod);
//                    $em->flush();
//                    $em->clear();
//                }
//                ///GROUP By RESULT
//                $q = $em->createQuery("select p.no,sum(p.qt) as somme
//                               from App\Entity\Eclat p
//                               GROUP BY p.no");
//                $besoin = $q->getResult();



                foreach ($besoin as $b) {
                    $pr = new Production();
                    $pr->setNo($b['no']);
                    $pr->setQt($b['qt']);
                    $em->persist($pr);
                    $em->flush();
                    $em->clear();
                }

                $qb = $em->createQueryBuilder();        //Reset Table Eclat
                $qb->delete('App\Entity\Eclat', 's')
                    ->getQuery()
                    ->getResult();
                foreach ($besoin as $b) {           //// Insert To Eclat To Re Calcul
                    $pr = new Eclat();
                    $pr->setNo($b['no']);
                    $pr->setQt($b['qt']);
                    $em->persist($pr);
                    $em->flush();
                    $em->clear();
                }
            }
        }


        $q = $em->createQuery("select p.no,sum(p.qt) as somme
                               from App\Entity\Production p 
                               GROUP BY p.no");
        $besoin = $q->getResult();

        $qb = $em->createQueryBuilder();        //Reset Table Product
        $qb->delete('App\Entity\Production', 's')
            ->getQuery()
            ->getResult();

        foreach ($besoin as $b) {
            $pr = new Production();
            $pr->setNo($b['no']);
            $pr->setQt($b['somme']);
            $em->persist($pr);
            $em->flush();
            $em->clear();

        }
        $q = $em->createQuery("select p.no,sum(p.qt) as somme
                               from App\Entity\Achat p 
                               GROUP BY p.no");
        $besoin = $q->getResult();

        $qb = $em->createQueryBuilder();        //Reset Table Product
        $qb->delete('App\Entity\Achat', 's')
            ->getQuery()
            ->getResult();

        foreach ($besoin as $b) {
//            $ss=$s->findoneBy(['no' => $b['no']]);
//            if ($ss){
//                $stock=$ss->getQt();}
//            else $stock=0;

            $pr = new Achat();
            $pr->setNo($b['no']);
            $pr->setQt($b['somme']);
            $em->persist($pr);
            $em->flush();
            $em->clear();

        }


        return $this->redirectToRoute('besoin_index');
    }

    /**
     * @Route("/new", name="besoin_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $besoin = new Besoin();
        $form = $this->createForm(BesoinType::class, $besoin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($besoin);
            $entityManager->flush();

            return $this->redirectToRoute('besoin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('besoin/new.html.twig', [
            'besoin' => $besoin,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="besoin_show", methods={"GET"})
     */
    public function show(Besoin $besoin): Response
    {
        return $this->render('besoin/show.html.twig', [
            'besoin' => $besoin,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="besoin_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Besoin $besoin): Response
    {
        $form = $this->createForm(BesoinType::class, $besoin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('besoin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('besoin/edit.html.twig', [
            'besoin' => $besoin,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="besoin_delete", methods={"POST"})
     */
    public function delete(Request $request, Besoin $besoin): Response
    {
        if ($this->isCsrfTokenValid('delete' . $besoin->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($besoin);
            $entityManager->flush();
        }

        return $this->redirectToRoute('besoin_index', [], Response::HTTP_SEE_OTHER);
    }
}
