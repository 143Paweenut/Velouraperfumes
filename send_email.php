<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/email_config.php";


function sendVelouraEmail(
    $customerName,
    $customerEmail,
    $phone,
    $subject,
    $message
) {

    $mail = new PHPMailer(true);

    try {

        /*
        =================================================
        SMTP
        =================================================
        */

        $mail->isSMTP();

        $mail->Host = "smtp.gmail.com";

        $mail->SMTPAuth = true;

        $mail->Username = MAIL_USERNAME;

        $mail->Password = MAIL_PASSWORD;

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port = 587;


        /*
        =================================================
        CHARACTER
        =================================================
        */

        $mail->CharSet = "UTF-8";


        /*
        =================================================
        FROM
        =================================================
        */

        $mail->setFrom(
            MAIL_USERNAME,
            MAIL_FROM_NAME
        );


        /*
        =================================================
        TO OWNER
        =================================================
        */

        $mail->addAddress(
            OWNER_EMAIL,
            "Veloura Perfumes"
        );


        /*
        =================================================
        REPLY TO
        =================================================
        */

        if (
            !empty($customerEmail) &&
            filter_var($customerEmail, FILTER_VALIDATE_EMAIL)
        ) {

            $mail->addReplyTo(
                $customerEmail,
                $customerName
            );

        }


        /*
        =================================================
        SUBJECT
        =================================================
        */

        $mail->Subject =
            "✦ Veloura Perfumes | " . $subject;


        /*
        =================================================
        HTML EMAIL
        =================================================
        */

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

        $safePhone =
            htmlspecialchars(
                $phone,
                ENT_QUOTES,
                "UTF-8"
            );

        $safeSubject =
            htmlspecialchars(
                $subject,
                ENT_QUOTES,
                "UTF-8"
            );

        $safeMessage =
            nl2br(
                htmlspecialchars(
                    $message,
                    ENT_QUOTES,
                    "UTF-8"
                )
            );


        $mail->isHTML(true);

        $mail->Body = '

<!DOCTYPE html>

<html lang="th">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

</head>

<body style="
    margin:0;
    padding:0;
    background:#f5f1ec;
    font-family:Arial,sans-serif;
">

<table width="100%"
       cellpadding="0"
       cellspacing="0"
       border="0"
       style="
       background:#f5f1ec;
       padding:40px 15px;
       ">

<tr>

<td align="center">

<table width="650"
       cellpadding="0"
       cellspacing="0"
       border="0"
       style="
       max-width:650px;
       background:#fffdfb;
       border:1px solid #e3d9cf;
       ">

<!-- HEADER -->

<tr>

<td style="
    background:#29231f;
    padding:35px 40px;
    text-align:center;
">

<div style="
    color:#d7bd98;
    font-size:10px;
    letter-spacing:4px;
    margin-bottom:14px;
">

VELOURA PERFUMES

</div>

<div style="
    color:#ffffff;
    font-family:Georgia,serif;
    font-size:38px;
    letter-spacing:7px;
">

VELOURA

</div>

<div style="
    color:#cdbca7;
    font-size:9px;
    letter-spacing:5px;
    margin-top:8px;
">

DISCOVER YOUR SIGNATURE SCENT

</div>

</td>

</tr>


<!-- GOLD LINE -->

<tr>

<td style="
    height:4px;
    background:#b9976d;
">

</td>

</tr>


<!-- CONTENT -->

<tr>

<td style="
    padding:40px;
">

<div style="
    color:#a28360;
    font-size:10px;
    letter-spacing:3px;
    margin-bottom:10px;
">

NEW CUSTOMER MESSAGE

</div>


<h1 style="
    margin:0;
    color:#302721;
    font-family:Georgia,serif;
    font-size:30px;
    font-weight:normal;
">

มีข้อความใหม่จากลูกค้า ✦

</h1>


<div style="
    width:45px;
    height:1px;
    background:#b9976d;
    margin:20px 0 28px;
">

</div>


<!-- CUSTOMER -->

<table width="100%"
       cellpadding="0"
       cellspacing="0"
       style="
       border-collapse:collapse;
       ">

<tr>

<td style="
    padding:13px 0;
    border-bottom:1px solid #eee6df;
    color:#8d8178;
    font-size:12px;
    width:35%;
">

ชื่อลูกค้า

</td>

<td style="
    padding:13px 0;
    border-bottom:1px solid #eee6df;
    color:#302721;
    font-size:13px;
">

' . $safeName . '

</td>

</tr>


<tr>

<td style="
    padding:13px 0;
    border-bottom:1px solid #eee6df;
    color:#8d8178;
    font-size:12px;
">

อีเมล

</td>

<td style="
    padding:13px 0;
    border-bottom:1px solid #eee6df;
    color:#302721;
    font-size:13px;
">

' . $safeEmail . '

</td>

</tr>


<tr>

<td style="
    padding:13px 0;
    border-bottom:1px solid #eee6df;
    color:#8d8178;
    font-size:12px;
">

เบอร์โทรศัพท์

</td>

<td style="
    padding:13px 0;
    border-bottom:1px solid #eee6df;
    color:#302721;
    font-size:13px;
">

' . $safePhone . '

</td>

</tr>


<tr>

<td style="
    padding:13px 0;
    color:#8d8178;
    font-size:12px;
">

หัวข้อ

</td>

<td style="
    padding:13px 0;
    color:#302721;
    font-size:13px;
">

' . $safeSubject . '

</td>

</tr>

</table>


<!-- MESSAGE -->

<div style="
    margin-top:30px;
    background:#faf7f3;
    border:1px solid #eee4da;
    padding:22px;
">

<div style="
    color:#9a7856;
    font-size:10px;
    letter-spacing:2px;
    margin-bottom:12px;
">

MESSAGE

</div>

<div style="
    color:#514740;
    font-size:13px;
    line-height:2;
">

' . $safeMessage . '

</div>

</div>


<!-- BUTTON -->

<div style="
    text-align:center;
    margin-top:30px;
">

<a href="mailto:' . $safeEmail . '"
   style="
   display:inline-block;
   background:#302721;
   color:#ffffff;
   text-decoration:none;
   padding:14px 30px;
   font-size:10px;
   letter-spacing:2px;
   ">

REPLY TO CUSTOMER

</a>

</div>

</td>

</tr>


<!-- FOOTER -->

<tr>

<td style="
    background:#29231f;
    padding:25px;
    text-align:center;
">

<div style="
    color:#d4bd9d;
    font-family:Georgia,serif;
    font-size:19px;
    letter-spacing:4px;
">

VELOURA

</div>

<div style="
    color:#95877a;
    font-size:9px;
    letter-spacing:2px;
    margin-top:8px;
">

PERFUMES

</div>

<div style="
    color:#756960;
    font-size:8px;
    margin-top:16px;
">

Thank you for choosing Veloura.

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
        PLAIN TEXT
        =================================================
        */

        $mail->AltBody =
            "VELOURA PERFUMES\n\n" .
            "มีข้อความใหม่จากลูกค้า\n\n" .
            "ชื่อ: " . $customerName . "\n" .
            "อีเมล: " . $customerEmail . "\n" .
            "เบอร์โทร: " . $phone . "\n" .
            "หัวข้อ: " . $subject . "\n\n" .
            "ข้อความ:\n" . $message;


        /*
        =================================================
        SEND
        =================================================
        */

        $mail->send();

        return true;

    } catch (Exception $e) {

        return false;

    }

}