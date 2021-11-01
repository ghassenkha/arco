<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Besoin;
use App\Entity\Stock;
use App\Form\StockType;
use App\Repository\PeriodRepository;
use App\Repository\StockRepository;
use App\Service\SommeService;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/stock")
 */
class StockController extends AbstractController
{
    /**
     * @Route("/", name="stock_index", methods={"GET"})
     */
    public function index(StockRepository $stockRepository): Response
    {


        return $this->render('stock/index.html.twig', [
            'stocks' => $stockRepository->findAll(),
        ]);
    }

    /**
     * @Route("/stock_import", name="stock_import")
     */
    public function xslx(SommeService $SommeService)
    {
        $fileFolder = __DIR__ . '/../../public/uploads/';  //choose the folder in which the uploaded file will be stored
//
        $filePathName = 'Stock.xlsx';

        $spreadsheet = IOFactory::load($fileFolder . $filePathName); // Here we are able to read from the excel file
        $row = $spreadsheet->getActiveSheet()->removeRow(1); // I added this to be able to remove the line off title
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true); // here, the read data is turned into an array
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();        //delete all date from besoin
        $qb->delete('App\Entity\Stock', 's')
            ->getQuery()
            ->getResult();

        $alphas = range('A', 'Z'); //table alphabet A-Z


        function mb_str_replace($needle, $replacement, $haystack)  //////supprimer les ','
        {
            return implode($replacement, mb_split($needle, $haystack));
        }

        $batchSize = 5000;
        $i = 0;
        foreach ($sheetData as $Row) {
            $no = $Row['A'];
            $qt = floatval($Row['B']);


//            $qt = mb_str_replace(',', '', $Row[$alphas[1]]);


            if (strlen($no) and $qt > 0) {  //// to stop the insertion of null line
                $Stock = new Stock();
                $Stock->setNo($no);
                $Stock->setQt($qt);

                $em->persist($Stock);
                $i++;


            }
            $i++;

            if (($i % $batchSize) === 0) {
                $em->flush();
                $em->clear(); // Detaches all objects from Doctrine!
            }


        }
        // here Doctrine checks all the fields of all fetched data and make a transaction to the database.
        $em->flush();
        $em->clear();
        return $this->redirectToRoute('stock_index');

//        return $this->redirectToRoute('article_index'
//        );

    }

    /**
     * @Route("/new", name="stock_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $stock = new Stock();
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($stock);
            $entityManager->flush();

            return $this->redirectToRoute('stock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stock/new.html.twig', [
            'stock' => $stock,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="stock_show", methods={"GET"})
     */
    public function show(Stock $stock): Response
    {
        return $this->render('stock/show.html.twig', [
            'stock' => $stock,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="stock_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Stock $stock): Response
    {
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('stock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stock/edit.html.twig', [
            'stock' => $stock,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="stock_delete", methods={"POST"})
     */
    public function delete(Request $request, Stock $stock): Response
    {
        if ($this->isCsrfTokenValid('delete' . $stock->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($stock);
            $entityManager->flush();
        }

        return $this->redirectToRoute('stock_index', [], Response::HTTP_SEE_OTHER);
    }
}
