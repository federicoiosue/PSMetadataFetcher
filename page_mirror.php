<!--
Copyright 2014 Federico Iosue (federico.iosue@gmail.com)

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

// Queried url will be retrieved
$mURL = $_GET['url'];
$content = file_get_contents($mURL);

// Removing image tags
$content = preg_replace("/<img[^>]+\>/i", "", $content);

// Removing most javascript
$content = preg_replace("/<script[^>]+\>/i", "", $content);

// UNSAFE: Removing portion of content from "recomandation" to the end of file
$recomandationDivPattern = "<div class=\"details-section recommendation";
$pos = strpos($content, $recomandationDivPattern);
$content = substr($content, 0, $pos);

echo $content;
?>
