<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Controller extends AbstractController
{
    public string $exel = '../../Applications FM.xlsx';
    public string $sheetPrincipal = 'VI et MP';
    public string $sheetChoice = 'Liste déroulante de choix';
    public string $name = 'Name of CURRENT APPLICATION';
    public array $array1; //This variable to all data to display the applications
    public array $array2; //this variable to all data to create the application census form

    public function __construct() 
    {
        $exel = $this->exel;

        $sheetPrincipal = $this->sheetPrincipal;

        $sheetChoice = $this->sheetChoice;

        $this->array1 = $this->read($exel, $sheetPrincipal); //We read and save in a variable to be able to use it several times

        $this->array2 = $this->read1($exel, $sheetChoice);

    }

    #[Route('/', name: 'app_home')]
    public function index1(): Response
    {
        return $this->render('/index.html.twig', [
            $array1 = $this->array1, //We read and save in a variable to be able to use it several times
            'array1' => $this->famille($array1),
            'controller_name' => 'Controller',
        ]); 
    }

    #[Route('/search', name: 'app_search')]
    public function index2(): Response
    {
        return $this->render('/search.html.twig', [
            'controller_name' => 'Controller',

        ]); 
    }

    #[Route('/new', name: 'app_new')]
    public function index3(): Response
    {
        return $this->render('new/index.html.twig', [
            'controller_name' => 'Controller',
            $array1 = $this->array1, //We read and save in a variable to be able to use it several times
            $name = $this->name,
            'array1' => $this->array1,
            'array2' => $this->array2,
            'name' => $this->name,
            'sheet' => $this->sheetPrincipal,
            'max' => $this->max($array1),
            'exel' => $this->exel,
            'arrayname' => $this->name($array1, $name), 
        ]);
    }

    #[Route('/admin', name: 'app_admin')]
    public function index4(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'exel' => $this->exel,
            'principal' => $this->sheetPrincipal,
            'choice' => $this->sheetChoice,
            'name' => $this->name,
        ]);
    }

    public function read(string $exel, string $sheetC)
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

        $writer = new Xlsx($spreadsheet);
        $writer->save($exel);
        $letter++;
        
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

    public function max(array $arr){
        $max = 0;
        foreach ($arr as $cle => $valeur) {
            if ($max < substr($cle, 1, 4)) {
                $max++;
            }
        }
        return $max+1;
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

    public function name(array $arr,string $name) { // function to get an array of all application names listed 
        foreach ($arr as $cle => $valeur) {
            if ($valeur == $name) {
                $letter=$cle[0];
            }
        }
        $array = array();
        $n = 0;
        foreach ($arr as $cle => $valeur) {
            if ($cle[0] == $letter && $n != 0 &&$n != 1 ) {
                $array[] = $valeur;
            }elseif ($cle[0] == $letter){
                $n++;
            }
        }
        return $array;
    }

    public function famille(array $arr) {
        $array = array();
        foreach ($arr as $cle => $valeur) {
            if (substr($cle, 1, 4) == '2') {
                $array[] = $valeur;
            }
        }
        $array = array_unique($array);
        return $array;
    }
}
