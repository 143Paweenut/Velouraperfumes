<?php
session_start();
require_once "connect.php";

/* =========================
   ตรวจสอบตะกร้า
========================= */
if (!isset($_SESSION["cart"]) || !is_array($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

/* จำนวนสินค้าในรถเข็น */
$cart_count = 0;

foreach ($_SESSION["cart"] as $quantity) {
    $cart_count += intval($quantity);
}

/* =========================
   ดึงสินค้าคอลเลกชันแนะนำ
========================= */
$featured_products = [];

$sql = "SELECT id, name, price, image, description
        FROM products
        ORDER BY id DESC
        LIMIT 3";

$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $featured_products[] = $row;
    }
}

/* =========================
   สินค้าใหม่ล่าสุด
========================= */
$new_product = null;

$sql_new = "SELECT id, name, price, image, description
            FROM products
            ORDER BY id DESC
            LIMIT 1";

$result_new = $conn->query($sql_new);

if ($result_new && $result_new->num_rows > 0) {
    $new_product = $result_new->fetch_assoc();
}

/* ค่าเริ่มต้นสินค้าใหม่ */
$new_name = "VELOURA ESSENCE";
$new_price = "1,290";
$new_image = "images/perfume-hero.jpg";
$new_description = "กลิ่นหอมละมุน เรียบหรู และมีเสน่ห์ในแบบ VELOURA";

if ($new_product) {
    $new_name = $new_product["name"];
    $new_price = number_format((float)$new_product["price"]);
    $new_image = !empty($new_product["image"])
        ? $new_product["image"]
        : "images/perfume-hero.jpg";

    $new_description = !empty($new_product["description"])
        ? $new_product["description"]
        : $new_description;
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="description"
          content="VELOURA PERFUMES น้ำหอมคุณภาพในสไตล์หรูหรา ค้นหากลิ่นที่เหมาะกับตัวคุณ">

    <meta name="keywords"
          content="VELOURA, perfume, น้ำหอม, น้ำหอมผู้หญิง, น้ำหอมผู้ชาย, น้ำหอมออนไลน์, luxury perfume">

    <title>VELOURA PERFUMES | น้ำหอมที่เป็นตัวคุณ</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&family=Noto+Sans+Thai:wght@300;400;500;600&display=swap"
          rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        html{
            scroll-behavior:smooth;
        }

        body{
            font-family:"Noto Sans Thai","Montserrat",sans-serif;
            background:#fffafc;
            color:#4b303b;
            overflow-x:hidden;
        }

        a{
            text-decoration:none;
            color:inherit;
        }

        img{
            max-width:100%;
        }

        /* =========================
           SPARKLES
        ========================= */

        .sparkle{
            position:fixed;
            width:4px;
            height:4px;
            background:#e8a9bd;
            border-radius:50%;
            opacity:.45;
            animation:sparkle 4s infinite ease-in-out;
            pointer-events:none;
            z-index:0;
        }

        .sparkle:nth-child(1){
            top:20%;
            left:10%;
            animation-delay:0s;
        }

        .sparkle:nth-child(2){
            top:40%;
            left:85%;
            animation-delay:1s;
        }

        .sparkle:nth-child(3){
            top:70%;
            left:20%;
            animation-delay:2s;
        }

        .sparkle:nth-child(4){
            top:80%;
            left:75%;
            animation-delay:3s;
        }

        @keyframes sparkle{

            0%,100%{
                transform:scale(.5);
                opacity:.2;
            }

            50%{
                transform:scale(1.5);
                opacity:.8;
            }

        }

        /* =========================
           NAVBAR
        ========================= */

        .navbar{
            position:sticky;
            top:0;
            z-index:1000;
            height:78px;
            background:rgba(255,250,252,.92);
            backdrop-filter:blur(15px);
            border-bottom:1px solid rgba(120,75,90,.08);
            display:flex;
            align-items:center;
            justify-content:center;
            padding:0 6%;
        }

        .nav-inner{
            width:100%;
            max-width:1250px;
            display:flex;
            align-items:center;
            justify-content:space-between;
        }

        .logo{
            font-family:"Cormorant Garamond",serif;
            font-size:36px;
            font-weight:600;
            letter-spacing:5px;
            color:#593844;
        }

        .nav-links{
            display:flex;
            align-items:center;
            gap:34px;
            font-size:13px;
            color:#684651;
        }

        .nav-links a{
            position:relative;
            transition:.3s;
        }

        .nav-links a::after{
            content:"";
            position:absolute;
            left:0;
            bottom:-7px;
            width:0;
            height:1px;
            background:#b66d88;
            transition:.3s;
        }

        .nav-links a:hover{
            color:#a35370;
        }

        .nav-links a:hover::after{
            width:100%;
        }

        .nav-icons{
            display:flex;
            align-items:center;
            gap:20px;
            font-size:17px;
        }

        .nav-icons a{
            transition:.3s;
        }

        .nav-icons a:hover{
            color:#b35f7a;
            transform:translateY(-2px);
        }

        .cart{
            position:relative;
        }

        .cart-count{
            position:absolute;
            top:-10px;
            right:-12px;
            min-width:17px;
            height:17px;
            padding:0 4px;
            border-radius:50px;
            background:#a95b78;
            color:white;
            font-size:9px;
            display:flex;
            align-items:center;
            justify-content:center;
        }

        /* =========================
           HERO
        ========================= */

        .hero{
            min-height:650px;
            display:grid;
            grid-template-columns:1fr 1fr;
            background:#f9eef2;
        }

        .hero-content{
            display:flex;
            flex-direction:column;
            justify-content:center;
            padding:80px 10%;
            position:relative;
            z-index:2;
        }

        .hero-small{
            font-family:"Montserrat",sans-serif;
            font-size:11px;
            letter-spacing:5px;
            text-transform:uppercase;
            color:#9b6474;
            margin-bottom:22px;
        }

        .hero h1{
            font-family:"Cormorant Garamond",serif;
            font-size:82px;
            line-height:.9;
            font-weight:500;
            color:#56333f;
            margin-bottom:30px;
        }

        .hero h1 span{
            font-style:italic;
            color:#a95e7b;
        }

        .hero-description{
            max-width:480px;
            font-size:14px;
            line-height:2;
            color:#765660;
            margin-bottom:38px;
        }

        .hero-btn{
            width:max-content;
            padding:15px 32px;
            border:1px solid #8f5269;
            color:#713f50;
            font-size:12px;
            letter-spacing:2px;
            transition:.35s;
        }

        .hero-btn:hover{
            background:#713f50;
            color:#fff;
        }

        .hero-image{
            position:relative;
            min-height:650px;
            overflow:hidden;
        }

        .hero-image img{
            width:100%;
            height:100%;
            object-fit:cover;
            display:block;
        }

        .hero-image::after{
            content:"";
            position:absolute;
            inset:0;
            background:linear-gradient(
                90deg,
                rgba(249,238,242,.15),
                transparent 40%
            );
        }

        /* =========================
           INTRO
        ========================= */

        .intro{
            padding:110px 20px 90px;
            text-align:center;
            background:#fff;
        }

        .section-label{
            font-family:"Montserrat",sans-serif;
            font-size:10px;
            letter-spacing:4px;
            color:#ae6a80;
            margin-bottom:15px;
            text-transform:uppercase;
        }

        .intro h2{
            font-family:"Cormorant Garamond",serif;
            font-size:54px;
            font-weight:500;
            color:#593844;
            margin-bottom:25px;
        }

        .intro p{
            max-width:700px;
            margin:auto;
            color:#80626c;
            font-size:14px;
            line-height:2;
        }

        /* =========================
           COLLECTION
        ========================= */

        .collection{
            background:#fff;
            padding:25px 7% 100px;
        }

        .section-head{
            max-width:1120px;
            margin:0 auto 45px;
            text-align:center;
        }

        .section-head h2{
            font-family:"Cormorant Garamond",serif;
            font-size:56px;
            font-weight:500;
            color:#593844;
        }

        .section-head p{
            margin-top:8px;
            font-size:13px;
            color:#92727d;
        }

        /* =========================
           DESKTOP COLLECTION
           กลับมาใหญ่เหมือนเดิม
        ========================= */

        .products{
            display:grid;
            grid-template-columns:repeat(3,1fr);
            gap:30px;
            max-width:1120px;
            margin:auto;
        }

        .product{
            display:block;
            position:relative;
            transition:.35s;
            padding:0 0 5px;
        }

        .product:hover{
            transform:translateY(-5px);
        }

        .product-image{
            aspect-ratio:4/5;
            background:#f7eef1;
            overflow:hidden;
            position:relative;
            z-index:1;
            box-shadow:0 12px 28px rgba(95,57,70,.08);
        }

        .product-image img{
            width:100%;
            height:100%;
            display:block;
            object-fit:cover;
            transition:transform .7s ease;
        }

        .product:hover .product-image img{
            transform:scale(1.04);
        }

        .product-info{
            padding:18px 4px 0;
        }

        .product-info h3{
            font-family:"Cormorant Garamond",serif;
            font-size:26px;
            font-weight:600;
            color:#5d3945;
            margin-bottom:6px;
        }

        .product-info p{
            font-family:"Montserrat",sans-serif;
            font-size:10px;
            letter-spacing:1.5px;
            color:#a47784;
            text-transform:uppercase;
        }

        .product-price{
            margin-top:10px;
            font-family:"Montserrat",sans-serif;
            font-size:13px;
            color:#754658;
        }

        /* =========================
           NEW PRODUCT
        ========================= */

        .new-product{
            background:#f8edf1;
            padding:110px 7%;
        }

        .new-product-inner{
            max-width:1120px;
            margin:auto;
            display:grid;
            grid-template-columns:1fr 1fr;
            align-items:center;
            gap:70px;
        }

        .new-product-image{
            aspect-ratio:1/1;
            overflow:hidden;
            background:#f5e5ea;
        }

        .new-product-image img{
            width:100%;
            height:100%;
            object-fit:cover;
            display:block;
        }

        .new-product-content{
            padding:20px 0;
        }

        .new-product-content h2{
            font-family:"Cormorant Garamond",serif;
            font-size:60px;
            line-height:.95;
            font-weight:500;
            color:#593844;
            margin-bottom:20px;
        }

        .new-product-content p{
            font-size:14px;
            line-height:2;
            color:#7b5b66;
            margin-bottom:25px;
        }

        .new-product-price{
            font-family:"Montserrat",sans-serif;
            font-size:18px;
            color:#8e4e65;
            margin-bottom:30px;
        }

        .new-product-btn{
            display:inline-block;
            padding:15px 32px;
            background:#714052;
            color:#fff;
            font-size:11px;
            letter-spacing:2px;
            transition:.3s;
        }

        .new-product-btn:hover{
            background:#522c3a;
        }

        /* =========================
           STORY
        ========================= */

        .story{
            padding:120px 7%;
            background:#fff;
        }

        .story-inner{
            max-width:950px;
            margin:auto;
            text-align:center;
        }

        .story-inner h2{
            font-family:"Cormorant Garamond",serif;
            font-size:58px;
            font-weight:500;
            color:#593844;
            margin-bottom:25px;
        }

        .story-inner p{
            font-size:14px;
            line-height:2.2;
            color:#7c606a;
        }

        /* =========================
           NEWSLETTER
        ========================= */

        .newsletter{
            background:#6c3c4d;
            color:#fff;
            padding:90px 20px;
            text-align:center;
        }

        .newsletter h2{
            font-family:"Cormorant Garamond",serif;
            font-size:52px;
            font-weight:500;
            margin-bottom:12px;
        }

        .newsletter p{
            font-size:13px;
            color:#eadbe0;
            margin-bottom:30px;
        }

        .newsletter-form{
            max-width:500px;
            margin:auto;
            display:flex;
            border-bottom:1px solid rgba(255,255,255,.55);
        }

        .newsletter-form input{
            flex:1;
            border:0;
            outline:0;
            background:transparent;
            color:#fff;
            padding:15px 5px;
            font-family:"Noto Sans Thai",sans-serif;
            font-size:13px;
        }

        .newsletter-form input::placeholder{
            color:#e7cfd8;
        }

        .newsletter-form button{
            border:0;
            background:none;
            color:#fff;
            font-family:"Montserrat",sans-serif;
            letter-spacing:2px;
            cursor:pointer;
            padding:0 10px;
        }

        /* =========================
           FOOTER
        ========================= */

        footer{
            background:#fffafc;
            padding:70px 7% 30px;
        }

        .footer-inner{
            max-width:1120px;
            margin:auto;
            display:grid;
            grid-template-columns:2fr 1fr 1fr 1fr;
            gap:50px;
            padding-bottom:50px;
            border-bottom:1px solid #eadde1;
        }

        .footer-logo{
            font-family:"Cormorant Garamond",serif;
            font-size:40px;
            letter-spacing:4px;
            color:#593844;
            margin-bottom:15px;
        }

        .footer-about{
            font-size:13px;
            line-height:2;
            color:#856b74;
            max-width:330px;
        }

        .footer-col h3{
            font-family:"Montserrat",sans-serif;
            font-size:11px;
            letter-spacing:2px;
            color:#593844;
            margin-bottom:20px;
            text-transform:uppercase;
        }

        .footer-col a{
            display:block;
            font-size:12px;
            color:#846974;
            margin-bottom:12px;
            transition:.3s;
        }

        .footer-col a:hover{
            color:#ad5d78;
        }

        .social{
            display:flex;
            gap:15px;
            margin-top:20px;
        }

        .social a{
            width:35px;
            height:35px;
            border:1px solid #d9c3ca;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            transition:.3s;
        }

        .social a:hover{
            background:#6c3c4d;
            color:#fff;
            border-color:#6c3c4d;
        }

        .copyright{
            text-align:center;
            padding-top:25px;
            font-family:"Montserrat",sans-serif;
            font-size:9px;
            letter-spacing:1.5px;
            color:#a78b94;
        }

        /* =========================
           POPUP
        ========================= */

        .popup-overlay{
            position:fixed;
            inset:0;
            background:rgba(50,25,35,.65);
            backdrop-filter:blur(8px);
            z-index:9999;
            display:none;
            align-items:center;
            justify-content:center;
            padding:20px;
        }

        .popup-overlay.show{
            display:flex;
        }

        .popup{
            position:relative;
            width:100%;
            max-width:820px;
            background:#fffafc;
            box-shadow:0 25px 70px rgba(45,20,30,.3);
            overflow:hidden;
        }

        .popup-close{
            position:absolute;
            right:18px;
            top:15px;
            width:35px;
            height:35px;
            border:0;
            background:rgba(255,255,255,.7);
            border-radius:50%;
            cursor:pointer;
            z-index:10;
            color:#633c49;
            font-size:18px;
        }

        .popup-slider{
            position:relative;
            overflow:hidden;
        }

        .popup-slides{
            display:flex;
            transition:transform .5s ease;
        }

        .popup-slide{
            min-width:100%;
            display:grid;
            grid-template-columns:1fr 1fr;
        }

        .popup-image{
            min-height:420px;
            background:#f4e6eb;
        }

        .popup-image img{
            width:100%;
            height:100%;
            object-fit:cover;
        }

        .popup-content{
            padding:55px 45px;
            display:flex;
            flex-direction:column;
            justify-content:center;
        }

        .popup-content small{
            font-family:"Montserrat",sans-serif;
            font-size:9px;
            letter-spacing:3px;
            color:#ad6c81;
            margin-bottom:12px;
        }

        .popup-content h2{
            font-family:"Cormorant Garamond",serif;
            font-size:48px;
            line-height:1;
            font-weight:500;
            color:#593844;
            margin-bottom:18px;
        }

        .popup-content p{
            font-size:13px;
            line-height:2;
            color:#80636d;
            margin-bottom:25px;
        }

        .popup-btn{
            display:inline-block;
            width:max-content;
            padding:13px 25px;
            background:#704052;
            color:#fff;
            font-size:10px;
            letter-spacing:1.5px;
        }

        .popup-dots{
            position:absolute;
            bottom:15px;
            left:50%;
            transform:translateX(-50%);
            display:flex;
            gap:7px;
            z-index:5;
        }

        .popup-dot{
            width:7px;
            height:7px;
            border-radius:50%;
            background:#d5b8c2;
            cursor:pointer;
        }

        .popup-dot.active{
            background:#704052;
        }

        /* =========================
           TABLET
        ========================= */

        @media(max-width:1000px){

            .nav-links{
                gap:18px;
            }

            .hero h1{
                font-size:65px;
            }

            .products{
                gap:20px;
            }

            .footer-inner{
                grid-template-columns:1fr 1fr;
            }

        }

        /* =========================
           TABLET / MOBILE
        ========================= */

        @media(max-width:800px){

            .navbar{
                height:auto;
                padding:18px 20px;
            }

            .nav-inner{
                flex-wrap:wrap;
                gap:15px;
            }

            .logo{
                font-size:30px;
            }

            .nav-links{
                order:3;
                width:100%;
                justify-content:center;
                gap:20px;
                font-size:11px;
                overflow-x:auto;
                padding-bottom:4px;
            }

            .hero{
                grid-template-columns:1fr;
            }

            .hero-content{
                min-height:520px;
                padding:70px 8%;
            }

            .hero-image{
                min-height:450px;
            }

            .hero h1{
                font-size:65px;
            }

            .products{
                grid-template-columns:repeat(2,1fr);
                gap:22px;
            }

            .new-product-inner{
                grid-template-columns:1fr;
                gap:40px;
            }

            .new-product-content h2{
                font-size:50px;
            }

            .popup-slide{
                grid-template-columns:1fr;
            }

            .popup-image{
                min-height:260px;
            }

            .popup-content{
                padding:35px 30px 45px;
            }

        }

        /* =========================
           PHONE
           คงขนาดที่พอดีล่าสุด
        ========================= */

        @media(max-width:550px){

            .navbar{
                padding:15px;
            }

            .logo{
                font-size:27px;
                letter-spacing:3px;
            }

            .nav-icons{
                gap:14px;
            }

            .nav-links{
                gap:16px;
                justify-content:flex-start;
                white-space:nowrap;
            }

            .hero-content{
                min-height:450px;
                padding:60px 25px;
            }

            .hero h1{
                font-size:55px;
            }

            .hero-description{
                font-size:12px;
            }

            .hero-image{
                min-height:380px;
            }

            .intro{
                padding:80px 20px 70px;
            }

            .intro h2{
                font-size:42px;
            }

            .intro p{
                font-size:12px;
            }

            .collection{
                padding:20px 15px 70px;
            }

            /* =========================
               แก้เฉพาะมือถือ
               ให้รูปเล็กพอดี
            ========================= */

            .products{
                grid-template-columns:1fr;
                width:100%;
                max-width:280px;
                gap:28px;
                margin:0 auto;
            }

            .product{
                width:100%;
                max-width:280px;
                margin:0 auto;
            }

            .product-image{
                width:100%;
                aspect-ratio:1 / 1.15;
            }

            .product-info{
                padding:14px 3px 0;
            }

            .product-info h3{
                font-size:21px;
            }

            .product-info p{
                font-size:9px;
            }

            .product-price{
                font-size:12px;
            }

            .section-head{
                margin-bottom:35px;
            }

            .section-head h2{
                font-size:44px;
            }

            .new-product{
                padding:70px 20px;
            }

            .new-product-content h2{
                font-size:45px;
            }

            .story{
                padding:80px 20px;
            }

            .story-inner h2{
                font-size:45px;
            }

            .newsletter{
                padding:70px 20px;
            }

            .newsletter h2{
                font-size:42px;
            }

            .newsletter-form{
                width:100%;
            }

            footer{
                padding:60px 25px 25px;
            }

            .footer-inner{
                grid-template-columns:1fr;
                gap:35px;
            }

            .popup{
                max-height:90vh;
                overflow-y:auto;
            }

            .popup-image{
                min-height:220px;
            }

            .popup-content{
                padding:30px 25px 40px;
            }

            .popup-content h2{
                font-size:40px;
            }

        }

    </style>
</head>

<body>

    <!-- =========================
         SPARKLES
    ========================= -->

    <div class="sparkle"></div>
    <div class="sparkle"></div>
    <div class="sparkle"></div>
    <div class="sparkle"></div>


    <!-- =========================
         NAVBAR
    ========================= -->

    <nav class="navbar">

        <div class="nav-inner">

            <a href="index.php" class="logo">
                VELOURA
            </a>

            <div class="nav-links">

                <a href="index.php">
                    หน้าแรก
                </a>

                <a href="products.php">
                    คอลเลกชัน
                </a>

                <a href="quiz.php">
                    ค้นหากลิ่น
                </a>

                <a href="about.php">
                    เกี่ยวกับเรา
                </a>

                <a href="contact.php">
                    ติดต่อเรา
                </a>

            </div>

            <div class="nav-icons">

                <a href="account.php"
                   title="บัญชีผู้ใช้">

                    <i class="fa-regular fa-user"></i>

                </a>

                <a href="cart.php"
                   class="cart"
                   title="รถเข็น">

                    <i class="fa-solid fa-bag-shopping"></i>

                    <?php if ($cart_count > 0): ?>

                        <span class="cart-count">
                            <?= $cart_count ?>
                        </span>

                    <?php endif; ?>

                </a>

            </div>

        </div>

    </nav>


    <!-- =========================
         HERO
    ========================= -->

    <section class="hero">

        <div class="hero-content">

            <div class="hero-small">
                Discover Your Signature Scent
            </div>

            <h1>
                Your<br>
                <span>Signature</span><br>
                Scent
            </h1>

            <p class="hero-description">
                ค้นพบกลิ่นหอมที่สะท้อนตัวตนของคุณ
                ผ่านคอลเลกชันน้ำหอมที่ได้รับการออกแบบ
                อย่างพิถีพิถันจาก VELOURA
            </p>

            <a href="products.php"
               class="hero-btn">

                EXPLORE COLLECTION

            </a>

        </div>


        <div class="hero-image">

            <img src="images/perfume-hero.jpg"
                 alt="VELOURA PERFUMES">

        </div>

    </section>


    <!-- =========================
         INTRO
    ========================= -->

    <section class="intro">

        <div class="section-label">
            THE ESSENCE OF VELOURA
        </div>

        <h2>
            Scent is a story.
        </h2>

        <p>
            น้ำหอมไม่ใช่เพียงกลิ่นหอม
            แต่คือความทรงจำ ความรู้สึก
            และตัวตนที่ถูกถ่ายทอดออกมาในรูปแบบของกลิ่น
            VELOURA จึงสร้างสรรค์น้ำหอมที่สามารถเป็นส่วนหนึ่ง
            ของเรื่องราวในทุกวันของคุณ
        </p>

    </section>


    <!-- =========================
         COLLECTION
    ========================= -->

    <section class="collection"
             id="collection">

        <div class="section-head">

            <div class="section-label">
                OUR COLLECTION
            </div>

            <h2>
                Featured Scents
            </h2>

            <p>
                คอลเลกชันแนะนำที่คัดสรรมาเพื่อคุณ
            </p>

        </div>


        <div class="products">

            <?php if (!empty($featured_products)): ?>

                <?php foreach ($featured_products as $product): ?>

                    <a href="product_detail.php?id=<?= (int)$product["id"] ?>"
                       class="product">

                        <div class="product-image">

                            <img
                                src="<?= htmlspecialchars(!empty($product["image"]) ? $product["image"] : "images/perfume-hero.jpg") ?>"
                                alt="<?= htmlspecialchars($product["name"]) ?>"
                                loading="lazy"
                            >

                        </div>

                        <div class="product-info">

                            <h3>
                                <?= htmlspecialchars($product["name"]) ?>
                            </h3>

                            <p>
                                VELOURA PERFUMES
                            </p>

                            <div class="product-price">

                                ฿<?= number_format((float)$product["price"]) ?>

                            </div>

                        </div>

                    </a>

                <?php endforeach; ?>

            <?php else: ?>

                <div style="
                    grid-column:1/-1;
                    text-align:center;
                    padding:50px 20px;
                    color:#987781;
                ">

                    ยังไม่มีสินค้าในคอลเลกชัน

                </div>

            <?php endif; ?>

        </div>

    </section>


    <!-- =========================
         NEW PRODUCT
    ========================= -->

    <section class="new-product">

        <div class="new-product-inner">

            <div class="new-product-image">

                <img
                    src="<?= htmlspecialchars($new_image) ?>"
                    alt="<?= htmlspecialchars($new_name) ?>"
                >

            </div>


            <div class="new-product-content">

                <div class="section-label">
                    NEW ARRIVAL
                </div>

                <h2>
                    <?= htmlspecialchars($new_name) ?>
                </h2>

                <p>
                    <?= htmlspecialchars($new_description) ?>
                </p>

                <div class="new-product-price">

                    ฿<?= htmlspecialchars($new_price) ?>

                </div>

                <?php if ($new_product): ?>

                    <a href="product_detail.php?id=<?= (int)$new_product["id"] ?>"
                       class="new-product-btn">

                        DISCOVER MORE

                    </a>

                <?php else: ?>

                    <a href="products.php"
                       class="new-product-btn">

                        DISCOVER MORE

                    </a>

                <?php endif; ?>

            </div>

        </div>

    </section>


    <!-- =========================
         STORY
    ========================= -->

    <section class="story">

        <div class="story-inner">

            <div class="section-label">
                OUR PHILOSOPHY
            </div>

            <h2>
                Wear your story.
            </h2>

            <p>
                ทุกกลิ่นหอมของ VELOURA
                ถูกสร้างขึ้นเพื่อให้คุณสามารถบอกเล่าเรื่องราว
                และแสดงตัวตนในแบบที่เป็นคุณ
                เพราะกลิ่นหอมที่ดีที่สุด
                คือกลิ่นที่ทำให้คุณรู้สึกเป็นตัวเองมากที่สุด
            </p>

        </div>

    </section>


    <!-- =========================
         NEWSLETTER
    ========================= -->

    <section class="newsletter">

        <h2>
            Stay in the Scent
        </h2>

        <p>
            สมัครรับข่าวสารและโปรโมชั่นพิเศษจาก VELOURA
        </p>

        <form class="newsletter-form"
              action="subscribe.php"
              method="POST">

            <input
                type="email"
                name="email"
                placeholder="อีเมลของคุณ"
                required
            >

            <button type="submit">
                JOIN
            </button>

        </form>

    </section>


    <!-- =========================
         FOOTER
    ========================= -->

    <footer>

        <div class="footer-inner">

            <div>

                <div class="footer-logo">
                    VELOURA
                </div>

                <p class="footer-about">
                    น้ำหอมที่ออกแบบมาเพื่อสะท้อนตัวตน
                    ความรู้สึก และเรื่องราวของคุณ
                    ผ่านกลิ่นหอมที่มีเอกลักษณ์
                </p>

                <div class="social">

                    <a href="#">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>

                    <a href="#">
                        <i class="fa-brands fa-instagram"></i>
                    </a>

                    <a href="#">
                        <i class="fa-brands fa-tiktok"></i>
                    </a>

                </div>

            </div>


            <div class="footer-col">

                <h3>
                    Shop
                </h3>

                <a href="products.php">
                    คอลเลกชัน
                </a>

                <a href="quiz.php">
                    ค้นหากลิ่น
                </a>

                <a href="products.php">
                    สินค้าใหม่
                </a>

            </div>


            <div class="footer-col">

                <h3>
                    About
                </h3>

                <a href="about.php">
                    เกี่ยวกับเรา
                </a>

                <a href="contact.php">
                    ติดต่อเรา
                </a>

                <a href="account.php">
                    บัญชีของฉัน
                </a>

            </div>


            <div class="footer-col">

                <h3>
                    Help
                </h3>

                <a href="#">
                    คำถามที่พบบ่อย
                </a>

                <a href="#">
                    การจัดส่ง
                </a>

                <a href="#">
                    นโยบายการคืนสินค้า
                </a>

            </div>

        </div>


        <div class="copyright">

            © <?= date("Y") ?> VELOURA PERFUMES. ALL RIGHTS RESERVED.

        </div>

    </footer>


    <!-- =========================
         POPUP
    ========================= -->

    <div class="popup-overlay"
         id="popupOverlay">

        <div class="popup">

            <button class="popup-close"
                    id="popupClose">

                <i class="fa-solid fa-xmark"></i>

            </button>


            <div class="popup-slider">

                <div class="popup-slides"
                     id="popupSlides">


                    <!-- Slide 1 -->

                    <div class="popup-slide">

                        <div class="popup-image">

                            <img
                                src="images/perfume-hero.jpg"
                                alt="VELOURA"
                            >

                        </div>

                        <div class="popup-content">

                            <small>
                                WELCOME TO VELOURA
                            </small>

                            <h2>
                                Find Your<br>
                                Signature
                            </h2>

                            <p>
                                ค้นพบกลิ่นหอมที่เป็นตัวคุณ
                                และเลือกน้ำหอมที่เหมาะกับสไตล์ของคุณ
                            </p>

                            <a href="products.php"
                               class="popup-btn">

                                SHOP NOW

                            </a>

                        </div>

                    </div>


                    <!-- Slide 2 -->

                    <div class="popup-slide">

                        <div class="popup-image">

                            <img
                                src="images/perfume-hero.jpg"
                                alt="VELOURA COLLECTION"
                            >

                        </div>

                        <div class="popup-content">

                            <small>
                                EXPLORE OUR COLLECTION
                            </small>

                            <h2>
                                Your Scent,<br>
                                Your Story
                            </h2>

                            <p>
                                เลือกกลิ่นที่บอกเล่าเรื่องราว
                                และความเป็นตัวคุณในทุกช่วงเวลา
                            </p>

                            <a href="quiz.php"
                               class="popup-btn">

                                FIND YOUR SCENT

                            </a>

                        </div>

                    </div>


                    <!-- Slide 3 -->

                    <div class="popup-slide">

                        <div class="popup-image">

                            <img
                                src="images/perfume-hero.jpg"
                                alt="VELOURA"
                            >

                        </div>

                        <div class="popup-content">

                            <small>
                                DISCOVER VELOURA
                            </small>

                            <h2>
                                Elegance<br>
                                in Every Drop
                            </h2>

                            <p>
                                สัมผัสความหรูหรา
                                ผ่านกลิ่นหอมที่ถูกออกแบบอย่างพิถีพิถัน
                            </p>

                            <a href="about.php"
                               class="popup-btn">

                                DISCOVER MORE

                            </a>

                        </div>

                    </div>

                </div>


                <div class="popup-dots">

                    <span class="popup-dot active"
                          data-slide="0"></span>

                    <span class="popup-dot"
                          data-slide="1"></span>

                    <span class="popup-dot"
                          data-slide="2"></span>

                </div>

            </div>

        </div>

    </div>


    <!-- =========================
         JAVASCRIPT
    ========================= -->

    <script>

        /* =========================
           POPUP
        ========================= */

        const popupOverlay =
            document.getElementById("popupOverlay");

        const popupClose =
            document.getElementById("popupClose");

        const popupSlides =
            document.getElementById("popupSlides");

        const popupDots =
            document.querySelectorAll(".popup-dot");

        let currentSlide = 0;


        function showSlide(index){

            currentSlide = index;

            popupSlides.style.transform =
                "translateX(-" + (index * 100) + "%)";

            popupDots.forEach((dot,i)=>{

                dot.classList.toggle(
                    "active",
                    i === index
                );

            });

        }


        popupDots.forEach(dot => {

            dot.addEventListener("click",function(){

                showSlide(
                    parseInt(this.dataset.slide)
                );

            });

        });


        popupClose.addEventListener("click",function(){

            popupOverlay.classList.remove("show");

            sessionStorage.setItem(
                "veloura_popup_closed",
                "1"
            );

        });


        popupOverlay.addEventListener("click",function(e){

            if(e.target === popupOverlay){

                popupOverlay.classList.remove("show");

                sessionStorage.setItem(
                    "veloura_popup_closed",
                    "1"
                );

            }

        });


        /* แสดง Popup ครั้งแรก */

        window.addEventListener("load",function(){

            const popupClosed =
                sessionStorage.getItem(
                    "veloura_popup_closed"
                );

            if(!popupClosed){

                setTimeout(function(){

                    popupOverlay.classList.add("show");

                },1000);

            }

        });

    </script>

</body>

</html>
