<?php
namespace App\Http\Controllers;

use App\Models\Commercialdoc;
use TCPDF;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function generatePDF(Commercialdoc $doc)
    {
        // Create new PDF document
        $pdf = new TCPDF();

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Company');
        $pdf->SetTitle('Quote');
        $pdf->SetSubject('Quote Document');
        $pdf->SetKeywords('TCPDF, PDF, quote, invoice');

        // Set header and footer fonts
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 12);

        // Build the HTML content
        $html = <<<EOF
            <style>
                .header-left {
                    width: 50%;
                    float: left;
                }
                .header-right {
                    width: 50%;
                    float: right;
                    text-align: right;
                }
                .clearfix::after {
                    content: "";
                    clear: both;
                    display: table;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                th, td {
                    border: 1px solid black;
                    padding: 10px;
                    text-align: left;
                }
            </style>'
        EOF;

        // Add header content
        $html .= <<<EOF
            <div class="clearfix">
            <div class="header-left">
                <img src="path/to/logo.png" alt="Logo" style="height: 50px;">
                <p>Anthony Cattan<br>Chemin François Chavaz 32<br>1213 Onex</p>
            </div>
            <div class="header-right">
        EOF;

        $headerInfos = $doc->getInfo('header_res_id');
        foreach ($headerInfos as $headerInfo) {
            $html .= '<p>' . $headerInfo->name . ': ' . $headerInfo->id . '</p>';
        }

        $html .= '</div></div>';

        // Add client details
        $html .= '<h2>Client Details</h2>';
        $travelers = $doc->getInfo('traveler_line');
        foreach ($travelers as $traveler) {
            $html .= "<p>{$travelers->name} $travelers->ticket</p>";
        }

        // Add invoice details
        $html .= '<h2>Invoice</h2>';
        $html .= '<table>
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Qtté</th>
                            <th>Prix</th>
                            <th>Sous total</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($doc->items as $item) {
            $subtotal = $item->quantity * $item->unitprice * (1 - $item->discount / 100);
            $html .= '<tr>
                        <td>' . $item->description . '</td>
                        <td>' . $item->quantity . '</td>
                        <td>' . $item->unitprice . '</td>
                        <td>' . $subtotal . '</td>
                      </tr>';
        }

        $html .= '</tbody>
                </table>';

        // Add additional options
        $html .= '<h2>Options supplémentaires commandées</h2>';
        $html .= '<table>
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Qtté</th>
                            <th>Prix</th>
                            <th>Sous total</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($doc->items as $item) {
            if (in_array($item->description, ['Visa', 'Repas', 'Excursion'])) {
                $subtotal = $item->quantity * $item->unitprice;
                $html .= '<tr>
                            <td>' . $item->description . '</td>
                            <td>' . $item->quantity . '</td>
                            <td>' . $item->unitprice . '</td>
                            <td>' . $subtotal . '</td>';
            }
        }

        $html .= '</tbody>
                </table>';

        // Add footer content
        $html .= '<div class="footer">
                    <p>ADN Voyage SARL - Genève - Suisse | www.adnvoyage.com | info@adnvoyage.com</p>
                  </div>';

        // Print text using writeHTML()
        $pdf->writeHTML($html, true, false, true, false, '');

        // Close and output PDF document
        $pdf->Output('quote.pdf', 'D');
    }
}
