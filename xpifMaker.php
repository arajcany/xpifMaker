<?php

/*
 * The MIT License
 *
 * Copyright 2015 arajcany.
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

/**
 * xpifMaker Creates a "Xerox Printing Instruction Format" ticket
 *
 * @author arajcany
 * @version 0.01
 */
class xpifMaker {

    //xpifMaker version
    private $version = '0.01';
    //DOM
    private $imp = '';                              //variable for the DOMImplementation
    private $dtd = '';                              //variable for the DOMDocumentType 
    private $xml = '';                              //variable for the DOMDocument
    //XPIF properties
    private $copies = '';                           //(int)
    private $page_ranges = '';                      //(string) number range (e.g. '1-5' is 1,2,3,4,5) || (e.g. '' is all pages)
    private $sides = '';                            //(string) one-sided || two-sided-long-edge || two-sided-short-edge
    private $document_format = '';                  //(string) mime-type
    private $job_name = '';                         //(string)     
    private $requesting_user_name = '';             //(string) 
    private $job_account_id = '';                   //(int)    
    private $job_accounting_user_id = '';           //(string) 
    private $job_accounting_data = '';              //(string) 
    private $job_recipient_name = '';               //(string) 
    private $job_message_to_operator = '';          //(string) 
    private $job_sheet_message = '';                //(string) 
    private $finishings = '';                       //(string) 
    private $separator_sheets = '';                 //(string) 
    private $sheet_collate = '';                    //(string) 
    private $x_side1_image_shift = '';              //(int) 
    private $y_side1_image_shift = '';              //(int) 
    private $x_side2_image_shift = '';              //(int) 
    private $y_side2_image_shift = '';              //(int) 
    private $main_media_key = '';                   //(string)
    private $main_media_size = '';                  //(string)
    private $main_media_x_dimension = '';           //(int) will overwrite the $main_media_size property if set
    private $main_media_y_dimension = '';           //(int) will overwrite the $main_media_size property if set
    private $main_media_hole_count = '';            //(int) 
    private $main_media_color = '';                 //(string)
    private $main_media_front_coating = '';         //(string)
    private $main_media_back_coating = '';          //(string)
    private $main_media_type = '';                  //(string)
    private $main_media_weight_metric = '';         //(int)
    private $main_media_clean = array();            //(array)
    private $exceptions = array();                  //(array)
    private $exceptions_clean = array();            //(array)
    private $exceptions_clean_structure = array();  //(array)
    private $inserts = array();                     //(array)
    private $inserts_clean = array();               //(array)
    private $inserts_clean_structure = array();     //(array)
    private $covers = array();                      //(array)
    private $covers_clean = array();                //(array)
    private $covers_clean_structure = array();      //(array)

    /**
     * Constructor
     * 
     * Set default properties and create the DOMDocument
     * 
     * @return boolean 
     */

    public function __construct() {
        //create DOMImplementation
        $this->imp = new DOMImplementation;

        //create DOMDocumentType instance
        $this->dtd = $this->imp->createDocumentType('xpif', '', 'xpif-v02082.dtd');

        //create DOMDocument instance
        $this->xml = $this->imp->createDocument("", "", $this->dtd);
        $this->xml->encoding = 'UTF-8';
        $this->xml->preserveWhiteSpace = false;
        $this->xml->formatOutput = true;

        //set default properties for the XPIF
        $this->copies = 1;
        $this->sides = 'one-sided';
        $this->page_ranges = '';
        $this->job_name = 'XPIF_MAKER_JOB_' . date('Ymd_His');
        $this->requesting_user_name = 'XPIF_MAKER_v' . $this->version;

        //structure of the exceptions
        $this->exceptions_clean_structure = array(
            'range' => '',
            'sides' => '',
            'media_key' => '',
            'media_size' => '',
            'media_x_dimension' => '',
            'media_y_dimension' => '',
            'media_hole_count' => '',
            'media_color' => '',
            'media_front_coating' => '',
            'media_back_coating' => '',
            'media_type' => '',
            'media_weight_metric' => '',
        );

        //structure of the inserts
        $this->inserts_clean_structure = array(
            'range' => '',
            'count' => '',
            'media_key' => '',
            'media_size' => '',
            'media_x_dimension' => '',
            'media_y_dimension' => '',
            'media_hole_count' => '',
            'media_color' => '',
            'media_front_coating' => '',
            'media_back_coating' => '',
            'media_type' => '',
            'media_weight_metric' => '',
        );

        //structure of the covers
        $this->covers_clean_structure = array(
            'type' => '',
            'sides' => '',
            'media_key' => '',
            'media_size' => '',
            'media_x_dimension' => '',
            'media_y_dimension' => '',
            'media_hole_count' => '',
            'media_color' => '',
            'media_front_coating' => '',
            'media_back_coating' => '',
            'media_type' => '',
            'media_weight_metric' => '',
        );

        return true;
    }

    /**
     * setProperty
     * 
     * Set a property in the XPIF ticket
     *
     * @param string key the property that needs to be set
     * @param string value the value associated with the key  
     * @return boolean  
     */
    public function setProperty($key = NULL, $value = NULL) {
        if (isset($this->$key)) {
            $this->$key = $value;
            //print_r("SET ".$key."|".$value."\r\n");
            return true;
        } else {
            //print_r("NOT SET ".$key."|".$value."\r\n");
            return false;
        }
    }

    /**
     * inflectSize
     * 
     * Return the physical size in mm from keyword size.
     * e.g. A4 => array(210, 297);
     * 
     * @param string $size
     * @return mixed  
     */
    private function inflectSize($size = NULL) {
        if ($size == NULL) {
            return false;
        }

        $size = strtolower($size);

        $commonSizes = array(
            'a0' => array(841, 1189),
            'a1' => array(594, 841),
            'a2' => array(420, 594),
            'a3' => array(420, 297),
            'a4' => array(210, 297),
            'a5' => array(148, 210),
            'a6' => array(105, 148),
            'a7' => array(74, 105),
            'a8' => array(52, 74),
            'a9' => array(37, 52),
            'a10' => array(26, 37),
            'sra3' => array(320, 450),
            'sra4' => array(225, 320),
            'a3+' => array(329, 483),
            'letter' => array(215.9, 279.4),
            'dl' => array(99, 210),
            'dle' => array(110, 220),
            'government letter' => array(203.2, 266.7),
            'legal' => array(215.9, 355.6),
            'junior legal' => array(203.2, 127),
            'ledger' => array(432, 279),
            'tabloid' => array(279, 432),
        );

        if (isset($commonSizes[$size])) {
            return $commonSizes[$size];
        } else {
            return false;
        }
    }

    /**
     * inflectPlex
     * 
     * Return the XPIF compliant plex syntax from a common string.
     *    'one-sided'
     *    'two-sided-long-edge'
     *    'two-sided-short-edge'
     * 
     * @param string $size
     * @return mixed  
     */
    private function inflectPlex($plex = NULL) {
        if ($plex == NULL) {
            return false;
        }

        $plex = strtolower($plex);

        $commonPlexes = array(
            'one-sided' => 'one-sided',
            'two-sided-long-edge' => 'two-sided-long-edge',
            'two-sided-short-edge' => 'two-sided-short-edge',
            '1' => 'one-sided',
            'ss' => 'one-sided',
            'one' => 'one-sided',
            'simplex' => 'one-sided',
            'single' => 'one-sided',
            '2' => 'two-sided-long-edge',
            'ds' => 'two-sided-long-edge',
            'two' => 'two-sided-long-edge',
            'duplex' => 'two-sided-long-edge',
            'double' => 'two-sided-long-edge',
            'hh' => 'two-sided-long-edge', //head to head
            'turn' => 'two-sided-long-edge', //work and turn
            'ht' => 'two-sided-short-edge', //head to toe
            'tumble' => 'two-sided-short-edge', //work and tumble
        );

        if (isset($commonPlexes[$plex])) {
            return $commonPlexes[$plex];
        } else {
            return false;
        }
    }

    /**
     * addException
     * 
     * Add an exception page into the XPIF ticket.
     * 
     * @param array $expArray
     * @return boolean
     */
    public function addException($expArray = NULL) {
        if ($expArray == NULL) {
            return false;
        } elseif (!is_array($expArray)) {
            return false;
        }

        //make sure the any missing keys are added
        $expArray = array_merge($this->exceptions_clean_structure, $expArray);

        $this->exceptions[] = $expArray;
        return true;
    }

    /**
     * addInsert
     * 
     * Add an insert page into the XPIF ticket.
     * 
     * @param array $insArray
     * @return boolean
     */
    public function addInsert($insArray = NULL) {
        if ($insArray == NULL) {
            return false;
        } elseif (!is_array($insArray)) {
            return false;
        }

        //make sure the any missing keys are added
        $insArray = array_merge($this->inserts_clean_structure, $insArray);

        $this->inserts[] = $insArray;
        return true;
    }

    /**
     * addCover
     * 
     * Add an cover page into the XPIF ticket.
     * 
     * @param array $cvArray
     * @return boolean
     */
    public function addCover($cvArray = NULL) {
        if ($cvArray == NULL) {
            return false;
        } elseif (!is_array($cvArray)) {
            return false;
        }

        //make sure the any missing keys are added
        $cvArray = array_merge($this->covers_clean_structure, $cvArray);

        $this->covers[] = $cvArray;
        return true;
    }

    /**
     * rangeExpand
     * 
     * Expand a page range string to comma delimited string.
     * Will correct the page order.
     * Will correct overlapping page ranges.
     * e.g. '12-13,1-4,6' => '1,2,3,4,6,12,13'
     * 
     * @param string $r
     * @param string $returnFormat
     * @return mixed 
     */
    public function rangeExpand($r = NULL, $returnFormat = 'array') {
        if ($r == NULL) {
            return false;
        }

        $rangeFinal = array();

        $ranges = preg_replace('/[^0-9\-,.]/', '', $r);
        $ranges = explode(",", $ranges);
        foreach ($ranges as $range) {
            $range = explode("-", $range); //print_r($range);

            if (isset($range[0])) {
                $lower = ceil(1 * $range[0]);
            } else {
                return false;
            }

            if (isset($range[1])) {
                $upper = ceil(1 * $range[1]);
            } else {
                $upper = $lower;
            }

            $range = range($lower, $upper);
            $rangeFinal = array_merge($rangeFinal, $range);
        }

        $rangeFinal = array_unique($rangeFinal);
        sort($rangeFinal);

        if ($returnFormat == 'array') {
            return $rangeFinal;
        } elseif ($returnFormat == 'string') {
            $rangeFinal = implode(",", $rangeFinal);
            return $rangeFinal;
        } else {
            return false;
        }
    }

    /**
     * rangeCompact
     * 
     * Compact a string of page numbers to a comma delimited string.
     * Corrects the page order.
     * Corrects overlapping page ranges.
     * e.g. '3,4,6,12,13,10-20,1,2,8' => '1-4,6,8,10-20'
     * 
     * If $duplicateStringSingles==true then single pages will display as
     * a range.
     * e.g. '3,4,6,12,13,10-20,1,2,8' => '1-4,6-6,8-8,10-20'
     * 
     * @param type $r
     * @param type $returnFormat
     * @param type $duplicateStringSingles
     * @return mixed
     */
    public function rangeCompact($r = NULL, $returnFormat = 'array', $duplicateStringSingles = false) {
        if ($r == NULL) {
            return false;
        }

        //expand the range first as this will clean
        $numbers = $this->rangeExpand($r);

        $rangeFinal = array();
        $lower = NULL;
        $upper = NULL;

        foreach ($numbers as $key => $number) {

            //define current number
            $currNumber = $number;

            //define previous number
            if (isset($numbers[$key - 1])) {
                $prevNumber = $numbers[$key - 1];
            } else {
                $prevNumber = NULL;
            }

            //define next number
            if (isset($numbers[$key + 1])) {
                $nextNumber = $numbers[$key + 1];
            } else {
                $nextNumber = NULL;
            }

            //search for the lower bound
            if ($lower == NULL) {
                $lower = $currNumber;
            }

            //search for the upper bound
            if ($upper == NULL) {
                if ($currNumber + 1 != $nextNumber) {
                    $upper = $currNumber;
                }
            }

            //add to the final array
            if ($lower != NULL && $upper != NULL) {

                if ($lower == $upper) {
                    $stringSingle = $lower;
                    $stringDouble = $lower . '-' . $upper;
                } else {
                    $stringSingle = $lower . '-' . $upper;
                    $stringDouble = $lower . '-' . $upper;
                }

                $rangeFinal[] = array($lower, $upper, 'lower' => $lower, 'upper' => $upper, 'single' => $stringSingle, 'double' => $stringDouble);
                $lower = NULL;
                $upper = NULL;
            }
        }

        if ($returnFormat == 'array') {
            return $rangeFinal;
        } elseif ($returnFormat == 'string') {
            $rangeFinalStringArray = array();
            foreach ($rangeFinal as $range) {
                if ($duplicateStringSingles == false) {
                    $rangeFinalStringArray[] = $range['single'];
                } else {
                    $rangeFinalStringArray[] = $range['double'];
                }
            }
            $rangeFinalString = implode(",", $rangeFinalStringArray);
            return $rangeFinalString;
        } else {
            return false;
        }
    }

    /**
     * beforeRender
     * 
     * Called directly before the XPIF has been rendered. Mainly used to format
     * Properties that will be used in rendering of the XPIF DOMDocument.
     * Error checking is also performed on the Properties.
     * 
     * @return boolean
     */
    private function beforeRender() {
        //format the main_media
        $this->main_media_clean = array();
        $this->main_media_clean['sides'] = $this->inflectPlex($this->sides);
        $this->main_media_clean['page_ranges'] = $this->rangeCompact($this->page_ranges, 'string', true);
        $this->main_media_clean['media_key'] = $this->main_media_key;
        $this->main_media_clean['media_size'] = $this->main_media_size;
        $this->main_media_clean['media_x_dimension'] = 100 * $this->main_media_x_dimension;
        $this->main_media_clean['media_y_dimension'] = 100 * $this->main_media_y_dimension;
        $this->main_media_clean['media_hole_count'] = 1 * $this->main_media_hole_count;
        $this->main_media_clean['media_color'] = $this->main_media_color;
        $this->main_media_clean['media_front_coating'] = $this->main_media_front_coating;
        $this->main_media_clean['media_back_coating'] = $this->main_media_back_coating;
        $this->main_media_clean['media_type'] = $this->main_media_type;
        $this->main_media_clean['media_weight_metric'] = 1 * $this->main_media_weight_metric;
        $this->main_media_clean['x_side1_image_shift'] = 100 * $this->x_side1_image_shift;
        $this->main_media_clean['y_side1_image_shift'] = 100 * $this->y_side1_image_shift;
        $this->main_media_clean['x_side2_image_shift'] = 100 * $this->x_side2_image_shift;
        $this->main_media_clean['y_side2_image_shift'] = 100 * $this->y_side2_image_shift;

        //calculate inflected media dimensions
        $media_size_inflected = $this->inflectSize($this->main_media_clean['media_size']);
        if ($media_size_inflected) {
            $media_size_inflected_x = 100 * $media_size_inflected[0];
            $media_size_inflected_y = 100 * $media_size_inflected[1];
        } else {
            $media_size_inflected_x = '';
            $media_size_inflected_y = '';
        }

        //choose from x/y or inflected dimensions
        if ($this->main_media_clean['media_x_dimension'] > 0 && $this->main_media_clean['media_y_dimension'] > 0) {
            //use set dimensions
        } elseif ($media_size_inflected_x > 0 && $media_size_inflected_y > 0) {
            //fallback to inflected dimensions
            $this->main_media_clean['media_x_dimension'] = $media_size_inflected_x;
            $this->main_media_clean['media_y_dimension'] = $media_size_inflected_y;
        } else {
            //fallback to no dimensions
            $this->main_media_clean['media_x_dimension'] = '';
            $this->main_media_clean['media_y_dimension'] = '';
        }

        //image shifting
        if ($this->main_media_clean['x_side1_image_shift'] == 0) {
            $this->main_media_clean['x_side1_image_shift'] = '';
        }
        if ($this->main_media_clean['y_side1_image_shift'] == 0) {
            $this->main_media_clean['y_side1_image_shift'] = '';
        }
        if ($this->main_media_clean['x_side2_image_shift'] == 0) {
            $this->main_media_clean['x_side2_image_shift'] = '';
        }
        if ($this->main_media_clean['y_side2_image_shift'] == 0) {
            $this->main_media_clean['y_side2_image_shift'] = '';
        }

        //media weight
        if ($this->main_media_clean['media_weight_metric'] == 0) {
            $this->main_media_clean['media_weight_metric'] = '';
        }

        //media hole count
        if ($this->main_media_clean['media_hole_count'] == 0) {
            $this->main_media_clean['media_hole_count'] = '';
        }

        //format the covers - will only keep the last 'front' and 'back' cover
        $counter = 0;
        $this->covers_clean = array('front' => [], 'back' => []);
        foreach ($this->covers as $cover) {

            //format numbers for 100x precision
            if (isset($cover['media_x_dimension'])) {
                $cover['media_x_dimension'] = 100 * $cover['media_x_dimension'];
                if ($cover['media_x_dimension'] == 0) {
                    $cover['media_x_dimension'] = '';
                }
            }
            if (isset($cover['media_y_dimension'])) {
                $cover['media_y_dimension'] = 100 * $cover['media_y_dimension'];
                if ($cover['media_y_dimension'] == 0) {
                    $cover['media_y_dimension'] = '';
                }
            }

            //make sure media_weight_metric is an integer
            if (isset($cover['media_weight_metric'])) {
                $cover['media_weight_metric'] = 1 * $cover['media_weight_metric'];
                if ($cover['media_weight_metric'] == 0) {
                    $cover['media_weight_metric'] = '';
                }
            }

            //make sure media_hole_count is an integer
            if (isset($cover['media_hole_count'])) {
                $cover['media_hole_count'] = 1 * $cover['media_hole_count'];
                if ($cover['media_hole_count'] == 0) {
                    $cover['media_hole_count'] = '';
                }
            }

            //add to Class property
            if ($cover['type'] == 'front') {
                $this->covers_clean['front'] = $cover;
            } elseif ($cover['type'] == 'back') {
                $this->covers_clean['back'] = $cover;
            }

            //print_r($this->covers_clean);
        }

        //format the exceptions
        $counter = 0;
        $this->exceptions_clean = array();
        foreach ($this->exceptions as $exception) {
            $exceptionRanges = $this->rangeCompact($exception["range"]);
            foreach ($exceptionRanges as $exceptionRange) {

                //import the structure and merge in the existing properties
                $this->exceptions_clean[$counter] = array_merge($this->exceptions_clean_structure, $exception);

                //add in the lower and upper bound
                $this->exceptions_clean[$counter]['range'] = $exceptionRange['double'];

                //format numbers for 100x precision
                if (isset($this->exceptions_clean[$counter]['media_x_dimension'])) {
                    $this->exceptions_clean[$counter]['media_x_dimension'] = 100 * $this->exceptions_clean[$counter]['media_x_dimension'];
                    if ($this->exceptions_clean[$counter]['media_x_dimension'] == 0) {
                        $this->exceptions_clean[$counter]['media_x_dimension'] = '';
                    }
                }
                if (isset($this->exceptions_clean[$counter]['media_y_dimension'])) {
                    $this->exceptions_clean[$counter]['media_y_dimension'] = 100 * $this->exceptions_clean[$counter]['media_y_dimension'];
                    if ($this->exceptions_clean[$counter]['media_y_dimension'] == 0) {
                        $this->exceptions_clean[$counter]['media_y_dimension'] = '';
                    }
                }

                //make sure media_weight_metric is an integer
                if (isset($this->exceptions_clean[$counter]['media_weight_metric'])) {
                    $this->exceptions_clean[$counter]['media_weight_metric'] = 1 * $this->exceptions_clean[$counter]['media_weight_metric'];
                    if ($this->exceptions_clean[$counter]['media_weight_metric'] == 0) {
                        $this->exceptions_clean[$counter]['media_weight_metric'] = '';
                    }
                }

                //make sure media_hole_count is an integer
                if (isset($this->exceptions_clean[$counter]['media_hole_count'])) {
                    $this->exceptions_clean[$counter]['media_hole_count'] = 1 * $this->exceptions_clean[$counter]['media_hole_count'];
                    if ($this->exceptions_clean[$counter]['media_hole_count'] == 0) {
                        $this->exceptions_clean[$counter]['media_hole_count'] = '';
                    }
                }

                $counter++;
            }
        }

        //format the inserts
        $counter = 0;
        $this->inserts_clean = array();
        foreach ($this->inserts as $insert) {

            $insertRanges = $this->rangeExpand($insert["range"]); //print_r($insertRanges);

            foreach ($insertRanges as $insertRange) {

                //import the structure and merge in the existing properties
                $this->inserts_clean[$counter] = array_merge($this->inserts_clean_structure, $insert);

                //redefine the range
                $this->inserts_clean[$counter]['range'] = $insertRange;

                //format numbers for 100x precision
                if (isset($this->inserts_clean[$counter]['media_x_dimension'])) {
                    $this->inserts_clean[$counter]['media_x_dimension'] = 100 * $this->inserts_clean[$counter]['media_x_dimension'];
                    if ($this->inserts_clean[$counter]['media_x_dimension'] == 0) {
                        $this->inserts_clean[$counter]['media_x_dimension'] = '';
                    }
                }
                if (isset($this->inserts_clean[$counter]['media_y_dimension'])) {
                    $this->inserts_clean[$counter]['media_y_dimension'] = 100 * $this->inserts_clean[$counter]['media_y_dimension'];
                    if ($this->inserts_clean[$counter]['media_y_dimension'] == 0) {
                        $this->inserts_clean[$counter]['media_y_dimension'] = '';
                    }
                }

                //make sure media_weight_metric is an integer
                if (isset($this->inserts_clean[$counter]['media_weight_metric'])) {
                    $this->inserts_clean[$counter]['media_weight_metric'] = 1 * $this->inserts_clean[$counter]['media_weight_metric'];
                    if ($this->inserts_clean[$counter]['media_weight_metric'] == 0) {
                        $this->inserts_clean[$counter]['media_weight_metric'] = '';
                    }
                }

                //make sure media_hole_count is an integer
                if (isset($this->inserts_clean[$counter]['media_hole_count'])) {
                    $this->inserts_clean[$counter]['media_hole_count'] = 1 * $this->inserts_clean[$counter]['media_hole_count'];
                    if ($this->inserts_clean[$counter]['media_hole_count'] == 0) {
                        $this->inserts_clean[$counter]['media_hole_count'] = '';
                    }
                }


                $counter++;
            }
        }

        return true;
    }

    /**
     * afterRender
     * 
     * Called directly after the XPIF has been rendered. Mainly used to format
     * the XPIF DOMDocument.
     * 
     * @return boolean
     */
    private function afterRender() {
        //delete empty nodes
        $xpath = new DOMXPath($this->xml);
        $exit_loop = 0;
        while ($exit_loop == 0) {
            $pre_string = $this->xml->saveXML();
            foreach ($xpath->query('//*[not(node())]') as $node) {
                $node->parentNode->removeChild($node);
            }
            $post_string = $this->xml->saveXML();

            if ($pre_string == $post_string) {
                $exit_loop = 1;
            }
        }

        return true;
    }

    /**
     * renderTicket
     * 
     * Render ticket and return an XML string.
     * If $outputLocation is defined and valid, an XPIF ticket will be written
     * to the file system.
     * 
     * @param string $outputLocation
     * @return string
     */
    public function renderTicket($outputLocation = NULL) {

        $this->beforeRender();

        //----START - Render the XML------------------------
        //create root element
        $xpif = $this->xml->createElement("xpif");
        $xpif->setAttribute("version", "1.0");
        $xpif->setAttribute("xml:lang", "en-US");
        $xpif->setAttribute("cpss-version", "2.07");

        //create xpif-operation-attributes node
        $xpif_operation_attributes = $this->createXpifOperationAttributesNode();
        $xpif_operation_attributes = $this->xml->importNode($xpif_operation_attributes, true);

        //create job-template-attributes node
        $job_template_attributes = $this->createJobTemplateAttributesNode();
        $job_template_attributes = $this->xml->importNode($job_template_attributes, true);

        //append
        $xpif->appendChild($xpif_operation_attributes);
        $xpif->appendChild($job_template_attributes);
        $this->xml->appendChild($xpif);
        //----END - Render the XML------------------------

        $this->afterRender();

        //convert the DOMDocument to a string
        $xml_string = $this->xml->saveXML();

        //write to output location
        if ($outputLocation != NULL) {
            $outputLocationDir = pathinfo($outputLocation, PATHINFO_DIRNAME);
            $outputLocationFile = pathinfo($outputLocation, PATHINFO_BASENAME);
            if (is_dir($outputLocationDir)) {
                file_put_contents($outputLocation, $xml_string);
            }
        }

        //return the XML string
        return $xml_string;
    }

    /**
     * createXpifOperationAttributesNode
     * 
     * Creates a DOMNode for XPIF 'xpif-operation-attributes' elements
     * 
     * @return DOMNode
     */
    private function createXpifOperationAttributesNode() {
        $xoa = new DOMDocument;

        $xpif_operation_attributes = $xoa->createElement("xpif-operation-attributes");

        $document_format = $xoa->createElement("document-format", $this->document_format);
        $document_format->setAttribute("syntax", "mimeMediaType");
        $job_name = $xoa->createElement("job-name", $this->job_name);
        $job_name->setAttribute("syntax", "name");
        $job_name->setAttribute("xml:space", "preserve");
        $requesting_user_name = $xoa->createElement("requesting-user-name", $this->requesting_user_name);
        $requesting_user_name->setAttribute("syntax", "name");
        $requesting_user_name->setAttribute("xml:space", "preserve");

        //append
        $xpif_operation_attributes->appendChild($document_format);
        $xpif_operation_attributes->appendChild($job_name);
        $xpif_operation_attributes->appendChild($requesting_user_name);
        $xoa->appendChild($xpif_operation_attributes);

        //return a DOMNode for the  xpif-operation-attributes
        return $xoa->getElementsByTagName("xpif-operation-attributes")->item(0);
    }

    /**
     * createJobTemplateAttributesNode
     * 
     * Creates a DOMNode for XPIF 'job-template-attributes' elements
     * 
     * @return DOMNode
     */
    private function createJobTemplateAttributesNode() {
        $jta = new DOMDocument;

        $job_template_attributes = $jta->createElement("job-template-attributes");

        $job_account_id = $jta->createElement("job-account-id", $this->job_account_id);
        $job_account_id->setAttribute("syntax", "name");
        $job_account_id->setAttribute("xml:space", "preserve");
        $job_accounting_user_id = $jta->createElement("job-accounting-user-id", $this->job_accounting_user_id);
        $job_accounting_user_id->setAttribute("syntax", "name");
        $job_accounting_user_id->setAttribute("xml:space", "preserve");
        $job_accounting_data = $jta->createElement("job-accounting-data", $this->job_accounting_data);
        $job_accounting_data->setAttribute("syntax", "text");
        $job_accounting_data->setAttribute("xml:space", "preserve");
        $job_recipient_name = $jta->createElement("job-recipient-name", $this->job_recipient_name);
        $job_recipient_name->setAttribute("syntax", "name");
        $job_recipient_name->setAttribute("xml:space", "preserve");
        $job_sheet_message = $jta->createElement("job-sheet-message", $this->job_sheet_message);
        $job_sheet_message->setAttribute("syntax", "text");
        $job_sheet_message->setAttribute("xml:space", "preserve");
        $job_message_to_operator = $jta->createElement("job-message-to-operator", $this->job_message_to_operator);
        $job_message_to_operator->setAttribute("syntax", "text");
        $job_message_to_operator->setAttribute("xml:space", "preserve");

        $copies = $jta->createElement("copies", $this->copies);
        $copies->setAttribute("syntax", "integer");
        $sides = $jta->createElement("sides", $this->main_media_clean['sides']);
        $sides->setAttribute("syntax", "keyword");
        $separator_sheets = $jta->createElement("separator-sheets", $this->separator_sheets);
        $sheet_collate = $jta->createElement("sheet-collate", $this->sheet_collate);
        $sheet_collate->setAttribute("syntax", "keyword");

        $x_side1_image_shift = $jta->createElement("x-side1-image-shift", $this->main_media_clean['x_side1_image_shift']);
        $x_side1_image_shift->setAttribute("syntax", "integer");
        $y_side1_image_shift = $jta->createElement("y-side1-image-shift", $this->main_media_clean['y_side1_image_shift']);
        $y_side1_image_shift->setAttribute("syntax", "integer");
        $x_side2_image_shift = $jta->createElement("x-side2-image-shift", $this->main_media_clean['x_side2_image_shift']);
        $x_side2_image_shift->setAttribute("syntax", "integer");
        $y_side2_image_shift = $jta->createElement("y-side2-image-shift", $this->main_media_clean['y_side2_image_shift']);
        $y_side2_image_shift->setAttribute("syntax", "integer");

        //create page-ranges node
        $pageRangesNode = $this->createPageRangesNode($this->main_media_clean['page_ranges']);
        $pageRangesNode = $jta->importNode($pageRangesNode, true);

        //create finishings node
        $finishingsNode = $this->createfinishingsNode($this->finishings);
        $finishingsNode = $jta->importNode($finishingsNode, true);

        //create media-col node
        $mainMediaColNode = $this->createMediaColNode($this->main_media_clean);
        $mainMediaColNode = $jta->importNode($mainMediaColNode, true);

        //create cover-front and cover-back nodes
        $coverFrontNode = $this->createCoverNode('front');
        $coverFrontNode = $jta->importNode($coverFrontNode, true);
        $coverBackNode = $this->createCoverNode('back');
        $coverBackNode = $jta->importNode($coverBackNode, true);

        //create page-overrides node
        $pageOverridesNode = $this->createPageOverridesNode();
        $pageOverridesNode = $jta->importNode($pageOverridesNode, true);

        //create insert-sheet node
        $insertSheetNode = $this->createInsertSheetsNode();
        $insertSheetNode = $jta->importNode($insertSheetNode, true);

        //append
        $job_template_attributes->appendChild($job_account_id);
        $job_template_attributes->appendChild($job_accounting_user_id);
        $job_template_attributes->appendChild($job_accounting_data);
        $job_template_attributes->appendChild($job_recipient_name);
        $job_template_attributes->appendChild($job_sheet_message);
        $job_template_attributes->appendChild($job_message_to_operator);
        $job_template_attributes->appendChild($copies);
        $job_template_attributes->appendChild($pageRangesNode);
        $job_template_attributes->appendChild($finishingsNode);
        $job_template_attributes->appendChild($sides);
        $job_template_attributes->appendChild($separator_sheets);
        $job_template_attributes->appendChild($sheet_collate);
        $job_template_attributes->appendChild($x_side1_image_shift);
        $job_template_attributes->appendChild($y_side1_image_shift);
        $job_template_attributes->appendChild($x_side2_image_shift);
        $job_template_attributes->appendChild($y_side2_image_shift);
        $job_template_attributes->appendChild($mainMediaColNode);
        $job_template_attributes->appendChild($coverFrontNode);
        $job_template_attributes->appendChild($coverBackNode);
        $job_template_attributes->appendChild($pageOverridesNode);
        $job_template_attributes->appendChild($insertSheetNode);
        $jta->appendChild($job_template_attributes);

        //return a DOMNode for the  job-template-attributes
        return $jta->getElementsByTagName("job-template-attributes")->item(0);
    }

    /**
     * createPageOverridesNode
     * 
     * Creates a DOMNode for XPIF 'page-overrides' element.
     * 
     * @return DOMNode
     */
    private function createPageOverridesNode() {

        $po = new DOMDocument;

        $exceptions = $po->createElement('page-overrides');
        $exceptions->setAttribute("syntax", "1setOf");

        foreach ($this->exceptions_clean as $exceptionClean) {
            $value = $po->createElement("value");
            $value->setAttribute("syntax", "collection");

            //create input-documents node
            $inputDocumentsNode = $this->createInputDocumentsNode();
            $inputDocumentsNode = $po->importNode($inputDocumentsNode, true);

            //create page-ranges node
            $pageRangesNode = $this->createPagesNode($exceptionClean['range']);
            $pageRangesNode = $po->importNode($pageRangesNode, true);

            //create media-col node
            $exceptionMediaColNode = $this->createMediaColNode($exceptionClean);
            $exceptionMediaColNode = $po->importNode($exceptionMediaColNode, true);

            //append
            $value->appendChild($inputDocumentsNode);
            $value->appendChild($pageRangesNode);
            $value->appendChild($exceptionMediaColNode);
            $exceptions->appendChild($value);
        }

        //append
        $po->appendChild($exceptions);

        //return a DOMNode for the page-overrides
        return $po->getElementsByTagName('page-overrides')->item(0);
    }

    /**
     * createInsertSheetsNode
     * 
     * Creates a DOMNode for XPIF 'insert-sheet' element.
     * 
     * @return DOMNode
     */
    private function createInsertSheetsNode() {
        $is = new DOMDocument;

        $insert_sheet = $is->createElement('insert-sheet');
        $insert_sheet->setAttribute("syntax", "1setOf");

        foreach ($this->inserts_clean as $insertClean) {
            $value = $is->createElement("value");
            $value->setAttribute("syntax", "collection");

            //create insert-after-page-number node
            $insert_after_page_number = $is->createElement("insert-after-page-number", $insertClean['range']);
            $insert_after_page_number->setAttribute("syntax", "integer");

            //create insert-count node
            $insert_count = $is->createElement("insert-count", $insertClean['count']);
            $insert_count->setAttribute("syntax", "integer");

            //create media-col node
            $insertMediaColNode = $this->createMediaColNode($insertClean);
            $insertMediaColNode = $is->importNode($insertMediaColNode, true);

            //append
            $value->appendChild($insert_after_page_number);
            $value->appendChild($insert_count);
            $value->appendChild($insertMediaColNode);
            $insert_sheet->appendChild($value);
        }

        //append
        $is->appendChild($insert_sheet);

        //return a DOMNode for the insert-sheet
        return $is->getElementsByTagName('insert-sheet')->item(0);
    }

    /**
     * createCoverNode
     * 
     * Create and XPIF cover element (front or back);
     * 
     * @param string $type
     * @param string $sides
     * @return DOMNode
     */
    private function createCoverNode($type = NULL) {
        $cv = new DOMDocument;
           
        //wrong $type passsed in 
        if ($type == 'front') {
            $cover_type_string = 'cover-front';
        } elseif ($type == 'back') {
            $cover_type_string = 'cover-back';
        } else {
            //return empty DOMNode
            $empty = $cv->createElement("cover-none", "");
            $cv->appendChild($empty);
            return $cv->getElementsByTagName('cover-none')->item(0);
        }

        //empty Property
        if (empty($this->covers_clean[$type])) {
            //return empty DOMNode
            $empty = $cv->createElement("cover-none", "");
            $cv->appendChild($empty);
            return $cv->getElementsByTagName('cover-none')->item(0);
        }

        $sides = $this->covers_clean[$type]['sides'];
        if ($sides == 'front') {
            $print_sides_string = 'print-front';
        } elseif ($sides == 'back') {
            $print_sides_string = 'print-back';
        } elseif ($sides == 'both') {
            $print_sides_string = 'print-both';
        } elseif ($sides == 'all') {
            $print_sides_string = 'print-both';
        } else {
            $print_sides_string = '';
        }

        $cover_front_or_back = $cv->createElement($cover_type_string);
        $cover_front_or_back->setAttribute("syntax", "collection");

        $cover_type = $cv->createElement("cover-type", $print_sides_string);
        $cover_type->setAttribute("syntax", "keyword");

        //create media-col node
        $coverMediaColNode = $this->createMediaColNode($this->covers_clean[$type]);
        $coverMediaColNode = $cv->importNode($coverMediaColNode, true);

        //append
        $cover_front_or_back->appendChild($coverMediaColNode);
        $cover_front_or_back->appendChild($cover_type);

        $cv->appendChild($cover_front_or_back);

        //return a DOMNode for the cover
        return $cv->getElementsByTagName($cover_type_string)->item(0);
    }

    /**
     * createMediaColNode
     * 
     * Creates a XPIF media collection.
     * Pass in an array with the following keys:
     *  media_key
     *  media_x_dimension
     *  media_y_dimension
     *  media_hole_count
     *  media_front_coating
     *  media_back_coating
     *  media_type
     *  media_weight_metric
     * 
     * @param array $collection
     * @return DOMNode
     */
    private function createMediaColNode($collection = NULL) {

        $mc = new DOMDocument;

        $media_col = $mc->createElement("media-col");
        $media_col->setAttribute("syntax", "collection");
        if (isset($collection["media_key"])) {
            $media_key = $mc->createElement("media-key", $collection["media_key"]);
            $media_key->setAttribute("syntax", "name");
            $media_key->setAttribute("xml:space", "preserve");
            $media_col->appendChild($media_key);
        }
        if (isset($collection["media_x_dimension"]) || isset($collection["media_y_dimension"])) {
            $media_size = $mc->createElement("media-size");
            $media_size->setAttribute("syntax", "collection");
            if (isset($collection["media_x_dimension"])) {
                $media_x_dimension = $mc->createElement("x-dimension", $collection["media_x_dimension"]);
                $media_x_dimension->setAttribute("syntax", "integer");
                $media_size->appendChild($media_x_dimension);
            }
            if (isset($collection["media_y_dimension"])) {
                $media_y_dimension = $mc->createElement("y-dimension", $collection["media_y_dimension"]);
                $media_y_dimension->setAttribute("syntax", "integer");
                $media_size->appendChild($media_y_dimension);
            }
            $media_col->appendChild($media_size);
        }
        if (isset($collection["media_hole_count"])) {
            $media_hole_count = $mc->createElement("media-hole-count", $collection["media_hole_count"]);
            $media_hole_count->setAttribute("syntax", "integer");
            $media_col->appendChild($media_hole_count);
        }
        if (isset($collection["media_color"])) {
            $media_color = $mc->createElement("media-color", $collection["media_color"]);
            $media_color->setAttribute("syntax", "keyword");
            $media_col->appendChild($media_color);
        }
        if (isset($collection["media_front_coating"])) {
            $media_front_coating = $mc->createElement("media-front-coating", $collection["media_front_coating"]);
            $media_front_coating->setAttribute("syntax", "keyword");
            $media_col->appendChild($media_front_coating);
        }
        if (isset($collection["media_back_coating"])) {
            $media_back_coating = $mc->createElement("media-back-coating", $collection["media_back_coating"]);
            $media_back_coating->setAttribute("syntax", "keyword");
            $media_col->appendChild($media_back_coating);
        }
        if (isset($collection["media_type"])) {
            $media_type = $mc->createElement("media-type", $collection["media_type"]);
            $media_type->setAttribute("syntax", "keyword");
            $media_col->appendChild($media_type);
        }
        if (isset($collection["media_weight_metric"])) {
            $media_weight_metric = $mc->createElement("media-weight-metric", $collection["media_weight_metric"]);
            $media_weight_metric->setAttribute("syntax", "integer");
            $media_col->appendChild($media_weight_metric);
        }

        //append
        $mc->appendChild($media_col);

        //return a DOMNode for the  media-col
        return $mc->getElementsByTagName("media-col")->item(0);
    }

    /**
     * createPageRangesNode
     * 
     * Wrapper function for xpifMaker::createRangeNode.
     * 
     * @param string $range 
     * @return DOMNode
     */
    private function createPageRangesNode($range = NULL) {
        //wrapper function
        return $this->createRangeNode($range, 'page-ranges');
    }

    /**
     * createPagesNode
     * 
     * Wrapper function for xpifMaker::createRangeNode.
     * 
     * @param string $range
     * @return DOMNode
     */
    private function createPagesNode($range = NULL) {
        //wrapper function
        return $this->createRangeNode($range, 'pages');
    }

    /**
     * createInputDocumentsNode
     * 
     * Wrapper function for xpifMaker::createRangeNode.
     * 
     * @param string $range
     * @return DOMNode
     */
    private function createInputDocumentsNode($range = NULL) {
        //wrapper function
        if (is_null($range)) {
            $range = '1-1';
        }
        return $this->createRangeNode($range, 'input-documents');
    }

    /**
     * createRangeNode
     * 
     * Creates a DOMNode for XPIF 'upper' and 'lower' bound elements
     * 
     * @param string $range
     * @param string $tagName
     * @return DOMNode
     */
    private function createRangeNode($range = NULL, $tagName = 'range') {
        if (!is_null($range)) {
            $range = explode('-', $range);

            if (isset($range[0])) {
                $lower = 1 * $range[0]; //make sure its an int
            } else {
                $lower = NULL;
            }
            if (isset($range[1])) {
                $upper = 1 * $range[1]; //make sure its an int
            } else {
                $upper = NULL;
            }

            if ($lower == 0 || $upper == 0) {
                $lower = NULL;
                $upper = NULL;
            }
        } else {
            $lower = NULL;
            $upper = NULL;
        }

        $rng = new DOMDocument;

        $ranges = $rng->createElement($tagName);
        $ranges->setAttribute("syntax", "1setOf");
        $value = $rng->createElement("value");
        $value->setAttribute("syntax", "rangeOfInteger");
        $lower_bound = $rng->createElement("lower-bound", $lower);
        $lower_bound->setAttribute("syntax", "integer");
        $upper_bound = $rng->createElement("upper-bound", $upper);
        $upper_bound->setAttribute("syntax", "integer");

        //append
        $value->appendChild($lower_bound);
        $value->appendChild($upper_bound);
        $ranges->appendChild($value);
        $rng->appendChild($ranges);

        //return a DOMNode for the page-ranges
        return $rng->getElementsByTagName($tagName)->item(0);
    }

    /**
     * createFinishingsNode
     * 
     * Creates a DOMNode for XPIF 'finishings' elements
     * 
     * @param array $finVals
     * @return DOMNode
     */
    private function createFinishingsNode($finVals = NULL) {

        $finNumbers = explode(',', $finVals);

        $fin = new DOMDocument;

        $finishings = $fin->createElement('finishings');
        $finishings->setAttribute("syntax", "1setOf");

        foreach ($finNumbers as $finNumber) {
            $finNumber = trim($finNumber);
            if (is_numeric($finNumber)) {
                $finNumber = 1 * $finNumber;
                if (is_int($finNumber)) {
                    $value = $fin->createElement("value", $finNumber);
                    $value->setAttribute("syntax", "enum");
                    //append
                    $finishings->appendChild($value);
                }
            }
        }

        //append
        $fin->appendChild($finishings);

        //return a DOMNode for the finishings
        return $fin->getElementsByTagName('finishings')->item(0);
    }

}
