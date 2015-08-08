<?php

/*
 * The MIT License
 *
 * Copyright 2015 Administrator.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

//include the xpifMaker Class file
include_once '../xpifMaker.php';

//set the content type to be XML, so that the browser will recognise it as XML.
header("content-type: application/xml; charset=UTF-8");

//initiate and instance of the Object
$xpfObject = new xpifMaker;

//basic properties (mandatory)
$xpfObject->setProperty("job_name", "Example 01 Ticket");
$xpfObject->setProperty("requesting_user_name", "Jane Doe");
$xpfObject->setProperty("copies", 5);
$xpfObject->setProperty("sides", "2");

//other basic prperties
$xpfObject->setProperty("document_format", "application/pdf");
$xpfObject->setProperty("page_ranges", "");

//job info properties
$xpfObject->setProperty("job_account_id", "000123");
$xpfObject->setProperty("job_accounting_user_id", "au22892342");
$xpfObject->setProperty("job_accounting_data", "MYOB_67903");
$xpfObject->setProperty("job_recipient_name", "Mr Client");
$xpfObject->setProperty("job_sheet_message", "This is displayed in the RIP's front screen");
$xpfObject->setProperty("job_message_to_operator", "A more detailed set of notes that can \n include a new line");

//media properties
$xpfObject->setProperty("main_media_key", "plain-white-a4");
$xpfObject->setProperty("main_media_size", "SRA3");
//$xpfObject->setProperty("main_media_x_dimension", "210"); //will overwrite "SRA3"
//$xpfObject->setProperty("main_media_y_dimension", "297"); //will overwrite "SRA3"
$xpfObject->setProperty("main_media_hole_count", "0");
$xpfObject->setProperty("main_media_color", "white");
$xpfObject->setProperty("main_media_front_coating", "uncoated");
$xpfObject->setProperty("main_media_back_coating", "uncoated");
$xpfObject->setProperty("main_media_type", "Stationery");
$xpfObject->setProperty("main_media_weight_metric", "80");

//image shift properties
$xpfObject->setProperty("x_side1_image_shift", "10"); //in MM
$xpfObject->setProperty("y_side1_image_shift", "10"); //in MM
$xpfObject->setProperty("x_side2_image_shift", "10"); //in MM
$xpfObject->setProperty("y_side2_image_shift", "10"); //in MM

//finishing properties
$xpfObject->setProperty("finishings", "28");        //staple-dual-left
$xpfObject->setProperty("finishings", "28,92,93");  //staple-dual-left + 4-hole-left
$xpfObject->setProperty("finishings", "20");        //staple-top-left

//common finishing options
$options = array(
    '3' => 'none',
    '7' => 'bind',
    '13' => 'booklet-maker',
    '20' => 'staple-top-left',
    '21' => 'staple-bottom-left',
    '22' => 'staple-top-right',
    '23' => 'staple-bottom-right',
    '28' => 'staple-dual-left',
    '29' => 'staple-dual-top',
    '30' => 'staple-dual-right',
    '31' => 'staple-dual-bottom',
    '32' => 'staple-single-center-left',
    '33' => 'staple-single-center-top',
    '34' => 'staple-single-center-right',
    '35' => 'staple-single-center-bottom',
    '50' => 'bind-left',
    '52' => 'bind-right',
    '90' => 'punch-2-hole',
    '91' => 'punch-3-hole',
    '92' => 'punch-4-hole',
    '93' => 'punch-left',
    '94' => 'punch-top',
    '95' => 'punch-right',
    '96' => 'punch-bottom',
    '110' => 'fold-bi-short-edge-n-sheet-convex-saddle-stitch',
    '111' => 'fold-bi-short-edge-n-sheet-convex',
    '1000' => 'fold-c-short-edge-bottom-in-thirds-single-sheet-concave',
    '1001' => 'fold-z-short-edge-in-thirds-single-sheet-concave',
    '1003' => 'fold-bi-short-edge-single-sheet-concave',
    '1004' => 'fold-bi-short-edge-single-sheet-convex',
    '1005' => 'fold-c-short-edge-bottom-in-thirds-single-sheet-convex',
    '1008' => 'fold-z-short-edge-top-in-half-single-sheet-concave',
    '1009' => 'fold-z-short-edge-top-in-half-single-sheet-convex',
    '1010' => 'fold-z-short-edge-bottom-in-half-single-sheet-concave',
    '1011' => 'fold-z-short-edge-bottom-in-half-single-sheet-convex',
    '1012' => 'fold-z-short-edge-in-thirds-single-sheet-convex',
);

//render the XPIF Ticket
$xml_string = $xpfObject->renderTicket('C:\inetpub\wwwroot\xpifMaker\Examples\Example_04_Output.xpf');

//echo XPIF Ticket to screen
echo $xml_string;
