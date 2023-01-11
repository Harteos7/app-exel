<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Controller extends AbstractController
{
    
    #[Route('/', name: 'app_')]
    public function index(): Response
    {
        return $this->render('/index.html.twig', [
            'controller_name' => 'Controller',
            'array' => json_encode($this->read('C:\02 Mon projet\exel\hello world.xlsx'))
        ]);


    }









    public function read(string $exel)
    {

        //load spreadsheet
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(strval($exel));
        $sheet = $spreadsheet->getActiveSheet();

        $arr = array();
        $letter = 'A';
        for ($number = 1; ; $number++) { // $number and $letter are the coordinates (A1, A2, B2, C3, ...)

            $id = strval($letter) . strval($number); // $id is the coordinates of the exel box
            $cell = $sheet->getCell($id); // $cell is the content of the exel box
            if ($cell == '') { // we check that the new cell has data otherwise we change the column
                $letter++;
                $number = 1;
                $id = strval($letter) . strval($number);
                $cell = $sheet->getCell($id);

                if ($cell == '') {
                    break;
                } // we check if the first cell of the new column has data if not, we stop the for (break)
                else
                    $arr[strval($id)] = strval($cell);

            } else
                $arr[strval($id)] = strval($cell); // we put everything in array (the key is the coordinates and the value of their data)
        }

        $letterM='';  //$letterM is the largest letter used in the coordinates of the exel table
        $numberM=0;   //$numberM is the largest number used in the coordinates of the exel array

        foreach ($arr as $cle => $valeur) {
            $letterM = strval($letterM) . strval($cle[0]);
        }
        $letterM = substr(strval($letterM), -1); //gets the largest letter

        foreach ($arr as $cle => $valeur) {
            if($numberM<strval($cle[1])){
                $numberM = strval($cle[1]); //get the largest number of coordinates
            }
        }
        
        $letter = 'A';
        for ($number = 1; ;) { // $number and $letter are the coordinates (A1, A2, B2, C3, ...)

            $id = strval($letter) . strval($number); // $id is the coordinates of the exel box
            $cell = $sheet->getCell($id); // $cell is the content of the exel box
            if ($cell == '' ) // this script saves the cell if it is filled
                {} else {
                    $cell = $sheet->getCell($id);
                    $arr[strval($id)] = strval($cell);
                }

            if ($number == $numberM) { // either we change column if we are on the last line or we change line
                $letter++;
                $number = 1;
                $id = strval($letter) . strval($number);
            } else {$number++;}

            if ($letter == $letterM && $number == $numberM) { // if we are on the last cell
                $id = strval($letter) . strval($number); // $id is the coordinates of the exel box
                $cell = $sheet->getCell($id); // $cell is the content of the exel box
                if ($cell == '' ) // this script saves the cell if it is filled
                {break;}
                $cell = $sheet->getCell($id);
                $arr[strval($id)] = strval($cell);
                break; // end

            }
            
        }
        return $arr;
    }
}
