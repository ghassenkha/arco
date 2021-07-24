<?php

namespace App\Service;

use App\Entity\Achat;
use App\Entity\Nomenclature;
use App\Entity\Production;
use App\Entity\Eclat;
use App\Form\NomenclatureType;
use App\Repository\NomenclatureRepository;
use Symfony\Component\HttpFoundation\Response;

class EclatService
{
    public function Eclat($e,$besoin,$s)
    {

        $prod=[];
        $achat=[];
        ////////-------------------- Besoin doit etre GROUPED BY
        foreach ($besoin as $b){
            $nomc = $e->findBy(['BOM_No' => $b->getNo()]); //chercher les articles dans Nomenclature


            if ($nomc){
                foreach ($nomc as $n) {
                    $no =$n->getNo();
                    $somme=$n->getQtper()*$b->getQt();

                    $stock = $s->findoneBy(['no' => $no]);
                    if ($stock){
                        $stock=$stock->getQt();
                    } else $stock=0;

                    if ($n->getSystReap()=="1"){

                        if ($stock<$somme){         ///Production
                            $somme = $n->getQtper() * $b->getQt() - $stock;
                            $p = new Eclat();
                            $p->setNo($no) ;
                            $p->setQt($somme) ;
                            array_push($prod,$p);

                        }
                    }else{
                        $a = new Achat();
                        $a->setNo($no) ;
                        $a->setQt($somme) ;
                        array_push($achat,$a);
                    }
                }
            }
        }
        return ['prod'=>$prod,
                'achat'=>$achat];
    }


}
