<?php
require_once '../src/modules/auth/User.php';

$user = new User();
$user->logout();

header('Location: index.php');
exit;
