<?php
date_default_timezone_set('America/Los_Angeles');
require_once("lib/simple_html_dom.php");
require_once("lib/twilio-php-master/Services/Twilio.php");
$config = parse_ini_file("tcdisrupt.ini");
$hour = date('G');
function sendMsg($message,$number) {
	global $config;
	// set your AccountSid and AuthToken from www.twilio.com/user/account
	$AccountSid = $config['twilio_sid'];
	$AuthToken = $config['twilio_token'];
 
	$client = new Services_Twilio($AccountSid, $AuthToken);
 
	$sms = $client->account->sms_messages->create(
    		$config['from_phone'], // From this number
    		$number, // To this number
    		$message
	);
 
	// Display a confirmation message on the screen
	echo "Sent message {$sms->sid}";

}

$URL = "http://www.eventbrite.com/tickets-external?eid=7302272293";
$html = file_get_html($URL);
//assuming there is only one row
$rowCells = $html->find('tr[class="ticket_row"] td');
//last cell is index = 4, contains availability status
$cellContents = $rowCells[4]->innertext;
if(stristr($cellContents,"soldout") !== false) {
	print "Still soldout!\n";
} else {
	sendMsg("DisruptHack tickets available at $URL",$config['to_phone']);
}



?>
