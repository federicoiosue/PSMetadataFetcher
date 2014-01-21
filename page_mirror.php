<?php
$mURL = $_GET['url'];

echo $mURL;

echo file_get_contents($mURL);
?>