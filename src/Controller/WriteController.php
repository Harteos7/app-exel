<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class WriteController extends AbstractController
{
    #[Route('/write', name: 'app_write')]
    public function index(): Response
    {
        return $this->render('ok.html.twig', [
            $this->write(),
        ]);
    }

    public function write()
    {
        // $_POST['max']; // variable corresponding to the line where we insert
        // $_POST['exel']; // variable corresponding to the name of the exel file
        // $_POST['sheet']; // variable corresponding to the sheet where we have to insert these data

        //load spreadsheet
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_POST['exel']);
        $sheet = $spreadsheet->getSheetByName($_POST['sheet']);

        for ($n = "A"; ; $n++) {
            if (isset($_POST[strval($n) . '1'])) {
                $max = $_POST['max'];
                $sheet->setCellValue(strval($n) . $max, $_POST[strval($n) . '1']);
            } else
                break;
        }
        //write it again to Filesystem with the same name (=replace)
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save($_POST['exel']);
    }

}
