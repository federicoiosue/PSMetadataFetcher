PSMetadataFetcher
========================

PHP and Javascript scripts useful to retrieve app metadata (size, version...) from Google Play Store


Usage
========================

Simply call this page:
<pre>
http://www.iosue.it/federico/apps/PSMetadataFetcher/index.php?url=
</pre>


Using as url query parameter the url of app into Google Play Store, like:
<pre>
https://play.google.com/store/apps/details?id=it.feio.android.omninotes
</pre>


A json will be returned in this form, the localization will depend on your locale:
<pre>
{
    "datePublished": "18 gennaio 2014", 
    "fileSize": "1,8M", 
    "numDownloads": "500-1.000", 
    "softwareVersion": "4.1.2", 
    "operatingSystems": "2.3 e superiori", 
    "contentRating": "Maturità bassa"
}
</pre>
