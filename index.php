<?php
$module = $_GET['module'];
$action = $_GET['action'];

$path = __DIR__ . "/modules/$module/index.php";

if (file_exists($path)) {
    require_once $path;
} else {
    echo "Module not found: $module";
}