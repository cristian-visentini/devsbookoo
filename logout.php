<?php
require "config.php";

$_SESSION['token'] = '';
header("Location: ".$Base);
exit;