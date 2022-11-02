<?php
$string = <<<XML
<?xml version='1.0'?> 
<document>
 <title>TestTitle</title>
 <body>
  Here is some text
 </body>
</document>
XML;

$xml = simplexml_load_string($string);

print_r($xml);
?>
