<?php

namespace App\Controller;

use App\Entity\Uap;
use App\Form\UapType;
use App\Repository\UapRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/uap")
 */
class UapController extends AbstractController
{
    /**
     * @Route("/", name="uap_index", methods={"GET"})
     */
    public function index(UapRepository $uapRepository): Response
    {
        return $this->render('uap/index.html.twig', [
            'uaps' => $uapRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="uap_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $uap = new Uap();
        $form = $this->createForm(UapType::class, $uap);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($uap);
            $entityManager->flush();

            return $this->redirectToRoute('uap_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('uap/new.html.twig', [
            'uap' => $uap,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="uap_show", methods={"GET"})
     */
    public function show(Uap $uap): Response
    {
        return $this->render('uap/show.html.twig', [
            'uap' => $uap,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="uap_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Uap $uap): Response
    {
        $form = $this->createForm(UapType::class, $uap);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('uap_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('uap/edit.html.twig', [
            'uap' => $uap,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="uap_delete", methods={"POST"})
     */
    public function delete(Request $request, Uap $uap): Response
    {
        if ($this->isCsrfTokenValid('delete'.$uap->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($uap);
            $entityManager->flush();
        }

        return $this->redirectToRoute('uap_index', [], Response::HTTP_SEE_OTHER);
    }
}
