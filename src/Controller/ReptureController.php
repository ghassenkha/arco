<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Form\AchatType;
use App\Repository\AchatRepository;
use App\Repository\StockRepository;
use App\Service\EclatService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/repture")
 */
class ReptureController extends AbstractController
{
    /**
     * @Route("/", name="repture_index", methods={"GET"})
     */
    public function index(AchatRepository $achatRepository,StockRepository $stockRepository,EclatService $EclatService): Response
    {

        $achats = array();

        $prod=$achatRepository->findAll();
        foreach ($prod as $p){
            $stock=$stockRepository->findoneBy(['no' => $p->getNo()]);
            if ($stock){
                $stock=$stock->getQt();}
            else $stock=0;
            if ($stock<$p->getQt()) {
                $ecart=$stock-$p->getQt();
                $couv=100*$stock/$p->getQt();
                $acmd= $ecart*1.1;
            	array_push($achats,["no"=> $p->getNo(),"qt"=>$p->getQt(),"origine"=>$p->getOrigine(),"stock"=>$stock,"ecart"=>$ecart,"couv"=>$couv,"acmd"=>$acmd]);
            }


            


        }
        return $this->render('repture/index.html.twig', [
            'achats' => $achats,
        ]);
    }

    /**
     * @Route("/new", name="achat_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $achat = new Achat();
        $form = $this->createForm(AchatType::class, $achat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($achat);
            $entityManager->flush();

            return $this->redirectToRoute('achat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('achat/new.html.twig', [
            'achat' => $achat,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="achat_show", methods={"GET"})
     */
    public function show(Achat $achat): Response
    {
        return $this->render('achat/show.html.twig', [
            'achat' => $achat,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="achat_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Achat $achat): Response
    {
        $form = $this->createForm(AchatType::class, $achat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('achat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('achat/edit.html.twig', [
            'achat' => $achat,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="achat_delete", methods={"POST"})
     */
    public function delete(Request $request, Achat $achat): Response
    {
        if ($this->isCsrfTokenValid('delete'.$achat->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($achat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('achat_index', [], Response::HTTP_SEE_OTHER);
    }
}
