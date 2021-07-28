<?php

namespace App\Controller;

use App\Entity\Production;
use App\Form\ProductionType;
use App\Repository\ProductionRepository;
use App\Repository\StockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/production")
 */
class ProductionController extends AbstractController
{
    /**
     * @Route("/", name="production_index", methods={"GET"})
     */
    public function index(ProductionRepository $productionRepository,StockRepository $stockRepository): Response
    {

        $productions = array();

        $prod=$productionRepository->findAll();
        foreach ($prod as $p){
            $stock=$stockRepository->findoneBy(['no' => $p->getNo()]);
            if ($stock){
                $stock=$stock->getQt();}
            else $stock=0;
            array_push($productions,["no"=> $p->getNo(),"qt"=>$p->getQt(),"stock"=>$stock]);
        }

        return $this->render('production/index.html.twig', [
            'productions' => $productions,
        ]);
    }

    /**
     * @Route("/new", name="production_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $production = new Production();
        $form = $this->createForm(ProductionType::class, $production);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($production);
            $entityManager->flush();

            return $this->redirectToRoute('production_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('production/new.html.twig', [
            'production' => $production,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="production_show", methods={"GET"})
     */
    public function show(Production $production): Response
    {
        return $this->render('production/show.html.twig', [
            'production' => $production,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="production_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Production $production): Response
    {
        $form = $this->createForm(ProductionType::class, $production);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('production_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('production/edit.html.twig', [
            'production' => $production,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="production_delete", methods={"POST"})
     */
    public function delete(Request $request, Production $production): Response
    {
        if ($this->isCsrfTokenValid('delete'.$production->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($production);
            $entityManager->flush();
        }

        return $this->redirectToRoute('production_index', [], Response::HTTP_SEE_OTHER);
    }
}
