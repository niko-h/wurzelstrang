<?php
session_start();

$admin_mail_addr='phelix@howtofaq.org';

if (isset($_SESSION['user']) && ($_SESSION['user'] == $admin_mail_addr) ) {

	echo 'you are admin! '.$_SESSION['user']."<br>";

}

>
