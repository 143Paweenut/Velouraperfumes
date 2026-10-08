<?php

session_start();
require_once "connect.php";

/* =====================================================
   จำนวนสินค้าในรถเข็น
===================================================== */

$cart_count = 0;

if (isset($_SESSION["cart"]) && is_array($_SESSION["cart"])) {
    $cart_count = array_sum($_SESSION["cart"]);
}


/* =====================================================
   ดึงสินค้า 3 รายการล่าสุด
===================================================== */

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


/* =====================================================
   ดึงสินค้าใหม่ล่าสุด
===================================================== */

$new_product = null;

$sql_new = "SELECT id, name, price, image, description
            FROM products
            ORDER BY id DESC
            LIMIT 1";

$result_new = $conn->query($sql_new);

if ($result_new && $result_new->num_rows > 0) {

    $new_product = $result_new->fetch_assoc();

}


/* =====================================================
   เตรียมข้อมูลสินค้าใหม่
===================================================== */

$new_product_name = "New Signature";
$new_product_price = "—";
$new_product_image = "images/perfume-hero.jpg";
$new_product_description = "ค้นพบกลิ่นใหม่จาก VELOURA";

if ($new_product) {

    $new_product_name = $new_product["name"];

    $new_product_price = "$" . number_format(
        (float)$new_product["price"],
        2
    );

    if (!empty($new_product["image"])) {

        $new_product_image = $new_product["image"];

    }

    if (!empty($new_product["description"])) {

        $new_product_description = $new_product["description"];

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
    Veloura Perfumes | Luxury Fragrance
</title>


<!-- =====================================================
     GOOGLE FONTS
===================================================== -->

<link rel="preconnect" href="https://fonts.googleapis.com">

<link
    rel="preconnect"
    href="https://fonts.gstatic.com"
    crossorigin
>

<link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&family=Noto+Sans+Thai:wght@300;400;500;600&display=swap"
    rel="stylesheet"
>


<!-- =====================================================
     FONT AWESOME
===================================================== -->

<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
>


<style>

/* =====================================================
   RESET
===================================================== */

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

html{
    scroll-behavior:smooth;
}

body{
    font-family:
        "Noto Sans Thai",
        "Montserrat",
        sans-serif;

    background:#fff9fb;

    color:#30272a;

    overflow-x:hidden;
}

a{
    text-decoration:none;
    color:inherit;
}


/* =====================================================
   FLOATING SPARKLES
===================================================== */

.sparkles{
    position:fixed;

    inset:0;

    pointer-events:none;

    z-index:9999;

    overflow:hidden;
}

.sparkles span{
    position:absolute;

    color:#d89caf;

    opacity:0;

    animation:
        twinkle
        3.8s
        ease-in-out
        infinite;
}

.sparkles span:nth-child(1){
    left:5%;
    top:18%;
    font-size:16px;
    animation-delay:.2s;
}

.sparkles span:nth-child(2){
    left:15%;
    top:72%;
    font-size:10px;
    animation-delay:1.4s;
}

.sparkles span:nth-child(3){
    left:27%;
    top:31%;
    font-size:13px;
    animation-delay:2s;
}

.sparkles span:nth-child(4){
    left:41%;
    top:83%;
    font-size:18px;
    animation-delay:.8s;
}

.sparkles span:nth-child(5){
    left:56%;
    top:20%;
    font-size:11px;
    animation-delay:2.7s;
}

.sparkles span:nth-child(6){
    left:69%;
    top:68%;
    font-size:16px;
    animation-delay:1.1s;
}

.sparkles span:nth-child(7){
    left:81%;
    top:27%;
    font-size:12px;
    animation-delay:2.4s;
}

.sparkles span:nth-child(8){
    left:94%;
    top:78%;
    font-size:17px;
    animation-delay:.5s;
}

@keyframes twinkle{

    0%,100%{
        opacity:0;
        transform:
            scale(.4)
            rotate(0);
    }

    35%,65%{
        opacity:.75;
        transform:
            scale(1.1)
            rotate(25deg);
    }

}


/* =====================================================
   TOP BAR
===================================================== */

.top-bar{

    min-height:38px;

    background:
        linear-gradient(
            90deg,
            #251b1f,
            #4a3039,
            #251b1f
        );

    color:#fff1f5;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:10px;

    letter-spacing:2px;

    position:relative;

    overflow:hidden;
}

.top-bar:after{

    content:"";

    position:absolute;

    top:0;

    left:-20%;

    width:20%;

    height:100%;

    background:
        linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,.3),
            transparent
        );

    animation:
        shine
        5s
        linear
        infinite;
}

@keyframes shine{

    to{
        left:120%;
    }

}


/* =====================================================
   NAVBAR
===================================================== */

nav{

    min-height:86px;

    background:
        rgba(255,250,252,.94);

    backdrop-filter:blur(16px);

    display:flex;

    align-items:center;

    justify-content:space-between;

    padding:0 6%;

    border-bottom:
        1px solid #efdde3;

    position:sticky;

    top:0;

    z-index:1000;

    box-shadow:
        0 8px 30px
        rgba(109,66,81,.06);
}

.logo{

    text-align:center;

    line-height:1;

    color:#30272a;

    min-width:190px;

    position:relative;
}

.logo:before,
.logo:after{

    content:"✦";

    position:absolute;

    color:#d28da2;

    font-size:10px;

    top:3px;
}

.logo:before{
    left:0;
}

.logo:after{
    right:0;
}

.logo-main{

    display:block;

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:35px;

    font-weight:600;

    letter-spacing:7px;
}

.logo-sub{

    display:block;

    font-family:
        "Montserrat",
        sans-serif;

    font-size:8px;

    letter-spacing:6px;

    margin-top:8px;

    color:#b37c91;
}

.nav-links{

    display:flex;

    align-items:center;

    gap:27px;
}

.nav-links a,
.nav-right a{

    font-size:12px;

    color:#62545a;

    transition:.3s;

    position:relative;
}

.nav-links a:after{

    content:"";

    position:absolute;

    left:50%;

    bottom:-10px;

    width:0;

    height:1px;

    background:#c9879c;

    transition:.3s;

    transform:
        translateX(-50%);
}

.nav-links a:hover,
.nav-links a.active,
.nav-right a:hover{

    color:#b36e85;
}

.nav-links a:hover:after,
.nav-links a.active:after{

    width:70%;
}

.nav-right{

    display:flex;

    align-items:center;

    gap:20px;
}

.cart{

    position:relative;
}

.cart-count{

    position:absolute;

    top:-10px;

    right:-13px;

    width:18px;

    height:18px;

    border-radius:50%;

    background:
        linear-gradient(
            135deg,
            #d996aa,
            #b96f89
        );

    color:#fff;

    font:10px "Montserrat";

    display:flex;

    align-items:center;

    justify-content:center;

    box-shadow:
        0 4px 10px
        rgba(185,111,137,.3);
}


/* =====================================================
   HERO
===================================================== */

.hero{

    min-height:680px;

    position:relative;

    display:grid;

    grid-template-columns:58% 42%;

    overflow:hidden;

    background:
        linear-gradient(
            135deg,
            #f5e4e8,
            #f8eef1 55%,
            #f0dce2
        );
}

.hero:before{

    content:"";

    position:absolute;

    width:420px;

    height:420px;

    border-radius:50%;

    background:
        rgba(255,255,255,.38);

    right:-130px;

    top:-130px;

    filter:blur(2px);
}

.hero-image{

    position:relative;

    min-height:680px;

    overflow:hidden;
}

.hero-image:after{

    content:"";

    position:absolute;

    inset:0;

    background:
        linear-gradient(
            90deg,
            transparent 55%,
            rgba(246,226,232,.82)
        );
}

.hero-image:before{

    content:"✦  ✧  ⋆";

    position:absolute;

    z-index:2;

    right:35px;

    top:35px;

    color:#fff;

    font-size:18px;

    letter-spacing:10px;

    text-shadow:
        0 2px 12px
        rgba(110,60,77,.25);

    animation:
        floatSparkle
        3s
        ease-in-out
        infinite;
}

@keyframes floatSparkle{

    50%{
        transform:
            translateY(-8px)
            rotate(4deg);
    }

}

.hero-image img{

    width:100%;

    height:100%;

    object-fit:cover;

    display:block;

    transition:
        transform
        1.2s
        ease;
}

.hero:hover .hero-image img{

    transform:scale(1.025);
}

.hero-content{

    display:flex;

    flex-direction:column;

    justify-content:center;

    padding:70px 10%;

    position:relative;

    z-index:3;
}

.hero-content:before{

    content:"✧";

    position:absolute;

    top:82px;

    right:18%;

    font-size:30px;

    color:#d092a7;
}

.hero-content:after{

    content:"⋆";

    position:absolute;

    bottom:110px;

    right:12%;

    font-size:24px;

    color:#d092a7;
}

.eyebrow{

    font:
        500 10px
        "Montserrat";

    letter-spacing:4px;

    color:#b36e85;

    margin-bottom:20px;
}

.hero h1{

    font:
        500
        clamp(50px,5vw,78px)/.88
        "Cormorant Garamond",
        serif;

    color:#33282c;

    margin-bottom:20px;
}

.hero h1 em{

    font-weight:400;

    color:#b66f86;
}

.hero-line{

    width:80px;

    height:2px;

    background:
        linear-gradient(
            90deg,
            #c7859a,
            #eed2da
        );

    margin:
        5px 0 22px;
}

.hero-description{

    font-size:13px;

    line-height:2;

    color:#786b71;

    max-width:390px;

    margin-bottom:32px;
}

.shop-button{

    display:inline-flex;

    align-items:center;

    justify-content:center;

    width:200px;

    padding:15px 20px;

    background:
        linear-gradient(
            135deg,
            #30272a,
            #50353e
        );

    color:#fff;

    font:
        500 10px
        "Montserrat";

    letter-spacing:2px;

    transition:.35s;

    box-shadow:
        0 12px 28px
        rgba(63,42,50,.16);
}

.shop-button i{

    margin-left:12px;

    font-size:9px;
}

.shop-button:hover{

    background:
        linear-gradient(
            135deg,
            #c47e94,
            #a96178
        );

    transform:translateY(-4px);

    box-shadow:
        0 15px 30px
        rgba(169,97,120,.25);
}

.hero-note{

    margin-top:25px;

    font-size:9px;

    letter-spacing:1.5px;

    color:#9b858d;
}


/* =====================================================
   INTRO
===================================================== */

.intro{

    text-align:center;

    padding:
        82px 20px 38px;

    background:#fff;
}

.intro-kicker{

    font:
        500 9px
        "Montserrat";

    letter-spacing:4px;

    color:#bd7d91;

    margin-bottom:15px;
}

.intro h2{

    font:
        500 47px
        "Cormorant Garamond",
        serif;

    color:#352a2e;

    margin-bottom:13px;
}

.intro p{

    font-size:12px;

    color:#95868d;

    line-height:1.9;
}

.intro h2:after{

    content:"  ✦";

    color:#d596aa;

    font-size:17px;

    vertical-align:middle;
}


/* =====================================================
   PRODUCTS / COLLECTION
===================================================== */

.collection{

    background:#fff;

    padding:
        8px 6% 100px;
}

.products{

    display:grid;

    grid-template-columns:
        repeat(3,1fr);

    gap:24px;

    max-width:1050px;

    margin:0 auto;
}


/* =====================================================
   PRODUCT CARD
===================================================== */

.product{

    display:block;

    position:relative;

    transition:.35s;

    padding:
        0 0 8px;
}

.product:after{

    content:"";

    position:absolute;

    inset:-8px;

    border-radius:6px;

    background:
        linear-gradient(
            135deg,
            rgba(231,182,195,.18),
            transparent 50%
        );

    opacity:0;

    transition:.35s;

    z-index:0;
}

.product:hover{

    transform:translateY(-6px);
}

.product:hover:after{

    opacity:1;
}


/* =====================================================
   PRODUCT IMAGE
===================================================== */

.product-image{

    width:100%;

    max-width:300px;

    aspect-ratio:4/5;

    margin:0 auto;

    background:#f7eef1;

    overflow:hidden;

    position:relative;

    z-index:1;

    box-shadow:
        0 12px 28px
        rgba(95,57,70,.08);
}

.product-image:after{

    content:"✧";

    position:absolute;

    right:15px;

    bottom:13px;

    color:#fff;

    font-size:20px;

    opacity:.8;

    text-shadow:
        0 2px 10px
        rgba(60,30,40,.25);

    z-index:3;
}

.product-image img{

    width:100%;

    height:100%;

    display:block;

    object-fit:cover;

    transition:
        transform
        .7s
        ease;
}

.product:hover
.product-image img{

    transform:scale(1.055);
}


/* =====================================================
   PRODUCT TAG
===================================================== */

.product-tag{

    position:absolute;

    top:14px;

    left:14px;

    background:
        rgba(255,248,250,.94);

    padding:
        7px 11px;

    font:
        500 8px
        "Montserrat";

    letter-spacing:1.5px;

    color:#8c5d6d;

    z-index:4;

    border:
        1px solid #f0d6de;
}


/* =====================================================
   PRODUCT INFO
   แสดงเฉพาะชื่อ + ราคา
===================================================== */

.product-info{

    padding:
        15px 4px 0;

    position:relative;

    z-index:1;

    text-align:center;
}


/* ชื่อสินค้า */

.product-info h3{

    font:
        600 25px
        "Cormorant Garamond",
        serif;

    letter-spacing:1px;

    color:#352a2e;

    margin:
        0 0 7px;
}


/* ส่วนราคา */

.product-bottom{

    display:flex;

    justify-content:center;

    align-items:center;
}

.product-price{

    font:
        500 13px
        "Montserrat",
        sans-serif;

    letter-spacing:1px;

    color:#9a6577;
}


/* =====================================================
   ซ่อนรายละเอียดสินค้า
===================================================== */

.product-info p{

    display:none;
}


/* =====================================================
   ซ่อน VIEW DETAILS
===================================================== */

.view-product{

    display:none;
}


/* =====================================================
   NEWSLETTER CTA
===================================================== */

.newsletter-cta-section{

    position:relative;

    overflow:hidden;

    padding:
        75px 20px 85px;

    background:

        radial-gradient(
            circle at 50% 0%,
            rgba(255,220,230,.65),
            transparent 42%
        ),

        linear-gradient(
            135deg,
            #fff8fa,
            #f8e9ee 52%,
            #fff
        );

    border-top:
        1px solid #f1dce3;

    border-bottom:
        1px solid #f1dce3;

    text-align:center;
}

.newsletter-cta-section:before,
.newsletter-cta-section:after{

    content:"";

    position:absolute;

    border:
        1px solid
        rgba(193,119,143,.16);

    border-radius:50%;

    pointer-events:none;
}

.newsletter-cta-section:before{

    width:330px;

    height:330px;

    left:-190px;

    top:-145px;
}

.newsletter-cta-section:after{

    width:270px;

    height:270px;

    right:-145px;

    bottom:-145px;
}

.newsletter-cta-decoration{

    color:#c9859a;

    font-size:12px;

    letter-spacing:7px;

    margin-bottom:17px;

    opacity:.8;
}

.newsletter-cta-content{

    position:relative;

    z-index:2;

    max-width:620px;

    margin:auto;
}

.newsletter-cta-kicker{

    font:
        600 9px
        "Montserrat";

    letter-spacing:4px;

    color:#b36e85;

    margin-bottom:12px;
}

.newsletter-cta-content h2{

    font:
        500
        clamp(40px,5vw,57px)/.95
        "Cormorant Garamond",
        serif;

    color:#352a2e;

    margin-bottom:14px;
}

.newsletter-cta-content h2 em{

    color:#b66f86;

    font-style:normal;

    font-weight:400;
}

.newsletter-cta-content p{

    max-width:470px;

    margin:
        0 auto 27px;

    color:#83747b;

    font-size:11px;

    line-height:2;
}

.newsletter-main-button{

    position:relative;

    display:inline-flex;

    align-items:center;

    gap:15px;

    min-width:330px;

    padding:
        15px 19px;

    text-align:left;

    background:
        linear-gradient(
            120deg,
            #30272a,
            #60424d,
            #30272a
        );

    background-size:200% 100%;

    color:#fff;

    box-shadow:
        0 15px 35px
        rgba(62,40,49,.2);

    transition:.4s ease;

    overflow:hidden;
}

.newsletter-main-button:hover{

    transform:translateY(-4px);

    background-position:100% 0;

    box-shadow:
        0 20px 42px
        rgba(62,40,49,.28);
}

.button-icon{

    width:40px;

    height:40px;

    display:flex;

    align-items:center;

    justify-content:center;

    border:
        1px solid
        rgba(255,255,255,.28);

    background:
        rgba(255,255,255,.08);

    border-radius:50%;

    color:#e8b6c5;

    font-size:13px;

    flex:none;
}

.newsletter-main-button strong{

    display:block;

    font:
        500 12px
        "Noto Sans Thai",
        "Montserrat",
        sans-serif;

    letter-spacing:.4px;
}

.newsletter-main-button small{

    display:block;

    margin-top:3px;

    color:#cdbdc3;

    font:
        500 7px
        "Montserrat";

    letter-spacing:2px;
}

.button-arrow{

    margin-left:auto;

    color:#e3aebe;

    font-size:10px;

    transition:.3s;
}

.newsletter-main-button:hover
.button-arrow{

    transform:translateX(5px);
}

.newsletter-cta-note{

    margin-top:15px;

    color:#a48d96;

    font-size:8px;

    letter-spacing:.6px;
}


/* =====================================================
   STORY
===================================================== */

.story{

    background:
        linear-gradient(
            135deg,
            #30272a,
            #443038 60%,
            #33272c
        );

    color:#fff;

    display:grid;

    grid-template-columns:1fr 1fr;

    min-height:370px;

    position:relative;

    overflow:hidden;
}

.story:before{

    content:"✦   ✧   ⋆";

    position:absolute;

    right:8%;

    top:38px;

    color:#d49aac;

    letter-spacing:14px;

    font-size:16px;

    opacity:.75;
}

.story:after{

    content:"";

    position:absolute;

    width:300px;

    height:300px;

    border-radius:50%;

    border:
        1px solid
        rgba(223,157,176,.16);

    right:-80px;

    bottom:-130px;
}

.story-content{

    padding:
        75px 12%;

    display:flex;

    flex-direction:column;

    justify-content:center;

    position:relative;

    z-index:2;
}

.story-kicker{

    font:
        500 9px
        "Montserrat";

    letter-spacing:4px;

    color:#d59aac;

    margin-bottom:15px;
}

.story h2{

    font:
        500 45px/1
        "Cormorant Garamond",
        serif;

    margin-bottom:20px;
}

.story p{

    font-size:12px;

    line-height:2;

    color:#d4c5ca;

    max-width:460px;
}

.story-mark{

    display:flex;

    align-items:center;

    justify-content:center;

    font:
        500 120px
        "Cormorant Garamond";

    color:#b87b8e;

    border-left:
        1px solid
        rgba(255,255,255,.09);

    position:relative;
}

.story-mark:before{

    content:"✦";

    position:absolute;

    top:80px;

    left:18%;

    font-size:20px;

    color:#d9a0af;
}

.story-mark:after{

    content:"✧";

    position:absolute;

    bottom:65px;

    right:18%;

    font-size:25px;

    color:#d9a0af;
}


/* =====================================================
   FOOTER
===================================================== */

footer{

    background:#20191c;

    color:#fff;

    padding:
        58px 7% 25px;

    position:relative;
}

footer:before{

    content:"✦  ✧  ⋆  ✦  ✧";

    display:block;

    text-align:center;

    color:#bd8093;

    letter-spacing:16px;

    font-size:12px;

    margin-bottom:35px;

    opacity:.7;
}

.footer-container{

    display:grid;

    grid-template-columns:
        2fr 1fr 1fr;

    gap:60px;

    max-width:1120px;

    margin:auto;
}

.footer-logo{

    font:
        600 31px
        "Cormorant Garamond";

    letter-spacing:6px;

    margin-bottom:12px;
}

footer p,
footer a{

    font-size:11px;

    line-height:2;

    color:#aaa0a4;
}

footer h3{

    font-size:12px;

    letter-spacing:1px;

    margin-bottom:18px;

    color:#f0dbe2;
}

footer a{

    display:block;

    margin-bottom:7px;

    transition:.2s;
}

footer a:hover{

    color:#e0a1b3;

    transform:translateX(3px);
}

.copyright{

    text-align:center;

    border-top:
        1px solid #3b3034;

    margin-top:45px;

    padding-top:20px;

    color:#777075;

    font-size:10px;
}


/* =====================================================
   VELOURA ADVERTISEMENT POPUP
===================================================== */

.veloura-ad-overlay{

    position:fixed;

    inset:0;

    z-index:20000;

    display:flex;

    align-items:center;

    justify-content:center;

    padding:20px;

    background:
        rgba(36,25,30,.48);

    backdrop-filter:blur(7px);

    -webkit-backdrop-filter:blur(7px);

    animation:
        adOverlayIn
        .35s
        ease;
}


/* MAIN BOX */

.veloura-ad-box{

    width:min(850px,100%);

    min-height:440px;

    position:relative;

    overflow:hidden;

    background:#fff8fa;

    border:
        1px solid
        rgba(213,145,164,.55);

    box-shadow:
        0 35px 100px
        rgba(30,20,25,.35),

        0 5px 20px
        rgba(120,70,90,.12);

    animation:
        adBoxIn
        .45s
        ease;
}


/* SLIDER */

.veloura-ad-slider{

    width:100%;

    height:100%;

    min-height:440px;

    position:relative;
}

.veloura-ad-slide{

    position:absolute;

    inset:0;

    display:grid;

    grid-template-columns:1fr 1fr;

    opacity:0;

    visibility:hidden;

    transform:translateX(20px);

    transition:
        opacity .45s ease,
        transform .45s ease,
        visibility .45s ease;
}

.veloura-ad-slide.active{

    opacity:1;

    visibility:visible;

    transform:translateX(0);
}


/* CLOSE */

.veloura-ad-close{

    position:absolute;

    z-index:20;

    top:15px;

    right:15px;

    width:38px;

    height:38px;

    border-radius:50%;

    border:
        1px solid #e4cbd4;

    background:
        rgba(255,255,255,.88);

    color:#5c4b52;

    cursor:pointer;

    transition:.3s;

    display:flex;

    align-items:center;

    justify-content:center;
}

.veloura-ad-close:hover{

    background:#35282d;

    color:#fff;

    transform:rotate(90deg);
}


/* =====================================================
   AD 1
===================================================== */

.ad-store-image{

    position:relative;

    min-height:440px;

    overflow:hidden;

    background:
        linear-gradient(
            135deg,
            #e7c3cd,
            #f7e7ec
        );
}

.ad-store-image:before{

    content:"VELOURA";

    position:absolute;

    left:25px;

    bottom:20px;

    color:
        rgba(255,255,255,.6);

    font:
        600 65px
        "Cormorant Garamond";

    letter-spacing:8px;

    z-index:2;
}

.ad-store-image img{

    width:100%;

    height:100%;

    object-fit:cover;

    display:block;

    transition:1s;
}

.veloura-ad-slide.active
.ad-store-image img{

    transform:scale(1.04);
}


/* AD CONTENT */

.ad-content{

    padding:
        65px 55px;

    display:flex;

    flex-direction:column;

    justify-content:center;

    background:

        radial-gradient(
            circle at 90% 10%,
            rgba(255,255,255,.9),
            transparent 30%
        ),

        #fff8fa;
}

.ad-kicker{

    font:
        600 9px
        "Montserrat";

    letter-spacing:4px;

    color:#b36e85;

    margin-bottom:18px;
}

.ad-content h2{

    font:
        500 52px/.9
        "Cormorant Garamond",
        serif;

    color:#352a2e;

    margin-bottom:20px;
}

.ad-content h2 em{

    color:#b66f86;

    font-style:normal;

    font-weight:400;
}

.ad-content p{

    font-size:12px;

    line-height:2;

    color:#786b71;

    max-width:340px;

    margin-bottom:25px;
}

.ad-button{

    display:inline-flex;

    align-items:center;

    justify-content:center;

    gap:10px;

    width:max-content;

    min-width:180px;

    padding:
        14px 20px;

    background:#34282d;

    color:#fff;

    font:
        600 9px
        "Montserrat";

    letter-spacing:1.8px;

    transition:.3s;

    box-shadow:
        0 12px 25px
        rgba(52,40,45,.18);
}

.ad-button:hover{

    background:#a9667d;

    transform:translateY(-3px);
}


/* =====================================================
   AD 2
===================================================== */

.ad-promotion{

    background:

        radial-gradient(
            circle at 50% 20%,
            rgba(255,255,255,.25),
            transparent 25%
        ),

        linear-gradient(
            135deg,
            #38282f,
            #62404d
        );

    color:#fff;

    display:flex;

    align-items:center;

    justify-content:center;

    text-align:center;

    position:relative;

    overflow:hidden;
}

.ad-promotion:before{

    content:"✦  ✧  ⋆  ✦  ✧";

    position:absolute;

    top:45px;

    left:50%;

    transform:
        translateX(-50%);

    color:#e1aabd;

    letter-spacing:12px;

    font-size:13px;

    opacity:.8;
}

.ad-promotion:after{

    content:"";

    position:absolute;

    width:400px;

    height:400px;

    border:
        1px solid
        rgba(255,255,255,.1);

    border-radius:50%;

    right:-220px;

    bottom:-250px;
}

.ad-promotion-content{

    position:relative;

    z-index:2;

    padding:
        50px 30px;
}

.ad-promotion .ad-kicker{

    color:#e5b4c2;

    margin-bottom:12px;
}

.ad-promotion h2{

    color:#fff;

    font-size:60px;

    margin-bottom:10px;
}

.ad-promotion h2 em{

    color:#e6afc0;
}

.promo-big{

    font:
        600 25px
        "Montserrat";

    letter-spacing:2px;

    color:#fff;

    margin-bottom:12px;
}

.promo-detail{

    color:#d8c6cd;

    font-size:11px;

    line-height:2;

    margin:
        0 auto 25px;

    max-width:440px;
}

.promo-code{

    display:inline-block;

    padding:
        9px 20px;

    border:
        1px solid
        rgba(232,185,199,.55);

    color:#f0c8d4;

    font:
        600 10px
        "Montserrat";

    letter-spacing:2px;

    margin-bottom:25px;
}

.ad-promotion .ad-button{

    background:#f0c7d3;

    color:#3b2b31;

    margin:auto;
}

.ad-promotion .ad-button:hover{

    background:#fff;
}


/* =====================================================
   AD 3
===================================================== */

.ad-new-product{

    display:grid;

    grid-template-columns:45% 55%;

    background:#fff8fa;
}

.ad-new-image{

    position:relative;

    min-height:440px;

    overflow:hidden;

    background:#f3e1e7;
}

.ad-new-image img{

    width:100%;

    height:100%;

    object-fit:cover;

    display:block;

    transition:1s;
}

.veloura-ad-slide.active
.ad-new-image img{

    transform:scale(1.035);
}

.new-label{

    position:absolute;

    top:22px;

    left:22px;

    z-index:4;

    padding:
        8px 12px;

    background:
        rgba(255,248,250,.94);

    border:
        1px solid #e5c9d2;

    color:#9a6174;

    font:
        600 8px
        "Montserrat";

    letter-spacing:1.7px;
}

.ad-new-content{

    padding:
        55px 48px;

    display:flex;

    flex-direction:column;

    justify-content:center;
}

.ad-new-content h2{

    font-size:48px;

    line-height:.95;

    margin-bottom:10px;
}

.ad-new-name{

    font:
        600 27px
        "Cormorant Garamond",
        serif;

    color:#b16d83;

    margin-bottom:10px;
}

.ad-new-description{

    color:#796c72;

    font-size:11px;

    line-height:1.9;

    margin-bottom:17px;

    display:-webkit-box;

    -webkit-line-clamp:3;

    -webkit-box-orient:vertical;

    overflow:hidden;
}

.ad-new-price{

    font:
        600 14px
        "Montserrat";

    color:#8e6070;

    letter-spacing:1px;

    margin-bottom:22px;
}


/* =====================================================
   SLIDER CONTROLS
===================================================== */

.ad-controls{

    position:absolute;

    left:50%;

    bottom:18px;

    transform:
        translateX(-50%);

    z-index:30;

    display:flex;

    align-items:center;

    gap:7px;
}

.ad-dot{

    width:8px;

    height:8px;

    border-radius:50%;

    border:0;

    background:#d9b7c2;

    cursor:pointer;

    transition:.3s;

    padding:0;
}

.ad-dot.active{

    width:24px;

    border-radius:10px;

    background:#a9677d;
}

.ad-arrow{

    position:absolute;

    top:50%;

    transform:
        translateY(-50%);

    z-index:25;

    width:38px;

    height:38px;

    border-radius:50%;

    border:
        1px solid #dfc5cd;

    background:
        rgba(255,255,255,.85);

    color:#725963;

    cursor:pointer;

    transition:.3s;

    display:flex;

    align-items:center;

    justify-content:center;
}

.ad-arrow:hover{

    background:#35282d;

    color:#fff;
}

.ad-prev{
    left:15px;
}

.ad-next{
    right:15px;
}


/* =====================================================
   PROGRESS
===================================================== */

.ad-progress{

    position:absolute;

    left:0;

    bottom:0;

    height:3px;

    width:33.333%;

    background:#c37d93;

    z-index:40;

    transition:
        width
        .3s
        ease;
}


/* =====================================================
   AD ANIMATIONS
===================================================== */

@keyframes adOverlayIn{

    from{
        opacity:0;
    }

    to{
        opacity:1;
    }

}

@keyframes adBoxIn{

    from{

        opacity:0;

        transform:
            translateY(25px)
            scale(.96);
    }

    to{

        opacity:1;

        transform:
            translateY(0)
            scale(1);
    }

}


/* =====================================================
   RESPONSIVE AD
===================================================== */

@media(max-width:700px){

    .veloura-ad-box{

        min-height:500px;
    }

    .veloura-ad-slider{

        min-height:500px;
    }

    .veloura-ad-slide{

        grid-template-columns:1fr;

        overflow-y:auto;
    }

    .ad-store-image{

        min-height:220px;

        height:220px;
    }

    .ad-store-image:before{

        font-size:42px;
    }

    .ad-content{

        padding:
            35px 30px 65px;

        min-height:280px;
    }

    .ad-content h2{

        font-size:42px;
    }

    .ad-promotion{

        min-height:500px;
    }

    .ad-promotion h2{

        font-size:48px;
    }

    .ad-new-product{

        grid-template-columns:1fr;
    }

    .ad-new-image{

        min-height:230px;

        height:230px;
    }

    .ad-new-content{

        padding:
            30px 28px 65px;

        min-height:270px;
    }

    .ad-new-content h2{

        font-size:40px;
    }

}


/* =====================================================
   MOBILE AD
===================================================== */

@media(max-width:550px){

    .veloura-ad-overlay{

        padding:12px;
    }

    .veloura-ad-box{

        width:100%;

        min-height:540px;
    }

    .veloura-ad-slider{

        min-height:540px;
    }

    .ad-store-image{

        min-height:200px;

        height:200px;
    }

    .ad-content{

        padding:
            30px 24px 65px;
    }

    .ad-content h2{

        font-size:39px;
    }

    .ad-content p{

        font-size:11px;
    }

    .ad-promotion{

        min-height:540px;
    }

    .ad-promotion-content{

        padding:
            50px 22px 70px;
    }

    .ad-promotion h2{

        font-size:43px;
    }

    .promo-big{

        font-size:19px;
    }

    .ad-new-image{

        min-height:200px;

        height:200px;
    }

    .ad-new-content{

        padding:
            25px 22px 65px;
    }

    .ad-new-name{

        font-size:24px;
    }

    .ad-arrow{

        width:34px;

        height:34px;
    }

}


/* =====================================================
   RESPONSIVE WEBSITE
===================================================== */

@media(max-width:1000px){

    nav{

        padding:
            0 3%;
    }

    .nav-links{

        gap:15px;
    }

    .logo{

        min-width:auto;
    }

    .products{

        max-width:750px;
    }

    .story-content{

        padding:
            60px 8%;
    }

}


@media(max-width:800px){

    nav{

        height:auto;

        padding:
            18px 20px;

        flex-wrap:wrap;

        gap:16px;

        justify-content:center;
    }

    .logo{

        order:-1;

        width:100%;
    }

    .nav-links{

        flex-wrap:wrap;

        justify-content:center;

        gap:14px;
    }

    .nav-right{

        position:absolute;

        right:20px;

        top:112px;
    }

    .hero{

        grid-template-columns:1fr;
    }

    .hero-image{

        min-height:430px;
    }

    .hero-content{

        min-height:500px;

        align-items:center;

        text-align:center;

        padding:
            60px 25px;
    }

    .hero-content:before,
    .hero-content:after{

        display:none;
    }

    .hero-line{

        margin-left:auto;

        margin-right:auto;
    }

    .hero-description{

        max-width:430px;
    }

    .products{

        grid-template-columns:
            repeat(2,1fr);

        gap:22px;
    }

    .story{

        grid-template-columns:1fr;
    }

    .story-mark{

        display:none;
    }

}


@media(max-width:550px){

    .top-bar{

        font-size:8px;

        letter-spacing:1px;

        text-align:center;

        padding:
            0 8px;
    }

    .nav-right{

        position:static;
    }

    .hero-image{

        min-height:330px;
    }

    .hero h1{

        font-size:50px;
    }

    .intro{

        padding:
            65px 20px 25px;
    }

    .intro h2{

        font-size:38px;
    }

    .collection{

        padding:
            5px 20px 80px;
    }

    .products{

        grid-template-columns:1fr;

        max-width:360px;

        gap:28px;
    }

    .product-image{

        max-width:330px;
    }

    .footer-container{

        grid-template-columns:1fr;

        gap:30px;
    }

}

</style>

</head>


<body>


<!-- =====================================================
     VELOURA 3 ADS POPUP
===================================================== -->

<div
    class="veloura-ad-overlay"
    id="velouraAd"
    role="dialog"
    aria-modal="true"
    aria-label="Veloura Advertisement"
>

    <div class="veloura-ad-box">


        <!-- CLOSE -->

        <button
            type="button"
            class="veloura-ad-close"
            id="closeVelouraAd"
            aria-label="ปิดโฆษณา"
        >

            <i class="fa-solid fa-xmark"></i>

        </button>


        <div class="veloura-ad-slider">


            <!-- =================================================
                 AD 1
            ================================================== -->

            <div class="veloura-ad-slide active">


                <div class="ad-store-image">

                    <img
                        src="images/perfume-hero.jpg"
                        alt="Veloura Perfumes"
                        onerror="this.src='images/perfume-hero.jpg';"
                    >

                </div>


                <div class="ad-content">

                    <div class="ad-kicker">

                        ✦ WELCOME TO VELOURA ✦

                    </div>


                    <h2>

                        The Art<br>

                        <em>
                            of Fragrance
                        </em>

                    </h2>


                    <p>

                        VELOURA เชื่อว่าน้ำหอมไม่ใช่เพียงแค่กลิ่นหอม
                        แต่คือรายละเอียดที่สะท้อนตัวตน
                        ความรู้สึก และความทรงจำของคุณ

                    </p>


                    <a
                        href="about.php"
                        class="ad-button"
                    >

                        รู้จัก VELOURA

                        <i class="fa-solid fa-arrow-right"></i>

                    </a>

                </div>

            </div>


            <!-- =================================================
                 AD 2
            ================================================== -->

            <div class="veloura-ad-slide">


                <div class="ad-promotion">

                    <div class="ad-promotion-content">


                        <div class="ad-kicker">

                            ✦ VELOURA SPECIAL OFFER ✦

                        </div>


                        <h2>

                            Special<br>

                            <em>
                                Privilege
                            </em>

                        </h2>


                        <div class="promo-big">

                            FREE SHIPPING

                        </div>


                        <p class="promo-detail">

                            จัดส่งฟรีสำหรับทุกคำสั่งซื้อ
                            เมื่อมียอดสั่งซื้อครบ $50
                            พร้อมพบกับคอลเลกชันและสิทธิพิเศษ
                            จาก VELOURA

                        </p>


                        <div class="promo-code">

                            ✦ ORDER $50+ • FREE SHIPPING ✦

                        </div>


                        <br>


                        <a
                            href="products.php"
                            class="ad-button"
                        >

                            ช้อปสินค้า

                            <i class="fa-solid fa-arrow-right"></i>

                        </a>


                    </div>

                </div>

            </div>


            <!-- =================================================
                 AD 3
            ================================================== -->

            <div class="veloura-ad-slide">


                <div class="ad-new-product">


                    <div class="ad-new-image">


                        <div class="new-label">

                            ✦ NEW ARRIVAL

                        </div>


                        <img
                            src="<?= htmlspecialchars($new_product_image) ?>"
                            alt="<?= htmlspecialchars($new_product_name) ?>"
                            onerror="this.src='images/perfume-hero.jpg';"
                        >

                    </div>


                    <div class="ad-new-content">


                        <div class="ad-kicker">

                            VELOURA NEW COLLECTION

                        </div>


                        <h2>

                            Meet<br>

                            <em>
                                the New Scent
                            </em>

                        </h2>


                        <div class="ad-new-name">

                            <?= htmlspecialchars($new_product_name) ?>

                        </div>


                        <p class="ad-new-description">

                            <?= htmlspecialchars($new_product_description) ?>

                        </p>


                        <div class="ad-new-price">

                            <?= htmlspecialchars($new_product_price) ?>

                        </div>


                        <?php if ($new_product): ?>

                            <a
                                href="products.php?id=<?= (int)$new_product['id'] ?>"
                                class="ad-button"
                            >

                                ดูสินค้าใหม่

                                <i class="fa-solid fa-arrow-right"></i>

                            </a>

                        <?php else: ?>

                            <a
                                href="products.php"
                                class="ad-button"
                            >

                                ดูสินค้าทั้งหมด

                                <i class="fa-solid fa-arrow-right"></i>

                            </a>

                        <?php endif; ?>


                    </div>

                </div>

            </div>


        </div>


        <!-- PREVIOUS -->

        <button
            type="button"
            class="ad-arrow ad-prev"
            id="adPrev"
            aria-label="โฆษณาก่อนหน้า"
        >

            <i class="fa-solid fa-chevron-left"></i>

        </button>


        <!-- NEXT -->

        <button
            type="button"
            class="ad-arrow ad-next"
            id="adNext"
            aria-label="โฆษณาถัดไป"
        >

            <i class="fa-solid fa-chevron-right"></i>

        </button>


        <!-- DOTS -->

        <div class="ad-controls">


            <button
                type="button"
                class="ad-dot active"
                data-slide="0"
                aria-label="โฆษณาที่ 1"
            ></button>


            <button
                type="button"
                class="ad-dot"
                data-slide="1"
                aria-label="โฆษณาที่ 2"
            ></button>


            <button
                type="button"
                class="ad-dot"
                data-slide="2"
                aria-label="โฆษณาที่ 3"
            ></button>


        </div>


        <!-- PROGRESS -->

        <div
            class="ad-progress"
            id="adProgress"
        ></div>


    </div>

</div>


<!-- =====================================================
     FLOATING SPARKLES
===================================================== -->

<div
    class="sparkles"
    aria-hidden="true"
>

    <span>✦</span>

    <span>✧</span>

    <span>⋆</span>

    <span>✦</span>

    <span>✧</span>

    <span>⋆</span>

    <span>✦</span>

    <span>✧</span>

</div>


<!-- =====================================================
     TOP BAR
===================================================== -->

<div class="top-bar">

    ✦ จัดส่งฟรี เมื่อสั่งซื้อครบ $50

    &nbsp; • &nbsp;

    LUXURY FRAGRANCE FOR EVERY MOMENT ✦

</div>


<!-- =====================================================
     NAVBAR
===================================================== -->

<nav>


    <a
        href="index.php"
        class="logo"
    >

        <span class="logo-main">
            VELOURA
        </span>

        <span class="logo-sub">
            PERFUMES
        </span>

    </a>


    <div class="nav-links">


        <a
            href="index.php"
            class="active"
        >
            หน้าแรก
        </a>


        <a href="products.php">
            สินค้า
        </a>


        <a href="about.php">
            เกี่ยวกับเรา
        </a>


        <a href="collection.php">
            คอลเลกชัน
        </a>


        <a href="find-scent.php">
            ค้นหากลิ่น
        </a>


        <a href="contact.php">
            ติดต่อเรา
        </a>


    </div>


    <div class="nav-right">


        <a href="profile.php">
            บัญชี
        </a>


        <a
            href="cart.php"
            class="cart"
        >

            <i class="fa-solid fa-bag-shopping"></i>


            <?php if ($cart_count > 0): ?>

                <span class="cart-count">

                    <?= $cart_count ?>

                </span>

            <?php endif; ?>


        </a>


    </div>


</nav>


<!-- =====================================================
     HERO
===================================================== -->

<section class="hero">


    <div class="hero-image">


        <img
            src="images/perfume-hero.jpg"
            alt="Veloura Luxury Perfume"
        >


    </div>


    <div class="hero-content">


        <div class="eyebrow">

            ✦ THE ART OF FRAGRANCE ✦

        </div>


        <h1>

            กลิ่นหอมที่<br>

            <em>
                เป็นตัวคุณ
            </em>

        </h1>


        <div class="hero-line"></div>


        <p class="hero-description">

            ค้นพบเสน่ห์ของน้ำหอมที่คัดสรรอย่างพิถีพิถัน
            ให้ทุกกลิ่นบอกเล่าเรื่องราวและสะท้อนตัวตนของคุณ

        </p>


        <a
            href="products.php"
            class="shop-button"
        >

            เลือกซื้อน้ำหอม

            <i class="fa-solid fa-arrow-right"></i>

        </a>


        <div class="hero-note">

            ✧ EAU DE PARFUM • SIGNATURE COLLECTION ✧

        </div>


    </div>

</section>


<!-- =====================================================
     INTRO
===================================================== -->

<section class="intro">


    <div class="intro-kicker">

        VELOURA SIGNATURE ✦

    </div>


    <h2>

        คอลเลกชันแนะนำ

    </h2>


    <p>

        สามกลิ่นที่คัดสรรจากสินค้าจริงในคอลเลกชันของ VELOURA

    </p>


</section>


<!-- =====================================================
     PRODUCTS
===================================================== -->

<section class="collection">


    <div class="products">


        <?php if (count($featured_products) > 0): ?>


            <?php foreach ($featured_products as $index => $product): ?>


                <a
                    href="products.php?id=<?= (int)$product['id'] ?>"
                    class="product"
                >


                    <div class="product-image">


                        <?php if ($index === 0): ?>

                            <div class="product-tag">

                                ✦ BEST SELLER

                            </div>

                        <?php elseif ($index === 1): ?>

                            <div class="product-tag">

                                ✧ SIGNATURE

                            </div>

                        <?php endif; ?>


                        <img
                            src="<?= htmlspecialchars($product['image']) ?>"
                            alt="<?= htmlspecialchars($product['name']) ?>"
                            onerror="this.src='images/perfume-hero.jpg';"
                        >


                    </div>


                    <!-- =================================================
                         PRODUCT INFO
                         แสดงเฉพาะชื่อ + ราคา
                    ================================================== -->

                    <div class="product-info">


                        <h3>

                            <?= htmlspecialchars($product['name']) ?>

                        </h3>


                        <div class="product-bottom">


                            <span class="product-price">

                                $

                                <?= number_format(
                                    (float)$product['price'],
                                    2
                                ) ?>

                            </span>


                        </div>


                    </div>


                </a>


            <?php endforeach; ?>


        <?php else: ?>


            <p
                style="
                    grid-column:1/-1;
                    text-align:center;
                    color:#998d85;
                    padding:50px 0;
                "
            >

                ยังไม่มีสินค้าในฐานข้อมูล

            </p>


        <?php endif; ?>


    </div>

</section>


<!-- =====================================================
     NEWSLETTER CTA
===================================================== -->

<section class="newsletter-cta-section">


    <div class="newsletter-cta-decoration">

        ✦ &nbsp; ✧ &nbsp; ⋆ &nbsp; ✧ &nbsp; ✦

    </div>


    <div class="newsletter-cta-content">


        <div class="newsletter-cta-kicker">

            VELOURA PRIVILEGE

        </div>


        <h2>

            เรื่องราวดี ๆ

            <em>
                ส่งตรงถึงคุณ
            </em>

        </h2>


        <p>

            รับข่าวสารคอลเลกชันใหม่ โปรโมชั่นพิเศษ
            และเรื่องราวของกลิ่นหอมจาก VELOURA

        </p>


        <a
            href="newsletter.php"
            class="newsletter-main-button"
        >


            <span class="button-icon">

                <i class="fa-regular fa-envelope"></i>

            </span>


            <span>


                <strong>

                    สมัครรับข่าวสาร

                </strong>


                <small>

                    VELOURA NEWSLETTER

                </small>


            </span>


            <i
                class="fa-solid fa-arrow-right button-arrow"
            ></i>


        </a>


        <div class="newsletter-cta-note">

            ✧ สมัครฟรี • รับสิทธิพิเศษเฉพาะสมาชิก ✧

        </div>


    </div>

</section>


<!-- =====================================================
     STORY
===================================================== -->

<section class="story">


    <div class="story-content">


        <div class="story-kicker">

            ABOUT VELOURA ✧

        </div>


        <h2>

            หอมอย่างมี<br>

            เอกลักษณ์

        </h2>


        <p>

            VELOURA เชื่อว่าน้ำหอมไม่ใช่เพียงกลิ่นหอม
            แต่คือรายละเอียดเล็ก ๆ ที่ช่วยสะท้อนตัวตน
            ความรู้สึก และความทรงจำของผู้สวมใส่

        </p>


    </div>


    <div class="story-mark">

        V

    </div>


</section>


<!-- =====================================================
     FOOTER
===================================================== -->

<footer>


    <div class="footer-container">


        <div>


            <div class="footer-logo">

                VELOURA

            </div>


            <p>

                Luxury fragrance crafted for those

                <br>

                who embrace their individuality.

            </p>


        </div>


        <div>


            <h3>

                เมนู

            </h3>


            <a href="index.php">

                หน้าแรก

            </a>


            <a href="products.php">

                สินค้า

            </a>


            <a href="about.php">

                เกี่ยวกับเรา

            </a>


            <a href="collection.php">

                คอลเลกชัน

            </a>


        </div>


        <div>


            <h3>

                บริการ

            </h3>


            <a href="find-scent.php">

                ค้นหากลิ่น

            </a>


            <a href="contact.php">

                ติดต่อเรา

            </a>


            <a href="profile.php">

                บัญชีของฉัน

            </a>


            <a href="cart.php">

                รถเข็น

            </a>


        </div>


    </div>


    <div class="copyright">

        © <?= date("Y") ?>

        VELOURA PERFUMES.

        All Rights Reserved. ✦

    </div>


</footer>


<!-- =====================================================
     JAVASCRIPT : 3 AD SLIDER
===================================================== -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function(){

        const ad =
            document.getElementById(
                "velouraAd"
            );


        const closeAd =
            document.getElementById(
                "closeVelouraAd"
            );


        const slides =
            document.querySelectorAll(
                ".veloura-ad-slide"
            );


        const dots =
            document.querySelectorAll(
                ".ad-dot"
            );


        const prev =
            document.getElementById(
                "adPrev"
            );


        const next =
            document.getElementById(
                "adNext"
            );


        const progress =
            document.getElementById(
                "adProgress"
            );


        if(
            !ad ||
            !closeAd ||
            slides.length === 0
        ){

            return;

        }


        let currentSlide = 0;

        let autoSlide = null;

        const slideTime = 4500;


        /* =================================================
           SHOW SLIDE
        ================================================= */

        function showSlide(index){


            if(index < 0){

                index =
                    slides.length - 1;

            }


            if(index >= slides.length){

                index = 0;

            }


            slides.forEach(
                function(slide){

                    slide.classList.remove(
                        "active"
                    );

                }
            );


            dots.forEach(
                function(dot){

                    dot.classList.remove(
                        "active"
                    );

                }
            );


            slides[index].classList.add(
                "active"
            );


            if(dots[index]){

                dots[index].classList.add(
                    "active"
                );

            }


            currentSlide = index;


            /* Progress bar */

            if(progress){

                progress.style.width =
                    (
                        (index + 1)
                        /
                        slides.length
                        *
                        100
                    )
                    +
                    "%";

            }

        }


        /* =================================================
           NEXT
        ================================================= */

        function nextSlide(){

            showSlide(
                currentSlide + 1
            );

            restartAutoSlide();

        }


        /* =================================================
           PREVIOUS
        ================================================= */

        function prevSlide(){

            showSlide(
                currentSlide - 1
            );

            restartAutoSlide();

        }


        /* =================================================
           AUTO SLIDE
        ================================================= */

        function startAutoSlide(){

            autoSlide =
                setInterval(
                    function(){

                        showSlide(
                            currentSlide + 1
                        );

                    },
                    slideTime
                );

        }


        function restartAutoSlide(){

            clearInterval(
                autoSlide
            );

            startAutoSlide();

        }


        /* =================================================
           BUTTONS
        ================================================= */

        if(next){

            next.addEventListener(
                "click",
                nextSlide
            );

        }


        if(prev){

            prev.addEventListener(
                "click",
                prevSlide
            );

        }


        /* =================================================
           DOTS
        ================================================= */

        dots.forEach(
            function(dot){

                dot.addEventListener(
                    "click",
                    function(){

                        const index =
                            parseInt(
                                dot.getAttribute(
                                    "data-slide"
                                )
                            );


                        showSlide(index);

                        restartAutoSlide();

                    }
                );

            }
        );


        /* =================================================
           CLOSE
        ================================================= */

        function hideAd(){

            ad.style.opacity = "0";

            ad.style.transition =
                "opacity .25s ease";


            setTimeout(
                function(){

                    ad.style.display =
                        "none";

                },
                250
            );


            sessionStorage.setItem(
                "velouraAdsClosed",
                "1"
            );


            clearInterval(
                autoSlide
            );

        }


        closeAd.addEventListener(
            "click",
            hideAd
        );


        /* =================================================
           CLICK OUTSIDE POPUP
        ================================================= */

        ad.addEventListener(
            "click",
            function(event){

                if(
                    event.target === ad
                ){

                    hideAd();

                }

            }
        );


        /* =================================================
           ESC
        ================================================= */

        document.addEventListener(
            "keydown",
            function(event){

                if(
                    event.key === "Escape" &&
                    ad.style.display !== "none"
                ){

                    hideAd();

                }

            }
        );


        /* =================================================
           TOUCH / SWIPE MOBILE
        ================================================= */

        let touchStartX = 0;

        let touchEndX = 0;


        ad.addEventListener(
            "touchstart",
            function(event){

                touchStartX =
                    event.changedTouches[0]
                        .screenX;

            },
            {
                passive:true
            }
        );


        ad.addEventListener(
            "touchend",
            function(event){

                touchEndX =
                    event.changedTouches[0]
                        .screenX;


                const distance =
                    touchStartX -
                    touchEndX;


                if(
                    Math.abs(distance) < 50
                ){

                    return;

                }


                if(distance > 0){

                    nextSlide();

                }else{

                    prevSlide();

                }

            },
            {
                passive:true
            }
        );


        /* =================================================
           แสดง Popup แค่ครั้งเดียวต่อ Session
        ================================================= */

        if(
            sessionStorage.getItem(
                "velouraAdsClosed"
            ) === "1"
        ){

            ad.style.display =
                "none";

            return;

        }


        /* =================================================
           เริ่ม Slider
        ================================================= */

        showSlide(0);

        startAutoSlide();

    }
);

</script>


</body>

</html>
