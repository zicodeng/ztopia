<?php
// Collect input from front-end and assign them to variables
$name = strip_tags( $_POST['name'] );
$email = strip_tags( $_POST['email'] );
$message = strip_tags( $_POST['message'] );

$to = 'zicodeng@gmail.com';
$subject = 'Contact';
$headers = 'From: ' . $email .'\r\n';
$headers .= 'Reply-To: ' . $email .'\r\n';
$headers .= 'Location: http://www.zicodeng.me';

if ( mail( $to, $subject, $message, $headers ) ) {
    echo 'Email sent successfully!';
} else {
    die('Failure: Email was not sent!');
}
?>
