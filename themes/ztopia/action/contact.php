<?php
// Collect input from front-end and assign them to variables
$name = strip_tags( $_POST['name'] );
$email = strip_tags( $_POST['email'] );
$message = strip_tags( $_POST['message'] );

$to = 'zicodeng@gmail.com';
$subject = 'Contact From www.zicodeng.me';
$headers = 'From: ' . $email;

if ( mail( $to, $subject, $message, $headers ) ) {
	?>
	<script type="text/javascript">
		window.location = "http://www.zicodeng.me/thank-you";
	</script>
	<?php
} else {
	?>
	<script type="text/javascript">
		window.location = "http://www.zicodeng.me";
	</script>
	<?php
}
?>
