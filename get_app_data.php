<?php

include "./constants.php";
include "./simple_html_dom.php";


function getDataWithCacheProxy($CACHE_FILE, $DELAY) {
	if (time() > filemtime($CACHE_FILE) + $DELAY) {
		$url = "http://www.iosue.it/federico/apps/PSMetadataFetcher/page_mirror.php?url=".$_GET['url'];
		$html = file_get_html("$url");
		file_put_contents($CACHE_FILE, $html);
		return $html;
	} else { 
		return file_get_html($CACHE_FILE);
	}
}

// Create DOM from URL or file
$html = getDataWithCacheProxy($CACHE_FILE, $DELAY);

// These are the necessary "itemprop" values extracted from the Play Store page
$itemsProps = array("datePublished", "fileSize", "numDownloads", "softwareVersion", "operatingSystems", "contentRating");

$json = '{';

// A JSON object string is now created cyling on "itemprop" values above
for ($i=0; $i < count($itemsProps); $i++) {

    foreach($html->find('div[itemprop='.$itemsProps[$i].']') as $element)
        $info = trim($element->plaintext);
	
	$json .= $i != 0 ? ", " : "";
	$json .= '"' . $itemsProps[$i] . '": "' . $info . '"';

}

$json .= '}';

echo $json;

?>
