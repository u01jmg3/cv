<?php

define('K_TCPDF_EXTERNAL_CONFIG', true);
require_once 'includes/tcpdf_autoconfig.php';
require_once 'includes/config.inc.php';
require_once 'includes/functions.inc.php';
require_once 'vendor/autoload.php';

use Bookworm\Bookworm;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Yaml\Yaml;
use League\ColorExtractor\Palette;
use League\ColorExtractor\Color;

/**
 * Create a PDF using Dompdf with relevant options defined
 *
 * @param string  $html
 * @param string  $filename
 * @param boolean $stream
 * @param string  $dirname
 * @param object  $yaml
 * @return array
 */
function createPDF($html, $filename, $stream, $dirname, $yaml)
{
    $options = new Options();
    $options->set([
        'isPhpEnabled'     => true,
        'isRemoteEnabled'  => true,
        'fontDir'          => 'fonts',
        'fontCache'        => 'fonts',
        'defaultPaperSize' => 'a4',
    ]);
    $dompdf = new Dompdf($options);
    // https://github.com/dompdf/dompdf/commit/23a6939
    ob_start();
    include $html;
    $html = ob_get_clean();
    $dompdf->load_html($html);
    $dompdf->render();

    if ($stream) {
        $dompdf->stream($filename . '.pdf');
    } else {
        $output = $dompdf->output();
        file_put_contents($dirname . $filename . '.pdf', $output);
    }

    return $dompdf->getBookmarks();
}

class PDF extends FPDI_with_annots {
    /**
     * FPDI extension for TCPDF that preserves hyperlinks
     * when copying PDF pages
     *
     * @param integer $pagecount
     * @param string  $orientation
     * @return void
     */
    public function Make($pagecount, $orientation)
    {
        $page_format = [
            'MediaBox' => ['llx' => 0,  'lly' => 0,  'urx' => 210, 'ury' => 297],
            'CropBox'  => ['llx' => 0,  'lly' => 0,  'urx' => 210, 'ury' => 297],
            'BleedBox' => ['llx' => 5,  'lly' => 5,  'urx' => 205, 'ury' => 292],
            'TrimBox'  => ['llx' => 10, 'lly' => 10, 'urx' => 200, 'ury' => 287],
            'ArtBox'   => ['llx' => 15, 'lly' => 15, 'urx' => 195, 'ury' => 282],
            'Dur'      => 3,
            'trans'    => ['D' => 1.5, 'S' => 'Split', 'Dm' => 'V', 'M' => 'O'],
            'Rotate'   => 0,
            'PZ'       => 1,
        ];

        for ($loop = 1; $loop <= $pagecount; $loop++) {
            $this->AddPage($orientation, $page_format, false, false);
            $this->useTemplate($this->importPage($loop));
        }
    }

    /**
     * Define a coloured header
     * and set the background colour if required
     *
     * @return void
     */
    public function Header()
    {
        // Check if colour is white
        if ($this->backgroundColour !== [255, 255, 255]) {
            $this->Rect(0, 0, 210, 297, 'F', '', $this->backgroundColour);
        }

        $this->Rect(0, 0, 210, 4, 'F', '', $this->primaryColour); // 7 is max for $h (fourth parameter)
    }

    /**
     * Define a coloured footer with a centred 1D barcode of the current date
     * Include profile picture if enabled
     *
     * @return void
     */
    public function Footer()
    {
        $this->SetY(-13.4);
        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, 0);
        $this->SetTextColor(40, 40, 40); // Darkest Grey

        $style = [
            'position'     => '',
            'align'        => 'C',
            'stretch'      => false,
            'fitwidth'     => true,
            'cellfitalign' => 'C',
            'border'       => false,
            'padding'      => 0,
            'fgcolor'      => [40, 40, 40],
            'bgcolor'      => false,
            'text'         => false,
        ];
        date_default_timezone_set('Europe/London');
        // https://play.google.com/store/apps/details?id=com.google.zxing.client.android - Barcode Scanner
        $this->write1DBarcode(date('d-m-Y'), 'C128B', '', 286, 190, 3, .3, $style, 'M');
        $this->Rect(0, 293, 210, 4, 'F', '', $this->primaryColour); // 7 is max $h

        if (SHOW_PROFILE_PICTURE) {
            $this->SetAlpha(.35);
            $image = 'images/profile.jpg';
            copy($this->image, $image); // Download locally
            $this->Circle(184, 30, 26, 0, 360, 'CNZ'); // Clipping mask
            $this->Image($image, 158, 4, 52, 52, '', '', '', true, 96);
            $this->Circle(184, 30, 26, 0, 360, null, ['width' => 1, 'color' => [40, 40, 40]]); // Border
        }
    }
}

/**
 * Create a PDF using TCPDF piped from Dompdf (using FPDI)
 * This includes creating bookmarks if a numbered title is found (e.g. `1. Title`)
 * If imagick is loaded an estimated reading time overlay is displayed
 * and a colour swatch of the 6 most used colours
 *
 * @param array   $bookmarks
 * @param array   $array
 * @param integer $pagecounts
 * @return array
 */
function addToBookmarks(array $bookmarks, array $array, $pagecounts)
{
    if (empty($bookmarks)) {
        return $array;
    } else {
        $temp = [];

        foreach ($array as $key => $value) {
            $info       = explode('|', $value);
            $pagenumber = $info[0] + $pagecounts;
            $y          = $info[1];

            if (array_key_exists($key, $bookmarks)) {
                $key .= " - {$pagenumber}"; // Remember array keys must be unique
            }
            $temp[$key] = implode('|', [$pagenumber, $y]);
        }

        $result = array_merge($bookmarks, $temp);
    }

    return $result;
}

// --------------------------------------------

$htmlHome        = 'html';
$filenames       = glob("{$htmlHome}/*.php");
$filename        = basename($filenames[0], '.php'); // First match
$files           = ['temp/unprotected_' . rand() . '.pdf' => "P|{$filename}.php"];
$destinationFile = "{$filename}.pdf";
$stream          = false;
$bookmarks       = [];
$doBookmarks     = true;
$pagecounts      = 0;
$password        = 'ruc6?ray=bu6+J=neb6u';
$yaml            = Yaml::parse(file_get_contents($htmlHome . "/{$filename}.yaml"));
$text            = recursive_implode($yaml);
Bookworm::configure(['wordsPerMinute' => 275]);
$reading_time    = Bookworm::estimate($text, ' min');
$yaml            = json_decode(json_encode($yaml), false);

if (!$stream) {
    // https://sourceforge.net/p/tcpdf/discussion/435311/thread/b16d9231/
    // https://stackoverflow.com/questions/5598257/tcpdf-encryption-digital-signature
    $pdf = new Pdf();
    $pdf->backgroundColour = $yaml->colours->backgroundColour;
    $pdf->primaryColour    = $yaml->colours->primaryColour;
    $pdf->image            = $yaml->basics->picture;
    $pdf->SetDisplayMode($zoom = 125/*'real'*/, $layout = 'continuous', $mode = 'UseNone');
    $preferences = [
        'HideMenubar'     => false,
        'HideToolbar'     => false,
        'HideWindowUI'    => false, // Scrollbars
        'FitWindow'       => true,  // Cause viewer window to be resized (if needed) to fit Page 1 at its set zoom level
        'CenterWindow'    => true,  // Cause viewer window to be centred on the screen
        'DisplayDocTitle' => false, // Display filename as opposed to the 'Title' tag
       #'Duplex'          => 'Simplex', // Print single-sided
        'Duplex'          => 'DuplexFlipLongEdge', // Print double-sided
        'PrintScaling'    => 'None',
    ];
    $pdf->setViewerPreferences($preferences);
    $name = $yaml->basics->name;
    $pdf->SetAuthor($name);
    $pdf->SetCreator($name);

    foreach ($files as $sourceFile => $config) {
        $info        = explode('|', $config);
        $orientation = $info[0];
        $htmlFile    = $htmlHome . '/' . $info[1];

        if ($doBookmarks) {
            $bookmarks = addToBookmarks($bookmarks, createPDF($htmlFile, basename($sourceFile, '.pdf'), $stream, dirname($sourceFile) . '/', $yaml), $pagecounts);
        } else if (!$doBookmarks) {
            createPDF($htmlFile, basename($sourceFile, '.pdf'), $stream, dirname($sourceFile) . '/', $yaml);
        }
        $pagecount = $pdf->setSourceFile($sourceFile);
        $pagecounts += $pagecount;
        $pdf->Make($pagecount, $orientation);
    }

    // Add top of page bookmarks
    $pages = [];
    if ($doBookmarks) {
        for ($i = 1; $i <= $pagecounts; $i++) {
            $pages[$i . '-000'] = 'Page ' . $i;
        }
    }

    foreach ($bookmarks as $key => $value) {
        $info       = explode('|', $value);
        $pagenumber = $info[0];
        $y          = $info[1];
        $pages[$pagenumber . '-' . str_pad((841.88999999998 - $y), 15, 0, STR_PAD_LEFT)] = $key;
    }

    ksort($pages); // Sort bookmarks of Pages and Headers

    foreach ($pages as $key => $value) {
        $info = explode('-', $key);

        $level = (strncmp($value, 'Page ', strlen('Page ')) === 0) ? 0 : 1;
        $pagenumber = $info[0];
        $y = isset($info[1]) ? $info[1] : 0;
        $y = (($y / 841.88999999998) * 296.64730555555);
        $y = ($y === 0) ? -1 : $y;
        $color = [76, 76, 76];

        // Level 2
        if ($level === 1) {
            $y += 2;
            $color = [88, 163, 160];
        }

        $pdf->Bookmark($value, $level, $y, $pagenumber, $style = '', $color);
    }

    $pdf->SetProtection($permissions = ['modify', 'copy', 'annot-forms', 'fill-forms', 'extract', 'assemble'], '', $password, 1, null);

    if (extension_loaded('imagick')) {
        $pdf_output = $pdf->Output($destinationFile, 'S');

        $blob = new Imagick();
        $blob->setResolution(200, 200);
        $blob->readImageBlob($pdf_output);

        $imagick = new Imagick();
        $imagick->newimage(1654, 2339, convert_colour_to_rgb($yaml->colours->backgroundColour)); // Set background to white
        $imagick->compositeimage($blob, Imagick::COMPOSITE_OVER, 0, 0); // Merge both images

        $file_format = 'png';
        $imagick->setImageFormat($file_format);
        $image_path = "images/cv.{$file_format}";
        file_put_contents($image_path, $imagick);

        $html_output = '<style>body { margin: 0 }</style>';

        if (file_exists($image_path)) {
            $palette = Palette::fromFilename($image_path);
            $colours = $palette->getMostUsedColors(6);

            $keys = array_map(function ($colour) {
                return strtolower(ltrim(Color::fromIntToHex($colour), '#'));
            }, array_keys($colours));
            $colours = array_combine($keys, $colours);

            $colours = sort_hex_colours($colours);

            $html_output .= '<style>
            @font-face {
                font-family: secca;
                src: url("fonts/Secca Std - Regular.ttf");
            }
            body {
                font-family: secca;
                background-color: #525659;
            }
            ul {
                position: fixed;
                margin: 60px 0 0 30px;
                padding: 0;
            }
            li {
                min-width: 40px;
                min-height: 20px;
                list-style-type: none;
            }
            li:first-child {
                border-top-left-radius: 3px;
                border-top-right-radius: 3px;
            }
            li:last-child {
                border-bottom-left-radius: 3px;
                border-bottom-right-radius: 3px;
            }
            li:hover {
                transform: scale(1.3);
                box-shadow: 1px 1px 2px rgba(0, 0, 0, .3);
                border-radius: 0;
            }
            .fff { box-shadow: inset 0 0 1px #ccc }
            .reading-time {
                position: fixed;
                left: 50%;
                bottom: 0%;
                transform: translateX(-50%);
                padding: 3px 12px;
                border-top-left-radius: 3px;
                border-top-right-radius: 3px;
                background: rgb(50, 54, 57);
                color: rgb(241, 241, 241);
                opacity: .9;
            }
            .vertically-align {
                position: relative;
                -webkit-transform: translateY(-50%);
                        transform: translateY(-50%);
            }
            .pdf-container {
                top: 620px;
                left: calc(50% - 397px);
                width: 793px;
                box-shadow: 1px 1px 12px -1px rgba(0, 0, 0, .75);
                margin-bottom: 7px;
            }
            .pdf-img { width: 793px }
            @media only screen and (max-width: 1000px) { ul { display: none } }
            </style>';

            $html_output .= '<ul>';
            foreach ($colours as $hex) {
                // If `ffffff` trim to `fff`
                if (preg_match('/^(.)\1*$/', $hex)) {
                    $hex = substr($hex, 0, 3);
                }

                $rgb = implode(', ', Color::fromIntToRgb(Color::fromHexToInt($hex)));

                $html_output .= "<li class='{$hex}' style='background-color: #{$hex}' title='#{$hex} &middot; rgb({$rgb})'></li>";
            }
            $html_output .= '</ul>';
        }

        $view_as_image = false;
        if ($view_as_image) {
            $html_output .= "<div class='vertically-align pdf-container'><img class='pdf-img' src='{$image_path}'></div>";
        } else {
            $pdf_encoded  = base64_encode($pdf_output);
            $html_output .= "<embed width='100%' height='100%' src='data:application/pdf;base64,{$pdf_encoded}' type='application/pdf'>";
        }

        echo sprintf("<body>%s<small class='reading-time'>$reading_time read</small></body>", $html_output);
    } else {
        // Use pdf2png.com
        $pdf->Output($destinationFile, 'I');
    }

    $unprotected = array_keys($files);
    $unprotected = $unprotected[0];
    unlink($unprotected);
} else {
    foreach ($files as $sourceFile => $config) {
        $info        = explode('|', $config);
        $orientation = $info[0];
        $htmlFile    = $htmlHome . '/' . $info[1];

        createPDF($htmlFile, basename($sourceFile, '.pdf'), $stream, dirname($sourceFile) . '/', $yaml);
    }
}