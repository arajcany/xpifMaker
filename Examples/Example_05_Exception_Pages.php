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

//add some exceptions
$ex = array(
    'range' => '3-4,15-20',
    'sides' => 'one-sided',
    'media_key' => 'plain-pink-a4',
    'media_size' => 'A4',
    'media_x_dimension' => '210',
    'media_y_dimension' => '297',
    'media_hole_count' => '0',
    'media_color' => 'pink',
    'media_front_coating' => 'none',
    'media_back_coating' => 'none',
    'media_type' => 'stationery',
    'media_weight_metric' => '80',
);
$xpfObject->addException($ex);

//add more exceptions with slightly different properties
$ex = array(
    'range' => '55,43,28-32',
    'sides' => 'one-sided',
    'media_key' => 'plain-blue-a4',
    'media_size' => 'A4',
    'media_x_dimension' => '210',
    'media_y_dimension' => '297',
    'media_hole_count' => '0',
    'media_color' => 'blue',
    'media_front_coating' => 'none',
    'media_back_coating' => 'none',
    'media_type' => 'stationery',
    'media_weight_metric' => '80',
);
$xpfObject->addException($ex);

//add some inserts
$in = array(
    'range' => '2,14',
    'count' => '1',
    'media_key' => 'plain-blue-a4',
    'media_size' => 'A4',
    'media_x_dimension' => '210',
    'media_y_dimension' => '297',
    'media_hole_count' => '0',
    'media_color' => 'blue',
    'media_front_coating' => 'none',
    'media_back_coating' => 'none',
    'media_type' => 'stationery',
    'media_weight_metric' => '80',
);
$xpfObject->addInsert($in);

//add more inserts with slightly different properties
$in = array(
    'range' => '54,42,27',
    'count' => '1',
    'media_key' => 'plain-pink-a4',
    'media_size' => 'A4',
    'media_x_dimension' => '210',
    'media_y_dimension' => '297',
    'media_hole_count' => '0',
    'media_color' => 'pink',
    'media_front_coating' => 'none',
    'media_back_coating' => 'none',
    'media_type' => 'stationery',
    'media_weight_metric' => '80gsm',
);
$xpfObject->addInsert($in);

//render the XPIF Ticket
$xml_string = $xpfObject->renderTicket('C:\inetpub\wwwroot\xpifMaker\Examples\Example_05_Output.xpf');

//echo XPIF Ticket to screen
echo $xml_string;
