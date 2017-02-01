<?php
/**
 * This ia a very simple example on how to add a subscriber in PHP and the 3.0 API.
 *
 * Author: Patrick van der Pols
 * Author URI: http://www.misdirect.info
 */


$mailchimpApikey    = 'REPLACE-THIS-WITH-YOUR-API-KEY'; // Your mailchimp API key;
$startPoint         = 'https://us15.api.mailchimp.com/3.0/'; // The URL you have to make a request to, make sure you point to the right datacenter. (It's the number after the DASH in your api key)
$listID             = 'REPLACE-THIS-WITH-YOUR-LIST-ID'; // Your list ID.
$subscribeAction    = $startPoint . 'lists/' . $listID . '/members/'; // The URL for addin a subscriber
$requestMethod      = 'PUT'; // We make a PUT request, so update or create is the same.
$postData           = [
    'apikey' => $mailchimpApikey,
    'email_address' => strtolower($_REQUEST['email']), // The address you want to subscribe
    'status' => 'subscribed',
    'merge_fields' => [
        'FNAME' => $_REQUEST['first_name'], // Replace this with the actual first name
        'LNAME' => $_REQUEST['last_name'] // Replace this with the actual last name
    ]
];

$json = json_encode($postData); // Convert the array to a json string.



// We create a hash based on the email, if Mailchimp already has this email it will be updated
// otherwise it will be added to the list.
$url = $subscribeAction . md5(strtolower($_REQUEST['email']));

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $mailchimpApikey); // Add your api key to the request
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']); // Set the content type to application/hson
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // This makes sure we can read the response
curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout after 10 seconds
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestMethod); // the PUT request
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Don't verify SSL
curl_setopt($ch, CURLOPT_POSTFIELDS, $json); // Add the fields

$result = curl_exec($ch); // Make the request
curl_close($ch); // Close the connection

print_r($result); // This will tell you if it succeeded or not.