<?php

namespace App\Service;

use App\Entity\Achat;
use App\Entity\Nomenclature;
use App\Entity\Eclat;
use App\Entity\Production;
use App\Entity\Stock;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EclatService extends AbstractController
{
    public function Eclat($besoin)
    {
        $e = $this->getDoctrine()->getRepository(Nomenclature::class);
        $aa = [];
        $achat = [];

        foreach ($besoin as $b) {
            $nomc = $e->findBy(['BOM_No' => $b['no']]); //chercher les articles dans Nomenclature
            $qt = $b['qt'];

            foreach ($nomc as $n) {
                $no = $n->getNo();
                $somme = $n->getQtper() * $qt;
                if ($n->getSystReap() == "1") {   ///Production
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
        $prod = $this->sumGB($aa);
        return ['prod' => $prod,
            'achat' => $achat
        ];
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

    public function VerifStock($ss, $besoin)
    {
        if (sizeof($ss) > 0) { // if stock
            foreach ($besoin as $b => $bal) {
                foreach ($ss as $st => $val) {
                    if ($val['no'] == (String)$bal['no']) {
                        if ($bal['qt'] >= $val['qt']) {
//                            $ss[$st]['qt'] = 0;
                            $besoin[$b]['qt'] = $bal['qt'] - $val['qt'];
                            unset($ss[$st]);

                        } else {
                            $ss[$st]['qt'] = $val['qt'] - $bal['qt'];
//                            $besoin[$b]['qt'] = 0;
                            unset($besoin[$b]);
                        }
                        break;
                    }
                }
            }
        }

        return ['stock' => $ss,
            'besoin' => $besoin];

    }

    public function CasEmp($besoin)
    {

            $e = $this->getDoctrine()->getRepository(Nomenclature::class);


            $nomc = $e->findBy(['No' => $besoin->getNo()]); //chercher les articles dans Nomenclature

            dd($nomc);
        foreach ($nomc as $n)
        dd($b->getNo(),$n->getQtPer(),$n->getBOMNo());
        return $nomc;
    }


}
