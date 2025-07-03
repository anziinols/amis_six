<?php

namespace App\Helpers;

use TCPDF;

/**
 * PDF Helper Class
 * 
 * Provides standardized PDF generation functionality for AMIS system
 * with consistent formatting, headers, footers, and styling
 */
class PdfHelper
{
    private $pdf;
    private $title;
    private $orientation;
    private $unit;
    private $format;

    /**
     * Constructor
     * 
     * @param string $title Document title
     * @param string $orientation Page orientation (P=Portrait, L=Landscape)
     * @param string $unit Unit of measurement (mm, pt, cm, in)
     * @param string $format Page format (A4, A3, LETTER, etc.)
     */
    public function __construct($title = 'AMIS Report', $orientation = 'P', $unit = 'mm', $format = 'A4')
    {
        $this->title = $title;
        $this->orientation = $orientation;
        $this->unit = $unit;
        $this->format = $format;
        
        $this->initializePdf();
    }

    /**
     * Initialize PDF with standard settings
     */
    private function initializePdf()
    {
        // Create new PDF document
        $this->pdf = new TCPDF($this->orientation, $this->unit, $this->format, true, 'UTF-8', false);

        // Set document information
        $this->pdf->SetCreator('AMIS System');
        $this->pdf->SetAuthor('Department of Agriculture and Livestock');
        $this->pdf->SetTitle($this->title);
        $this->pdf->SetSubject('AMIS System Report');
        $this->pdf->SetKeywords('AMIS, Agriculture, Livestock, PNG, Report');

        // Set default header data
        $this->setHeader();

        // Set default footer data
        $this->setFooter();

        // Set margins
        $this->pdf->SetMargins(15, 30, 15);
        $this->pdf->SetHeaderMargin(5);
        $this->pdf->SetFooterMargin(10);

        // Set auto page breaks
        $this->pdf->SetAutoPageBreak(TRUE, 25);

        // Set image scale factor
        $this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // Set default font
        $this->pdf->SetFont('helvetica', '', 10);
    }

    /**
     * Set custom header
     */
    private function setHeader()
    {
        // Logo path - adjust as needed
        $logoPath = FCPATH . 'assets/images/logo.png';

        // Check if logo exists, if not use text header
        if (file_exists($logoPath)) {
            $this->pdf->SetHeaderData($logoPath, 30, 'Department of Agriculture and Livestock',
                "Papua New Guinea\nAgriculture Management Information System (AMIS)\n" . $this->title);
        } else {
            // Use text-only header when logo is not available
            $this->pdf->SetHeaderData('', 0, 'Department of Agriculture and Livestock',
                "Papua New Guinea\nAgriculture Management Information System (AMIS)\n" . $this->title);
        }

        // Set header font
        $this->pdf->setHeaderFont(['helvetica', '', 10]);
    }

    /**
     * Set custom footer
     */
    private function setFooter()
    {
        // Set footer font
        $this->pdf->setFooterFont(['helvetica', '', 8]);
        
        // Footer text
        $this->pdf->setFooterData([0,64,0], [0,64,128]);
    }

    /**
     * Add a new page
     */
    public function addPage()
    {
        $this->pdf->AddPage();
        return $this;
    }

    /**
     * Set font
     * 
     * @param string $family Font family
     * @param string $style Font style
     * @param int $size Font size
     */
    public function setFont($family = 'helvetica', $style = '', $size = 10)
    {
        $this->pdf->SetFont($family, $style, $size);
        return $this;
    }

    /**
     * Add title
     * 
     * @param string $title Title text
     * @param int $fontSize Font size
     */
    public function addTitle($title, $fontSize = 16)
    {
        $this->pdf->SetFont('helvetica', 'B', $fontSize);
        $this->pdf->Cell(0, 10, $title, 0, 1, 'C');
        $this->pdf->Ln(5);
        return $this;
    }

    /**
     * Add subtitle
     * 
     * @param string $subtitle Subtitle text
     * @param int $fontSize Font size
     */
    public function addSubtitle($subtitle, $fontSize = 12)
    {
        $this->pdf->SetFont('helvetica', 'B', $fontSize);
        $this->pdf->Cell(0, 8, $subtitle, 0, 1, 'L');
        $this->pdf->Ln(3);
        return $this;
    }

    /**
     * Add text content
     * 
     * @param string $text Text content
     * @param int $fontSize Font size
     * @param string $align Text alignment (L, C, R, J)
     */
    public function addText($text, $fontSize = 10, $align = 'L')
    {
        $this->pdf->SetFont('helvetica', '', $fontSize);
        $this->pdf->Cell(0, 6, $text, 0, 1, $align);
        return $this;
    }

    /**
     * Add HTML content
     * 
     * @param string $html HTML content
     */
    public function addHtml($html)
    {
        $this->pdf->writeHTML($html, true, false, true, false, '');
        return $this;
    }

    /**
     * Add table from array data
     * 
     * @param array $headers Table headers
     * @param array $data Table data
     * @param array $widths Column widths (optional)
     */
    public function addTable($headers, $data, $widths = [])
    {
        // Calculate column widths if not provided
        if (empty($widths)) {
            $colCount = count($headers);
            $totalWidth = $this->pdf->getPageWidth() - 30; // Account for margins
            $widths = array_fill(0, $colCount, $totalWidth / $colCount);
        }

        // Add table header
        $this->pdf->SetFont('helvetica', 'B', 9);
        $this->pdf->SetFillColor(230, 230, 230);
        
        foreach ($headers as $i => $header) {
            $this->pdf->Cell($widths[$i], 8, $header, 1, 0, 'C', true);
        }
        $this->pdf->Ln();

        // Add table data
        $this->pdf->SetFont('helvetica', '', 8);
        $this->pdf->SetFillColor(255, 255, 255);
        
        foreach ($data as $row) {
            foreach ($row as $i => $cell) {
                $this->pdf->Cell($widths[$i], 6, $cell, 1, 0, 'L', true);
            }
            $this->pdf->Ln();
        }
        
        $this->pdf->Ln(5);
        return $this;
    }

    /**
     * Add line break
     * 
     * @param int $height Height of line break
     */
    public function addLineBreak($height = 5)
    {
        $this->pdf->Ln($height);
        return $this;
    }

    /**
     * Add horizontal line
     */
    public function addHorizontalLine()
    {
        $this->pdf->Line(15, $this->pdf->GetY(), $this->pdf->getPageWidth() - 15, $this->pdf->GetY());
        $this->pdf->Ln(3);
        return $this;
    }

    /**
     * Add image
     * 
     * @param string $imagePath Path to image file
     * @param float $x X position
     * @param float $y Y position
     * @param float $width Width
     * @param float $height Height
     */
    public function addImage($imagePath, $x = '', $y = '', $width = 0, $height = 0)
    {
        if (file_exists($imagePath)) {
            $this->pdf->Image($imagePath, $x, $y, $width, $height);
        }
        return $this;
    }

    /**
     * Get PDF instance for advanced operations
     * 
     * @return TCPDF
     */
    public function getPdf()
    {
        return $this->pdf;
    }

    /**
     * Output PDF to browser
     * 
     * @param string $filename Output filename
     * @param string $destination Output destination (I=inline, D=download, F=file, S=string)
     */
    public function output($filename = 'document.pdf', $destination = 'I')
    {
        return $this->pdf->Output($filename, $destination);
    }

    /**
     * Save PDF to file
     * 
     * @param string $filepath Full file path
     */
    public function save($filepath)
    {
        return $this->pdf->Output($filepath, 'F');
    }
}
