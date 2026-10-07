<?php

session_start();

require_once "connect.php";


/* =========================================================
   PHPMailer
========================================================= */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . "/PHPMailer/src/Exception.php";
require_once __DIR__ . "/PHPMailer/src/PHPMailer.php";
require_once __DIR__ . "/PHPMailer/src/SMTP.php";


/* =========================================================
   GMAIL SMTP
========================================================= */

$gmail_username = "paweenutnamdaeng009@gmail.com";
$gmail_app_password = "ddzs fejn nvna vboe";



/* =========================================================
   URL ร้าน
========================================================= */

$store_url = "http://localhost/Veloura/products.php";


/* =========================================================
   รูปที่จะฝังในอีเมล
========================================================= */

$email_image =
    __DIR__ . "/images/veloura-welcome.jpg";


/* =========================================================
   MESSAGE
========================================================= */

$message = "";

$message_type = "";


/* =========================================================
   ฟังก์ชันส่งอีเมล
========================================================= */

function sendVelouraWelcomeEmail(
    $customerEmail,
    $customerName
) {

    global $gmail_username;
    global $gmail_app_password;
    global $store_url;
    global $email_image;


    $mail = new PHPMailer(true);


    try {

        /* =====================================================
           SMTP
        ===================================================== */

        $mail->isSMTP();

        $mail->Host = "smtp.gmail.com";

        $mail->SMTPAuth = true;

        $mail->Username = $gmail_username;

        $mail->Password =
            str_replace(
                " ",
                "",
                $gmail_app_password
            );

        $mail->SMTPSecure =
            PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port = 587;


        /* =====================================================
           CHARACTER
        ===================================================== */

        $mail->CharSet = "UTF-8";

        $mail->Encoding = "base64";


        /* =====================================================
           FROM
        ===================================================== */

        $mail->setFrom(
            $gmail_username,
            "Veloura Perfumes"
        );


        /* =====================================================
           TO
        ===================================================== */

        $mail->addAddress(
            $customerEmail,
            $customerName
        );


        /* =====================================================
           SUBJECT
        ===================================================== */

        $mail->Subject =
            "✦ ยินดีต้อนรับสู่ Veloura Perfumes ✦";


        /* =====================================================
           ชื่อ
        ===================================================== */

        $safeName =
            htmlspecialchars(
                $customerName,
                ENT_QUOTES,
                "UTF-8"
            );


        $safeEmail =
            htmlspecialchars(
                $customerEmail,
                ENT_QUOTES,
                "UTF-8"
            );


        /* =====================================================
           ตรวจสอบรูป
        ===================================================== */

        if (!file_exists($email_image)) {

            throw new Exception(
                "ไม่พบไฟล์รูป: " . $email_image
            );
        }


        /* =====================================================
           EMBED IMAGE
        ===================================================== */

        $mail->addEmbeddedImage(
            $email_image,
            "velouraWelcome",
            "veloura-welcome.jpg",
            "base64",
            "image/jpeg"
        );


        /* =====================================================
           HTML EMAIL
        ===================================================== */

        $mail->isHTML(true);


        $mail->Body = <<<HTML

<!DOCTYPE html>

<html lang="th">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<meta
    name="x-apple-disable-message-reformatting"
>

<title>
    Veloura Perfumes
</title>


<style>

html,
body {

    margin:0 !important;

    padding:0 !important;

    width:100% !important;

    background:#f5e8e4;
}


body {

    font-family:
        Arial,
        Tahoma,
        sans-serif;

    -webkit-text-size-adjust:100%;

    -ms-text-size-adjust:100%;
}


table {

    border-spacing:0;

    border-collapse:collapse;
}


img {

    border:0;

    display:block;

    max-width:100%;

}


a {

    text-decoration:none;
}


/* =====================================================
   MOBILE
===================================================== */

@media only screen and (max-width:600px) {

    .email-wrapper {

        padding:
            10px !important;
    }


    .email-container {

        width:100% !important;

        max-width:100% !important;
    }


    .top-text {

        padding:
            25px 18px !important;
    }


    .top-title {

        font-size:28px !important;
    }


    .top-name {

        font-size:22px !important;
    }


    .email-image {

        width:100% !important;

        height:auto !important;
    }


    .bottom-content {

        padding:
            25px 18px !important;
    }


    .shop-button {

        display:block !important;

        width:auto !important;

        padding:
            14px 25px !important;
    }

}

</style>

</head>


<body>


<!-- =====================================================
     BACKGROUND
===================================================== -->

<table
    width="100%"
    cellpadding="0"
    cellspacing="0"
    border="0"
    role="presentation"
    style="
        width:100%;
        background:
        linear-gradient(
            135deg,
            #f8eeeb 0%,
            #f4e2df 50%,
            #eee0d9 100%
        );
    "
>

<tr>

<td
    align="center"
    class="email-wrapper"
    style="
        padding:
            30px 10px 50px;
    "
>


<!-- =====================================================
     EMAIL CONTAINER
===================================================== -->

<table
    width="640"
    cellpadding="0"
    cellspacing="0"
    border="0"
    role="presentation"
    class="email-container"
    style="
        width:640px;
        max-width:640px;

        background:#fffaf7;

        border-radius:12px;

        overflow:hidden;

        box-shadow:
        0 20px 60px
        rgba(88,55,45,.20);
    "
>


<!-- =====================================================
     TOP TEXT
===================================================== -->

<tr>

<td
    align="center"
    class="top-text"
    style="
        padding:
            34px 35px 28px;

        background:
        linear-gradient(
            135deg,
            #fffaf7,
            #f8ebe7
        );
    "
>


<!-- SPARKLES -->

<div
    style="
        color:#c69a70;

        font-size:17px;

        letter-spacing:8px;

        margin-bottom:12px;
    "
>
    ✦　✧　✦
</div>


<!-- SMALL BRAND -->

<div
    style="
        color:#76514a;

        font-family:
            Georgia,
            serif;

        font-size:11px;

        letter-spacing:6px;

        margin-bottom:8px;
    "
>
    VELOURA
</div>


<div
    style="
        color:#b68d78;

        font-size:8px;

        letter-spacing:4px;

        margin-bottom:18px;
    "
>
    PERFUMES
</div>


<!-- WELCOME -->

<div
    class="top-title"
    style="
        color:#76504c;

        font-family:
            Georgia,
            'Times New Roman',
            serif;

        font-size:32px;

        line-height:1.3;
    "
>

    Welcome ♡

</div>


<!-- CUSTOMER -->

<div
    class="top-name"
    style="
        color:#593d3a;

        font-family:
            Georgia,
            'Times New Roman',
            serif;

        font-size:24px;

        margin-top:7px;
    "
>

    ยินดีต้อนรับคุณ

    <br>

    <span
        style="
            color:#9d6170;
        "
    >
        $safeName
    </span>

</div>


<!-- EMAIL -->

<div
    style="
        margin-top:12px;

        color:#927873;

        font-size:11px;

        word-break:break-all;
    "
>

    ✉ &nbsp;
    $safeEmail

</div>


<!-- GOLD LINE -->

<div
    style="
        margin:
            17px auto 0;

        width:80px;

        border-top:
            1px solid #c9a27e;
    "
></div>


</td>

</tr>


<!-- =====================================================
     MAIN IMAGE
===================================================== -->

<tr>

<td
    align="center"
    style="
        padding:0;

        background:#f7eee9;
    "
>


<!--
===========================================================
รูปหลัก

รูปต้นฉบับ:
640 x 960 px

CID:
velouraWelcome

ดังนั้นไม่ต้องใช้ URL localhost
===========================================================
-->

<img
    src="cid:velouraWelcome"
    width="640"
    alt="Welcome to Veloura Perfumes"
    class="email-image"
    style="
        display:block;

        width:640px;

        max-width:100%;

        height:auto;

        margin:0;

        border:0;
    "
>


</td>

</tr>


<!-- =====================================================
     SHOP BUTTON
===================================================== -->

<tr>

<td
    align="center"
    class="bottom-content"
    style="
        padding:
            28px 30px 32px;

        background:
        linear-gradient(
            135deg,
            #fff9f6,
            #f8e9e6
        );
    "
>


<div
    style="
        color:#74524e;

        font-family:
            Georgia,
            serif;

        font-size:18px;

        margin-bottom:7px;
    "
>

    Your signature scent awaits ♡

</div>


<div
    style="
        color:#a18780;

        font-size:10px;

        line-height:1.8;

        margin-bottom:18px;
    "
>

    ค้นพบกลิ่นที่เป็นตัวคุณ
    และเลือกน้ำหอมที่อยากให้เป็นส่วนหนึ่งของทุกวัน

</div>


<!-- BUTTON -->

<table
    cellpadding="0"
    cellspacing="0"
    border="0"
    role="presentation"
    align="center"
>

<tr>

<td
    align="center"
    style="
        border-radius:30px;

        background:
        linear-gradient(
            90deg,
            #70434a,
            #99616b,
            #70434a
        );

        box-shadow:
        0 8px 22px
        rgba(112,67,74,.25);
    "
>


<a
    href="$store_url"
    target="_blank"
    class="shop-button"
    style="
        display:inline-block;

        padding:
            14px 34px;

        color:#ffffff;

        font-family:
            Arial,
            Tahoma,
            sans-serif;

        font-size:10px;

        letter-spacing:2px;

        font-weight:bold;

        border-radius:30px;
    "
>

    ✦ &nbsp; SHOP NOW &nbsp; →

</a>


</td>

</tr>

</table>


<!-- DIVIDER -->

<div
    style="
        margin:
            22px 0 12px;

        color:#c59b76;

        font-size:13px;

        letter-spacing:6px;
    "
>
    ✦　✧　✦
</div>


<div
    style="
        color:#91756e;

        font-size:9px;

        line-height:1.8;
    "
>

    ขอบคุณที่ให้ Veloura
    เป็นส่วนหนึ่งของเรื่องราวของคุณ ♡

</div>


</td>

</tr>


<!-- =====================================================
     FOOTER
===================================================== -->

<tr>

<td
    align="center"
    style="
        padding:
            17px 15px;

        background:
        #4b3029;

        color:#e5cfc2;

        font-size:8px;

        letter-spacing:3px;
    "
>

    VELOURA PERFUMES

    <br>

    <span
        style="
            color:#c6a789;

            font-size:7px;

            letter-spacing:2px;

            line-height:2.5;
        "
    >
        MORE THAN A SCENT · IT'S A PART OF YOU
    </span>

</td>

</tr>


</table>


</td>

</tr>

</table>


</body>

</html>

HTML;


        /* =====================================================
           TEXT VERSION
        ===================================================== */

        $mail->AltBody =

            "VELOURA PERFUMES\n\n"

            . "Welcome ♡\n\n"

            . "ยินดีต้อนรับคุณ "
            . $customerName
            . "\n\n"

            . "ขอบคุณที่เข้ามาเป็นส่วนหนึ่งของ Veloura\n\n"

            . "ค้นพบกลิ่นที่เป็นตัวคุณ\n"

            . "SHOP NOW:\n"
            . $store_url
            . "\n\n"

            . "Veloura Perfumes\n"
            . "More Than A Scent · It's A Part Of You";


        /* =====================================================
           SEND
        ===================================================== */

        $mail->send();

        return true;

    }

    catch (Exception $e) {

        error_log(
            "Veloura Email Error: "
            . $mail->ErrorInfo
        );

        return false;
    }
}


/* =========================================================
   FORM
========================================================= */

if (
    $_SERVER["REQUEST_METHOD"]
    === "POST"
) {


    $fullname =
        trim(
            $_POST["fullname"] ?? ""
        );


    $email =
        trim(
            $_POST["email"] ?? ""
        );


    $favorite_notes =
        $_POST["favorite_notes"] ?? [];


    $consent =
        isset($_POST["consent"])
            ? 1
            : 0;


    if (
        !is_array(
            $favorite_notes
        )
    ) {

        $favorite_notes = [];
    }


    $favorite_notes =
        array_slice(
            $favorite_notes,
            0,
            2
        );


    $favorite_notes_text =
        implode(
            ", ",
            $favorite_notes
        );


    /* =====================================================
       VALIDATE
    ===================================================== */

    if ($fullname === "") {

        $message =
            "กรุณากรอกชื่อของคุณ";

        $message_type =
            "error";

    }

    elseif ($email === "") {

        $message =
            "กรุณากรอกอีเมลของคุณ";

        $message_type =
            "error";

    }

    elseif (
        !filter_var(
            $email,
            FILTER_VALIDATE_EMAIL
        )
    ) {

        $message =
            "กรุณากรอกอีเมลให้ถูกต้อง";

        $message_type =
            "error";

    }

    elseif ($consent !== 1) {

        $message =
            "กรุณายอมรับการรับข่าวสารจาก Veloura";

        $message_type =
            "error";

    }

    else {


        /* =================================================
           CHECK EMAIL
        ================================================= */

        $check =
            $conn->prepare("
                SELECT
                    id,
                    status
                FROM
                    newsletter_subscribers
                WHERE
                    email = ?
            ");


        if (!$check) {

            $message =
                "เกิดข้อผิดพลาดในการตรวจสอบข้อมูล";

            $message_type =
                "error";

        }

        else {


            $check->bind_param(
                "s",
                $email
            );


            $check->execute();


            $result =
                $check->get_result();


            /* =================================================
               EMAIL มีอยู่แล้ว
            ================================================= */

            if (
                $result->num_rows > 0
            ) {


                $row =
                    $result->fetch_assoc();


                if (
                    $row["status"]
                    === "subscribed"
                ) {

                    $message =
                        "อีเมลนี้สมัครรับข่าวสารจาก Veloura อยู่แล้ว ✦";

                    $message_type =
                        "info";

                }

                else {


                    $empty = "";


                    $update =
                        $conn->prepare("
                            UPDATE newsletter_subscribers

                            SET
                                fullname = ?,
                                favorite_notes = ?,
                                perfume_time = ?,
                                weather_preference = ?,
                                priority = ?,
                                budget = ?,
                                buying_style = ?,
                                consent = 1,
                                status = 'subscribed'

                            WHERE
                                email = ?
                        ");


                    $update->bind_param(
                        "ssssssss",
                        $fullname,
                        $favorite_notes_text,
                        $empty,
                        $empty,
                        $empty,
                        $empty,
                        $empty,
                        $email
                    );


                    if (
                        $update->execute()
                    ) {


                        $emailSent =
                            sendVelouraWelcomeEmail(
                                $email,
                                $fullname
                            );


                        if ($emailSent) {

                            $message =
                                "ยินดีต้อนรับกลับสู่ Veloura ✦ อีเมลถูกส่งไปที่ "
                                . $email
                                . " แล้ว";

                        }

                        else {

                            $message =
                                "สมัครสมาชิกสำเร็จ ✦ แต่ส่งอีเมลไม่สำเร็จ กรุณาตรวจสอบ Gmail SMTP";

                        }


                        $message_type =
                            "success";

                    }

                    else {

                        $message =
                            "เกิดข้อผิดพลาด กรุณาลองใหม่";

                        $message_type =
                            "error";
                    }


                    $update->close();
                }

            }

            /* =================================================
               สมาชิกใหม่
            ================================================= */

            else {


                $empty = "";


                $insert =
                    $conn->prepare("
                        INSERT INTO newsletter_subscribers
                        (
                            email,
                            fullname,
                            favorite_notes,
                            perfume_time,
                            weather_preference,
                            priority,
                            budget,
                            buying_style,
                            consent,
                            status
                        )

                        VALUES
                        (
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            1,
                            'subscribed'
                        )
                    ");


                if (!$insert) {

                    $message =
                        "ไม่สามารถเตรียมข้อมูลได้";

                    $message_type =
                        "error";

                }

                else {


                    $insert->bind_param(
                        "ssssssss",
                        $email,
                        $fullname,
                        $favorite_notes_text,
                        $empty,
                        $empty,
                        $empty,
                        $empty,
                        $empty
                    );


                    if (
                        $insert->execute()
                    ) {


                        $emailSent =
                            sendVelouraWelcomeEmail(
                                $email,
                                $fullname
                            );


                        if ($emailSent) {

                            $message =
                                "สมัครรับข่าวสารสำเร็จ ✦ ส่ง Welcome Email ไปที่ "
                                . $email
                                . " แล้ว";

                        }

                        else {

                            $message =
                                "สมัครรับข่าวสารสำเร็จ ✦ แต่ส่งอีเมลไม่สำเร็จ กรุณาตรวจสอบ Gmail SMTP";

                        }


                        $message_type =
                            "success";

                    }

                    else {

                        $message =
                            "ไม่สามารถสมัครสมาชิกได้ กรุณาลองใหม่";

                        $message_type =
                            "error";
                    }


                    $insert->close();
                }
            }


            $check->close();
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


<title>
    Veloura | Welcome
</title>


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
    href="
    https://fonts.googleapis.com/css2?
    family=Cormorant+Garamond:wght@400;500;600;700
    &family=Montserrat:wght@400;500;600
    &family=Noto+Sans+Thai:wght@300;400;500;600
    &display=swap
    "
    rel="stylesheet"
>


<style>

* {

    box-sizing:border-box;

    margin:0;

    padding:0;
}


body {

    min-height:100vh;

    font-family:
        "Noto Sans Thai",
        sans-serif;

    background:

        radial-gradient(
            circle at 10% 10%,
            #f6dce4,
            transparent 30%
        ),

        radial-gradient(
            circle at 90% 20%,
            #f2dfcf,
            transparent 28%
        ),

        linear-gradient(
            135deg,
            #fff9f7,
            #f8e8ec,
            #fff8ef
        );

    color:#50363e;
}


/* =====================================================
   TOP BAR
===================================================== */

.topbar {

    height:40px;

    display:flex;

    align-items:center;

    justify-content:center;

    background:
        linear-gradient(
            90deg,
            #a85871,
            #d4a06c,
            #bd7289,
            #d5a36e,
            #a85871
        );

    color:#fff;

    font-size:9px;

    letter-spacing:2px;
}


/* =====================================================
   CONTAINER
===================================================== */

.container {

    width:
        min(
            760px,
            calc(100% - 30px)
        );

    margin:
        55px auto 70px;
}


/* =====================================================
   HEADER
===================================================== */

.header {

    text-align:center;

    margin-bottom:30px;
}


.logo {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:60px;

    letter-spacing:10px;

    background:
        linear-gradient(
            90deg,
            #995269,
            #d09a66,
            #bd7087,
            #d9ad78,
            #995269
        );

    -webkit-background-clip:text;

    -webkit-text-fill-color:transparent;
}


.logo-sub {

    color:#b18268;

    font-family:
        Montserrat,
        sans-serif;

    font-size:8px;

    letter-spacing:6px;
}


.header h1 {

    margin-top:18px;

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:50px;

    font-weight:500;
}


.header h1 span {

    color:#a75c73;
}


.header p {

    max-width:550px;

    margin:15px auto;

    color:#887078;

    font-size:12px;

    line-height:2;
}


/* =====================================================
   MESSAGE
===================================================== */

.message {

    padding:14px 18px;

    margin-bottom:20px;

    border-radius:10px;

    text-align:center;

    font-size:12px;
}


.success {

    background:#e8f4e9;

    color:#3b6442;

    border:1px solid #c6dfc8;
}


.error {

    background:#f7e1e1;

    color:#763c42;

    border:1px solid #e2bfc2;
}


.info {

    background:#f5e7ed;

    color:#70495b;

    border:1px solid #dfc2ce;
}


/* =====================================================
   CARD
===================================================== */

.card {

    background:
        rgba(255,255,255,.94);

    border:
        1px solid #e2cbd1;

    box-shadow:
        0 30px 70px
        rgba(112,65,82,.14);
}


.form {

    padding:45px;
}


.section {

    margin-bottom:30px;
}


.section-title {

    display:flex;

    align-items:center;

    gap:12px;

    margin-bottom:20px;
}


.number {

    width:34px;

    height:34px;

    display:flex;

    align-items:center;

    justify-content:center;

    border-radius:50%;

    color:white;

    background:
        linear-gradient(
            135deg,
            #b86681,
            #d3a06d
        );

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:18px;
}


.section-title h2 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:28px;
}


.field {

    margin-bottom:17px;
}


.field label {

    display:block;

    margin-bottom:7px;

    font-size:11px;

    color:#715c64;
}


.input {

    width:100%;

    padding:15px;

    border:
        1px solid #e5d0d7;

    outline:none;

    border-radius:3px;

    background:#fffafa;

    font-family:
        "Noto Sans Thai",
        sans-serif;
}


.input:focus {

    border-color:#bd7b91;

    box-shadow:
        0 0 0 3px
        rgba(189,123,145,.10);
}


.notes {

    display:grid;

    grid-template-columns:
        repeat(4,1fr);

    gap:10px;
}


.note {

    position:relative;
}


.note input {

    position:absolute;

    opacity:0;
}


.note label {

    display:flex;

    min-height:65px;

    justify-content:center;

    align-items:center;

    flex-direction:column;

    gap:4px;

    border:
        1px solid #e4d1d7;

    cursor:pointer;

    font-size:10px;

    color:#745862;

    background:#fffafb;

    transition:.3s;
}


.note input:checked + label {

    background:
        linear-gradient(
            135deg,
            #f5d7e1,
            #fff0f3,
            #f5e1cf
        );

    border-color:#c58a72;

    color:#8b5368;

    box-shadow:
        0 8px 20px
        rgba(188,111,139,.15);
}


.consent {

    display:flex;

    gap:8px;

    align-items:flex-start;

    margin-bottom:20px;

    color:#75656b;

    font-size:10px;

    line-height:1.8;
}


.consent input {

    margin-top:4px;

    accent-color:#b66c84;
}


.submit {

    width:100%;

    border:0;

    padding:17px;

    cursor:pointer;

    color:white;

    background:
        linear-gradient(
            90deg,
            #a85570,
            #d29a68,
            #bd7187,
            #a85570
        );

    background-size:250% 100%;

    font-family:Montserrat,sans-serif;

    font-size:10px;

    letter-spacing:3px;

    transition:.4s;
}


.submit:hover {

    background-position:100% 0;

    transform:translateY(-2px);
}


.shop {

    display:block;

    text-align:center;

    margin-top:12px;

    padding:15px;

    border:
        1px solid #c99b6c;

    color:#956448;

    font-size:10px;

    letter-spacing:2px;

    text-decoration:none;

    background:#fffaf5;
}


.shop:hover {

    background:#f9e6ec;

}


.footer {

    text-align:center;

    margin-top:22px;

    color:#aa8d97;

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:15px;

    letter-spacing:2px;
}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:600px) {

    .container {

        width:
            calc(100% - 20px);

        margin-top:35px;
    }


    .form {

        padding:25px 18px;
    }


    .logo {

        font-size:43px;

        letter-spacing:7px;
    }


    .header h1 {

        font-size:40px;
    }


    .notes {

        grid-template-columns:
            1fr 1fr;
    }

}

</style>

</head>


<body>


<div class="topbar">

    ✦ WELCOME TO THE VELOURA FAMILY ✦

</div>


<div class="container">


<header class="header">


    <div class="logo">

        VELOURA

    </div>


    <div class="logo-sub">

        PERFUMES

    </div>


    <h1>

        Welcome to
        <span>Veloura.</span>

    </h1>


    <p>

        สมัครรับข่าวสารจาก Veloura
        และค้นพบโลกแห่งกลิ่นหอม
        คอลเลกชันใหม่ และสิทธิพิเศษสำหรับคุณ

    </p>


</header>


<?php if ($message !== ""): ?>

<div
    class="message <?= htmlspecialchars(
        $message_type,
        ENT_QUOTES,
        "UTF-8"
    ) ?>"
>

    <?= htmlspecialchars(
        $message,
        ENT_QUOTES,
        "UTF-8"
    ) ?>

</div>

<?php endif; ?>


<div class="card">


<form
    method="POST"
    class="form"
>


<section class="section">


    <div class="section-title">

        <div class="number">
            01
        </div>

        <h2>
            ข้อมูลของคุณ
        </h2>

    </div>


    <div class="field">

        <label>
            ชื่อ-นามสกุล *
        </label>


        <input
            type="text"
            name="fullname"
            class="input"
            placeholder="กรอกชื่อของคุณ"
            value="<?= htmlspecialchars(
                $_POST["fullname"] ?? "",
                ENT_QUOTES,
                "UTF-8"
            ) ?>"
            required
        >

    </div>


    <div class="field">

        <label>
            อีเมล *
        </label>


        <input
            type="email"
            name="email"
            class="input"
            placeholder="example@email.com"
            value="<?= htmlspecialchars(
                $_POST["email"] ?? "",
                ENT_QUOTES,
                "UTF-8"
            ) ?>"
            required
        >

    </div>


</section>


<section class="section">


    <div class="section-title">

        <div class="number">
            02
        </div>

        <h2>
            กลิ่นที่คุณชอบ
        </h2>

    </div>


    <div class="notes">


        <div class="note">

            <input
                type="checkbox"
                name="favorite_notes[]"
                value="Vanilla"
                id="vanilla"
            >

            <label for="vanilla">
                ♡
                Vanilla
            </label>

        </div>


        <div class="note">

            <input
                type="checkbox"
                name="favorite_notes[]"
                value="Rose"
                id="rose"
            >

            <label for="rose">
                ✿
                Rose
            </label>

        </div>


        <div class="note">

            <input
                type="checkbox"
                name="favorite_notes[]"
                value="Jasmine"
                id="jasmine"
            >

            <label for="jasmine">
                ✧
                Jasmine
            </label>

        </div>


        <div class="note">

            <input
                type="checkbox"
                name="favorite_notes[]"
                value="Musk"
                id="musk"
            >

            <label for="musk">
                ✦
                Musk
            </label>

        </div>


        <div class="note">

            <input
                type="checkbox"
                name="favorite_notes[]"
                value="Amber"
                id="amber"
            >

            <label for="amber">
                ◆
                Amber
            </label>

        </div>


        <div class="note">

            <input
                type="checkbox"
                name="favorite_notes[]"
                value="Oud"
                id="oud"
            >

            <label for="oud">
                ♢
                Oud
            </label>

        </div>


        <div class="note">

            <input
                type="checkbox"
                name="favorite_notes[]"
                value="Cherry"
                id="cherry"
            >

            <label for="cherry">
                ♥
                Cherry
            </label>

        </div>


        <div class="note">

            <input
                type="checkbox"
                name="favorite_notes[]"
                value="Lavender"
                id="lavender"
            >

            <label for="lavender">
                ✾
                Lavender
            </label>

        </div>


    </div>


</section>


<label class="consent">

    <input
        type="checkbox"
        name="consent"
        value="1"
        required
    >

    <span>

        ฉันยินยอมให้ Veloura
        ส่งข่าวสาร โปรโมชั่น
        คอลเลกชันใหม่
        และเรื่องราวเกี่ยวกับน้ำหอม
        ผ่านทางอีเมล

    </span>

</label>


<button
    type="submit"
    class="submit"
>

    ✦ &nbsp;
    JOIN VELOURA
    &nbsp; ✦

</button>


<a
    href="products.php"
    class="shop"
>

    ✦ &nbsp;
    ช้อปน้ำหอมของเรา
    &nbsp; →

</a>


</form>


</div>


<div class="footer">

    ✦ Fragrance · Beauty · You ✦

</div>


</div>


<script>

/* =====================================================
   จำกัดเลือกกลิ่นสูงสุด 2 กลิ่น
===================================================== */

const notes =
    document.querySelectorAll(
        'input[name="favorite_notes[]"]'
    );


notes.forEach(
    function(note) {

        note.addEventListener(
            "change",
            function() {

                const checked =
                    document.querySelectorAll(
                        'input[name="favorite_notes[]"]:checked'
                    );


                if (checked.length >= 2) {

                    notes.forEach(
                        function(item) {

                            if (!item.checked) {

                                item.disabled = true;

                                item.parentElement.style.opacity =
                                    ".4";
                            }

                        }
                    );

                } else {

                    notes.forEach(
                        function(item) {

                            item.disabled = false;

                            item.parentElement.style.opacity =
                                "1";
                        }
                    );

                }

            }
        );

    }
);

</script>


</body>

</html>