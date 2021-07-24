<?php

namespace App\Controller;

use App\Entity\Article;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Doctrine\ORM\Query\ResultSetMapping;


class ImportxslController extends AbstractController
{
    /**
     * @Route("/importxsl", name="importxsl")
     */
    public function index(): Response
    {
        $this->xslx();
        return $this->render('importxsl/index.html.twig', [
            'controller_name' => 'ImportxslController',
        ]);
    }

    public function xslx()
    {
//        $file = $request->files->get('file'); // get the file from the sent request

        $fileFolder = __DIR__ . '/../../public/uploads/';  //choose the folder in which the uploaded file will be stored

        $filePathName = 'ARTICLES.xlsx';

        $spreadsheet = IOFactory::load($fileFolder . $filePathName); // Here we are able to read from the excel file
        $row = $spreadsheet->getActiveSheet()->removeRow(1); // I added this to be able to remove the first file line
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true); // here, the read data is turned into an array
//        dump($sheetData);
        $entityManager = $this->getDoctrine()->getManager();
        $article_existant = $entityManager->getRepository(Article::class)->findAll();

        foreach ($sheetData as $row){

            foreach ($article_existant as $art){

            }
        }



        $batchSize = 20;
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
                $i++;
                if (($i % $batchSize) === 0) {
                    $entityManager->flush();
                    $entityManager->clear(); // Detaches all objects from Doctrine!
                }


                // here Doctrine checks all the fields of all fetched data and make a transaction to the database.
            }
            $entityManager->flush();
            $entityManager->clear();
        }
        return $this->json('users registered', 200);

    }
}
