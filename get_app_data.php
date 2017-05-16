<!--
Copyright 2014-2017 Federico Iosue (federico.iosue@gmail.com)

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
-->

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

function checkVersionPattern($tagInfo) {
	return preg_match('/[0-9]+\.[0-9]+(\.[0-9]+)*/', $tagInfo) == 1;
}

function getTagInfo($html, $element, $itemsProp) {
	$tagInfo = trim($element->plaintext);
	// If multiple app's versions are released on the play store this field would 
	// contain just something like "Varies with device" so we need to dig a little further
	if ($itemsProp == "softwareVersion" && !checkVersionPattern($tagInfo)) {
		$changelogVersion = trim($html->find('div.recent-change', 0)->plaintext);
		if (checkVersionPattern($changelogVersion)) {
			$tagInfo = $changelogVersion;
		}
	}
	return $tagInfo;
}

// Create DOM from URL or file
$html = getDataWithCacheProxy($CACHE_FILE, $DELAY, $_GET['url']);

// These are the necessary "itemprop" values extracted from the Play Store page
$itemsProps = array("datePublished", "fileSize", "numDownloads", "softwareVersion", "operatingSystems", "contentRating");

// A JSON object string is now created cyling on "itemprop" values above
$json = '{';
for ($i=0; $i < count($itemsProps); $i++) {
    foreach($html->find('div[itemprop='.$itemsProps[$i].']') as $element) {
        $info = getTagInfo($html, $element, $itemsProps[$i]);
		$json .= $i != 0 ? ", " : "";
		$json .= '"' . $itemsProps[$i] . '": "' . $info . '"';
	}
}
$json .= '}';

echo $json;

?>
