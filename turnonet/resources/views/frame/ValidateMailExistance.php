<?php
set_time_limit(0);

$email = $_GET['email1'];

include_once 'class.verifyEmail.php';

//$email = 'dev223.reinaldo.cardenas@gmail.com';

$vmail = new verifyEmail();
$vmail->setStreamTimeoutWait(20);
$vmail->Debug= TRUE;
$vmail->Debugoutput= 'html';

$vmail->setEmailFrom('viska@viska.is');

if ($vmail->check($email)) {
    echo 'exist!';
    mail('dev.reinaldo.cardenas@gmail.com', 'test','testing this sh1t', 'ff');
} elseif (verifyEmail::validate($email)) {
    echo 'valid but not created!';
} else {
    echo 'nono'; //not valid not created
}
?>
