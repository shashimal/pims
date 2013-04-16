<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

class ExportPdfUtility extends TCPDF {

    // Colored table
    public function ColoredTable($header, $data, $celWidth = 40) {

        // Colors, line width and bold font
        //$this->SetFillColor(155, 203, 240);
        $this->SetFillColor(61, 125, 229);
        $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        
        // Header
        $num_headers = count($header);
        
        $w = array();
        for ($a = 0; $a <$num_headers; ++$a) {
            $w[$a] = $celWidth;
        }
        
        for ($i = 0; $i <$num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        
        $this->Ln();
        
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        
        // Data
        $fill = 0;
        foreach ($data as $row) {
            
            foreach ($row as $val) {
                $this->Cell($w[0], 6, $val, 'LR', 0, 'L', $fill);
            }
            
            $this->Ln();
            $fill = ! $fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }

    //Set the common settings for a PDF file
    public function setCommonSettings(ExportPdfUtility $pdf) {

        $language_meta = Array();
        $language_meta['a_meta_charset'] = 'UTF-8';
        $language_meta['a_meta_dir'] = 'ltr';
        $language_meta['a_meta_language'] = 'en';
        $language_meta['w_page'] = 'page';
        
        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
        
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        
        //set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        
        //set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        
        //set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        
        //set some language-dependent strings
        $pdf->setLanguageArray($language_meta);
        
        // set font
        $pdf->SetFont('helvetica', '', 10);
        
        // add a page
        $pdf->AddPage();
    }
}
?>
