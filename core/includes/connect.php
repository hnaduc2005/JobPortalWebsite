<?php
checkAccessToken();

// Kết nối đến PDO
try {
    if (class_exists('PDO')) {

        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        $host = DB_HOST;     
        $port = DB_PORT;     
        $dbname = DB_NAME;

        $dsn = DB_DRIVER . ":host={$host};port={$port};dbname={$dbname};charset=utf8";

        $conn = new PDO(
            $dsn,
            DB_USER,
            DB_PASS,
            $options
        );
    }
} catch (Exception $ex) {
    echo 'Lỗi kết nối: ' . $ex->getMessage();
}

?>