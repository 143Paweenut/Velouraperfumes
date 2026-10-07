<?php

session_start();

// ล้างข้อมูล Session ทั้งหมด
session_unset();
session_destroy();

// กลับไปหน้าแรก
header("Location: index.php");
exit();

?>