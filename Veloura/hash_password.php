<?php

require_once "connect.php";


/*
=====================================================
Username และ Password
=====================================================
*/

$passwords = [

    "admin"  => "1234567",

    "admin2" => "112233",

    "user1"  => "123456"

];


/*
=====================================================
สร้าง Password Hash
=====================================================
*/

foreach ($passwords as $username => $password) {

    $hash = password_hash(
        $password,
        PASSWORD_DEFAULT
    );


    /*
    =================================================
    UPDATE Password
    =================================================
    */

    $sql = "
        UPDATE users
        SET password = ?
        WHERE username = ?
    ";

    $stmt = $conn->prepare($sql);


    if (!$stmt) {

        echo
            "เกิดข้อผิดพลาดของ $username<br>";

        continue;
    }


    $stmt->bind_param(
        "ss",
        $hash,
        $username
    );


    if ($stmt->execute()) {

        if ($stmt->affected_rows > 0) {

            echo
                "เปลี่ยน Password ของ "
                . htmlspecialchars($username)
                . " สำเร็จ<br>";

        } else {

            echo
                "ไม่พบ Username "
                . htmlspecialchars($username)
                . "<br>";

        }

    } else {

        echo
            "ไม่สามารถเปลี่ยน Password ของ "
            . htmlspecialchars($username)
            . " ได้<br>";
    }


    $stmt->close();
}


$conn->close();

?>