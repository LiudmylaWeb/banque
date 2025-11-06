<?php
require('../../init.php');

$auth = new Auth($conn);
$auth->logout();
header("Location: /");


