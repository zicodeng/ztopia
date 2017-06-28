<?php
// Collect input from front-end and assign them to variables
$name = strip_tags( $_POST['name'] );
$email = strip_tags( $_POST['email'] );
$message = strip_tags( $_POST['message'] );

$to = 'zicodeng@gmail.com';
$subject = 'zicodeng.me: Message From ' . $name;
$headers = 'From: ' . $email . '\r\n';
$headers .= 'Reply-To:' . $email . '\r\n';
$headers .= 'Return-Path:' . $email . '\r\n';

if ( mail( $to, $subject, $message, $headers ) ) {
	header("Location: http://www.zicodeng.me/thank-you");
} else {
	header("Location: http://www.zicodeng.me/thank-you");
}
?>
