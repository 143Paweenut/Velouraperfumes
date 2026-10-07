<?php
session_start();
require_once "connect.php";

/*
=====================================================
 VELOURA PERFUMES - FIND YOUR SCENT
 ระบบค้นหากลิ่นที่เหมาะกับลูกค้า
 - ภาษาไทย
 - ราคาเงินบาท
 - แนะนำ 3 กลิ่น
 - ส่งผลทาง Gmail ด้วย PHPMailer
 - ดึง Email จาก users ที่สมัครไว้
=====================================================
*/

// =====================================================
// ตรวจสอบ Login
// =====================================================

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: login.php?redirect=find-scent.php");
    exit;
}


// =====================================================
// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
// =====================================================

$user_id = intval($_SESSION["user_id"] ?? 0);

$user = null;

if ($user_id > 0) {

    $stmt = $conn->prepare("
        SELECT id, username, email, fullname
        FROM users
        WHERE id = ?
        LIMIT 1
    ");

    if ($stmt) {

        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
        }

        $stmt->close();
    }
}


// =====================================================
// ถ้าหา user ไม่เจอ ลองใช้ session
// =====================================================

if (!$user) {

    $user = [
        "id"       => $_SESSION["user_id"] ?? 0,
        "username" => $_SESSION["username"] ?? "",
        "email"    => $_SESSION["email"] ?? "",
        "fullname" => $_SESSION["fullname"] ?? ""
    ];
}


// =====================================================
// ข้อมูลลูกค้า
// =====================================================

$customer_name = trim($user["fullname"] ?? "");

if ($customer_name === "") {
    $customer_name = trim($user["username"] ?? "");
}

if ($customer_name === "") {
    $customer_name = "คุณลูกค้า";
}

$customer_email = trim($user["email"] ?? "");


// =====================================================
// ตัวแปร
// =====================================================

$recommendations = [];
$email_sent = false;
$error_message = "";
$success_message = "";


// =====================================================
// ฟังก์ชันเงินบาท
// =====================================================

function baht($price)
{
    return "฿" . number_format((float)$price, 2);
}


// =====================================================
// สร้าง URL เว็บไซต์
// =====================================================

$protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off")
    ? "https://"
    : "http://";

$host = $_SERVER["HTTP_HOST"] ?? "localhost";

$base_url = $protocol . $host . "/Veloura";


// =====================================================
// ฟังก์ชัน escape
// =====================================================

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}


// =====================================================
// เมื่อกดค้นหากลิ่น
// =====================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $favorite_notes = $_POST["favorite_notes"] ?? [];
    $perfume_time = $_POST["perfume_time"] ?? "";
    $weather = $_POST["weather"] ?? "";
    $budget = $_POST["budget"] ?? "";
    $buying_style = $_POST["buying_style"] ?? "";

    if (!is_array($favorite_notes)) {
        $favorite_notes = [];
    }


    // =================================================
    // คะแนนสินค้า
    // =================================================

    $scores = [

        "Veloura Essence" => 0,
        "Veloura Rose" => 0,
        "Veloura Noir" => 0,
        "Veloura Bloom" => 0,
        "Crimson Desire" => 0,
        "Midnight Allure" => 0,
        "Golden Elysium" => 0,
        "Veloura Lavender" => 0

    ];


    // =================================================
    // ให้คะแนนตาม Notes
    // =================================================

    foreach ($favorite_notes as $note) {

        switch ($note) {

            case "สดชื่น":
                $scores["Veloura Essence"] += 5;
                $scores["Veloura Bloom"] += 5;
                $scores["Golden Elysium"] += 3;
                break;

            case "ดอกไม้":
                $scores["Veloura Rose"] += 6;
                $scores["Veloura Bloom"] += 5;
                $scores["Veloura Lavender"] += 4;
                break;

            case "หวาน":
                $scores["Veloura Rose"] += 5;
                $scores["Crimson Desire"] += 6;
                $scores["Midnight Allure"] += 5;
                break;

            case "ไม้":
                $scores["Veloura Noir"] += 6;
                $scores["Golden Elysium"] += 5;
                $scores["Veloura Lavender"] += 3;
                break;

            case "เซ็กซี่":
                $scores["Crimson Desire"] += 7;
                $scores["Veloura Noir"] += 6;
                $scores["Midnight Allure"] += 6;
                break;

            case "หรูหรา":
                $scores["Golden Elysium"] += 7;
                $scores["Veloura Noir"] += 6;
                $scores["Midnight Allure"] += 5;
                break;

            case "วานิลลา":
                $scores["Veloura Rose"] += 4;
                $scores["Midnight Allure"] += 6;
                $scores["Crimson Desire"] += 5;
                break;

            case "มัสก์":
                $scores["Veloura Essence"] += 5;
                $scores["Golden Elysium"] += 4;
                break;
        }
    }


    // =================================================
    // ช่วงเวลาที่ใช้
    // =================================================

    switch ($perfume_time) {

        case "กลางวัน":
            $scores["Veloura Essence"] += 5;
            $scores["Veloura Bloom"] += 5;
            $scores["Golden Elysium"] += 2;
            break;

        case "กลางคืน":
            $scores["Veloura Noir"] += 5;
            $scores["Crimson Desire"] += 6;
            $scores["Midnight Allure"] += 6;
            break;

        case "ทุกเวลา":
            $scores["Veloura Essence"] += 3;
            $scores["Veloura Rose"] += 3;
            $scores["Golden Elysium"] += 3;
            break;
    }


    // =================================================
    // สภาพอากาศ
    // =================================================

    switch ($weather) {

        case "ร้อน":
            $scores["Veloura Essence"] += 5;
            $scores["Veloura Bloom"] += 5;
            $scores["Veloura Lavender"] += 3;
            break;

        case "เย็น":
            $scores["Veloura Noir"] += 5;
            $scores["Midnight Allure"] += 5;
            $scores["Golden Elysium"] += 4;
            break;

        case "ทุกสภาพอากาศ":
            $scores["Veloura Essence"] += 3;
            $scores["Veloura Rose"] += 3;
            $scores["Golden Elysium"] += 3;
            break;
    }


    // =================================================
    // งบประมาณ
    // =================================================

    if ($budget === "ไม่เกิน 60") {

        foreach ($scores as $name => $score) {
            if (in_array($name, [
                "Veloura Essence"
            ])) {
                $scores[$name] += 5;
            }
        }

    } elseif ($budget === "61-70") {

        foreach ($scores as $name => $score) {
            if (in_array($name, [
                "Veloura Rose",
                "Veloura Noir",
                "Veloura Bloom",
                "Midnight Allure",
                "Veloura Lavender"
            ])) {
                $scores[$name] += 5;
            }
        }

    } elseif ($budget === "71-80") {

        foreach ($scores as $name => $score) {
            if (in_array($name, [
                "Crimson Desire",
                "Golden Elysium"
            ])) {
                $scores[$name] += 6;
            }
        }
    }


    // =================================================
    // สไตล์การซื้อ
    // =================================================

    switch ($buying_style) {

        case "ใช้ทุกวัน":
            $scores["Veloura Essence"] += 5;
            $scores["Veloura Bloom"] += 5;
            break;

        case "ออกเดท":
            $scores["Crimson Desire"] += 7;
            $scores["Midnight Allure"] += 6;
            break;

        case "ทำงาน":
            $scores["Veloura Rose"] += 4;
            $scores["Golden Elysium"] += 5;
            $scores["Veloura Essence"] += 4;
            break;

        case "ปาร์ตี้":
            $scores["Veloura Noir"] += 6;
            $scores["Crimson Desire"] += 7;
            break;

        case "โอกาสพิเศษ":
            $scores["Golden Elysium"] += 7;
            $scores["Veloura Noir"] += 6;
            break;
    }


    // =================================================
    // เรียงคะแนน
    // =================================================

    arsort($scores);

    $top_names = array_slice(array_keys($scores), 0, 3);


    // =================================================
    // ดึงสินค้าจาก Database
    // =================================================

    $placeholders = implode(",", array_fill(0, count($top_names), "?"));

    $sql = "
        SELECT id, name, price, image, description
        FROM products
        WHERE name IN ($placeholders)
    ";

    $stmt = $conn->prepare($sql);

    if ($stmt) {

        $types = str_repeat("s", count($top_names));

        $stmt->bind_param(
            $types,
            ...$top_names
        );

        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {

            $row["score"] = $scores[$row["name"]] ?? 0;

            $recommendations[$row["name"]] = $row;
        }

        $stmt->close();
    }


    // =================================================
    // จัดลำดับให้ตรงกับคะแนน
    // =================================================

    $ordered = [];

    foreach ($top_names as $name) {

        if (isset($recommendations[$name])) {
            $ordered[] = $recommendations[$name];
        }
    }

    $recommendations = $ordered;


    // =================================================
    // ถ้ามีสินค้าน้อยกว่า 3
    // =================================================

    if (count($recommendations) < 3) {

        $sql = "
            SELECT id, name, price, image, description
            FROM products
            ORDER BY id DESC
            LIMIT 3
        ";

        $result = $conn->query($sql);

        if ($result) {

            while ($row = $result->fetch_assoc()) {

                $already_exists = false;

                foreach ($recommendations as $existing) {

                    if ($existing["id"] == $row["id"]) {
                        $already_exists = true;
                        break;
                    }
                }

                if (!$already_exists) {
                    $recommendations[] = $row;
                }

                if (count($recommendations) >= 3) {
                    break;
                }
            }
        }
    }


    // =================================================
    // ส่ง Email
    // =================================================

    if (!empty($customer_email) && count($recommendations) > 0) {

        /*
        =================================================
        PHPMailer
        ต้องมีโฟลเดอร์

        PHPMailer/
        └── src/
            ├── Exception.php
            ├── PHPMailer.php
            └── SMTP.php

        หรือใช้ Composer:
        vendor/autoload.php
        =================================================
        */

        $autoload = __DIR__ . "/vendor/autoload.php";

        if (file_exists($autoload)) {

            require_once $autoload;

        } elseif (file_exists(__DIR__ . "/PHPMailer/src/Exception.php")) {

            require_once __DIR__ . "/PHPMailer/src/Exception.php";
            require_once __DIR__ . "/PHPMailer/src/PHPMailer.php";
            require_once __DIR__ . "/PHPMailer/src/SMTP.php";

        } else {

            $error_message = "ยังไม่พบ PHPMailer กรุณาติดตั้ง PHPMailer ก่อน";

        }


        if ($error_message === "") {

            try {

                $mail = new PHPMailer\PHPMailer\PHPMailer(true);

                // =========================================
                // SMTP Gmail
                // =========================================

                $mail->isSMTP();

                $mail->Host = "smtp.gmail.com";

                $mail->SMTPAuth = true;

                /*
                ==========================================
                ใส่ Gmail ของร้าน
                ==========================================
                */

                $mail->Username = "paweenutnamdaeng009@gmail.com";

                /*
                ==========================================
                ใส่ Gmail App Password 16 ตัว
                ห้ามใส่รหัส Gmail ปกติ
                ==========================================
                */

                $mail->Password = "ddzs fejn nvna vboe";

                $mail->SMTPSecure =
                    PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;

                $mail->Port = 587;

                $mail->CharSet = "UTF-8";


                // =========================================
                // ผู้ส่ง
                // =========================================

                $mail->setFrom(
                    "paweenutnamdaeng009@gmail.com",
                    "VELOURA PERFUMES"
                );


                // =========================================
                // ผู้รับ = Email ที่สมัครสมาชิก
                // =========================================

                $mail->addAddress(
                    $customer_email,
                    $customer_name
                );


                // =========================================
                // หัวข้อ
                // =========================================

                $mail->Subject =
                    "✨ ผลลัพธ์กลิ่นที่เหมาะกับคุณ | VELOURA PERFUMES";


                // =========================================
                // Logo / Header
                // =========================================

                $email_html = '

<!DOCTYPE html>

<html lang="th">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>VELOURA PERFUMES</title>

</head>

<body style="
margin:0;
padding:0;
background:#f7eee9;
font-family:Arial,Helvetica,sans-serif;
color:#38252d;
">

<table width="100%" cellpadding="0" cellspacing="0">

<tr>

<td align="center" style="padding:30px 10px;">

<table width="680" cellpadding="0" cellspacing="0"
style="
max-width:680px;
background:#ffffff;
border-radius:24px;
overflow:hidden;
box-shadow:0 10px 35px rgba(70,40,50,.12);
">

<!-- HEADER -->

<tr>

<td align="center"
style="
padding:42px 25px;
background:linear-gradient(135deg,#f8e3df,#ead1d7,#f7e9df);
">

<div style="
font-size:34px;
letter-spacing:7px;
font-weight:bold;
color:#5c3444;
">

VELOURA

</div>

<div style="
font-size:13px;
letter-spacing:5px;
margin-top:8px;
color:#765664;
">

PERFUMES

</div>

<div style="
margin-top:20px;
font-size:24px;
font-weight:bold;
color:#452a35;
">

✨ YOUR SIGNATURE SCENT ✨

</div>

</td>

</tr>


<!-- INTRO -->

<tr>

<td style="padding:35px 35px 15px;">

<div style="
font-size:22px;
font-weight:bold;
color:#4a2c38;
">

สวัสดีคุณ ' . e($customer_name) . ' 💕
</div>

<p style="
font-size:15px;
line-height:1.9;
color:#66555c;
">

เราได้วิเคราะห์สไตล์และความชอบของคุณแล้ว
และคัดเลือกน้ำหอมจาก <b>VELOURA PERFUMES</b>
ที่คิดว่าน่าจะเข้ากับตัวคุณมากที่สุดมาให้แล้ว ✨

</p>

</td>

</tr>

';


                // =========================================
                // Product Cards
                // =========================================

                foreach ($recommendations as $index => $product) {

                    $product_id = intval($product["id"]);

                    $product_name = $product["name"];

                    $product_price = baht($product["price"]);

                    $product_description =
                        $product["description"] ?? "";

                    if ($product_description === "") {

                        $product_description =
                            "กลิ่นหอมที่คัดสรรมาเพื่อสะท้อนตัวตนของคุณ";

                    }


                    // URL สินค้า

                    $product_url =
                        $base_url .
                        "/product_detail.php?id=" .
                        $product_id;


                    // =====================================
                    // รูปสินค้า
                    // =====================================

                    $image_name = basename(
                        $product["image"] ?? ""
                    );

                    $image_path =
                        __DIR__ .
                        "/images/" .
                        $image_name;


                    $cid = "product_" . $product_id;


                    if (
                        $image_name !== "" &&
                        file_exists($image_path)
                    ) {

                        $mail->addEmbeddedImage(
                            $image_path,
                            $cid,
                            $image_name
                        );

                        $image_src =
                            "cid:" . $cid;

                    } else {

                        $image_src =
                            $base_url .
                            "/images/perfume1.jpg";
                    }


                    $rank = $index + 1;


                    $email_html .= '

<!-- PRODUCT -->

<tr>

<td style="padding:12px 30px;">

<table width="100%" cellpadding="0" cellspacing="0"
style="
border:1px solid #eadde0;
border-radius:18px;
overflow:hidden;
background:#fffaf9;
">

<tr>

<td width="230"
align="center"
style="
padding:20px;
">

<img src="' . $image_src . '"
width="190"
style="
display:block;
max-width:190px;
height:auto;
border-radius:14px;
"
alt="' . e($product_name) . '">

</td>


<td style="padding:25px 20px 25px 5px;">

<div style="
font-size:12px;
color:#a47b89;
letter-spacing:2px;
font-weight:bold;
">

RECOMMENDED #' . $rank . '

</div>

<div style="
font-size:21px;
font-weight:bold;
color:#4a2b38;
margin-top:7px;
">

' . e($product_name) . '

</div>

<div style="
font-size:20px;
font-weight:bold;
color:#9b5b70;
margin-top:8px;
">

' . $product_price . '

</div>

<div style="
font-size:13px;
line-height:1.8;
color:#6e6065;
margin-top:10px;
">

' . e($product_description) . '

</div>

<div style="
margin-top:18px;
">

<a href="' . $product_url . '"
style="
display:inline-block;
padding:11px 20px;
background:#6b3c4e;
color:#ffffff;
text-decoration:none;
border-radius:30px;
font-size:13px;
font-weight:bold;
">

ดูรายละเอียดสินค้า ✨

</a>

</div>

</td>

</tr>

</table>

</td>

</tr>

';

                }


                // =========================================
                // Footer
                // =========================================

                $email_html .= '

<tr>

<td style="padding:30px 35px 40px;">

<div style="
background:#f8eef0;
border-radius:18px;
padding:22px;
text-align:center;
">

<div style="
font-size:18px;
font-weight:bold;
color:#50303d;
">

🌸 กลิ่นหอมที่เป็นคุณ

</div>

<div style="
font-size:13px;
line-height:1.8;
color:#75656b;
margin-top:8px;
">

เพราะน้ำหอมที่ดีที่สุด
คือกลิ่นที่ทำให้คุณรู้สึกเป็นตัวเองมากที่สุด

</div>

</div>

</td>

</tr>


<tr>

<td align="center"
style="
padding:25px;
background:#402936;
color:#ffffff;
">

<div style="
font-size:20px;
letter-spacing:5px;
font-weight:bold;
">

VELOURA

</div>

<div style="
font-size:11px;
letter-spacing:3px;
margin-top:7px;
color:#e5cfd7;
">

LUXURY PERFUMES

</div>

<div style="
font-size:11px;
margin-top:15px;
color:#cdb9c1;
">

© ' . date("Y") . ' VELOURA PERFUMES

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


                // =========================================
                // ส่ง Email
                // =========================================

                $mail->isHTML(true);

                $mail->Body = $email_html;

                $mail->AltBody =
                    "ผลลัพธ์กลิ่นที่เหมาะกับคุณจาก VELOURA PERFUMES";


                $mail->send();

                $email_sent = true;

                $success_message =
                    "ส่งผลลัพธ์กลิ่นไปที่ " .
                    $customer_email .
                    " เรียบร้อยแล้ว 💌";


            } catch (Exception $e) {

                $error_message =
                    "ไม่สามารถส่งอีเมลได้: " .
                    $mail->ErrorInfo;

            }

        }

    } else {

        if (empty($customer_email)) {

            $error_message =
                "ไม่พบอีเมลของบัญชีนี้ กรุณาตรวจสอบข้อมูลสมาชิก";

        } elseif (count($recommendations) === 0) {

            $error_message =
                "ไม่พบสินค้าที่สามารถแนะนำได้";

        }
    }
}

?>

<!DOCTYPE html>

<html lang="th">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>ค้นหากลิ่นที่ใช่ | VELOURA PERFUMES</title>


<!-- Google Fonts -->

<link rel="preconnect"
href="https://fonts.googleapis.com">

<link rel="preconnect"
href="https://fonts.gstatic.com"
crossorigin>

<link href="
https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&family=Noto+Sans+Thai:wght@400;500;600;700&display=swap"
rel="stylesheet">


<style>

* {
    box-sizing:border-box;
}

body {

    margin:0;

    font-family:
        "Noto Sans Thai",
        "Montserrat",
        sans-serif;

    background:
        radial-gradient(
            circle at top left,
            #fff7f5,
            transparent 35%
        ),
        linear-gradient(
            135deg,
            #f7ebe8,
            #f5dfe3,
            #eee0df
        );

    color:#422c35;

    min-height:100vh;
}


/* =========================================
   HEADER
========================================= */

.navbar {

    height:80px;

    display:flex;

    align-items:center;

    justify-content:space-between;

    padding:0 7%;

    background:
        rgba(255,255,255,.82);

    backdrop-filter:blur(15px);

    border-bottom:
        1px solid rgba(100,60,70,.08);

    position:sticky;

    top:0;

    z-index:20;

}


.logo {

    text-decoration:none;

    color:#593544;

}


.logo-main {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:32px;

    font-weight:700;

    letter-spacing:6px;

}


.logo-sub {

    font-size:9px;

    letter-spacing:4px;

    margin-left:4px;

}


.nav-right {

    display:flex;

    align-items:center;

    gap:15px;

}


.nav-user {

    color:#765968;

    font-size:14px;

}


.back-btn {

    text-decoration:none;

    padding:9px 18px;

    border-radius:30px;

    border:1px solid #d9b9c4;

    color:#65404f;

    font-size:13px;

    transition:.3s;

}


.back-btn:hover {

    background:#65404f;

    color:white;

}


/* =========================================
   HERO
========================================= */

.hero {

    max-width:1000px;

    margin:55px auto 25px;

    padding:0 25px;

    text-align:center;

}


.eyebrow {

    font-size:12px;

    letter-spacing:5px;

    color:#9c6b7b;

    font-weight:600;

}


.hero h1 {

    margin:10px 0;

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:60px;

    line-height:1;

    color:#4d2c39;

}


.hero p {

    max-width:650px;

    margin:20px auto;

    line-height:1.9;

    color:#74636a;

    font-size:15px;

}


/* =========================================
   FORM
========================================= */

.container {

    max-width:920px;

    margin:auto;

    padding:20px 25px 80px;

}


.card {

    background:
        rgba(255,255,255,.92);

    border-radius:30px;

    padding:40px;

    box-shadow:
        0 20px 60px rgba(85,45,60,.12);

    border:
        1px solid rgba(120,80,90,.08);

}


.section {

    margin-bottom:38px;

}


.section-title {

    display:flex;

    align-items:center;

    gap:10px;

    font-size:20px;

    font-weight:700;

    color:#51323e;

    margin-bottom:18px;

}


.options {

    display:grid;

    grid-template-columns:
        repeat(2,1fr);

    gap:12px;

}


.option {

    position:relative;

}


.option input {

    position:absolute;

    opacity:0;

}


.option label {

    display:block;

    padding:17px;

    border:1px solid #ead8dd;

    border-radius:18px;

    cursor:pointer;

    transition:.25s;

    background:#fffafa;

    font-size:14px;

}


.option label:hover {

    border-color:#b98697;

    transform:translateY(-2px);

}


.option input:checked + label {

    background:
        linear-gradient(
            135deg,
            #f3dce2,
            #f9eeee
        );

    border-color:#9d6578;

    box-shadow:
        0 7px 20px rgba(120,70,85,.10);

}


/* =========================================
   BUTTON
========================================= */

.submit-btn {

    width:100%;

    border:0;

    padding:18px;

    border-radius:50px;

    background:
        linear-gradient(
            135deg,
            #633c4c,
            #9a6074
        );

    color:white;

    font-size:16px;

    font-family:inherit;

    font-weight:700;

    cursor:pointer;

    box-shadow:
        0 12px 30px rgba(92,52,68,.25);

    transition:.3s;

}


.submit-btn:hover {

    transform:translateY(-3px);

    box-shadow:
        0 18px 35px rgba(92,52,68,.32);

}


/* =========================================
   MESSAGE
========================================= */

.message {

    margin-bottom:25px;

    padding:18px 20px;

    border-radius:18px;

    text-align:center;

    font-size:14px;

}


.success {

    background:#edf8f1;

    color:#286440;

    border:1px solid #cce8d5;

}


.error {

    background:#fff0f0;

    color:#9a4141;

    border:1px solid #efcccc;

}


/* =========================================
   RESULTS
========================================= */

.results {

    margin-top:45px;

}


.results-title {

    text-align:center;

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:46px;

    color:#4d2c39;

}


.results-sub {

    text-align:center;

    color:#78676d;

    font-size:14px;

    margin-bottom:30px;

}


.products {

    display:grid;

    grid-template-columns:
        repeat(3,1fr);

    gap:20px;

}


.product {

    background:white;

    border-radius:24px;

    overflow:hidden;

    box-shadow:
        0 15px 35px rgba(70,40,50,.10);

    transition:.3s;

}


.product:hover {

    transform:translateY(-7px);

}


.product-img {

    width:100%;

    height:270px;

    object-fit:cover;

    background:#f7eeee;

}


.product-body {

    padding:23px;

}


.rank {

    font-size:11px;

    color:#a56d80;

    letter-spacing:2px;

    font-weight:bold;

}


.product h3 {

    margin:7px 0;

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:28px;

    color:#4c2d39;

}


.price {

    font-size:20px;

    color:#995c70;

    font-weight:700;

}


.desc {

    color:#75666b;

    font-size:13px;

    line-height:1.8;

    min-height:70px;

}


.view-product {

    display:block;

    text-align:center;

    text-decoration:none;

    margin-top:18px;

    padding:11px;

    border-radius:30px;

    background:#5e3949;

    color:white;

    font-size:13px;

    font-weight:600;

}


.view-product:hover {

    background:#402633;

}


/* =========================================
   EMAIL NOTICE
========================================= */

.email-notice {

    margin-top:30px;

    padding:22px;

    background:
        linear-gradient(
            135deg,
            #fbf0f3,
            #f9e7ec
        );

    border-radius:20px;

    text-align:center;

    color:#654552;

    font-size:14px;

}


.email {

    font-weight:700;

    color:#8d566a;

}


/* =========================================
   RESPONSIVE
========================================= */

@media(max-width:750px) {

    .hero h1 {
        font-size:45px;
    }

    .card {
        padding:25px 20px;
    }

    .options {
        grid-template-columns:1fr;
    }

    .products {
        grid-template-columns:1fr;
    }

    .navbar {
        padding:0 20px;
    }

    .nav-user {
        display:none;
    }

}

</style>

</head>


<body>


<!-- =========================================
     NAVBAR
========================================= -->

<header class="navbar">

<a href="index.php" class="logo">

<div class="logo-main">
VELOURA
</div>

<div class="logo-sub">
PERFUMES
</div>

</a>


<div class="nav-right">

<div class="nav-user">

♡ <?= e($customer_name) ?>

</div>

<a href="index.php" class="back-btn">
กลับหน้าหลัก
</a>

</div>

</header>



<!-- =========================================
     HERO
========================================= -->

<section class="hero">

<div class="eyebrow">
FIND YOUR SIGNATURE SCENT
</div>

<h1>
ค้นหากลิ่นที่ใช่สำหรับคุณ ✨
</h1>

<p>

ตอบคำถามสั้น ๆ แล้วให้ VELOURA
ช่วยค้นหาน้ำหอมที่เข้ากับบุคลิก
ไลฟ์สไตล์ และช่วงเวลาของคุณ
พร้อมคัดมาให้ถึง <b>3 กลิ่น</b> 💕🌸

</p>

</section>



<div class="container">

<?php if ($success_message): ?>

<div class="message success">

<?= e($success_message) ?>

</div>

<?php endif; ?>


<?php if ($error_message): ?>

<div class="message error">

<?= e($error_message) ?>

</div>

<?php endif; ?>


<!-- =========================================
     FORM
========================================= -->

<div class="card">

<form method="POST">


<!-- NOTES -->

<div class="section">

<div class="section-title">
🌸 คุณชอบโทนกลิ่นแบบไหน?
</div>


<div class="options">

<div class="option">

<input
type="checkbox"
name="favorite_notes[]"
value="สดชื่น"
id="note1">

<label for="note1">
🍋 สดชื่น สะอาด มีชีวิตชีวา
</label>

</div>


<div class="option">

<input
type="checkbox"
name="favorite_notes[]"
value="ดอกไม้"
id="note2">

<label for="note2">
🌹 ดอกไม้ หอมละมุน โรแมนติก
</label>

</div>


<div class="option">

<input
type="checkbox"
name="favorite_notes[]"
value="หวาน"
id="note3">

<label for="note3">
🍰 หวาน น่ารัก ชวนหลงใหล
</label>

</div>


<div class="option">

<input
type="checkbox"
name="favorite_notes[]"
value="ไม้"
id="note4">

<label for="note4">
🌲 ไม้ อบอุ่น สุขุม
</label>

</div>


<div class="option">

<input
type="checkbox"
name="favorite_notes[]"
value="เซ็กซี่"
id="note5">

<label for="note5">
💋 เซ็กซี่ น่าค้นหา เย้ายวน
</label>

</div>


<div class="option">

<input
type="checkbox"
name="favorite_notes[]"
value="หรูหรา"
id="note6">

<label for="note6">
✨ หรูหรา ดูแพง มีเสน่ห์
</label>

</div>


<div class="option">

<input
type="checkbox"
name="favorite_notes[]"
value="วานิลลา"
id="note7">

<label for="note7">
🍦 วานิลลา นุ่มละมุน
</label>

</div>


<div class="option">

<input
type="checkbox"
name="favorite_notes[]"
value="มัสก์"
id="note8">

<label for="note8">
🤍 มัสก์ สะอาด นุ่มนวล
</label>

</div>

</div>

</div>



<!-- TIME -->

<div class="section">

<div class="section-title">
🕰️ คุณมักใช้น้ำหอมช่วงไหน?
</div>


<div class="options">

<div class="option">

<input
type="radio"
name="perfume_time"
value="กลางวัน"
id="time1"
required>

<label for="time1">
☀️ กลางวัน
</label>

</div>


<div class="option">

<input
type="radio"
name="perfume_time"
value="กลางคืน"
id="time2">

<label for="time2">
🌙 กลางคืน
</label>

</div>


<div class="option">

<input
type="radio"
name="perfume_time"
value="ทุกเวลา"
id="time3">

<label for="time3">
✨ ได้ทุกเวลา
</label>

</div>

</div>

</div>



<!-- WEATHER -->

<div class="section">

<div class="section-title">
🌤️ สภาพอากาศที่คุณอยู่บ่อย ๆ
</div>


<div class="options">

<div class="option">

<input
type="radio"
name="weather"
value="ร้อน"
id="weather1"
required>

<label for="weather1">
☀️ อากาศร้อน
</label>

</div>


<div class="option">

<input
type="radio"
name="weather"
value="เย็น"
id="weather2">

<label for="weather2">
❄️ อากาศเย็น
</label>

</div>


<div class="option">

<input
type="radio"
name="weather"
value="ทุกสภาพอากาศ"
id="weather3">

<label for="weather3">
🌤️ ทุกสภาพอากาศ
</label>

</div>

</div>

</div>



<!-- BUDGET -->

<div class="section">

<div class="section-title">
💰 งบประมาณที่คุณต้องการ
</div>


<div class="options">

<div class="option">

<input
type="radio"
name="budget"
value="ไม่เกิน 60"
id="budget1"
required>

<label for="budget1">
💵 ไม่เกิน ฿60
</label>

</div>


<div class="option">

<input
type="radio"
name="budget"
value="61-70"
id="budget2">

<label for="budget2">
💎 ฿61 – ฿70
</label>

</div>


<div class="option">

<input
type="radio"
name="budget"
value="71-80"
id="budget3">

<label for="budget3">
👑 ฿71 – ฿80
</label>

</div>

</div>

</div>



<!-- STYLE -->

<div class="section">

<div class="section-title">
💫 คุณจะใช้น้ำหอมในโอกาสไหนมากที่สุด?
</div>


<div class="options">

<div class="option">

<input
type="radio"
name="buying_style"
value="ใช้ทุกวัน"
id="style1"
required>

<label for="style1">
🌸 ใช้ทุกวัน
</label>

</div>


<div class="option">

<input
type="radio"
name="buying_style"
value="ออกเดท"
id="style2">

<label for="style2">
💕 ออกเดท
</label>

</div>


<div class="option">

<input
type="radio"
name="buying_style"
value="ทำงาน"
id="style3">

<label for="style3">
💼 ไปทำงาน
</label>

</div>


<div class="option">

<input
type="radio"
name="buying_style"
value="ปาร์ตี้"
id="style4">

<label for="style4">
🥂 ปาร์ตี้
</label>

</div>


<div class="option">

<input
type="radio"
name="buying_style"
value="โอกาสพิเศษ"
id="style5">

<label for="style5">
👑 โอกาสพิเศษ
</label>

</div>

</div>

</div>



<button
type="submit"
class="submit-btn">

✨ ค้นหากลิ่นที่ใช่สำหรับฉัน ✨

</button>


</form>

</div>



<!-- =========================================
     RESULTS
========================================= -->

<?php if (!empty($recommendations)): ?>

<div class="results">

<div class="results-title">
กลิ่นที่เราเลือกให้คุณ 💕
</div>

<div class="results-sub">

เราเลือก 3 กลิ่นที่คิดว่าเหมาะกับคุณที่สุด
จากสไตล์และความชอบที่คุณเลือก ✨

</div>


<div class="products">


<?php foreach ($recommendations as $index => $product): ?>

<?php

$image =
    trim($product["image"] ?? "");

if ($image === "") {
    $image = "perfume1.jpg";
}

$image_url =
    "images/" . $image;

?>


<div class="product">


<img
src="<?= e($image_url) ?>"
class="product-img"
alt="<?= e($product["name"]) ?>"
onerror="this.src='images/perfume1.jpg';">


<div class="product-body">


<div class="rank">

RECOMMENDED #<?= $index + 1 ?>

</div>


<h3>
<?= e($product["name"]) ?>
</h3>


<div class="price">

<?= baht($product["price"]) ?>

</div>


<p class="desc">

<?= e(
    $product["description"]
    ?: "กลิ่นหอมที่คัดสรรมาเพื่อสะท้อนตัวตนของคุณ"
) ?>

</p>


<a
href="product_detail.php?id=<?= intval($product["id"]) ?>"
class="view-product">

ดูสินค้า ✨

</a>


</div>

</div>


<?php endforeach; ?>

</div>


<?php if ($email_sent): ?>

<div class="email-notice">

💌 <b>ส่งผลลัพธ์ไปทางอีเมลแล้ว!</b>

<br>

เราได้ส่งรายละเอียดกลิ่นที่แนะนำทั้ง 3 กลิ่น
พร้อมรูปสินค้าและปุ่มดูสินค้าไปที่

<div class="email">

<?= e($customer_email) ?>

</div>

</div>

<?php else: ?>

<div class="email-notice">

📧 ผลลัพธ์ของคุณจะถูกส่งไปยังอีเมลสมาชิก

<div class="email">

<?= e($customer_email) ?>

</div>

</div>

<?php endif; ?>


</div>

<?php endif; ?>

</div>


</body>

</html>