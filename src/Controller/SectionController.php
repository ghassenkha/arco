<?php

namespace App\Controller;

use App\Entity\Gamme;
use App\Entity\Section;
use App\Form\SectionType;
use App\Repository\SectionRepository;
use App\Service\SommeService;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/section")
 */
class SectionController extends AbstractController
{
    /**
     * @Route("/", name="section_index", methods={"GET"})
     */
    public function index(SectionRepository $sectionRepository): Response
    {
        return $this->render('section/index.html.twig', [
            'sections' => $sectionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/Section_import", name="Section_import")
     */
    public function xslx(SommeService $SommeService)
    {
        $fileFolder = __DIR__ . '/../../public/uploads/';  //choose the folder in which the uploaded file will be stored
//
        $filePathName = 'Section.xlsx';

        $spreadsheet = IOFactory::load($fileFolder . $filePathName); // Here we are able to read from the excel file
        $row = $spreadsheet->getActiveSheet()->removeRow(1); // I added this to be able to remove the line off title
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true); // here, the read data is turned into an array
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();        //delete all date from besoin
        $qb->delete('App\Entity\Section', 's')
            ->getQuery()
            ->getResult();


        function mb_str_replace($needle, $replacement, $haystack)  //////supprimer les ','
        {
            return implode($replacement, mb_split($needle, $haystack));
        }

        $batchSize = 5000;
        $i = 0;
        foreach ($sheetData as $Row) {
            $poste = $Row['A'];
            $sect = $Row['B'];
            $eff = $Row['C'];
            $ineff = $Row['D'];




//            $qt = mb_str_replace(',', '', $Row[$alphas[1]]);


            if (strlen($poste) ) {  //// to stop the insertion of null line
                $Section = new Section();
                $Section->setPoste($poste);
                $Section->setSect($sect);
                $Section->setEff($eff);
                $Section->setIneff($ineff);


                $em->persist($Section);



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
        return $this->redirectToRoute('section_index');

//        return $this->redirectToRoute('article_index'
//        );

    }

    /**
     * @Route("/new", name="section_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $section = new Section();
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($section);
            $entityManager->flush();

            return $this->redirectToRoute('section_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('section/new.html.twig', [
            'section' => $section,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="section_show", methods={"GET"})
     */
    public function show(Section $section): Response
    {
        return $this->render('section/show.html.twig', [
            'section' => $section,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="section_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Section $section): Response
    {
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('section_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('section/edit.html.twig', [
            'section' => $section,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="section_delete", methods={"POST"})
     */
    public function delete(Request $request, Section $section): Response
    {
        if ($this->isCsrfTokenValid('delete'.$section->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($section);
            $entityManager->flush();
        }

        return $this->redirectToRoute('section_index', [], Response::HTTP_SEE_OTHER);
    }
}
