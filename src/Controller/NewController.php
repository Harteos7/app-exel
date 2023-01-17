<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewController extends AbstractController
{
    #[Route('/new', name: 'app_new')]
    public function index(): Response
    {
        $array1=$this->read1('../../Applications FM.xlsx', 'Liste déroulante de choix');
        $array2=$this->read2('../../Applications FM.xlsx','VI et MP');




        return $this->render('new/index.html.twig', [
            'controller_name' => 'NewController',
            'array1' => $array2,
            'max' => $this->max(),
        ]);
    }

    public function read1(string $exel, string $sheetC)
    {

    //load spreadsheet
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(strval($exel));
    $sheet = $spreadsheet->getSheetByName(strval($sheetC));

    $arr = array();
    $letter ='A';
    $letterM='A';  //$letterM is the largest letter used in the coordinates of the exel table
    $numberM=0;   //$numberM is the largest number used in the coordinates of the exel array
    for ($number = 1; ; $number++) { // $number and $letter are the coordinates (A1, A2, B2, C3, ...)
        
        $id = strval($letter) . strval($number); // $id is the coordinates of the exel box
        $cell = $sheet->getCell($id);// $cell is the content of the exel box
        if($number>$numberM){
            $numberM = $number;
        }
        if ($cell == '') { // we check that the new cell has data otherwise we change the column
            $letter++;
            $letterM=$letter;  //$letterM is the largest letter used in the coordinates of the exel table;
            $number = 1;
            $id = strval($letter) . strval($number);
            $cell = $sheet->getCell($id);

            if ($cell == '') {
                $letterM = substr(strval($letterM), -1); //We recover the letter of before because this one corresponds to an empty column
                break;
            } // we check if the first cell of the new column has data if not, we stop the for (break)
            else
                $arr[strval($id)] = strval($cell);
        } else
            $arr[strval($id)] = strval($cell); // we put everything in array (the key is the coordinates and the value of their data)
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

    public function read2(string $exel, string $sheetC) //this reading is only for the first line (line 1 corresponds to all column names)
    {

    //load spreadsheet
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(strval($exel));
    $sheet = $spreadsheet->getSheetByName(strval($sheetC));

    $arr = array();
    $letter ='A';

    for ($number = 1; ; ) { // $number and $letter are the coordinates (A1, A2, B2, C3, ...)
        
        $id = strval($letter) . strval($number); // $id is the coordinates of the exel box
        $cell = $sheet->getCell($id);// $cell is the content of the exel box
        if ($cell == '') { // we check that the new cell has data otherwise we change the column
            break;
        } else
            $arr[strval($id)] = strval($cell);
            $letter++; // we put everything in array (the key is the coordinates and the value of their data)
    }
    return $arr;
    }

    public function max(){ // the total number of applications is noted in the exel file + the first line
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('../../Applications FM.xlsx');
        $sheet = $spreadsheet->getSheetByName('VI et MP');
        $cell = intval(strval($sheet->getCell('X2')));// The reading makes that we obtain a string, we pass it in int, +1 because we want the number of line to use and not the number of application (the first line!!)

        return $cell+1;
    }

    public function input(){

    }
}