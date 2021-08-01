<?php

namespace App\Controller;


use App\Entity\Achat;
use App\Repository\BesoinRepository;
use App\Repository\PeriodRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\EclatService;

/**
 * @Route("/")
 */
class CasemploiController extends AbstractController
{
    /**
     * @Route("/CasEmp", name="CasEmp", methods={"GET","POST"})
     */
    public function index(EclatService $EclatService): Response
    {
        $achatRepository = $this->getDoctrine()->getRepository(Achat::class);
        $achat = $achatRepository->findAll();

        $cas=[];

            $EclatResult = $EclatService->CasEmp( $achat[0] );

        dd($EclatResult);
    }
}