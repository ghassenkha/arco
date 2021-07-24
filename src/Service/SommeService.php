<?php
namespace App\Service;

class SommeService
{
    public function SommeCalcule( $besoin,$lastweek) :int
    {
        $somme=0;
        for ($i=1;$i<=16;$i++){
            if ($i<=$lastweek){
                $somme+=$besoin->{"getS".$i}();
            }
        }
        return $somme;
    }


}