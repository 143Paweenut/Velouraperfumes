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
   URL หน้าร้าน
========================================================= */

$store_url = "http://localhost/Veloura/products.php";


/* =========================================================
   MESSAGE
========================================================= */

$message = "";
$message_type = "";


/* =========================================================
   SEND VELOURA EMAIL
========================================================= */

function sendVelouraEmail(
    $customerEmail,
    $customerName,
    $isReturning = false
) {

    global $gmail_username;
    global $gmail_app_password;
    global $store_url;

    $mail = new PHPMailer(true);

    try {

        /* =====================================================
           SMTP
        ===================================================== */

        $mail->isSMTP();

        $mail->Host = "smtp.gmail.com";

        $mail->SMTPAuth = true;

        $mail->Username = $gmail_username;

        $mail->Password = str_replace(
            " ",
            "",
            $gmail_app_password
        );

        $mail->SMTPSecure =
            PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port = 587;


        /* =====================================================
           EMAIL SETTINGS
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

        if ($isReturning) {

            $mail->Subject =
                "✦ ยินดีต้อนรับกลับสู่ Veloura Perfumes ✦";

        } else {

            $mail->Subject =
                "✦ Welcome to Veloura Perfumes ✦";
        }


        /* =====================================================
           SAFE DATA
        ===================================================== */

        $safeName = htmlspecialchars(
            $customerName !== ""
                ? $customerName
                : "คุณ",
            ENT_QUOTES,
            "UTF-8"
        );


        $safeEmail = htmlspecialchars(
            $customerEmail,
            ENT_QUOTES,
            "UTF-8"
        );


        /* =====================================================
           TITLE
        ===================================================== */

        if ($isReturning) {

            $mainTitle =
                "ยินดีต้อนรับกลับสู่<br><span>Veloura</span>";

            $subText =
                "ดีใจที่ได้พบคุณอีกครั้ง";

        } else {

            $mainTitle =
                "Welcome to<br><span>Veloura</span>";

            $subText =
                "ขอบคุณที่เข้ามาเป็นส่วนหนึ่งของครอบครัวเรา";
        }


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

/* =====================================================
   RESET
===================================================== */

html,
body {

    margin: 0 !important;

    padding: 0 !important;

    width: 100% !important;
}


body {

    background: #f8e9ee;

    font-family:
        Arial,
        Tahoma,
        sans-serif;

    -webkit-text-size-adjust: 100%;

    -ms-text-size-adjust: 100%;
}


table {

    border-spacing: 0;

    border-collapse: collapse;
}


img {

    border: 0;

    display: block;
}


a {

    text-decoration: none;
}


/* =====================================================
   MOBILE
===================================================== */

@media only screen and (max-width:600px) {

    .wrapper {

        padding:
            15px 8px !important;
    }


    .container {

        width: 100% !important;

        border-radius: 20px !important;
    }


    .content {

        padding:
            28px 20px !important;
    }


    .logo {

        font-size: 36px !important;

        letter-spacing: 7px !important;
    }


    .title {

        font-size: 27px !important;
    }


    .description {

        font-size: 13px !important;

        line-height: 1.9 !important;
    }


    .email-card {

        width: 100% !important;
    }


    .benefit {

        display: block !important;

        width: 100% !important;

        border-right: 0 !important;

        border-bottom:
            1px solid #e7d1d9 !important;

        padding:
            14px !important;
    }


    .benefit:last-child {

        border-bottom: 0 !important;
    }


    .shop-button {

        display: block !important;

        padding:
            15px 22px !important;
    }


    .footer-text {

        font-size: 9px !important;
    }

}

</style>

</head>


<body>


<!-- =====================================================
     OUTER
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
            radial-gradient(
                circle at 10% 10%,
                #f6dce5,
                transparent 30%
            ),
            radial-gradient(
                circle at 90% 90%,
                #f4e2c9,
                transparent 30%
            ),
            #f8e9ee;
    "
>

<tr>

<td
    align="center"
    class="wrapper"
    style="
        padding:
            35px 12px 50px;
    "
>


<!-- =====================================================
     MAIN CONTAINER
===================================================== -->

<table
    width="600"
    cellpadding="0"
    cellspacing="0"
    border="0"
    role="presentation"
    class="container"
    style="
        width:600px;
        max-width:600px;

        background:#fff8fa;

        border-radius:26px;

        overflow:hidden;

        border:
            1px solid #dfbdc9;

        box-shadow:
            0 25px 70px
            rgba(145,80,103,.20);
    "
>


<!-- =====================================================
     TOP LUXURY BAR
===================================================== -->

<tr>

<td
    align="center"
    style="
        padding:
            8px;

        background:
            linear-gradient(
                90deg,
                #b96b84,
                #d7a66c,
                #bd738a,
                #e0b77d,
                #b96b84
            );

        color:#fff;

        font-size:8px;

        letter-spacing:4px;
    "
>

✦ &nbsp;
VELOURA PRIVATE BEAUTY CLUB
&nbsp; ✦

</td>

</tr>


<!-- =====================================================
     BRAND HEADER
===================================================== -->

<tr>

<td
    align="center"
    style="
        padding:
            30px 20px 26px;

        background:
            linear-gradient(
                135deg,
                #fff9fa 0%,
                #fcecf1 48%,
                #fff8ee 100%
            );
    "
>


<!-- TOP SPARKLES -->

<div
    style="
        color:#c59a68;

        font-size:17px;

        letter-spacing:10px;

        margin-bottom:12px;
    "
>
    ✦ ✧ ⋆ ✧ ✦
</div>


<!-- LOGO -->

<div
    class="logo"
    style="
        color:#9e536d;

        font-family:
            Georgia,
            'Times New Roman',
            serif;

        font-size:43px;

        font-weight:normal;

        letter-spacing:9px;

        line-height:1;
    "
>
    VELOURA
</div>


<!-- GOLD LINE -->

<table
    width="280"
    cellpadding="0"
    cellspacing="0"
    border="0"
    role="presentation"
    style="
        margin:
            13px auto 0;
    "
>

<tr>

<td
    width="80"
    style="
        border-top:
            1px solid #d0a26d;
    "
></td>


<td
    align="center"
    width="120"
    style="
        color:#b38359;

        font-size:8px;

        letter-spacing:3px;
    "
>
    PERFUMES
</td>


<td
    width="80"
    style="
        border-top:
            1px solid #d0a26d;
    "
></td>

</tr>

</table>


<div
    style="
        margin-top:7px;

        color:#9e7b70;

        font-size:8px;

        letter-spacing:4px;
    "
>
    MORE THAN A SCENT
</div>


<div
    style="
        margin-top:3px;

        color:#b88c6d;

        font-size:8px;

        letter-spacing:3px;
    "
>
    IT'S A PART OF YOU
</div>


</td>

</tr>


<!-- =====================================================
     MAIN CONTENT
===================================================== -->

<tr>

<td
    class="content"
    style="
        padding:
            35px 40px 40px;

        background:
            linear-gradient(
                150deg,
                #5a3745 0%,
                #704658 42%,
                #50313e 100%
            );

        color:#fff;
    "
>


<!-- =====================================================
     SPARKLES
===================================================== -->

<div
    align="center"
    style="
        color:#e4c18f;

        font-size:20px;

        letter-spacing:11px;

        margin-bottom:10px;

        text-shadow:
            0 0 12px
            rgba(229,193,143,.55);
    "
>
    ✦　✧　⋆　✧　✦
</div>


<!-- =====================================================
     LITTLE HEART
===================================================== -->

<div
    align="center"
    style="
        color:#f8d8e2;

        font-size:31px;

        margin-bottom:8px;
    "
>
    ♡
</div>


<!-- =====================================================
     TITLE
===================================================== -->

<div
    class="title"
    align="center"
    style="
        color:#fff8fa;

        font-family:
            Georgia,
            'Times New Roman',
            serif;

        font-size:31px;

        line-height:1.45;

        font-weight:normal;
    "
>

    $mainTitle

</div>


<!-- =====================================================
     SUBTITLE
===================================================== -->

<div
    align="center"
    style="
        color:#e9c8d3;

        font-size:11px;

        letter-spacing:1px;

        margin-top:8px;
    "
>
    $subText
</div>


<!-- GOLD DIVIDER -->

<div
    align="center"
    style="
        color:#dfb77c;

        font-size:17px;

        margin:
            10px 0 18px;
    "
>
    ─── ✦ ───
</div>


<!-- =====================================================
     NAME
===================================================== -->

<div
    align="center"
    style="
        color:#fff0f4;

        font-family:
            Georgia,
            'Times New Roman',
            serif;

        font-size:18px;

        margin-bottom:4px;
    "
>
    สวัสดีค่ะ $safeName
</div>


<!-- =====================================================
     EMAIL UNDER NAME
===================================================== -->

<div
    align="center"
    style="
        color:#edcfda;

        font-size:12px;

        line-height:1.8;

        margin-bottom:20px;
    "
>

<span
    style="
        color:#e3bb7d;

        font-size:13px;
    "
>
    ✉
</span>

&nbsp;

<span
    style="
        color:#f5e3e8;

        word-break:break-all;
    "
>
    $safeEmail
</span>

</div>


<!-- =====================================================
     DESCRIPTION
===================================================== -->

<div
    class="description"
    align="center"
    style="
        color:#f1e1e6;

        font-size:13px;

        line-height:2;

        padding:
            0 5px;
    "
>

ขอบคุณที่เข้ามาเป็นส่วนหนึ่งของ Veloura

<br>

เราตั้งใจคัดสรรเรื่องราวแห่งกลิ่นหอม
เพื่อให้ทุกกลิ่นกลายเป็นส่วนหนึ่งของความทรงจำ

<br>

คุณจะได้รับข่าวสารคอลเลกชันใหม่
โปรโมชั่นพิเศษ และเรื่องราวจาก Veloura

<br>

ก่อนใครผ่านทางอีเมลนี้ ♡

</div>


<!-- =====================================================
     BENEFITS CARD
===================================================== -->

<table
    width="100%"
    cellpadding="0"
    cellspacing="0"
    border="0"
    role="presentation"
    style="
        margin-top:25px;

        background:
            linear-gradient(
                135deg,
                #fff8fa,
                #fdf0f4,
                #fff8ef
            );

        border-radius:17px;

        border:
            1px solid #e1c2cc;

        overflow:hidden;

        box-shadow:
            0 8px 25px
            rgba(35,15,25,.12);
    "
>

<tr>


<!-- BENEFIT 1 -->

<td
    class="benefit"
    width="25%"
    align="center"
    style="
        padding:
            17px 6px;

        border-right:
            1px solid #e7d1d9;

        color:#76515f;
    "
>

<div
    style="
        font-size:20px;

        color:#c59a68;

        margin-bottom:7px;
    "
>
    ✦
</div>

<div
    style="
        font-size:9px;

        line-height:1.6;
    "
>
    คอลเลกชันใหม่<br>
    ก่อนใคร
</div>

</td>


<!-- BENEFIT 2 -->

<td
    class="benefit"
    width="25%"
    align="center"
    style="
        padding:
            17px 6px;

        border-right:
            1px solid #e7d1d9;

        color:#76515f;
    "
>

<div
    style="
        font-size:20px;

        color:#c59a68;

        margin-bottom:7px;
    "
>
    %
</div>

<div
    style="
        font-size:9px;

        line-height:1.6;
    "
>
    โปรโมชั่นพิเศษ<br>
    สำหรับสมาชิก
</div>

</td>


<!-- BENEFIT 3 -->

<td
    class="benefit"
    width="25%"
    align="center"
    style="
        padding:
            17px 6px;

        border-right:
            1px solid #e7d1d9;

        color:#76515f;
    "
>

<div
    style="
        font-size:20px;

        color:#c59a68;

        margin-bottom:7px;
    "
>
    ✧
</div>

<div
    style="
        font-size:9px;

        line-height:1.6;
    "
>
    Fragrance<br>
    Inspiration
</div>

</td>


<!-- BENEFIT 4 -->

<td
    class="benefit"
    width="25%"
    align="center"
    style="
        padding:
            17px 6px;

        color:#76515f;
    "
>

<div
    style="
        font-size:20px;

        color:#c59a68;

        margin-bottom:7px;
    "
>
    ♡
</div>

<div
    style="
        font-size:9px;

        line-height:1.6;
    "
>
    ข่าวสารและ<br>
    กิจกรรมพิเศษ
</div>

</td>


</tr>

</table>


<!-- =====================================================
     EMAIL CARD
===================================================== -->

<table
    width="90%"
    class="email-card"
    cellpadding="0"
    cellspacing="0"
    border="0"
    role="presentation"
    align="center"
    style="
        margin:
            22px auto 0;

        background:
            linear-gradient(
                135deg,
                #fffafc,
                #f9e9ee 55%,
                #fff5e9
            );

        border-radius:17px;

        border:
            1px solid #d8b07b;

        box-shadow:
            0 7px 25px
            rgba(25,10,18,.12);
    "
>

<tr>

<td
    align="center"
    style="
        padding:
            18px 18px;
    "
>


<!-- CARD SPARKLE -->

<div
    style="
        color:#c3945e;

        font-size:17px;

        letter-spacing:6px;

        margin-bottom:5px;
    "
>
    ✦ ✉ ✦
</div>


<div
    style="
        color:#704657;

        font-family:
            Georgia,
            'Times New Roman',
            serif;

        font-size:16px;
    "
>
    อีเมลของคุณ
</div>


<div
    style="
        color:#9b6376;

        font-size:12px;

        margin-top:6px;

        word-break:break-all;
    "
>
    $safeEmail
</div>


<div
    style="
        margin-top:9px;

        color:#9b795a;

        font-size:9px;

        letter-spacing:.5px;
    "
>
    ✓ สมัครรับข่าวสารจาก Veloura เรียบร้อยแล้ว
</div>


</td>

</tr>

</table>


<!-- =====================================================
     SHOP BUTTON
===================================================== -->

<table
    cellpadding="0"
    cellspacing="0"
    border="0"
    role="presentation"
    align="center"
    style="
        margin:
            24px auto 0;
    "
>

<tr>

<td
    align="center"
    style="
        border-radius:30px;

        background:
            linear-gradient(
                90deg,
                #c06f87,
                #d4a06c,
                #c06f87
            );

        box-shadow:
            0 10px 28px
            rgba(26,10,18,.20);
    "
>


<a
    href="$store_url"
    target="_blank"
    class="shop-button"
    style="
        display:inline-block;

        padding:
            15px 34px;

        color:#fff;

        font-family:
            Arial,
            Tahoma,
            sans-serif;

        font-size:11px;

        font-weight:bold;

        border-radius:30px;

        letter-spacing:1px;
    "
>

✦ &nbsp;
SHOP VELOURA
&nbsp; →

</a>


</td>

</tr>

</table>


<!-- =====================================================
     QUOTE
===================================================== -->

<div
    align="center"
    style="
        margin-top:25px;

        color:#e8cbd4;

        font-family:
            Georgia,
            'Times New Roman',
            serif;

        font-size:12px;

        font-style:italic;

        line-height:1.8;
    "
>

“Every scent tells a story.<br>
Make yours unforgettable.”

</div>


<!-- =====================================================
     BOTTOM SPARKLES
===================================================== -->

<div
    align="center"
    style="
        color:#dfb77c;

        font-size:15px;

        letter-spacing:9px;

        margin-top:18px;
    "
>
    ✧　✦　⋆　✦　✧
</div>


<!-- =====================================================
     SIGNATURE
===================================================== -->

<div
    align="center"
    style="
        margin-top:6px;

        color:#f0dbe1;

        font-size:11px;

        line-height:1.9;
    "
>

ด้วยความรักจาก

<br>

<span
    style="
        color:#f2d09a;

        font-family:
            Georgia,
            'Times New Roman',
            serif;

        font-size:18px;

        font-style:italic;
    "
>
    Veloura Perfumes
</span>

<br>

<span
    style="
        color:#dcb9a0;

        font-size:8px;

        letter-spacing:2px;
    "
>
    MORE THAN A SCENT · IT'S A PART OF YOU
</span>

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
            15px;

        background:
            linear-gradient(
                90deg,
                #4a2c38,
                #684052,
                #4a2c38
            );

        color:#d9b8a0;

        font-size:8px;

        letter-spacing:3px;
    "
>

FRAGRANCE
&nbsp; ✦ &nbsp;
BEAUTY
&nbsp; ✦ &nbsp;
YOU

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

            "✦ VELOURA PERFUMES ✦\n\n"

            . "สวัสดีค่ะ "
            . (
                $customerName !== ""
                    ? $customerName
                    : "คุณ"
            )
            . "\n"

            . "อีเมล: "
            . $customerEmail
            . "\n\n"

            . "ขอบคุณที่เข้ามาเป็นส่วนหนึ่งของ Veloura\n\n"

            . "คุณจะได้รับข่าวสาร คอลเลกชันใหม่ "
            . "โปรโมชั่น และเรื่องราวจาก Veloura\n\n"

            . "SHOP VELOURA:\n"
            . $store_url
            . "\n\n"

            . "ด้วยความรักจาก\n"
            . "Veloura Perfumes";


        /* =====================================================
           SEND
        ===================================================== */

        $mail->send();

        return true;

    } catch (Exception $e) {

        error_log(
            "Veloura PHPMailer Error: "
            . $mail->ErrorInfo
        );

        return false;
    }
}


/* =========================================================
   FORM SUBMIT
========================================================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    /* =====================================================
       DATA
    ===================================================== */

    $fullname = trim(
        $_POST["fullname"] ?? ""
    );


    $email = trim(
        $_POST["email"] ?? ""
    );


    $favorite_notes =
        $_POST["favorite_notes"] ?? [];


    $consent =
        isset($_POST["consent"])
            ? 1
            : 0;


    /* =====================================================
       FAVORITE NOTES
    ===================================================== */

    if (!is_array($favorite_notes)) {

        $favorite_notes = [];
    }


    /* สูงสุด 2 กลิ่น */

    $favorite_notes =
        array_slice(
            $favorite_notes,
            0,
            2
        );


    $favorite_notes_text =
        implode(
            ", ",
            array_map(
                "trim",
                $favorite_notes
            )
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

        $check = $conn->prepare("
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
               EXISTING EMAIL
            ================================================= */

            if ($result->num_rows > 0) {


                $row =
                    $result->fetch_assoc();


                /* =============================================
                   ALREADY SUBSCRIBED
                ============================================= */

                if (
                    $row["status"]
                    === "subscribed"
                ) {

                    $message =
                        "อีเมลนี้สมัครรับข่าวสารจาก Veloura อยู่แล้ว ✦";

                    $message_type =
                        "info";

                }


                /* =============================================
                   RESUBSCRIBE
                ============================================= */

                else {


                    $empty = "";


                    $update = $conn->prepare("
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


                    if (!$update) {

                        $message =
                            "ไม่สามารถเตรียมข้อมูลได้";

                        $message_type =
                            "error";

                    }

                    else {


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


                            $emailSent = false;


                            if (
                                $gmail_username
                                !== "YOUR_GMAIL@gmail.com"

                                &&

                                $gmail_app_password
                                !== "YOUR_APP_PASSWORD"
                            ) {

                                $emailSent =
                                    sendVelouraEmail(
                                        $email,
                                        $fullname,
                                        true
                                    );
                            }


                            if ($emailSent) {

                                $message =
                                    "ยินดีต้อนรับกลับสู่ Veloura ✦ ส่งอีเมลไปที่ "
                                    . $email
                                    . " แล้ว";

                            } else {

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

            }


            /* =================================================
               NEW EMAIL
            ================================================= */

            else {


                $empty = "";


                $insert = $conn->prepare("
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


                        $emailSent = false;


                        if (
                            $gmail_username
                            !== "YOUR_GMAIL@gmail.com"

                            &&

                            $gmail_app_password
                            !== "YOUR_APP_PASSWORD"
                        ) {

                            $emailSent =
                                sendVelouraEmail(
                                    $email,
                                    $fullname,
                                    false
                                );
                        }


                        if ($emailSent) {

                            $message =
                                "สมัครรับข่าวสารสำเร็จ ✦ ส่งอีเมลไปที่ "
                                . $email
                                . " แล้ว";

                        } else {

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
    Veloura | Join the Family
</title>


<!-- =====================================================
     GOOGLE FONT
===================================================== -->

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

/* =====================================================
   RESET
===================================================== */

* {

    margin: 0;

    padding: 0;

    box-sizing: border-box;
}


body {

    min-height: 100vh;

    background:

        radial-gradient(
            circle at 5% 5%,
            rgba(247,190,207,.45),
            transparent 28%
        ),

        radial-gradient(
            circle at 95% 10%,
            rgba(224,183,125,.25),
            transparent 27%
        ),

        radial-gradient(
            circle at 50% 100%,
            rgba(255,214,226,.55),
            transparent 35%
        ),

        linear-gradient(
            135deg,
            #fff8fa 0%,
            #fbe9ef 48%,
            #fff8ef 100%
        );

    color: #4b303a;

    font-family:
        "Noto Sans Thai",
        sans-serif;
}


/* =====================================================
   TOP
===================================================== */

.top {

    height: 40px;

    display: flex;

    align-items: center;

    justify-content: center;

    background:
        linear-gradient(
            90deg,
            #a95770,
            #d39b68,
            #bd7289,
            #d5a16c,
            #a95770
        );

    color: #fff;

    font-size: 9px;

    letter-spacing: 2px;

    box-shadow:
        0 4px 18px
        rgba(175,95,120,.18);
}


/* =====================================================
   CONTAINER
===================================================== */

.container {

    position: relative;

    width:
        min(
            780px,
            calc(100% - 30px)
        );

    margin:
        55px auto 80px;
}


/* =====================================================
   FLOATING SPARKLES
===================================================== */

.container::before {

    content: "✦";

    position: absolute;

    top: 110px;

    left: -42px;

    color: #c99b64;

    font-size: 24px;

    opacity: .65;

    text-shadow:
        0 0 15px
        rgba(207,160,101,.6);

    animation:
        sparkle 3s
        ease-in-out
        infinite;
}


.container::after {

    content: "✧";

    position: absolute;

    top: 290px;

    right: -38px;

    color: #c97891;

    font-size: 27px;

    opacity: .7;

    text-shadow:
        0 0 15px
        rgba(201,120,145,.5);

    animation:
        sparkle 3.5s
        ease-in-out
        infinite;
}


@keyframes sparkle {

    0%,
    100% {

        transform:
            scale(.8)
            rotate(0deg);

        opacity: .35;
    }

    50% {

        transform:
            scale(1.35)
            rotate(20deg);

        opacity: 1;
    }
}


/* =====================================================
   HEADER
===================================================== */

.header {

    text-align: center;

    margin-bottom: 30px;
}


.logo {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size: 62px;

    letter-spacing: 10px;

    background:
        linear-gradient(
            90deg,
            #914e64,
            #c89360,
            #bd6e88,
            #ddb27a,
            #914e64
        );

    -webkit-background-clip: text;

    -webkit-text-fill-color: transparent;

    background-clip: text;
}


.logo-sub {

    margin-top: -5px;

    font-family:
        "Montserrat",
        sans-serif;

    font-size: 9px;

    letter-spacing: 7px;

    color: #b68462;
}


.line {

    width: 85px;

    height: 1px;

    margin: 18px auto;

    background:
        linear-gradient(
            90deg,
            transparent,
            #c49664,
            transparent
        );
}


.header-small {

    color: #b56882;

    font-size: 10px;

    letter-spacing: 4px;

    margin-bottom: 10px;
}


.header h1 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size: 60px;

    line-height: 1;

    font-weight: 500;

    color: #4b303a;
}


.header h1 span {

    background:
        linear-gradient(
            90deg,
            #b76b84,
            #d09b63,
            #c37a91,
            #d2a064
        );

    -webkit-background-clip: text;

    -webkit-text-fill-color: transparent;

    background-clip: text;
}


.header p {

    max-width: 560px;

    margin: 19px auto 0;

    color: #806a74;

    font-size: 12px;

    line-height: 2;
}


/* =====================================================
   MESSAGE
===================================================== */

.message {

    margin-bottom: 20px;

    padding:
        14px 18px;

    border-radius: 12px;

    text-align: center;

    font-size: 12px;

    box-shadow:
        0 8px 25px
        rgba(100,50,70,.08);
}


.message.success {

    background:
        linear-gradient(
            135deg,
            #edf8ed,
            #e2f2e4
        );

    color: #315c38;

    border:
        1px solid #bfdcbf;
}


.message.error {

    background:
        linear-gradient(
            135deg,
            #f9e1df,
            #f5d7dc
        );

    color: #743a31;

    border:
        1px solid #e3b8bd;
}


.message.info {

    background:
        linear-gradient(
            135deg,
            #f8eaf0,
            #f5e3e9
        );

    color: #70495a;

    border:
        1px solid #ddc0cb;
}


/* =====================================================
   CARD
===================================================== */

.card {

    position: relative;

    overflow: hidden;

    background:
        linear-gradient(
            145deg,
            rgba(255,255,255,.98),
            rgba(255,245,248,.98)
        );

    border:
        1px solid
        rgba(201,145,162,.35);

    border-radius: 5px;

    box-shadow:
        0 30px 80px
        rgba(137,76,96,.16),

        0 5px 20px
        rgba(207,164,105,.10);
}


.card::before {

    content: "";

    position: absolute;

    inset: 10px;

    border:
        1px solid
        rgba(196,151,92,.25);

    pointer-events: none;
}


/* =====================================================
   FORM
===================================================== */

.form {

    position: relative;

    z-index: 1;

    padding: 50px;
}


/* =====================================================
   SECTION
===================================================== */

.section {

    margin-bottom: 32px;
}


.section-title {

    display: flex;

    align-items: center;

    gap: 12px;

    margin-bottom: 20px;
}


.number {

    width: 34px;

    height: 34px;

    display: flex;

    justify-content: center;

    align-items: center;

    border-radius: 50%;

    background:
        linear-gradient(
            135deg,
            #b96882,
            #d69a69,
            #bd7188
        );

    color: #fff;

    font-family:
        "Cormorant Garamond",
        serif;

    font-size: 18px;

    box-shadow:
        0 6px 18px
        rgba(185,104,130,.25);
}


.section-title h2 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size: 29px;

    font-weight: 600;

    color: #4e333d;
}


/* =====================================================
   FIELD
===================================================== */

.field {

    margin-bottom: 18px;
}


.field label {

    display: block;

    margin-bottom: 8px;

    color: #685860;

    font-size: 11px;
}


.input {

    width: 100%;

    padding:
        15px 17px;

    border:
        1px solid #ead5dc;

    border-radius: 2px;

    background:
        linear-gradient(
            135deg,
            #fff,
            #fff9fa
        );

    outline: none;

    color: #49333b;

    font-family:
        "Noto Sans Thai",
        sans-serif;

    transition: .3s;

    box-shadow:
        inset 0 1px 5px
        rgba(150,90,110,.03);
}


.input:focus {

    border-color: #c58a72;

    box-shadow:
        0 0 0 3px
        rgba(214,154,169,.12),

        0 7px 20px
        rgba(184,117,137,.10);
}


/* =====================================================
   NOTE HELP
===================================================== */

.note-help {

    margin-bottom: 14px;

    color: #99878f;

    font-size: 10px;
}


/* =====================================================
   NOTES
===================================================== */

.notes {

    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 10px;
}


.note {

    position: relative;
}


.note input {

    position: absolute;

    opacity: 0;
}


.note label {

    min-height: 65px;

    display: flex;

    align-items: center;

    justify-content: center;

    flex-direction: column;

    gap: 4px;

    border:
        1px solid #ead6dd;

    border-radius: 3px;

    background:
        linear-gradient(
            145deg,
            #fff,
            #fff7f9
        );

    color: #70545e;

    font-size: 10px;

    cursor: pointer;

    transition: .3s;

    box-shadow:
        0 4px 12px
        rgba(130,80,100,.04);
}


.note label:hover {

    border-color: #c994a4;

    transform:
        translateY(-4px);

    box-shadow:
        0 10px 25px
        rgba(190,117,140,.15);
}


.note input:checked + label {

    background:
        linear-gradient(
            135deg,
            #f8dbe4,
            #fff1f4,
            #f4dfcf
        );

    border-color: #c58a72;

    color: #7e485d;

    box-shadow:
        0 10px 25px
        rgba(190,117,140,.18),

        inset 0 0 20px
        rgba(255,255,255,.7);
}


/* =====================================================
   CONSENT
===================================================== */

.consent {

    display: flex;

    align-items: flex-start;

    gap: 9px;

    color: #75666d;

    font-size: 10px;

    line-height: 1.8;

    cursor: pointer;

    margin-bottom: 20px;
}


.consent input {

    margin-top: 4px;

    accent-color: #b9748c;
}


/* =====================================================
   SUBMIT
===================================================== */

.submit {

    position: relative;

    width: 100%;

    padding: 18px;

    border: none;

    border-radius: 2px;

    cursor: pointer;

    background:
        linear-gradient(
            100deg,
            #a95770 0%,
            #c87891 28%,
            #d6a06c 50%,
            #c87891 72%,
            #a95770 100%
        );

    background-size: 250% 100%;

    color: white;

    font-family:
        "Montserrat",
        sans-serif;

    font-size: 10px;

    letter-spacing: 4px;

    transition: .4s;

    box-shadow:
        0 12px 30px
        rgba(169,87,112,.25);
}


.submit:hover {

    background-position:
        100% 0;

    transform:
        translateY(-3px);

    box-shadow:
        0 18px 38px
        rgba(169,87,112,.32);
}


/* =====================================================
   SHOP
===================================================== */

.shop {

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 8px;

    margin-top: 12px;

    padding: 15px;

    border:
        1px solid #c79a6b;

    color: #a06b4b;

    background:
        linear-gradient(
            135deg,
            #fffaf5,
            #fff1f4
        );

    text-decoration: none;

    font-size: 10px;

    letter-spacing: 2px;

    transition: .3s;

    box-shadow:
        0 5px 18px
        rgba(194,148,96,.08);
}


.shop:hover {

    background:
        linear-gradient(
            135deg,
            #fbe2ea,
            #f9ead8
        );

    border-color: #b77b5b;

    color: #8c5367;

    transform:
        translateY(-3px);

    box-shadow:
        0 12px 25px
        rgba(190,120,140,.15);
}


/* =====================================================
   FOOTER
===================================================== */

.footer {

    text-align: center;

    margin-top: 25px;

    color: #aa929c;

    font-family:
        "Cormorant Garamond",
        serif;

    font-size: 14px;

    letter-spacing: 2px;
}


/* =====================================================
   MOBILE PAGE
===================================================== */

@media(max-width:600px) {

    .container {

        margin-top: 35px;
    }


    .form {

        padding:
            35px 20px;
    }


    .logo {

        font-size: 43px;

        letter-spacing: 7px;
    }


    .header h1 {

        font-size: 44px;
    }


    .notes {

        grid-template-columns:
            1fr 1fr;
    }


    .top {

        font-size: 8px;

        letter-spacing: 1px;
    }


    .container::before {

        display: none;
    }


    .container::after {

        display: none;
    }

}

</style>

</head>


<body>


<!-- =====================================================
     TOP BAR
===================================================== -->

<div class="top">

    ✦ JOIN THE VELOURA FAMILY
    &nbsp; • &nbsp;
    DISCOVER YOUR SIGNATURE SCENT ✦

</div>


<div class="container">


<!-- =====================================================
     HEADER
===================================================== -->

<header class="header">


    <div class="logo">

        VELOURA

    </div>


    <div class="logo-sub">

        PERFUMES

    </div>


    <div class="line"></div>


    <div class="header-small">

        ✦ WELCOME TO VELOURA ✦

    </div>


    <h1>

        Join the
        <span>Veloura Family.</span>

    </h1>


    <p>

        สมัครสมาชิกเพื่อรับข่าวสาร โปรโมชั่น
        คอลเลกชันใหม่ และเรื่องราวจากโลกแห่งกลิ่นหอม
        ของ Veloura

    </p>

</header>


<!-- =====================================================
     MESSAGE
===================================================== -->

<?php if ($message !== ""): ?>

<div
    class="
        message
        <?= htmlspecialchars(
            $message_type,
            ENT_QUOTES,
            "UTF-8"
        ) ?>
    "
>

    <?= htmlspecialchars(
        $message,
        ENT_QUOTES,
        "UTF-8"
    ) ?>

</div>

<?php endif; ?>


<!-- =====================================================
     FORM CARD
===================================================== -->

<div class="card">


<form
    method="POST"
    class="form"
>


<!-- =====================================================
     SECTION 01
===================================================== -->

<section class="section">


    <div class="section-title">

        <div class="number">

            01

        </div>


        <h2>

            ข้อมูลของคุณ

        </h2>

    </div>


    <!-- NAME -->

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


    <!-- EMAIL -->

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


<!-- =====================================================
     SECTION 02
===================================================== -->

<section class="section">


    <div class="section-title">

        <div class="number">

            02

        </div>


        <h2>

            กลิ่นที่คุณชอบ

        </h2>

    </div>


    <div class="note-help">

        ✦ เลือกได้ไม่เกิน 2 กลิ่น
        หรือไม่เลือกก็ได้

    </div>


    <div class="notes">


        <!-- VANILLA -->

        <div class="note">

            <input
                type="checkbox"
                id="vanilla"
                name="favorite_notes[]"
                value="Vanilla"
            >

            <label for="vanilla">

                ♡

                Vanilla

            </label>

        </div>


        <!-- ROSE -->

        <div class="note">

            <input
                type="checkbox"
                id="rose"
                name="favorite_notes[]"
                value="Rose"
            >

            <label for="rose">

                ✿

                Rose

            </label>

        </div>


        <!-- JASMINE -->

        <div class="note">

            <input
                type="checkbox"
                id="jasmine"
                name="favorite_notes[]"
                value="Jasmine"
            >

            <label for="jasmine">

                ✧

                Jasmine

            </label>

        </div>


        <!-- MUSK -->

        <div class="note">

            <input
                type="checkbox"
                id="musk"
                name="favorite_notes[]"
                value="Musk"
            >

            <label for="musk">

                ✦

                Musk

            </label>

        </div>


        <!-- AMBER -->

        <div class="note">

            <input
                type="checkbox"
                id="amber"
                name="favorite_notes[]"
                value="Amber"
            >

            <label for="amber">

                ◆

                Amber

            </label>

        </div>


        <!-- OUD -->

        <div class="note">

            <input
                type="checkbox"
                id="oud"
                name="favorite_notes[]"
                value="Oud"
            >

            <label for="oud">

                ♢

                Oud

            </label>

        </div>


        <!-- CHERRY -->

        <div class="note">

            <input
                type="checkbox"
                id="cherry"
                name="favorite_notes[]"
                value="Cherry"
            >

            <label for="cherry">

                ♥

                Cherry

            </label>

        </div>


        <!-- LAVENDER -->

        <div class="note">

            <input
                type="checkbox"
                id="lavender"
                name="favorite_notes[]"
                value="Lavender"
            >

            <label for="lavender">

                ✾

                Lavender

            </label>

        </div>


    </div>


</section>


<!-- =====================================================
     CONSENT
===================================================== -->

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
        และข้อมูลเกี่ยวกับน้ำหอม
        ผ่านทางอีเมล

    </span>


</label>


<!-- =====================================================
     SUBMIT
===================================================== -->

<button
    type="submit"
    class="submit"
>

    ✦ &nbsp; JOIN VELOURA &nbsp; ✦

</button>


<!-- =====================================================
     SHOP
===================================================== -->

<a
    href="products.php"
    class="shop"
>

    ✦ &nbsp; ช้อปเลย &nbsp; →

</a>


</form>


</div>


<!-- =====================================================
     FOOTER
===================================================== -->

<div class="footer">

    ✦ Fragrance · Beauty · You ✦

</div>


</div>


<!-- =====================================================
     JAVASCRIPT
===================================================== -->

<script>

/* =====================================================
   จำกัดเลือกกลิ่น 2 รายการ
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
                                    "0.45";
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