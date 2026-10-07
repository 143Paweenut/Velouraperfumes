<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {

    // =====================================================
    // ตั้งค่า SMTP Gmail
    // =====================================================

    $mail->isSMTP();

    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;

    // ใส่ Gmail ของคุณตรงนี้
    $mail->Username   = 'อีเมลของคุณ@gmail.com';

    // ใส่ App Password 16 ตัวที่ Google สร้างให้
    $mail->Password   = 'รหัส App Password ของคุณ';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;


    // =====================================================
    // ผู้ส่ง
    // =====================================================

    $mail->setFrom(
        'อีเมลของคุณ@gmail.com',
        'VELOURA PERFUMES'
    );


    // =====================================================
    // ผู้รับ
    // =====================================================

    // ทดลองส่งเข้าหาอีเมลของตัวเองก่อน
    $mail->addAddress(
        'อีเมลของคุณ@gmail.com',
        'Veloura Customer'
    );


    // =====================================================
    // ตั้งค่าภาษา
    // =====================================================

    $mail->CharSet = 'UTF-8';

    $mail->isHTML(true);


    // =====================================================
    // หัวข้ออีเมล
    // =====================================================

    $mail->Subject = '✦ ยินดีต้อนรับสู่ VELOURA PERFUMES ✦';


    // =====================================================
    // เนื้อหาอีเมล
    // =====================================================

    $mail->Body = '

    <!DOCTYPE html>

    <html lang="th">

    <head>

        <meta charset="UTF-8">

    </head>

    <body style="
        margin:0;
        padding:0;
        background:#f4efe9;
        font-family:Arial, Helvetica, sans-serif;
    ">

        <table
            width="100%"
            cellpadding="0"
            cellspacing="0"
            border="0"
            style="
                background:#f4efe9;
                padding:40px 15px;
            "
        >

            <tr>

                <td align="center">

                    <table
                        width="600"
                        cellpadding="0"
                        cellspacing="0"
                        border="0"
                        style="
                            max-width:600px;
                            width:100%;
                            background:#fffdfb;
                            border:1px solid #e3d7ca;
                            box-shadow:0 10px 35px rgba(50,40,30,.10);
                        "
                    >

                        <!-- TOP -->

                        <tr>

                            <td
                                align="center"
                                style="
                                    background:#2f2722;
                                    padding:28px 20px;
                                "
                            >

                                <div style="
                                    color:#d7b98e;
                                    font-size:11px;
                                    letter-spacing:4px;
                                    margin-bottom:10px;
                                ">

                                    ✦ VELOURA PERFUMES ✦

                                </div>

                                <div style="
                                    color:#ffffff;
                                    font-size:28px;
                                    letter-spacing:6px;
                                    font-family:Georgia,serif;
                                ">

                                    VELOURA

                                </div>

                                <div style="
                                    color:#cbbba8;
                                    font-size:9px;
                                    letter-spacing:5px;
                                    margin-top:8px;
                                ">

                                    PERFUMES

                                </div>

                            </td>

                        </tr>


                        <!-- CONTENT -->

                        <tr>

                            <td
                                style="
                                    padding:45px 45px 35px;
                                    text-align:center;
                                "
                            >

                                <div style="
                                    color:#a48662;
                                    font-size:10px;
                                    letter-spacing:3px;
                                    margin-bottom:14px;
                                ">

                                    WELCOME TO VELOURA

                                </div>


                                <h1 style="
                                    margin:0;
                                    color:#302721;
                                    font-family:Georgia,serif;
                                    font-size:34px;
                                    font-weight:normal;
                                ">

                                    ยินดีต้อนรับ

                                </h1>


                                <div style="
                                    width:45px;
                                    height:1px;
                                    background:#c5a47c;
                                    margin:22px auto;
                                ">

                                </div>


                                <p style="
                                    color:#574c44;
                                    font-size:15px;
                                    line-height:2;
                                    margin:0 0 15px;
                                ">

                                    สวัสดี คุณลูกค้า ✨

                                </p>


                                <p style="
                                    color:#746961;
                                    font-size:13px;
                                    line-height:2;
                                    margin:0;
                                ">

                                    ขอบคุณที่สมัครสมาชิกกับ
                                    <strong style="color:#5b4634;">
                                        Veloura Perfumes
                                    </strong>

                                    <br>

                                    ค้นพบกลิ่นหอมที่สะท้อนตัวตน
                                    <br>

                                    และสไตล์ของคุณ

                                </p>


                                <!-- GOLD BOX -->

                                <table
                                    width="100%"
                                    cellpadding="0"
                                    cellspacing="0"
                                    style="
                                        margin-top:30px;
                                        background:#faf6f0;
                                        border:1px solid #e5d8c9;
                                    "
                                >

                                    <tr>

                                        <td
                                            align="center"
                                            style="
                                                padding:25px 15px;
                                            "
                                        >

                                            <div style="
                                                color:#b18b5e;
                                                font-size:20px;
                                                margin-bottom:10px;
                                            ">

                                                ✦ ✧ ✦

                                            </div>


                                            <div style="
                                                color:#342a24;
                                                font-family:Georgia,serif;
                                                font-size:24px;
                                                letter-spacing:5px;
                                            ">

                                                VELOURA

                                            </div>


                                            <div style="
                                                color:#a38c76;
                                                font-size:9px;
                                                letter-spacing:5px;
                                                margin-top:7px;
                                            ">

                                                PERFUMES

                                            </div>


                                            <div style="
                                                color:#9b8878;
                                                font-size:11px;
                                                font-style:italic;
                                                margin-top:15px;
                                            ">

                                                Discover Your Signature Scent

                                            </div>

                                        </td>

                                    </tr>

                                </table>


                                <p style="
                                    color:#9b9088;
                                    font-size:11px;
                                    line-height:1.8;
                                    margin-top:25px;
                                ">

                                    ขอให้ทุกกลิ่นหอม
                                    <br>
                                    เป็นส่วนหนึ่งของเรื่องราวที่พิเศษของคุณ

                                </p>

                            </td>

                        </tr>


                        <!-- FOOTER -->

                        <tr>

                            <td
                                align="center"
                                style="
                                    background:#2f2722;
                                    padding:22px 15px;
                                "
                            >

                                <div style="
                                    color:#d7b98e;
                                    font-size:9px;
                                    letter-spacing:3px;
                                ">

                                    VELOURA PERFUMES

                                </div>

                                <div style="
                                    color:#8f8175;
                                    font-size:9px;
                                    margin-top:8px;
                                ">

                                    Discover Your Signature Scent

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


    // =====================================================
    // ข้อความสำหรับระบบที่ไม่รองรับ HTML
    // =====================================================

    $mail->AltBody = '
        ยินดีต้อนรับสู่ VELOURA PERFUMES

        ขอบคุณที่สมัครสมาชิกกับ Veloura Perfumes

        ค้นพบกลิ่นหอมที่สะท้อนตัวตน
        และสไตล์ของคุณ

        VELOURA PERFUMES
        Discover Your Signature Scent
    ';


    // =====================================================
    // ส่งอีเมล
    // =====================================================

    $mail->send();

    echo '
    <div style="
        font-family:Arial;
        text-align:center;
        margin-top:100px;
        color:#4d4036;
    ">

        <h1>✦ ส่งอีเมลสำเร็จ ✦</h1>

        <p>
            กรุณาเข้าไปตรวจสอบกล่องจดหมายของคุณ
        </p>

        <p>
            <a href="index.php">
                กลับหน้า Veloura
            </a>
        </p>

    </div>
    ';

} catch (Exception $e) {

    echo '
    <div style="
        font-family:Arial;
        max-width:700px;
        margin:80px auto;
        padding:30px;
        background:#fff5f3;
        border:1px solid #e5c7c0;
        color:#8b5148;
    ">

        <h2>ส่งอีเมลไม่สำเร็จ</h2>

        <p>
            ' . htmlspecialchars($mail->ErrorInfo) . '
        </p>

    </div>
    ';

}
?>
