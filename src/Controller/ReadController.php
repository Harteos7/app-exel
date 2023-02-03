<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReadController extends AbstractController
{

    #[Route('/read', name: 'app_read')]
    public function index(): Response
    {
        return $this->render('read/index.html.twig', [
            'controller_name' => 'ReadController',
            $sort = $_POST['test'],
            'test' => $_POST['test'],
            $a = new Controller(),
            $array3 = $a->getMonArray(),
            'sort' => $sort1 = $this->sort1($array3,$sort),
            'arr' => $this->sort2($array3,$sort1),
            'max' => $a->getMax(),
        ]);
    }

    public function sort1(array $array, string $sort){
        foreach ($array as $cle => $valeur) {
            if ($cle[1] == '2') {
                if (strpos($sort, $valeur) !== false) { // This if returns true if the value in the array is also in the variable $sort, which corresponds if in the second line (the family) the value is the same1
                    $arr[] = $cle[0];
                }
            }
        }
        return $arr;
    }

    public function sort2(array $array,array $arr){
        foreach ($array as $cle1 => $valeur1) {
            foreach ($arr as $cle2 => $valeur2) {
                if ($cle1[0]==$valeur2){
                    $array1[$cle1] = $valeur1;
                }
            }
        }
        return $array1;
    }
    
}
