<?php

# DASHBOARD
# /dashboard.php

require_once 'header.php';
require_once 'header_close.php';
require_once 'footer.php';

print preg_replace('~(\.body\s*{\s*)display: flex~', '$1display: block', $header);

print '
<script>

var jwt = localStorage.getItem(\'jwt\');

$.ajax({
	type: "POST",
	url: "dashboard_api.php",
	data: { jwt: jwt }
}).done(function( data ) {



	console.log( data );
	console.log(data.items);



	$.each(data.items, function(key, status_array) {
		console.log(status_array);
		
		var status_format = jQuery.parseJSON(status_array.status_format);
		console.log(status_format);
		
		
		console.log(status_array.status_message);
		
		if (status_array.active_status == 1) {
			$(".statuses_active").append("<div style=\"width: 200px; border-radius: 10px; margin: 10px; text-align: center; padding: 10px; font-family: " + status_format[\'font-family\'] + "; font-weight: " + status_format[\'font-weight\'] + "; color: " + status_format.color + "; background-color: " + status_format[\'background-color\'] + "\">" + status_format.icon + " &nbsp; " + status_array.status_message + "</div>");
		}
		else if (status_array.default_status == 1) {
			$(".statuses_default").append("<div style=\"width: 200px; border-radius: 10px; margin: 10px; text-align: center; padding: 10px; font-family: " + status_format[\'font-family\'] + "; font-weight: " + status_format[\'font-weight\'] + "; color: " + status_format.color + "; background-color: " + status_format[\'background-color\'] + "\">" + status_format.icon + " &nbsp; " + status_array.status_message + "</div>");
		}
		else {
			$(".statuses_other").append("<div style=\"width: 200px; border-radius: 10px; margin: 10px; text-align: center; padding: 10px; font-family: " + status_format[\'font-family\'] + "; font-weight: " + status_format[\'font-weight\'] + "; color: " + status_format.color + "; background-color: " + status_format[\'background-color\'] + "\">" + status_format.icon + " &nbsp; " + status_array.status_message + "</div>");
		}
		
		
	});



});
</script>';



print $header_close;




print '

<div id="statuses_active" class="statuses_active">Active Status</div>

<br><br>

<div style="display: block;" id="statuses_default" class="statuses_default">Default Status</div>

<br><br>

<div id="statuses_other" class="statuses_other">Other Statuses</div>

';




print $footer;
