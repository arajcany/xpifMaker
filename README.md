# xpifMaker
Create "Xerox Printing Instruction Format" tickets for Xerox print production devices

### Usage
For complete examples, see the 'Examples' folder.

Basic Ticket:
```php
//include the xpifMaker Class file
include_once 'xpifMaker.php';

//set the content type to be XML, so that the browser will recognise it as XML.
header("content-type: application/xml; charset=UTF-8");

//initiate and instance of the Object
$xpfObject = new xpifMaker;

//basic properties (mandatory)
$xpfObject->setProperty("job_name", "Basic_Ticket");
$xpfObject->setProperty("requesting_user_name", "Jane Doe");
$xpfObject->setProperty("copies", 5);
$xpfObject->setProperty("sides", "2");

//render the XPIF Ticket
$xml_string = $xpfObject->renderTicket('C:\tmp\basic_ticket.xpf');

//echo XPIF Ticket to screen
echo $xml_string;
```


Advanced Example:
```php
//include the xpifMaker Class file
include_once 'xpifMaker.php';

//set the content type to be XML, so that the browser will recognise it as XML.
header("content-type: application/xml; charset=UTF-8");

//initiate and instance of the Object
$xpfObject = new xpifMaker;

//basic properties (mandatory)
$xpfObject->setProperty("job_name", "Advanced_Ticket");
$xpfObject->setProperty("requesting_user_name", "Joe Citizen");
$xpfObject->setProperty("copies", 3);
$xpfObject->setProperty("sides", "1");

//media properties
$xpfObject->setProperty("main_media_key", "plain-white-sra3");
$xpfObject->setProperty("main_media_size", "SRA3");
//$xpfObject->setProperty("main_media_x_dimension", "210"); //will overwrite "SRA3"
//$xpfObject->setProperty("main_media_y_dimension", "297"); //will overwrite "SRA3"
$xpfObject->setProperty("main_media_hole_count", "0");
$xpfObject->setProperty("main_media_color", "white");
$xpfObject->setProperty("main_media_front_coating", "uncoated");
$xpfObject->setProperty("main_media_back_coating", "uncoated");
$xpfObject->setProperty("main_media_type", "Stationery");
$xpfObject->setProperty("main_media_weight_metric", "80");

//add some exceptions
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

//render the XPIF Ticket
$xml_string = $xpfObject->renderTicket('C:\tmp\advanced_ticket.xpf');

//echo XPIF Ticket to screen
echo $xml_string;
```

### Installation

Include the xpifMaker Class as part of your Web Application.

### Configuration

None - future builds may include some configuration options.

### Requirements

PHP 5.4.x or greater

### License

Licensed under The MIT License

Developed by arajcany