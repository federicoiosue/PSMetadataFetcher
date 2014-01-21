PSMetadataFetcher
========================

PHP and Javascript scripts useful to retrieve app metadata (size, version...) from Google Play Store.

Google doesn't provide an API access to retrieve easilly Android application's useful data like for example the actually released version to compare with the one installed on a device and eventually prompt for updates.

A common method to accomplish this task is by providing a service or a web page where to store the app necessary  data and then retrieve them directly from the device when needed.
But given that Google already provides a portal, as I am lazy, I'd like to try to take advantage of their web infrastructure.



##Usage

Simply call this page:
<pre>
http://www.iosue.it/federico/apps/PSMetadataFetcher/check_updates.php?url=
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


###Notes

To reduce data overload the following method is used:

1. Page_mirror.php script has the objective of retrieving a complete local replica of the url passed as parameter;
2. Once downloaded the data content will be stripped off of <img> and <src> tags as much as possible, but this procedure, due to limitation of used host, is accomplished with regex,not the best (of sure) way to do HTML parsing but it's quietly acceptable for our purpose;
3. Check_updates.php will perform an ajax call to get the minimum possible portion from page_mirror.php selecting tags with JQuery and will build a JSON object string to send back to client.
