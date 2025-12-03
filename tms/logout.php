<?php
session_start();
require_once('includes/config.php');
require_once('modules/auth/Auth.php');

$auth = new Auth($dbh);
$auth->logout();

header('Location: index.php');
exit();
?>

