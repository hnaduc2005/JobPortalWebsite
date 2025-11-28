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

// đếm số dòng trả về
function getRows($sql)
{
    global $conn;
    $stm = $conn->prepare($sql);
    $stm->execute();
    $rel = $stm->rowCount();

    return $rel;
}

// Insert dữ liệu
function insert($table, $data)
{
    global $conn;

    $keys = array_keys($data);
    $col = implode(',', $keys);
    $place = ':' . implode(',:', $keys);

    $sql = "INSERT INTO $table ($col) VALUES ($place)";

    $stm = $conn->prepare($sql);

    //thực thi câu lệnh
    $rel = $stm->execute($data);

    return $rel;
}

// Update dữ liệu
function update($table, $data, $condition = '')
{
    global $conn;
    $update = '';

    foreach ($data as $key => $value) {
        $update .= $key . '=:' . $key . ',';
    }

    $update = trim($update, ',');


    if (!empty($condition)) {
        $sql = "UPDATE $table SET $update WHERE $condition";
    } else {
        $sql = "UPDATE $table SET $update";
    }

    // chuẩn bị câu lệnh sql
    $tmp = $conn->prepare($sql);

    //thực thi câu lệnh
    $rel = $tmp->execute($data);
}

// Xoá dữ liệu
function delete($table, $condition = '')
{
    global $conn;
    if (!empty($condition)) {
        $sql = "DELETE FROM $table WHERE $condition";
    } else {
        $sql = "DELETE FROM $table";
    }

    $tmp = $conn->prepare($sql);

    $rel = $tmp->execute();
}

// lấy ra dòng dữ liệu mới được insert
function lastID()
{
    global $conn;
    return $conn->lastInsertId();
}
