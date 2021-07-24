<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {


        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="article_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );
            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }




    /**
     * @Route("/article_import", name="article_import")
     */
    public function xslx()
    {
        $fileFolder = __DIR__ . '/../../public/uploads/';  //choose the folder in which the uploaded file will be stored
//
        $filePathName = 'ARTICLES.xlsx';

        $spreadsheet = IOFactory::load($fileFolder . $filePathName); // Here we are able to read from the excel file
        $row = $spreadsheet->getActiveSheet()->removeRow(1); // I added this to be able to remove the first file line
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true); // here, the read data is turned into an array
//        dump($sheetData);
        $entityManager = $this->getDoctrine()->getManager();

        $batchSize = 5000;
        $i=0;
        foreach ($sheetData as $Row)

        {

            $no = $Row['A']; // store the first_name on each iteration
            $desc = $Row['B']; // store the last_name on each iteration
            $unity= $Row['C'];     // store the email on each iteration
            $replenishment_system = $Row['D'];   // store the phone on each iteration

            $article_existant = $entityManager->getRepository(Article::class)->findOneBy(array('No_' => $no));
                // make sure that the user does not already exists in your db
            if (!$article_existant)
            {
                $article = new Article();
                $article->setNo($no);
                $article->setDescription($desc);
                $article->setUnity($unity);
                $article->setReplenishmentSystem($replenishment_system);
                $entityManager->persist($article);



                // here Doctrine checks all the fields of all fetched data and make a transaction to the database.
            }
            $i++;
            if (($i % $batchSize) === 0) {
                $entityManager->flush();
                $entityManager->clear(); // Detaches all objects from Doctrine!
            }

        }
        $entityManager->flush();
        $entityManager->clear();
        return $this->redirectToRoute('article_index');

//        return $this->redirectToRoute('article_index'
//        );

    }

    /**
     * @Route("/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="article_delete", methods={"POST"})
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
    }
}
