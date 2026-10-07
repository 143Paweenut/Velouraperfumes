<?php

session_start();
require_once "connect.php";

/*
===========================================================
 PHPMailer
===========================================================
*/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . "/PHPMailer/src/Exception.php";
require_once __DIR__ . "/PHPMailer/src/PHPMailer.php";
require_once __DIR__ . "/PHPMailer/src/SMTP.php";


$error = "";
$success = "";
$mailStatus = "";


/*
===========================================================
 GMAIL SMTP
===========================================================

 ใส่ Gmail ของร้าน
 และ Gmail App Password 16 ตัว
 ไม่ใช่รหัสผ่าน Gmail ปกติ
===========================================================
*/

$smtpEmail = "paweenutnamdaeng009@gmail.com";

/*
 * ตัวอย่าง:
 * $smtpPassword = "abcdefghijklmnop";
 *
 * ต้องเป็น App Password จริง
 */
$smtpPassword = "ddzs fejn nvna vboe";


$shopUrl = "http://localhost/Veloura/index.php";


/*
===========================================================
 เมื่อกดสมัครสมาชิก
===========================================================
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /*
    =======================================================
    รับข้อมูลจาก Form
    =======================================================
    */

    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");

    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    $fullname = trim($_POST["fullname"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $phone = trim($_POST["phone"] ?? "");


    /*
    =======================================================
    ตรวจสอบข้อมูลว่าง
    =======================================================
    */

    if (
        $username === "" ||
        $email === "" ||
        $password === "" ||
        $confirm_password === "" ||
        $fullname === "" ||
        $address === "" ||
        $phone === ""
    ) {

        $error = "กรุณากรอกข้อมูลให้ครบทุกช่อง";

    }


    /*
    =======================================================
    ตรวจสอบ Email
    =======================================================
    */

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "กรุณากรอกอีเมลให้ถูกต้อง";

    }


    /*
    =======================================================
    ตรวจสอบ Password ตรงกัน
    =======================================================
    */

    elseif ($password !== $confirm_password) {

        $error = "รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน";

    }


    /*
    =======================================================
    Password อย่างน้อย 6 ตัว
    =======================================================
    */

    elseif (strlen($password) < 6) {

        $error = "รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร";

    }


    /*
    =======================================================
    ตรวจสอบ Username และ Email
    =======================================================
    */

    else {

        /*
        ===================================================
        ตรวจ Username ซ้ำ
        ===================================================
        */

        $check = $conn->prepare("
            SELECT id
            FROM users
            WHERE username = ?
            LIMIT 1
        ");

        if (!$check) {

            $error = "เกิดข้อผิดพลาดในการตรวจสอบ Username";

        } else {

            $check->bind_param(
                "s",
                $username
            );

            $check->execute();

            $result = $check->get_result();


            if ($result->num_rows > 0) {

                $error =
                    "Username นี้มีผู้ใช้งานแล้ว กรุณาใช้ Username อื่น";

            }

            $check->close();
        }


        /*
        ===================================================
        ถ้า Username ไม่ซ้ำ
        ===================================================
        */

        if ($error === "") {

            /*
            =================================================
            ตรวจ Email ซ้ำ
            =================================================
            */

            $checkEmail = $conn->prepare("
                SELECT id
                FROM users
                WHERE email = ?
                LIMIT 1
            ");

            if (!$checkEmail) {

                $error =
                    "เกิดข้อผิดพลาดในการตรวจสอบ Email";

            } else {

                $checkEmail->bind_param(
                    "s",
                    $email
                );

                $checkEmail->execute();

                $emailResult =
                    $checkEmail->get_result();


                if ($emailResult->num_rows > 0) {

                    $error =
                        "อีเมลนี้มีผู้ใช้งานแล้ว กรุณาใช้อีเมลอื่น";

                }

                $checkEmail->close();
            }
        }


        /*
        ===================================================
        ถ้า Username + Email ไม่ซ้ำ
        ===================================================
        */

        if ($error === "") {

            /*
            =================================================
            เข้ารหัส Password
            =================================================

            ตัวอย่าง:

            ผู้ใช้กรอก
            123456

            จะถูกเปลี่ยนเป็นประมาณ

            $2y$10$xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

            และเก็บค่านี้ลงฐานข้อมูล
            =================================================
            */

            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );


            /*
            =================================================
            ตรวจว่า Password Hash สำเร็จหรือไม่
            =================================================
            */

            if ($hashed_password === false) {

                $error =
                    "ไม่สามารถเข้ารหัสรหัสผ่านได้";

            }
        }


        /*
        ===================================================
        บันทึกสมาชิก
        ===================================================
        */

        if ($error === "") {

            /*
            =================================================
            users ในฐานข้อมูลของปริมมี

            id
            username
            email
            password
            fullname
            address
            phone
            created_at
            scent
            newsletter

            แต่ตอนนี้เราบันทึกข้อมูลหลักก่อน
            =================================================
            */

            $stmt = $conn->prepare("
                INSERT INTO users
                (
                    username,
                    email,
                    password,
                    fullname,
                    address,
                    phone
                )
                VALUES
                (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?
                )
            ");


            if (!$stmt) {

                $error =
                    "เกิดข้อผิดพลาดในการเตรียมข้อมูล: " .
                    $conn->error;

            } else {

                /*
                =================================================
                สำคัญมาก

                ตัวแปรที่ 3 คือ

                $hashed_password

                ไม่ใช่ $password
                =================================================
                */

                $stmt->bind_param(
                    "ssssss",
                    $username,
                    $email,
                    $hashed_password,
                    $fullname,
                    $address,
                    $phone
                );


                /*
                =================================================
                Execute
                =================================================
                */

                if ($stmt->execute()) {

                    /*
                    =================================================
                    สมัครสมาชิกสำเร็จ
                    =================================================
                    */

                    $success =
                        "สมัครสมาชิกสำเร็จ ✦";


                    /*
                    =================================================
                    ส่ง Welcome Email
                    =================================================
                    */

                    try {

                        $mail = new PHPMailer(true);


                        /*
                        =================================================
                        SMTP
                        =================================================
                        */

                        $mail->isSMTP();

                        $mail->Host =
                            "smtp.gmail.com";

                        $mail->SMTPAuth =
                            true;

                        $mail->Username =
                            $smtpEmail;

                        $mail->Password =
                            $smtpPassword;

                        $mail->SMTPSecure =
                            PHPMailer::ENCRYPTION_STARTTLS;

                        $mail->Port = 587;

                        $mail->CharSet = "UTF-8";


                        /*
                        =================================================
                        ผู้ส่ง
                        =================================================
                        */

                        $mail->setFrom(
                            $smtpEmail,
                            "Veloura Perfumes"
                        );


                        /*
                        =================================================
                        ผู้รับ
                        =================================================
                        */

                        $mail->addAddress(
                            $email,
                            $fullname
                        );


                        /*
                        =================================================
                        Logo
                        =================================================
                        */

                        $imagePath =
                            __DIR__ . "/images/ve.jpg";


                        if (file_exists($imagePath)) {

                            $mail->addEmbeddedImage(
                                $imagePath,
                                "veloura-logo"
                            );
                        }


                        /*
                        =================================================
                        Email
                        =================================================
                        */

                        $mail->isHTML(true);


                        $mail->Subject =
                            "Welcome to Veloura Perfumes ✦ " .
                            "สิทธิพิเศษสำหรับสมาชิกใหม่";


                        /*
                        =================================================
                        Email Body
                        =================================================
                        */

                        $safeName =
                            htmlspecialchars(
                                $fullname,
                                ENT_QUOTES,
                                "UTF-8"
                            );

                        $safeEmail =
                            htmlspecialchars(
                                $email,
                                ENT_QUOTES,
                                "UTF-8"
                            );


                        $mail->Body = '

<!DOCTYPE html>

<html lang="th">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Welcome to Veloura</title>

</head>


<body style="
margin:0;
padding:0;
background:#f7eee9;
font-family:Arial,Helvetica,sans-serif;
">

<table
width="100%"
cellpadding="0"
cellspacing="0"
border="0"
style="
background:#f7eee9;
padding:35px 10px;
"
>

<tr>

<td align="center">


<table
width="650"
cellpadding="0"
cellspacing="0"
border="0"
style="
max-width:650px;
background:#fffaf7;
border-radius:24px;
overflow:hidden;
"
>


<tr>

<td
style="
height:7px;
background:#d8a7a5;
"
>
</td>

</tr>


<tr>

<td
align="center"
style="
padding:32px 25px 15px;
"
>

<img
src="cid:veloura-logo"
width="250"
style="
max-width:75%;
display:block;
margin:auto;
"
alt="Veloura Perfumes"
>

<div
style="
margin-top:12px;
font-size:10px;
letter-spacing:5px;
color:#9c796d;
"
>
MORE THAN A SCENT · IT’S A PART OF YOU
</div>

</td>

</tr>


<tr>

<td
align="center"
style="
padding:10px 35px 25px;
"
>

<div
style="
font-family:Georgia,serif;
font-size:42px;
font-style:italic;
color:#76514d;
"
>
Welcome
</div>


<div
style="
font-size:20px;
color:#59423e;
margin-top:10px;
"
>
ยินดีต้อนรับคุณ
</div>


<div
style="
font-size:25px;
font-family:Georgia,serif;
color:#8e6260;
margin-top:5px;
"
>
' . $safeName . '
</div>


<div
style="
width:50px;
height:1px;
background:#d5b16c;
margin:18px auto;
"
>
</div>


<div
style="
font-size:14px;
line-height:2;
color:#75655f;
"
>
ขอบคุณที่เลือกเป็นส่วนหนึ่งของ
<br>
Veloura Perfumes
<br>
เราดีใจที่ได้ต้อนรับคุณเข้าสู่โลกแห่งกลิ่นหอม
</div>

</td>

</tr>


<tr>

<td align="center">

<table
width="88%"
cellpadding="0"
cellspacing="0"
style="
background:#fbf1ef;
border:1px solid #ead6d0;
border-radius:15px;
"
>

<tr>

<td
align="center"
style="
padding:16px;
"
>

<div
style="
font-size:10px;
letter-spacing:2px;
color:#a28278;
margin-bottom:5px;
"
>
MEMBER EMAIL
</div>


<div
style="
font-size:15px;
color:#5f4843;
font-weight:bold;
"
>
' . $safeEmail . '
</div>

</td>

</tr>

</table>

</td>

</tr>


<tr>

<td
style="
padding:30px;
"
>

<table
width="100%"
cellpadding="0"
cellspacing="0"
style="
background:#f9e9e6;
border:1px solid #e6cfc7;
border-radius:20px;
"
>

<tr>

<td
align="center"
style="
padding:28px 20px;
"
>

<div
style="
font-size:11px;
letter-spacing:4px;
color:#a17a72;
"
>
WELCOME GIFT
</div>


<div
style="
font-family:Georgia,serif;
font-size:65px;
font-weight:bold;
font-style:italic;
color:#79504d;
"
>
10%
</div>


<div
style="
font-size:15px;
color:#604943;
"
>
ส่วนลดสำหรับการสั่งซื้อครั้งแรก
</div>


<div
style="
display:inline-block;
margin-top:18px;
padding:10px 25px;
border:1px dashed #bd9b72;
border-radius:30px;
font-size:14px;
letter-spacing:2px;
color:#76574f;
background:#fffaf7;
"
>
VELOURA10
</div>


<div
style="
font-size:11px;
color:#9a8781;
margin-top:10px;
"
>
ใช้สำหรับการสั่งซื้อครั้งแรกเท่านั้น
</div>

</td>

</tr>

</table>

</td>

</tr>


<tr>

<td
align="center"
style="
padding:5px 30px 30px;
"
>

<a
href="' . $shopUrl . '"
style="
display:inline-block;
background:#714944;
color:#ffffff;
text-decoration:none;
padding:15px 45px;
border-radius:30px;
font-size:13px;
letter-spacing:2px;
font-weight:bold;
"
>
SHOP NOW → 
</a>

</td>

</tr>


<tr>

<td
align="center"
style="
padding:0 40px 35px;
"
>

<div
style="
font-family:Georgia,serif;
font-size:18px;
font-style:italic;
color:#80615b;
"
>
Your scent. Your story.
</div>


<div
style="
font-size:11px;
color:#9a8983;
margin-top:8px;
"
>
กลิ่นหอมที่เป็นตัวคุณ เริ่มต้นที่ Veloura
</div>

</td>

</tr>


<tr>

<td
align="center"
style="
background:#3c2d29;
padding:25px 20px;
"
>

<div
style="
font-family:Georgia,serif;
font-size:27px;
letter-spacing:4px;
color:#f6e5dc;
"
>
VELOURA
</div>


<div
style="
font-size:8px;
letter-spacing:4px;
color:#d6bfae;
margin-top:5px;
"
>
PERFUMES
</div>


<div
style="
font-size:10px;
line-height:1.8;
color:#cdbbb4;
margin-top:15px;
"
>
FRAGRANCE · BEAUTY · YOU
<br>
ขอบคุณที่เป็นส่วนหนึ่งของ Veloura Perfumes
</div>

</td>

</tr>


</table>

</td>

</tr>

</table>

</body>

</html>
';


                        /*
                        =================================================
                        ALT BODY
                        =================================================
                        */

                        $mail->AltBody =
                            "ยินดีต้อนรับคุณ " .
                            $fullname .
                            " สู่ Veloura Perfumes\n\n" .

                            "สมาชิก: " .
                            $email .
                            "\n\n" .

                            "WELCOME GIFT\n" .
                            "รับส่วนลด 10% สำหรับการสั่งซื้อครั้งแรก\n" .
                            "Coupon: WELCOME10\n\n" .

                            "Shop: " .
                            $shopUrl;


                        /*
                        =================================================
                        ส่ง Email
                        =================================================
                        */

                        $mail->send();


                        $mailStatus =
                            "เราได้ส่งอีเมลต้อนรับไปที่ " .
                            $email .
                            " แล้ว ✦";


                    } catch (Exception $e) {

                        $mailStatus =
                            "สมัครสมาชิกสำเร็จ แต่ส่งอีเมลไม่สำเร็จ " .
                            "กรุณาตรวจสอบ Gmail SMTP";

                    }


                    /*
                    =================================================
                    ล้าง Password ออกจาก POST
                    =================================================
                    */

                    unset($_POST["password"]);
                    unset($_POST["confirm_password"]);

                }

                else {

                    $error =
                        "เกิดข้อผิดพลาดในการสมัครสมาชิก: " .
                        $stmt->error;

                }


                $stmt->close();

            }
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

<title>สมัครสมาชิก | Veloura Perfumes</title>


<link rel="preconnect"
href="https://fonts.googleapis.com">

<link rel="preconnect"
href="https://fonts.gstatic.com"
crossorigin>


<link
href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&family=Noto+Sans+Thai:wght@300;400;500&display=swap"
rel="stylesheet"
>


<style>

* {
    margin:0;
    padding:0;
    box-sizing:border-box;
}


body {

    font-family:
        "Noto Sans Thai",
        sans-serif;

    background:

        radial-gradient(
            circle at 0% 0%,
            rgba(225,174,179,.20),
            transparent 28%
        ),

        radial-gradient(
            circle at 100% 100%,
            rgba(213,176,112,.13),
            transparent 30%
        ),

        #f7f3ee;

    color:#29231f;

    min-height:100vh;
}


.top-bar {

    width:100%;
    height:42px;

    background:#29231f;

    color:#e2c6a9;

    display:flex;
    justify-content:center;
    align-items:center;

    font-family:"Montserrat",sans-serif;

    font-size:9px;

    letter-spacing:2px;
}


.register-page {

    min-height:
        calc(100vh - 42px);

    display:flex;

    justify-content:center;

    align-items:center;

    padding:45px 20px;
}


.register-container {

    width:100%;

    max-width:1120px;

    background:#fffdfb;

    display:grid;

    grid-template-columns:40% 60%;

    border:1px solid #e5ddd4;

    box-shadow:
        0 30px 80px
        rgba(48,38,31,.12);

    overflow:hidden;
}


.register-left {

    position:relative;

    min-height:760px;

    background:

        linear-gradient(
            rgba(70,40,42,.10),
            rgba(45,30,27,.45)
        ),

        url("images/ve.jpg");

    background-size:cover;

    background-position:center;

    display:flex;

    align-items:flex-end;

    padding:50px 45px;

    overflow:hidden;
}


.register-left::before {

    content:"";

    position:absolute;

    inset:0;

    background:

        linear-gradient(
            to top,
            rgba(32,25,21,.90),
            rgba(32,25,21,.10) 65%,
            transparent
        );
}


.register-left::after {

    content:"";

    position:absolute;

    width:320px;
    height:320px;

    border:
        1px solid
        rgba(230,194,145,.25);

    border-radius:50%;

    right:-190px;
    top:-160px;
}


.brand-content {

    position:relative;

    z-index:3;

    color:white;
}


.brand-small {

    font-family:"Montserrat",sans-serif;

    font-size:9px;

    letter-spacing:4px;

    color:#e1c4a0;

    margin-bottom:15px;
}


.brand-title {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:62px;

    font-weight:500;

    letter-spacing:7px;

    line-height:.82;
}


.brand-subtitle {

    font-family:"Montserrat",sans-serif;

    font-size:9px;

    letter-spacing:6px;

    color:#e2d1c2;

    margin-top:15px;
}


.brand-line {

    width:48px;

    height:1px;

    background:#d5b16e;

    margin:25px 0;
}


.brand-description {

    font-size:12px;

    line-height:2;

    color:rgba(255,255,255,.86);
}


.privilege-box {

    margin-top:27px;

    padding:18px 20px;

    border:
        1px solid
        rgba(214,192,160,.25);

    background:
        rgba(30,24,21,.30);

    backdrop-filter:blur(5px);
}


.privilege-title {

    font-family:"Montserrat",sans-serif;

    font-size:8px;

    letter-spacing:2px;

    color:#e0c49c;

    margin-bottom:11px;
}


.privilege-item {

    font-size:10px;

    color:rgba(255,255,255,.82);

    margin:7px 0;
}


.register-right {

    padding:48px 58px;

    display:flex;

    flex-direction:column;

    justify-content:center;
}


.register-header {

    margin-bottom:23px;
}


.eyebrow {

    font-family:"Montserrat",sans-serif;

    font-size:8px;

    letter-spacing:3px;

    color:#ad7e83;

    margin-bottom:8px;
}


.register-header h1 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:46px;

    font-weight:500;

    color:#29231f;

    line-height:1;
}


.register-header p {

    margin-top:9px;

    font-size:11px;

    color:#8c8179;
}


.decor-line {

    display:flex;

    align-items:center;

    gap:9px;

    margin-top:15px;
}


.decor-line span {

    width:32px;

    height:1px;

    background:#c8a46b;
}


.decor-line i {

    width:5px;

    height:5px;

    border:
        1px solid
        #c8a46b;

    transform:rotate(45deg);
}


.section-title {

    display:flex;

    align-items:center;

    gap:12px;

    margin:
        21px
        0
        13px;
}


.section-title span {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:21px;

    color:#4b3c32;

    white-space:nowrap;
}


.section-title::after {

    content:"";

    flex:1;

    height:1px;

    background:#e9e0d8;
}


.form-row {

    display:grid;

    grid-template-columns:
        1fr 1fr;

    gap:20px;
}


.form-group {

    margin-bottom:13px;
}


.form-group label {

    display:block;

    font-family:
        "Montserrat",
        sans-serif;

    font-size:8px;

    letter-spacing:1.3px;

    color:#5d5149;

    margin-bottom:6px;
}


.form-group input,
.form-group textarea {

    width:100%;

    border:
        1px solid
        #e1d8d0;

    background:#fdfbf9;

    color:#302824;

    font-family:
        "Noto Sans Thai",
        sans-serif;

    font-size:12px;

    outline:none;

    border-radius:3px;

    transition:.25s;
}


.form-group input {

    height:41px;

    padding:0 12px;
}


.form-group textarea {

    height:57px;

    padding:9px 12px;

    resize:none;
}


.form-group input::placeholder,
.form-group textarea::placeholder {

    color:#b3a9a1;
}


.form-group input:focus,
.form-group textarea:focus {

    border-color:#c39a72;

    background:#fff;

    box-shadow:
        0 0 0 3px
        rgba(193,154,114,.08);
}


.password-hint {

    font-size:8px;

    color:#a59b94;

    margin-top:4px;
}


.error {

    background:#faf1ee;

    border-left:
        3px solid
        #a96d61;

    color:#8e554d;

    padding:10px 13px;

    margin-bottom:16px;

    font-size:10px;

    line-height:1.6;
}


.success {

    background:
        linear-gradient(
            135deg,
            #fff5f2,
            #faf1e9
        );

    border:
        1px solid
        #e6cfc3;

    border-left:
        3px solid
        #b88b76;

    color:#70564e;

    padding:15px 16px;

    margin-bottom:10px;

    font-size:10px;

    line-height:1.8;

    box-shadow:
        0 5px 20px
        rgba(120,80,70,.07);
}


.success a {

    color:#76564e;

    text-decoration:none;

    border-bottom:
        1px solid
        #c5a276;
}


.mail-status {

    color:#9a756c;

    font-size:9px;

    margin-top:3px;
}


.preference-title {

    font-size:10px;

    color:#74665d;

    margin-bottom:9px;
}


.preference-grid {

    display:grid;

    grid-template-columns:
        repeat(4,1fr);

    gap:7px;
}


.preference-option {

    position:relative;
}


.preference-option input {

    position:absolute;

    opacity:0;

    pointer-events:none;
}


.preference-option label {

    height:43px;

    display:flex;

    flex-direction:column;

    justify-content:center;

    align-items:center;

    text-align:center;

    border:
        1px solid
        #e2d8cf;

    background:#fdfbf9;

    color:#665951;

    font-family:
        "Montserrat",
        "Noto Sans Thai",
        sans-serif;

    font-size:8px;

    cursor:pointer;

    transition:all .25s ease;
}


.preference-option label span {

    font-size:14px;

    margin-bottom:2px;
}


.preference-option label:hover {

    border-color:#c39a72;

    background:#fff8f6;

    transform:translateY(-1px);
}


.preference-option input:checked + label {

    background:
        linear-gradient(
            135deg,
            #5a403c,
            #332724
        );

    border-color:#5a403c;

    color:#e5c99d;

    box-shadow:
        0 5px 15px
        rgba(45,36,30,.12);
}


.preference-option input:checked + label::after {

    content:"✓";

    position:absolute;

    top:4px;

    right:6px;

    font-size:9px;

    color:#e1bd80;
}


.newsletter {

    display:flex;

    align-items:center;

    gap:8px;

    margin:16px 0;

    color:#81756e;

    font-size:9px;

    cursor:pointer;
}


.newsletter input {

    width:14px;

    height:14px;

    accent-color:#a97973;

    cursor:pointer;
}


.register-button {

    width:100%;

    height:48px;

    border:none;

    background:
        linear-gradient(
            135deg,
            #3b2c28,
            #5b403a
        );

    color:#fff;

    font-family:
        "Montserrat",
        "Noto Sans Thai",
        sans-serif;

    font-size:9px;

    letter-spacing:2.2px;

    cursor:pointer;

    transition:all .3s ease;
}


.register-button:hover {

    background:
        linear-gradient(
            135deg,
            #a87b76,
            #76504c
        );

    transform:translateY(-1px);

    box-shadow:
        0 9px 22px
        rgba(48,39,33,.16);
}


.login-link {

    text-align:center;

    margin-top:17px;

    font-size:10px;

    color:#948780;
}


.login-link a {

    color:#765d48;

    text-decoration:none;

    margin-left:4px;

    border-bottom:
        1px solid
        #bba184;

    padding-bottom:2px;
}


.back {

    text-align:center;

    margin-top:11px;
}


.back a {

    font-family:"Montserrat",sans-serif;

    font-size:8px;

    letter-spacing:1.2px;

    color:#aaa09a;

    text-decoration:none;
}


@media (max-width:950px) {

    .register-container {

        grid-template-columns:1fr;
    }

    .register-left {

        min-height:330px;

        padding:35px;
    }

    .register-right {

        padding:42px 38px;
    }
}


@media (max-width:600px) {

    .register-page {

        padding:20px 10px;
    }

    .register-left {

        min-height:260px;

        padding:28px;
    }

    .brand-title {

        font-size:46px;
    }

    .register-right {

        padding:32px 22px;
    }

    .register-header h1 {

        font-size:39px;
    }

    .form-row {

        grid-template-columns:1fr;

        gap:0;
    }

    .preference-grid {

        grid-template-columns:
            repeat(2,1fr);
    }
}

</style>

</head>


<body>


<div class="top-bar">

VELOURA PERFUMES • DISCOVER YOUR SIGNATURE SCENT

</div>


<div class="register-page">


<div class="register-container">


<!-- LEFT -->

<div class="register-left">


<div class="brand-content">


<div class="brand-small">

DEFINE YOUR SCENT

</div>


<div class="brand-title">

VELOURA

</div>


<div class="brand-subtitle">

PERFUMES

</div>


<div class="brand-line"></div>


<div class="brand-description">

ค้นพบกลิ่นหอมที่สะท้อน
<br>
ตัวตนและสไตล์ของคุณ

</div>


<div class="privilege-box">


<div class="privilege-title">

VELOURA PRIVILEGE

</div>


<div class="privilege-item">

✦ Exclusive Promotions

</div>


<div class="privilege-item">

✦ New Collection Updates

</div>


<div class="privilege-item">

✦ Birthday Privileges

</div>


<div class="privilege-item">

✦ Personalized Fragrance Picks

</div>


</div>


</div>


</div>


<!-- RIGHT -->

<div class="register-right">


<div class="register-header">


<div class="eyebrow">

WELCOME TO VELOURA

</div>


<h1>

Create Account

</h1>


<p>

สมัครสมาชิกเพื่อสัมผัสประสบการณ์ที่พิเศษยิ่งขึ้น

</p>


<div class="decor-line">

<span></span>

<i></i>

<span></span>

</div>


</div>


<?php if ($error !== "") { ?>

<div class="error">

<?php echo htmlspecialchars($error); ?>

</div>

<?php } ?>


<?php if ($success !== "") { ?>

<div class="success">

<strong>

<?php echo htmlspecialchars($success); ?>

</strong>

<br>


<?php

if ($mailStatus !== "") {

    echo htmlspecialchars($mailStatus);

}

?>


<br>


<a href="login.php">

ไปหน้าเข้าสู่ระบบ →

</a>

</div>

<?php } ?>


<form method="POST" action="">


<!-- ACCOUNT -->

<div class="section-title">

<span>

Account

</span>

</div>


<div class="form-row">


<div class="form-group">

<label>

USERNAME

</label>


<input
type="text"
name="username"
placeholder="กรอก Username"
required
value="<?php

echo isset($_POST["username"])
    ? htmlspecialchars($_POST["username"])
    : "";

?>"
>

</div>


<div class="form-group">

<label>

EMAIL

</label>


<input
type="email"
name="email"
placeholder="example@gmail.com"
required
value="<?php

echo isset($_POST["email"])
    ? htmlspecialchars($_POST["email"])
    : "";

?>"
>

</div>


</div>


<div class="form-row">


<div class="form-group">

<label>

PASSWORD

</label>


<input
type="password"
name="password"
placeholder="อย่างน้อย 6 ตัวอักษร"
required
autocomplete="new-password"
>


<div class="password-hint">

อย่างน้อย 6 ตัวอักษร

</div>

</div>


<div class="form-group">

<label>

CONFIRM PASSWORD

</label>


<input
type="password"
name="confirm_password"
placeholder="กรอกรหัสผ่านอีกครั้ง"
required
autocomplete="new-password"
>

</div>


</div>


<!-- PERSONAL -->

<div class="section-title">

<span>

Personal Information

</span>

</div>


<div class="form-row">


<div class="form-group">

<label>

FULL NAME

</label>


<input
type="text"
name="fullname"
placeholder="ชื่อ - นามสกุล"
required
value="<?php

echo isset($_POST["fullname"])
    ? htmlspecialchars($_POST["fullname"])
    : "";

?>"
>

</div>


<div class="form-group">

<label>

PHONE

</label>


<input
type="tel"
name="phone"
placeholder="0812345678"
required
value="<?php

echo isset($_POST["phone"])
    ? htmlspecialchars($_POST["phone"])
    : "";

?>"
>

</div>


</div>


<div class="form-group">

<label>

SHIPPING ADDRESS

</label>


<textarea
name="address"
placeholder="กรอกที่อยู่สำหรับจัดส่งสินค้า"
required
><?php

echo isset($_POST["address"])
    ? htmlspecialchars($_POST["address"])
    : "";

?></textarea>

</div>


<!-- FRAGRANCE -->

<div class="section-title">

<span>

Your Fragrance

</span>

</div>


<div class="preference-title">

เลือกกลิ่นที่คุณชื่นชอบได้มากกว่าหนึ่งแบบ

</div>


<div class="preference-grid">


<div class="preference-option">

<input
type="checkbox"
id="floral"
name="scent[]"
value="Floral"
>

<label for="floral">

<span>🌹</span>

Floral

</label>

</div>


<div class="preference-option">

<input
type="checkbox"
id="fresh"
name="scent[]"
value="Fresh"
>

<label for="fresh">

<span>🍋</span>

Fresh

</label>

</div>


<div class="preference-option">

<input
type="checkbox"
id="sweet"
name="scent[]"
value="Sweet"
>

<label for="sweet">

<span>🍦</span>

Sweet

</label>

</div>


<div class="preference-option">

<input
type="checkbox"
id="woody"
name="scent[]"
value="Woody"
>

<label for="woody">

<span>🌲</span>

Woody

</label>

</div>


<div class="preference-option">

<input
type="checkbox"
id="oriental"
name="scent[]"
value="Oriental"
>

<label for="oriental">

<span>🌙</span>

Oriental

</label>

</div>


<div class="preference-option">

<input
type="checkbox"
id="fruity"
name="scent[]"
value="Fruity"
>

<label for="fruity">

<span>🍑</span>

Fruity

</label>

</div>


<div class="preference-option">

<input
type="checkbox"
id="powdery"
name="scent[]"
value="Powdery"
>

<label for="powdery">

<span>🌸</span>

Powdery

</label>

</div>


<div class="preference-option">

<input
type="checkbox"
id="musk"
name="scent[]"
value="Musk"
>

<label for="musk">

<span>✨</span>

Musk

</label>

</div>


</div>


<!-- NEWSLETTER -->

<label class="newsletter">

<input
type="checkbox"
name="newsletter"
value="1"
>

รับข่าวสาร Collection ใหม่ โปรโมชั่น
และสิทธิพิเศษจาก Veloura

</label>


<!-- BUTTON -->

<button
type="submit"
class="register-button"
>

CREATE MY ACCOUNT

</button>


</form>


<div class="login-link">

มีบัญชีอยู่แล้ว?

<a href="login.php">

เข้าสู่ระบบ

</a>

</div>


<div class="back">

<a href="index.php">

← BACK TO VELOURA

</a>

</div>


</div>


</div>

</div>


</body>

</html>