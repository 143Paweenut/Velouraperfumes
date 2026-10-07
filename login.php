<?php

session_start();
require_once "connect.php";

$error = "";


/*
===========================================================
 ตรวจสอบหน้าที่ต้องการให้เด้งกลับหลัง Login
===========================================================
*/

$redirect = $_GET["redirect"] ?? $_POST["redirect"] ?? "index.php";

$allowedRedirects = [
    "index.php",
    "products.php",
    "cart.php",
    "profile.php",
    "collection.php",
    "checkout.php",
    "product_detail.php"
];

if (!in_array($redirect, $allowedRedirects, true)) {
    $redirect = "index.php";
}


/*
===========================================================
 เมื่อกด Login
===========================================================
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";


    /*
    =======================================================
    ตรวจสอบว่ากรอกครบหรือไม่
    =======================================================
    */

    if ($username === "" || $password === "") {

        $error = "กรุณากรอก Username และ Password";

    } else {

        /*
        ===================================================
        ค้นหา Username ในฐานข้อมูล
        ===================================================
        */

        $sql = "
            SELECT
                id,
                username,
                email,
                password,
                fullname,
                address,
                phone
            FROM users
            WHERE username = ?
            LIMIT 1
        ";

        $stmt = $conn->prepare($sql);


        if (!$stmt) {

            $error = "เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล";

        } else {

            $stmt->bind_param("s", $username);

            $stmt->execute();

            $result = $stmt->get_result();


            /*
            =================================================
            พบ Username
            =================================================
            */

            if ($result && $result->num_rows === 1) {

                $user = $result->fetch_assoc();

                /*
                =================================================
                Password ในฐานข้อมูล
                =================================================
                */

                $hashedPassword = $user["password"];


                /*
                =================================================
                ตรวจสอบ Password
                =================================================

                Password ที่ผู้ใช้กรอก
                ↓
                password_verify()
                ↓
                Hash ในฐานข้อมูล
                */

                if (
                    !empty($hashedPassword) &&
                    password_verify(
                        $password,
                        $hashedPassword
                    )
                ) {


                    /*
                    =================================================
                    ตรวจสอบว่าควรสร้าง Hash ใหม่หรือไม่
                    =================================================
                    */

                    if (
                        password_needs_rehash(
                            $hashedPassword,
                            PASSWORD_DEFAULT
                        )
                    ) {

                        $newHash = password_hash(
                            $password,
                            PASSWORD_DEFAULT
                        );

                        $updatePassword = $conn->prepare("
                            UPDATE users
                            SET password = ?
                            WHERE id = ?
                        ");

                        if ($updatePassword) {

                            $updatePassword->bind_param(
                                "si",
                                $newHash,
                                $user["id"]
                            );

                            $updatePassword->execute();

                            $updatePassword->close();
                        }
                    }


                    /*
                    =================================================
                    ป้องกัน Session Fixation
                    =================================================
                    */

                    session_regenerate_id(true);


                    /*
                    =================================================
                    เก็บข้อมูลสมาชิกลง Session
                    =================================================
                    */

                    $_SESSION["user_id"] =
                        $user["id"];

                    $_SESSION["username"] =
                        $user["username"];

                    $_SESSION["email"] =
                        $user["email"];

                    $_SESSION["fullname"] =
                        $user["fullname"] ?? "";

                    $_SESSION["address"] =
                        $user["address"] ?? "";

                    $_SESSION["phone"] =
                        $user["phone"] ?? "";

                    $_SESSION["logged_in"] = true;


                    /*
                    =================================================
                    Login สำเร็จ
                    =================================================
                    */

                    header(
                        "Location: " . $redirect
                    );

                    exit();


                } else {

                    /*
                    =================================================
                    Password ไม่ตรง
                    =================================================
                    */

                    $error =
                        "Password ไม่ถูกต้อง";


                }


            } else {

                /*
                =================================================
                ไม่พบ Username
                =================================================
                */

                $error =
                    "ไม่พบ Username นี้ในระบบ";

            }


            $stmt->close();

        }

    }

}

?>


<!DOCTYPE html>

<html lang="th">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Login | Veloura Perfumes</title>


<!-- GOOGLE FONT -->

<link
    rel="preconnect"
    href="https://fonts.googleapis.com"
>

<link
    rel="preconnect"
    href="https://fonts.gstatic.com"
    crossorigin
>

<link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Montserrat:wght@300;400;500;600&family=Noto+Sans+Thai:wght@300;400;500&display=swap"
    rel="stylesheet"
>


<style>

/* =====================================================
   RESET
===================================================== */

* {
    box-sizing: border-box;
}

html,
body {
    margin: 0;
    min-height: 100%;
}

body {
    background: #f8f5f1;
    color: #302925;
    font-family:
        'Montserrat',
        'Noto Sans Thai',
        sans-serif;
}


/* =====================================================
   PAGE
===================================================== */

.page {

    min-height: 100vh;

    display: flex;

    align-items: center;

    justify-content: center;

    padding: 40px 20px;

    position: relative;

    overflow: hidden;

}


/* =====================================================
   BACKGROUND
===================================================== */

.bg-left {

    position: fixed;

    width: 480px;

    height: 480px;

    border-radius: 50%;

    background: #eee5dc;

    left: -270px;

    top: -150px;

    opacity: .7;

}


.bg-right {

    position: fixed;

    width: 420px;

    height: 420px;

    border-radius: 50%;

    background: #e8ddd3;

    right: -230px;

    bottom: -180px;

    opacity: .65;

}


/* =====================================================
   LOGIN BOX
===================================================== */

.login-wrapper {

    width: 100%;

    max-width: 900px;

    min-height: 570px;

    display: grid;

    grid-template-columns: 42% 58%;

    background: #fff;

    border: 1px solid #e6ddd5;

    box-shadow:
        0 25px 70px
        rgba(48,41,37,.10);

    position: relative;

    z-index: 2;

}


/* =====================================================
   BRAND
===================================================== */

.brand-side {

    background: #302925;

    color: #fff;

    display: flex;

    flex-direction: column;

    justify-content: center;

    align-items: center;

    text-align: center;

    padding: 50px 35px;

    position: relative;

    overflow: hidden;

}


.brand-side::before {

    content: "";

    position: absolute;

    width: 300px;

    height: 300px;

    border:
        1px solid
        rgba(255,255,255,.13);

    border-radius: 50%;

    top: -140px;

    left: -130px;

}


.brand-side::after {

    content: "";

    position: absolute;

    width: 250px;

    height: 250px;

    border:
        1px solid
        rgba(255,255,255,.10);

    border-radius: 50%;

    bottom: -120px;

    right: -120px;

}


/* =====================================================
   LOGO
===================================================== */

.brand-logo {

    width: 145px;

    height: 145px;

    background: #fff;

    display: flex;

    justify-content: center;

    align-items: center;

    margin-bottom: 30px;

    position: relative;

    z-index: 2;

    overflow: hidden;

}


.brand-logo img {

    width: 125px;

    height: 125px;

    object-fit: contain;

    display: block;

}


.brand-name {

    font-family:
        'Cormorant Garamond',
        serif;

    font-size: 35px;

    letter-spacing: 7px;

    font-weight: 400;

    position: relative;

    z-index: 2;

}


.brand-subtitle {

    margin-top: 10px;

    font-size: 9px;

    letter-spacing: 3px;

    color: #cbbeb3;

    position: relative;

    z-index: 2;

}


.brand-line {

    width: 45px;

    height: 1px;

    background: #a88a72;

    margin: 25px 0;

    position: relative;

    z-index: 2;

}


.brand-text {

    max-width: 250px;

    font-size: 11px;

    line-height: 1.9;

    color: #cfc5bd;

    font-weight: 300;

    position: relative;

    z-index: 2;

}


/* =====================================================
   FORM SIDE
===================================================== */

.form-side {

    padding: 55px 65px;

    display: flex;

    flex-direction: column;

    justify-content: center;

}


.form-small-title {

    font-size: 9px;

    color: #9d7b61;

    letter-spacing: 3px;

    text-transform: uppercase;

    margin-bottom: 10px;

}


h1 {

    margin: 0;

    font-family:
        'Cormorant Garamond',
        serif;

    font-size: 50px;

    font-weight: 400;

    color: #302925;

    line-height: 1;

}


.subtitle {

    color: #91857d;

    font-size: 12px;

    margin-top: 12px;

    margin-bottom: 30px;

    line-height: 1.7;

}


.title-line {

    width: 40px;

    height: 1px;

    background: #9d7b61;

    margin-bottom: 28px;

}


/* =====================================================
   ERROR
===================================================== */

.error {

    background: #faf1ef;

    border: 1px solid #ead8d3;

    border-left: 3px solid #9d635b;

    color: #8a5c5c;

    padding: 13px 15px;

    margin-bottom: 20px;

    font-size: 11px;

    line-height: 1.6;

}


/* =====================================================
   FORM
===================================================== */

.form-group {

    margin-bottom: 20px;

}


label {

    display: block;

    margin-bottom: 8px;

    color: #5e5550;

    font-size: 10px;

    letter-spacing: 1px;

    text-transform: uppercase;

}


input {

    width: 100%;

    height: 48px;

    border: none;

    border-bottom:
        1px solid
        #d8cec6;

    background: #fdfbf9;

    padding: 0 13px;

    outline: none;

    color: #302925;

    font-family:
        'Montserrat',
        'Noto Sans Thai',
        sans-serif;

    font-size: 12px;

    transition: .3s ease;

}


input::placeholder {

    color: #b5aaa2;

}


input:focus {

    border-bottom-color:
        #9d7b61;

    background: #fff;

}


/* =====================================================
   BUTTON
===================================================== */

.login-button {

    width: 100%;

    height: 50px;

    margin-top: 8px;

    border: none;

    background: #302925;

    color: #fff;

    font-family:
        'Montserrat',
        'Noto Sans Thai',
        sans-serif;

    font-size: 10px;

    letter-spacing: 2px;

    cursor: pointer;

    transition: .3s ease;

}


.login-button:hover {

    background: #9d7b61;

    transform:
        translateY(-1px);

}


/* =====================================================
   REGISTER
===================================================== */

.register {

    text-align: center;

    margin-top: 25px;

    color: #8c817a;

    font-size: 11px;

}


.register a {

    color: #80654f;

    text-decoration: none;

    font-weight: 500;

    margin-left: 4px;

}


.register a:hover {

    color: #302925;

}


/* =====================================================
   BACK
===================================================== */

.back {

    text-align: center;

    margin-top: 15px;

}


.back a {

    color: #aaa09a;

    text-decoration: none;

    font-size: 10px;

    transition: .3s ease;

}


.back a:hover {

    color: #9d7b61;

}


/* =====================================================
   RESPONSIVE
===================================================== */

@media (max-width:750px) {

    .page {

        padding: 25px 15px;

    }

    .login-wrapper {

        max-width: 480px;

        grid-template-columns: 1fr;

    }

    .brand-side {

        min-height: 260px;

        padding: 35px 25px;

    }

    .brand-logo {

        width: 100px;

        height: 100px;

        margin-bottom: 18px;

    }

    .brand-logo img {

        width: 88px;

        height: 88px;

    }

    .brand-name {

        font-size: 28px;

    }

    .brand-text {

        display: none;

    }

    .brand-line {

        margin: 15px 0;

    }

    .form-side {

        padding: 40px 30px;

    }

}


@media (max-width:400px) {

    .form-side {

        padding: 35px 22px;

    }

    h1 {

        font-size: 43px;

    }

}

</style>

</head>


<body>


<div class="page">


    <div class="bg-left"></div>

    <div class="bg-right"></div>


    <div class="login-wrapper">


        <!-- =================================================
             LEFT
        ================================================== -->

        <div class="brand-side">


            <div class="brand-logo">

                <img
                    src="images/ve.jpg"
                    alt="Veloura Perfumes"
                >

            </div>


            <div class="brand-name">
                VELOURA
            </div>


            <div class="brand-subtitle">
                PERFUMES
            </div>


            <div class="brand-line"></div>


            <div class="brand-text">

                Discover your signature scent.<br>

                Elegance in every moment.

            </div>


        </div>


        <!-- =================================================
             RIGHT
        ================================================== -->

        <div class="form-side">


            <div class="form-small-title">

                Member Login

            </div>


            <h1>

                Welcome Back

            </h1>


            <div class="subtitle">

                เข้าสู่ระบบเพื่อใช้งาน Veloura Perfumes

            </div>


            <div class="title-line"></div>


            <?php if ($error !== ""): ?>

                <div class="error">

                    <?= htmlspecialchars($error) ?>

                </div>

            <?php endif; ?>


            <form
                method="POST"
                action="login.php"
            >


                <input
                    type="hidden"
                    name="redirect"
                    value="<?= htmlspecialchars($redirect) ?>"
                >


                <!-- USERNAME -->

                <div class="form-group">

                    <label>
                        Username
                    </label>

                    <input
                        type="text"
                        name="username"
                        placeholder="กรอก Username"
                        value="<?= htmlspecialchars($_POST["username"] ?? "") ?>"
                        autocomplete="username"
                        required
                    >

                </div>


                <!-- PASSWORD -->

                <div class="form-group">

                    <label>
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        placeholder="กรอก Password ที่สมัครสมาชิก"
                        autocomplete="current-password"
                        required
                    >

                </div>


                <!-- LOGIN -->

                <button
                    type="submit"
                    class="login-button"
                >

                    เข้าสู่ระบบ

                </button>


            </form>


            <div class="register">

                ยังไม่มีบัญชี?

                <a href="register.php">
                    สมัครสมาชิก
                </a>

            </div>


            <div class="back">

                <a href="index.php">

                    ← กลับหน้าหลัก

                </a>

            </div>


        </div>

    </div>

</div>


</body>

</html>