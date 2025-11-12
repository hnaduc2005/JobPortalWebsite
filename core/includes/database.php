<?php 
checkAccessToken();

// truy vấn nhiều dòng dữ  liệu
function getAll($sql)
{
    global $conn;
    $stm = $conn->prepare($sql);

    $stm->execute();

    $result = $stm->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}


// Truy vấn 1 dòng dữ liệu
function getOne($sql)
{
    global $conn;
    $stm = $conn->prepare($sql);

    $stm->execute();

    $result = $stm->fetch(PDO::FETCH_ASSOC);

    return $result;
}
?>