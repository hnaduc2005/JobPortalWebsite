<?php
checkAccessToken();

// Kết nối đến PDO
try {
    if (class_exists('PDO')) {
        $option = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", // hỗ trợ tiếng việt
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // đẩy lỗi vào error exception
        );
        $dsn = DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_NAME;
        $conn = new PDO(
            $dsn,
            DB_USER,
            DB_PASS,
            $option
        );
    }
} catch (Exception $ex) {
    echo 'Lỗi kết nối: ' . $ex->getMessage();
}
?>