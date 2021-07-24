<?php

namespace App\Controller;

use App\Entity\Gamme;
use App\Entity\Stock;
use App\Form\GammeType;
use App\Repository\GammeRepository;
use App\Service\SommeService;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/gamme")
 */
class GammeController extends AbstractController
{
    /**
     * @Route("/", name="gamme_index", methods={"GET"})
     */
    public function index(GammeRepository $gammeRepository): Response
    {
        return $this->render('gamme/index.html.twig', [
            'gammes' => $gammeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/gamme_import", name="gamme_import")
     */
    public function xslx(SommeService $SommeService)
    {
        $fileFolder = __DIR__ . '/../../public/uploads/';  //choose the folder in which the uploaded file will be stored
//
        $filePathName = 'Gamme.xlsx';

        $spreadsheet = IOFactory::load($fileFolder . $filePathName); // Here we are able to read from the excel file
        $row = $spreadsheet->getActiveSheet()->removeRow(1); // I added this to be able to remove the line off title
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true); // here, the read data is turned into an array
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();        //delete all date from besoin
        $qb->delete('App\Entity\Gamme', 's')
            ->getQuery()
            ->getResult();


        function mb_str_replace($needle, $replacement, $haystack)  //////supprimer les ','
        {
            return implode($replacement, mb_split($needle, $haystack));
        }

        $batchSize = 5000;
        $i = 0;
        foreach ($sheetData as $Row) {
            $no = $Row['A'];
            $op = $Row['B'];
            $poste = $Row['C'];
            $description = $Row['D'];
            $setup = $Row['E'];
            $run = $Row['F'];
            $qth = $Row['G'];
            $qtmoh = $Row['H'];
             $qtmop = $Row['I'];



//            $qt = mb_str_replace(',', '', $Row[$alphas[1]]);


            if (strlen($no) ) {  //// to stop the insertion of null line
                $Gamme = new Gamme();
                $Gamme->setNo($no);
                $Gamme->setOp($op);
                $Gamme->setPoste($poste);
                $Gamme->setDescription($description);
                $Gamme->setSetup($setup);
                $Gamme->setRun($run);
                $Gamme->setQth($qth);
                $Gamme->setQtemoh($qtmoh);
                $Gamme->setQtemop($qtmop);

                $em->persist($Gamme);



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
        return $this->redirectToRoute('gamme_index');

//        return $this->redirectToRoute('article_index'
//        );

    }

    /**
     * @Route("/new", name="gamme_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $gamme = new Gamme();
        $form = $this->createForm(GammeType::class, $gamme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($gamme);
            $entityManager->flush();

            return $this->redirectToRoute('gamme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gamme/new.html.twig', [
            'gamme' => $gamme,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="gamme_show", methods={"GET"})
     */
    public function show(Gamme $gamme): Response
    {
        return $this->render('gamme/show.html.twig', [
            'gamme' => $gamme,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="gamme_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Gamme $gamme): Response
    {
        $form = $this->createForm(GammeType::class, $gamme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('gamme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gamme/edit.html.twig', [
            'gamme' => $gamme,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="gamme_delete", methods={"POST"})
     */
    public function delete(Request $request, Gamme $gamme): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gamme->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($gamme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('gamme_index', [], Response::HTTP_SEE_OTHER);
    }
}
