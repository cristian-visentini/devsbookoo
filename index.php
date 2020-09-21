<?php
require 'config.php';
require 'models/Auth.php';

$Auth = new Auth($pdo, $Base);

$UserInfo = $Auth->CheckToken();

echo "index";