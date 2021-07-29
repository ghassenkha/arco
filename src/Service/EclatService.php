<?php

namespace App\Service;

use App\Entity\Achat;
use App\Entity\Nomenclature;
use App\Entity\Eclat;
use App\Entity\Production;
use App\Entity\Stock;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EclatService extends AbstractController
{
    public function Eclat($besoin)
    {
        $e = $this->getDoctrine()->getRepository(Nomenclature::class);
        $s = $this->getDoctrine()->getRepository(Stock::class);

        $aa = [];
        $achat = [];

        foreach ($besoin as $b) {
            $nomc = $e->findBy(['BOM_No' => $b['no']]); //chercher les articles dans Nomenclature

            foreach ($nomc as $n) {
                $no = $n->getNo();
                $somme = $n->getQtper() * $b['qt'];

                $stock = $s->findoneBy(['no' => $no]);
                if ($stock) {
                    $stock = $stock->getQt();
                } else $stock = 0;

                if ($n->getSystReap() == "1") {   ///Production
                    $somme = $n->getQtper() * $b['qt'];
                    $p = new Production();
                    $p->setNo($no);
                    $p->setQt($somme);

                    array_push($aa, $p);
                } else {
                    $a = new Achat();
                    $a->setNo($no);
                    $a->setQt($somme);
                    array_push($achat, $a);
                }
            }
        }
        $aa = $this->sumGB($aa);
        $prod=array();
        foreach ($aa as $p){
            $no=$p['no'];
            $qt=$p['qt'];
            $stock = $s->findoneBy(['no' => $no]);
            if ($stock) {
                $stock = $stock->getQt();
            } else $stock = 0;

            $somme=$qt;
            $p = new Production();
            $p->setNo($no);
            $p->setQt($somme);
            array_push($prod, $p);
        }
        $prod = $this->sumGB($prod);

        return ['prod' => $prod,
            'achat' => $achat];
    }

    public function sumGB($table)
    {
        $data = array();
        $result = array();
        foreach ($table as $a)
            array_push($data, ["no" => $a->getNo(), "qt" => $a->getQt()]);

        // predefine array
        $data_summ = array();
        foreach ($data as $value) {
            $data_summ[$value['no']] = 0;
        }
        foreach ($data as $list) {

            $data_summ[$list['no']] += $list['qt'];
        }
        foreach ($data_summ as $no => $v) {
            array_push($result, ["no" => $no, "qt" => $v]);
        }
        return $result;
    }


}
