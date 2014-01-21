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

<script src="./jquery-1.10.2.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {	
		/* 	On document request an ajax call will be sent to the page on the server 
		*	that will retrieve Play Store app page content and filter images and
		* 	javascripts inclusions to reduce data overload
		*/
		$.ajax({
            type : 'GET',
            url : 'page_mirror.php',
            dataType : 'html',
            data: {
                url : "<?=$_GET['url']?>"
            },
            success : function(data){                                               
				
				// Just the interesting meta-data section of the page will be grapped
				var infos = $(data).find("div.details-section-contents");
				
				// These are the necessary "itemprop" values extracted from the Play Store page
				var itemsProp = new Array('datePublished', 'fileSize', 'numDownloads', 'softwareVersion', 'operatingSystems', 'contentRating');
				
				// A JSON object string is now created cyling on "itemprop" values above
				var json = '{';
				$.each(itemsProp, function( index, value ) {
					var info = $(infos).find("div[itemprop='" + value +"']").text().trim();
					json += index != 0 ? ", " : "";
					json += '"' + value + '": "' + info + '"';
				});
				json += '}';
				
				// JSON is printed on the screen 
				document.write(json);
				
            }   
        });
	}); 
</script>