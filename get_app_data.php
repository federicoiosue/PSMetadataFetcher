<?php

include "./constants.php";
include "./simple_html_dom.php";
		
function getDataWithCacheProxy($cacheFile, $delay, $url) {
	$refreshCacheFile = !is_file($cacheFile) || !filesize($cacheFile) || time() > filemtime($cacheFile) + $delay;
	if ($refreshCacheFile) {
		$html = file_get_html(getCurrentBaseUrl($url));
		file_put_contents($cacheFile, $html);
		return $html;
	} else { 
		return file_get_html($cacheFile);
	}
}

function getCurrentBaseUrl($url) {
	$currentUrl = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$currentBaseUrl = strtok($currentUrl,'?');
	$pageMirrorScript = dirname($currentBaseUrl) . "/page_mirror.php?url=" . $url;
	return $pageMirrorScript;
}

// Create DOM from URL or file
$html = getDataWithCacheProxy($CACHE_FILE, $DELAY, $_GET['url']);

// These are the necessary "itemprop" values extracted from the Play Store page
$itemsProps = array("datePublished", "fileSize", "numDownloads", "softwareVersion", "operatingSystems", "contentRating");

// A JSON object string is now created cyling on "itemprop" values above
$json = '{';
for ($i=0; $i < count($itemsProps); $i++) {
    foreach($html->find('div[itemprop='.$itemsProps[$i].']') as $element)
        $info = trim($element->plaintext);
	$json .= $i != 0 ? ", " : "";
	$json .= '"' . $itemsProps[$i] . '": "' . $info . '"';
}
$json .= '}';

echo $json;

?>
