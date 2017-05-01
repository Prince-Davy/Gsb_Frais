<?php
include ('fpdf.php');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$pdf = new FPDF();

   $pdf->AddPage();
   $pdf->SetFont('Courier','',12);
   $pdf->SetDrawColor(0,0,0);
   $pdf->SetFillColor(199,199,199);
   $pdf->SetTextColor(0,0,0);