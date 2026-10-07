<?php

session_start();

require_once "../connect.php";

$error = "";


/* =====================================================
   ตรวจสอบว่าฐานข้อมูลเชื่อมต่อได้หรือไม่
===================================================== */

if (!isset($conn) || !$conn) {

    die("ไม่สามารถเชื่อมต่อฐานข้อมูลได้");

}


/* =====================================================
   LOGIN ADMIN
===================================================== */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";


    /* ตรวจสอบข้อมูล */

    if ($username === "" || $password === "") {

        $error = "กรุณากรอกชื่อผู้ใช้และรหัสผ่าน";

    } else {


        /* =================================================
           ค้นหา Admin จากฐานข้อมูล
        ================================================= */

        $stmt = $conn->prepare("
            SELECT id, username, password
            FROM admins
            WHERE username = ?
            LIMIT 1
        ");


        if (!$stmt) {

            $error = "เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: "
                   . $conn->error;

        } else {

            $stmt->bind_param("s", $username);

            $stmt->execute();

            $result = $stmt->get_result();


            /* =================================================
               ตรวจสอบ Username
            ================================================= */

            if ($result->num_rows === 1) {

                $admin = $result->fetch_assoc();


                /* =================================================
                   ตรวจสอบ Password
                   
                   รองรับทั้ง
                   - password ธรรมดา admin123
                   - password_hash()
                ================================================= */

                $password_ok = false;


                // ถ้าเป็น password ธรรมดา
                if ($password === $admin["password"]) {

                    $password_ok = true;

                }

                // ถ้าเป็น password_hash()
                elseif (
                    password_get_info($admin["password"])["algo"] !== 0
                    &&
                    password_verify(
                        $password,
                        $admin["password"]
                    )
                ) {

                    $password_ok = true;

                }


                /* =================================================
                   LOGIN สำเร็จ
                ================================================= */

                if ($password_ok) {

                    session_regenerate_id(true);

                    $_SESSION["admin_logged_in"] = true;

                    $_SESSION["admin_id"] =
                        $admin["id"];

                    $_SESSION["admin_username"] =
                        $admin["username"];


                    header("Location: products.php");

                    exit;

                } else {

                    $error =
                        "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";

                }

            } else {

                $error =
                    "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";

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

<title>Admin Login | VELOURA</title>


<link rel="preconnect" href="https://fonts.googleapis.com">

<link
    rel="preconnect"
    href="https://fonts.gstatic.com"
    crossorigin
>

<link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Kanit:wght@300;400;500&family=Montserrat:wght@300;400;500&display=swap"
    rel="stylesheet"
>


<style>

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}


body {

    min-height: 100vh;

    font-family: "Kanit", sans-serif;

    background:

        linear-gradient(
            rgba(246,241,235,.94),
            rgba(246,241,235,.94)
        ),

        radial-gradient(
            circle at top left,
            #ead8c7,
            transparent 45%
        );

    color: #302b28;

    display: flex;

    justify-content: center;

    align-items: center;

    padding: 25px;

}


/* =====================================================
   WRAPPER
===================================================== */

.login-wrapper {

    width: 100%;

    max-width: 460px;

}


/* =====================================================
   BRAND
===================================================== */

.brand {

    text-align: center;

    margin-bottom: 30px;

}


.brand h1 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size: 62px;

    font-weight: 500;

    letter-spacing: 7px;

    color: #2d2825;

}


.brand-sub {

    display: flex;

    justify-content: center;

    align-items: center;

    gap: 10px;

    font-family:
        "Montserrat",
        sans-serif;

    font-size: 10px;

    letter-spacing: 5px;

    color: #9b8058;

}


.brand-sub span {

    width: 35px;

    height: 1px;

    background: #b99a68;

}


/* =====================================================
   LOGIN CARD
===================================================== */

.login-card {

    background:
        rgba(255,255,255,.95);

    border:
        1px solid #e3d9ce;

    padding:
        48px 45px;

    box-shadow:
        0 20px 60px
        rgba(55,42,32,.10);

}


/* =====================================================
   TITLE
===================================================== */

.login-title {

    text-align: center;

    margin-bottom: 35px;

}


.login-title small {

    display: block;

    font-family:
        "Montserrat",
        sans-serif;

    color: #a18554;

    font-size: 10px;

    letter-spacing: 4px;

    margin-bottom: 8px;

}


.login-title h2 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size: 38px;

    font-weight: 500;

}


.decor {

    display: flex;

    justify-content: center;

    align-items: center;

    gap: 12px;

    margin-top: 15px;

    color: #b29159;

}


.decor span {

    width: 50px;

    height: 1px;

    background: #cdb89b;

}


/* =====================================================
   FORM
===================================================== */

.form-group {

    margin-bottom: 22px;

}


.form-group label {

    display: block;

    font-size: 13px;

    margin-bottom: 8px;

    color: #5f5751;

}


.form-group input {

    width: 100%;

    height: 48px;

    border:
        1px solid #ddd2c7;

    background:
        #fffdfa;

    padding:
        0 15px;

    font-family:
        "Kanit",
        sans-serif;

    font-size: 14px;

    outline: none;

    transition: .3s;

}


.form-group input:focus {

    border-color: #a88a5c;

    box-shadow:
        0 0 0 3px
        rgba(168,138,92,.08);

}


/* =====================================================
   BUTTON
===================================================== */

.login-btn {

    width: 100%;

    height: 50px;

    border: none;

    background: #302b28;

    color: white;

    font-family:
        "Kanit",
        sans-serif;

    font-size: 14px;

    cursor: pointer;

    transition: .3s;

    margin-top: 8px;

}


.login-btn:hover {

    background: #a18554;

}


/* =====================================================
   ERROR
===================================================== */

.error {

    background:
        #f8e9e6;

    color:
        #a14f45;

    border:
        1px solid #ead0ca;

    padding: 12px;

    font-size: 13px;

    text-align: center;

    margin-bottom: 20px;

}


/* =====================================================
   BACK
===================================================== */

.back-shop {

    display: block;

    text-align: center;

    margin-top: 25px;

    color: #88725b;

    text-decoration: none;

    font-size: 13px;

}


.back-shop:hover {

    color: #302b28;

}


/* =====================================================
   FOOTER
===================================================== */

.footer-text {

    text-align: center;

    margin-top: 25px;

    color: #a49a91;

    font-family:
        "Montserrat",
        sans-serif;

    font-size: 9px;

    letter-spacing: 2px;

}


/* =====================================================
   RESPONSIVE
===================================================== */

@media(max-width: 500px) {

    .login-card {

        padding:
            35px 25px;

    }

    .brand h1 {

        font-size: 50px;

    }

}

</style>

</head>


<body>


<div class="login-wrapper">


    <!-- BRAND -->

    <div class="brand">

        <h1>VELOURA</h1>

        <div class="brand-sub">

            <span></span>

            PERFUMES

            <span></span>

        </div>

    </div>


    <!-- LOGIN -->

    <div class="login-card">


        <div class="login-title">

            <small>
                VELOURA ADMIN
            </small>

            <h2>
                เข้าสู่ระบบผู้ดูแล
            </h2>

            <div class="decor">

                <span></span>

                ✦

                <span></span>

            </div>

        </div>


        <!-- ERROR -->

        <?php if ($error !== ""): ?>

            <div class="error">

                <?= htmlspecialchars(
                    $error,
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>

            </div>

        <?php endif; ?>


        <!-- FORM -->

        <form
            method="POST"
            autocomplete="off"
        >


            <div class="form-group">

                <label>
                    ชื่อผู้ใช้
                </label>

                <input
                    type="text"
                    name="username"
                    placeholder="กรอกชื่อผู้ใช้"
                    value="<?= htmlspecialchars(
                        $_POST["username"] ?? "",
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>"
                    required
                    autofocus
                >

            </div>


            <div class="form-group">

                <label>
                    รหัสผ่าน
                </label>

                <input
                    type="password"
                    name="password"
                    placeholder="กรอกรหัสผ่าน"
                    required
                >

            </div>


            <button
                type="submit"
                class="login-btn"
            >

                เข้าสู่ระบบ

            </button>


        </form>


        <a
            href="../index.php"
            class="back-shop"
        >

            ← กลับไปหน้าร้าน

        </a>


    </div>


    <div class="footer-text">

        VELOURA PERFUMES · ADMIN PANEL

    </div>


</div>


</body>

</html>