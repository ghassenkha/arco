<?php

namespace App\Controller;

use App\Entity\Eclat;
use App\Form\EclatType;
use App\Repository\EclatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/eclat")
 */
class EclatController extends AbstractController
{
    /**
     * @Route("/", name="eclat_index", methods={"GET"})
     */
    public function index(EclatRepository $eclatRepository): Response
    {
        return $this->render('eclat/index.html.twig', [
            'eclats' => $eclatRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="eclat_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $eclat = new Eclat();
        $form = $this->createForm(EclatType::class, $eclat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($eclat);
            $entityManager->flush();

            return $this->redirectToRoute('eclat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('eclat/new.html.twig', [
            'eclat' => $eclat,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="eclat_show", methods={"GET"})
     */
    public function show(Eclat $eclat): Response
    {
        return $this->render('eclat/show.html.twig', [
            'eclat' => $eclat,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="eclat_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Eclat $eclat): Response
    {
        $form = $this->createForm(EclatType::class, $eclat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('eclat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('eclat/edit.html.twig', [
            'eclat' => $eclat,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="eclat_delete", methods={"POST"})
     */
    public function delete(Request $request, Eclat $eclat): Response
    {
        if ($this->isCsrfTokenValid('delete'.$eclat->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($eclat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('eclat_index', [], Response::HTTP_SEE_OTHER);
    }
}
