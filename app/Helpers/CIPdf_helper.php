<?php

use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

if (!function_exists('createPdf')) {
    function createPdf($data = array())
    {
        $config = array();
        if (is_array($data)) {
            $config = array(
                'data'          => $data['data']         ?? '',
                'paper_size'    => $data['paper_size']   ?? '',
                'file_name'     => $data['file_name']    ?? '',
                'margin'        => $data['margin']       ?? '',
                'stylesheet'    => $data['stylesheet']   ?? '',
                'font_face'     => $data['font_face']    ?? '',
                'font_size'     => $data['font_size']    ?? '',
                'orientation'   => $data['orientation']  ?? '',
                'margin_hf'     => $data['margin_hf']    ?? '',
                'download'      => !empty($data['download']) ? true : false,
                'title'         => $data['title']        ?? '',
                'header'        => $data['header']       ?? '',
                'footer'        => $data['footer']       ?? '',
                'json'          => !empty($data['json'])     ? true : false,
                'kwt'           => !empty($data['kwt'])      ? true : false,
                'save'          => !empty($data['save'])     ? true : false,
            );
        }

        $explode     = explode(' ', $config['margin']);
        $explode_hf  = explode(' ', $config['margin_hf']);
        $orientation = $config['orientation'] ?: 'L';
        $font_face   = $config['font_face'] ?? '';
        $font_size   = $config['font_size'] ?? '';
        $file_name   = $config['file_name'] ?: 'Laporan' . date('dMY');
        $title       = $config['title']     ?: 'Laporan';
        $header      = $config['header']    ?? '';
        $footer      = $config['footer']    ?? '';
        $json        = $config['json']      ?? false;

        ob_clean();

        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => $config['paper_size'],
            'default_font_size' => $font_size,
            'default_font' => $font_face,
            'margin_left' => $explode[3] ?? '',
            'margin_right' => $explode[1] ?? '',
            'margin_top' => $explode[0] ?? '',
            'margin_bottom' => $explode[2] ?? '',
            'margin_header' => $explode_hf[0] ?? '',
            'margin_footer' => $explode_hf[1] ?? '',
            'orientation' => $orientation
        ]);

        $xstylesheet = '';
        if (is_array($config['stylesheet'])) {
            foreach ($config['stylesheet'] as $style) {
                $xstylesheet .= file_get_contents($style);
            }
        } else {
            $xstylesheet = file_get_contents($config['stylesheet']);
        }

        $pdf->WriteHTML($xstylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $pdf->SetTitle($title);

        if ($header) {
            $pdf->SetHTMLHeader($header, '', true);
        }

        if ($footer) {
            $pdf->SetHTMLFooter($footer);
        }

        $pdf->WriteHTML($config['data'], \Mpdf\HTMLParserMode::HTML_BODY);

        ob_end_clean();

        if ($config['save']) {
            $pdf->Output(WRITEPATH . 'pdf/' . $file_name . '.pdf', 'F');
        } else {
            if ($json) {
                $pdfString = $pdf->Output('', 'S');
                $response = [
                    'success' => true,
                    'id' => $file_name,
                    'message' => 'Berhasil',
                    'record' => "data:application/pdf;base64," . base64_encode($pdfString)
                ];
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            } else {
                if ($config['download']) {
                    $pdf->Output($file_name . '.pdf', 'D');
                } else {
                    $pdf->Output($file_name . '.pdf', 'I');
                }
            }
        }
    }
}
