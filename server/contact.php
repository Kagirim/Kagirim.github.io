<?php
$from = 'morriskagiri@gmail.com';
$sendTo = 'morriskagiri@gmail.com';
$subject = 'New message from contact form';
$fields = array('name' => 'Name', 'email' => 'Email', 'message' => 'Message');
$okMessage = 'Contact form successfully submitted. Thank you, I will get back to you soon!';
$errorMessage = 'There was an error while submitting the form. Please try again later';
error_reporting(E_ALL & ~E_NOTICE);

if(isset($_POST['email'], $_POST['message'])){
    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $responseArray = array('type' => 'danger', 'message' => $errorMessage);
    }

    if(empty($_POST['message']) || empty($_POST['name'])){
        $responseArray = array('type' => 'danger', 'message' => $errorMessage);
    }

    if(!$responseArray){
        $message = $_POST['message'];
        $headers = 'From: ' . $_POST['email'] . "\r\n" .
            'Reply-To: ' . $_POST['email'] . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        $send = mail($sendTo, $subject, $message, $headers);
        if($send){
            $responseArray = array('type' => 'success', 'message' => $okMessage);
        } else {
            $responseArray = array('type' => 'danger', 'message' => $errorMessage);
        }
    }
}
try
{
    if(count($_POST) == 0) throw new \Exception('Form is empty');
    $emailText = "You have a new message from your contact form\n=============================\n";
    foreach ($_POST as $key => $value) {
        if (isset($fields[$key])) {
            $emailText .= "$fields[$key]: $value\n";
        }
    }
    $headers = array('Content-Type: text/plain; charset="UTF-8";',
        'From: ' . $from,
        'Reply-To: ' . $_POST['email'],
        'Return-Path: ' . $from,
    );
    mail($sendTo, $subject, $emailText, implode("\n", $headers));
    $responseArray = array('type' => 'success', 'message' => $okMessage);
}
catch (\Exception $e)
{
    $responseArray = array('type' => 'danger', 'message' => $errorMessage);
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);
    header('Content-Type: application/json');
    echo $encoded;
}
else {
    echo $responseArray['message'];
}
?>