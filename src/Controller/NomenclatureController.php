<?php

namespace App\Controller;

use App\Entity\Nomenclature;
use App\Form\NomenclatureType;
use App\Repository\NomenclatureRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MessageGenerator;
/**
 * @Route("/nomenclature")
 */
class NomenclatureController extends AbstractController
{
    /**
     * @Route("/", name="nomenclature_index", methods={"GET"})
     */
    public function index(NomenclatureRepository $nomenclatureRepository): Response
    {

        return $this->render('nomenclature/index.html.twig', [
            'nomenclatures' => $nomenclatureRepository->findAll(),
        ]);
    }


    /**
     * @Route("/nomc_import", name="nomc_import")
     */
    public function xslx()
    {
        $fileFolder = __DIR__ . '/../../public/uploads/';  //choose the folder in which the uploaded file will be stored
//
        $filePathName = 'nomenclature.xlsx';

        $spreadsheet = IOFactory::load($fileFolder . $filePathName); // Here we are able to read from the excel file
        $row = $spreadsheet->getActiveSheet()->removeRow(1); // I added this to be able to remove the line off title
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true); // here, the read data is turned into an array
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();        //delete all date from besoin
        $qb->delete('App\Entity\Nomenclature', 's')
            ->getQuery()
            ->getResult();

        $batchSize = 500;
        $i=0;
        function mb_str_replace($needle, $replacement, $haystack) {
            return implode($replacement, mb_split($needle, $haystack));
        }
        foreach ($sheetData as $Row)
        {

            $bom_no = $Row['A'];
            $no = $Row['B'];
            $qt_per = ($Row['C']);
            $qt_per=mb_str_replace(',', '.', $qt_per);
            $syst_reap = $Row['E'];

            if (strlen($bom_no)) {  //// to stop the insertion of null line
                $nomc = new Nomenclature();
                $nomc->setBOMNo($bom_no);
                $nomc->setNo($no);
                $nomc->setQtPer($qt_per);
                $nomc->setSystReap($syst_reap);

                $em->persist($nomc);



            }
            // here Doctrine checks all the fields of all fetched data and make a transaction to the database.
            $i++;
            if (($i % $batchSize) === 0) {
                $em->flush();
                $em->clear(); // Detaches all objects from Doctrine!
            }

        }

        $em->flush();
        $em->clear();
        return $this->redirectToRoute('nomenclature_index');

//        return $this->redirectToRoute('article_index'
//        );

    }
    /**
     * @Route("/new", name="nomenclature_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $nomenclature = new Nomenclature();
        $form = $this->createForm(NomenclatureType::class, $nomenclature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($nomenclature);
            $entityManager->flush();

            return $this->redirectToRoute('nomenclature_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('nomenclature/new.html.twig', [
            'nomenclature' => $nomenclature,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="nomenclature_show", methods={"GET"})
     */
    public function show(Nomenclature $nomenclature): Response
    {
        return $this->render('nomenclature/show.html.twig', [
            'nomenclature' => $nomenclature,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="nomenclature_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Nomenclature $nomenclature): Response
    {
        $form = $this->createForm(NomenclatureType::class, $nomenclature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('nomenclature_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('nomenclature/edit.html.twig', [
            'nomenclature' => $nomenclature,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="nomenclature_delete", methods={"POST"})
     */
    public function delete(Request $request, Nomenclature $nomenclature): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nomenclature->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($nomenclature);
            $entityManager->flush();
        }

        return $this->redirectToRoute('nomenclature_index', [], Response::HTTP_SEE_OTHER);
    }
}
