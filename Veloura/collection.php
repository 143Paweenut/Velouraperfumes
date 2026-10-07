<?php

session_start();

require_once "connect.php";

/* =========================================================
   CART SESSION
========================================================= */

if (!isset($_SESSION["cart"]) || !is_array($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}


/* =========================================================
   CART COUNT
========================================================= */

$cart_count = 0;

foreach ($_SESSION["cart"] as $quantity) {
    $cart_count += intval($quantity);
}


/* =========================================================
   GET PRODUCTS
========================================================= */

$sql = "
    SELECT id, name, price, image, description
    FROM products
    ORDER BY id ASC
";

$product_result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="th">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>คอลเลกชัน | VELOURA PERFUMES</title>


    <!-- =====================================================
         GOOGLE FONTS
    ====================================================== -->

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
    ====================================================== -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


<style>

/* =========================================================
   RESET
========================================================= */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {

    font-family:
        "Noto Sans Thai",
        "Montserrat",
        sans-serif;

    color: #33282d;

    background:

        radial-gradient(
            circle at 8% 10%,
            rgba(242,192,211,.24),
            transparent 25%
        ),

        radial-gradient(
            circle at 90% 25%,
            rgba(232,184,205,.20),
            transparent 28%
        ),

        radial-gradient(
            circle at 50% 85%,
            rgba(247,210,225,.20),
            transparent 30%
        ),

        #fffafd;

    overflow-x: hidden;
}

a {
    text-decoration: none;
    color: inherit;
}


/* =========================================================
   SELECTION
========================================================= */

::selection {
    background: #e9bfd1;
    color: #3c2932;
}


/* =========================================================
   SPARKLE LAYER
========================================================= */

.sparkle-layer {

    position: fixed;

    inset: 0;

    pointer-events: none;

    z-index: 9999;

    overflow: hidden;
}

.sparkle {

    position: absolute;

    color: #dca9c1;

    font-family:
        "Cormorant Garamond",
        serif;

    font-size: 18px;

    opacity: .4;

    text-shadow:
        0 0 5px rgba(220,169,193,.7),
        0 0 15px rgba(220,169,193,.5);

    animation:
        twinkle 4s ease-in-out infinite;
}

.s1 {
    top: 12%;
    left: 7%;
}

.s2 {
    top: 22%;
    left: 20%;
    animation-delay: 1s;
}

.s3 {
    top: 16%;
    right: 15%;
    animation-delay: 2s;
}

.s4 {
    top: 36%;
    right: 7%;
    animation-delay: 1.4s;
}

.s5 {
    top: 50%;
    left: 5%;
    animation-delay: 2.4s;
}

.s6 {
    top: 65%;
    right: 17%;
    animation-delay: .8s;
}

.s7 {
    top: 76%;
    left: 12%;
    animation-delay: 3s;
}

.s8 {
    top: 82%;
    right: 9%;
    animation-delay: 1.7s;
}

.s9 {
    top: 91%;
    left: 43%;
    animation-delay: 2.8s;
}

.s10 {
    top: 46%;
    left: 47%;
    animation-delay: 1.2s;
}

@keyframes twinkle {

    0%,
    100% {

        opacity: .15;

        transform:
            scale(.75)
            rotate(0deg);
    }

    50% {

        opacity: .9;

        transform:
            scale(1.35)
            rotate(18deg);
    }
}


/* =========================================================
   TOP BAR
========================================================= */

.top-bar {

    position: relative;

    height: 36px;

    display: flex;

    align-items: center;

    justify-content: center;

    background:

        linear-gradient(
            90deg,
            #30232a,
            #513442,
            #30232a
        );

    color: white;

    font-family:
        "Montserrat",
        sans-serif;

    font-size: 9px;

    letter-spacing: 2.5px;

    overflow: hidden;
}

.top-bar::after {

    content: "";

    position: absolute;

    top: 0;

    left: -100%;

    width: 55%;

    height: 100%;

    background:

        linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,.18),
            transparent
        );

    animation:
        topShine 5s infinite;
}

@keyframes topShine {

    0% {
        left: -100%;
    }

    50% {
        left: 130%;
    }

    100% {
        left: 130%;
    }
}


/* =========================================================
   NAVBAR
========================================================= */

nav {

    position: sticky;

    top: 0;

    z-index: 1000;

    min-height: 92px;

    display: grid;

    grid-template-columns:
        220px
        1fr
        220px;

    align-items: center;

    padding: 0 5%;

    background:
        rgba(255,250,252,.86);

    backdrop-filter:
        blur(20px);

    -webkit-backdrop-filter:
        blur(20px);

    border-bottom:
        1px solid
        rgba(183,137,157,.15);

    box-shadow:
        0 5px 25px
        rgba(105,65,82,.04);
}


/* =========================================================
   LOGO
========================================================= */

.logo {

    display: inline-block;

    text-align: left;

    line-height: 1;
}

.logo-main {

    display: block;

    font-family:
        "Cormorant Garamond",
        serif;

    font-size: 33px;

    font-weight: 600;

    letter-spacing: 7px;

    color: #382b31;

    transition: .3s;
}

.logo:hover .logo-main {
    color: #a55779;
}

.logo-sub {

    display: flex;

    align-items: center;

    gap: 8px;

    margin-top: 7px;

    font-family:
        "Montserrat",
        sans-serif;

    font-size: 8px;

    letter-spacing: 4px;

    color: #a8798d;
}

.logo-sub::before,
.logo-sub::after {

    content: "";

    width: 22px;

    height: 1px;

    background: #cda1b4;
}


/* =========================================================
   NAV LINKS
========================================================= */

.nav-links {

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 27px;
}

.nav-links a {

    position: relative;

    padding: 10px 0;

    font-family:
        "Montserrat",
        sans-serif;

    font-size: 10px;

    font-weight: 500;

    color: #725d65;

    transition: .3s;
}

.nav-links a::after {

    content: "";

    position: absolute;

    bottom: 3px;

    left: 50%;

    width: 0;

    height: 1px;

    background: #b77894;

    transform:
        translateX(-50%);

    transition:
        width .3s ease;
}

.nav-links a:hover,
.nav-links a.active {

    color: #a55779;
}

.nav-links a:hover::after,
.nav-links a.active::after {

    width: 100%;
}


/* =========================================================
   NAV RIGHT
========================================================= */

.nav-right {

    display: flex;

    justify-content: flex-end;

    align-items: center;

    gap: 10px;
}

.account-btn {

    width: 39px;

    height: 39px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    color: #725d65;

    background:
        rgba(255,255,255,.8);

    border:
        1px solid
        rgba(190,143,164,.22);

    transition: .3s;
}

.account-btn:hover {

    color: #a55779;

    transform:
        translateY(-3px);

    box-shadow:
        0 8px 20px
        rgba(165,87,121,.12);
}

.cart-btn {

    min-width: 82px;

    height: 39px;

    padding: 0 12px;

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 8px;

    border-radius: 30px;

    background:
        linear-gradient(
            135deg,
            rgba(248,224,234,.8),
            rgba(239,204,219,.75)
        );

    border:
        1px solid
        rgba(190,143,164,.25);

    color: #725d65;

    font-size: 12px;

    transition: .3s;
}

.cart-btn:hover {

    transform:
        translateY(-3px);

    box-shadow:
        0 10px 25px
        rgba(165,87,121,.12);
}

.cart-count {

    min-width: 20px;

    height: 20px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background:

        linear-gradient(
            135deg,
            #a55779,
            #d493af
        );

    color: white;

    font-family:
        "Montserrat",
        sans-serif;

    font-size: 9px;
}


/* =========================================================
   HERO
========================================================= */

.collection-hero {

    position: relative;

    min-height: 530px;

    display: flex;

    align-items: center;

    justify-content: center;

    text-align: center;

    color: white;

    background:

        linear-gradient(
            135deg,
            rgba(43,29,37,.60),
            rgba(76,48,61,.66)
        ),

        url("images/perfume-hero.jpg")
        center / cover no-repeat;

    overflow: hidden;
}

.collection-hero::before {

    content: "";

    position: absolute;

    width: 550px;

    height: 550px;

    border-radius: 50%;

    background:

        radial-gradient(
            circle,
            rgba(255,201,222,.20),
            transparent 70%
        );

    filter: blur(5px);

    animation:
        heroOrb 9s ease-in-out infinite;
}

.collection-hero::after {

    content: "";

    position: absolute;

    inset: 22px;

    border:
        1px solid
        rgba(255,255,255,.18);

    pointer-events: none;
}

@keyframes heroOrb {

    0%,
    100% {

        transform:
            translate(-100px,-20px)
            scale(1);
    }

    50% {

        transform:
            translate(100px,35px)
            scale(1.12);
    }
}

.hero-content {

    position: relative;

    z-index: 2;

    max-width: 900px;

    padding: 50px 25px;
}

.hero-kicker {

    margin-bottom: 17px;

    font-family:
        "Montserrat",
        sans-serif;

    font-size: 10px;

    letter-spacing: 6px;

    color: #f2cedd;
}

.hero-content h1 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:
        clamp(60px,9vw,112px);

    font-weight: 500;

    line-height: .85;

    letter-spacing: 9px;

    text-shadow:
        0 10px 30px
        rgba(0,0,0,.35);
}

.hero-line {

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 15px;

    width: min(370px,80%);

    margin: 28px auto;
}

.hero-line span {

    height: 1px;

    flex: 1;

    background:

        linear-gradient(
            90deg,
            transparent,
            rgba(255,220,233,.85)
        );
}

.hero-line span:last-child {

    background:

        linear-gradient(
            90deg,
            rgba(255,220,233,.85),
            transparent
        );
}

.hero-line i {

    color: #f4cadc;

    font-size: 14px;

    animation:
        sparkleRotate 3s ease-in-out infinite;
}

@keyframes sparkleRotate {

    0%,
    100% {
        transform: scale(1) rotate(0);
    }

    50% {
        transform: scale(1.3) rotate(15deg);
    }
}

.hero-content p {

    font-family:
        "Montserrat",
        sans-serif;

    font-size: 10px;

    letter-spacing: 5px;

    color:
        rgba(255,255,255,.88);
}


/* =========================================================
   INTRO
========================================================= */

.intro {

    max-width: 900px;

    margin: auto;

    padding:
        105px 30px 80px;

    text-align: center;
}

.intro-kicker {

    font-family:
        "Montserrat",
        sans-serif;

    font-size: 9px;

    letter-spacing: 5px;

    color: #b47791;

    margin-bottom: 13px;
}

.intro h2 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:
        clamp(43px,5vw,62px);

    font-weight: 600;

    color: #382b31;

    margin-bottom: 15px;
}

.line {

    width: 70px;

    height: 1px;

    margin:
        0 auto 28px;

    background:

        linear-gradient(
            90deg,
            transparent,
            #b97895,
            transparent
        );
}

.intro p {

    max-width: 730px;

    margin: auto;

    color: #76666e;

    font-size: 14px;

    line-height: 2.1;

    font-weight: 300;
}


/* =========================================================
   PRODUCTS
========================================================= */

.products {

    max-width: 1380px;

    margin: auto;

    padding:
        15px 5%
        105px;
}


/* =========================================================
   PRODUCT ROW
========================================================= */

.product-row {

    position: relative;

    display: grid;

    grid-template-columns:
        1fr
        1fr;

    min-height: 560px;

    margin-bottom: 45px;

    background:
        rgba(255,255,255,.76);

    border:
        1px solid
        rgba(188,140,161,.18);

    box-shadow:
        0 22px 65px
        rgba(108,67,85,.08);

    overflow: hidden;

    transition:
        transform .45s ease,
        box-shadow .45s ease;
}

.product-row:hover {

    transform:
        translateY(-7px);

    box-shadow:
        0 30px 80px
        rgba(108,67,85,.14);
}

.product-row::before {

    content: "✦";

    position: absolute;

    top: 22px;

    right: 28px;

    z-index: 10;

    color: #d9a8bc;

    font-size: 15px;

    opacity: .7;

    animation:
        twinkle 4s ease-in-out infinite;
}


/* =========================================================
   IMAGE
========================================================= */

.product-image {

    position: relative;

    height: 560px;

    overflow: hidden;

    background:

        linear-gradient(
            135deg,
            #f8e9ef,
            #eee0e6
        );
}

.product-image::before {

    content: "";

    position: absolute;

    inset: 0;

    z-index: 2;

    background:

        radial-gradient(
            circle at 30% 30%,
            rgba(255,255,255,.30),
            transparent 35%
        );

    pointer-events: none;
}

.product-image::after {

    content: "";

    position: absolute;

    inset: 0;

    z-index: 3;

    background:

        linear-gradient(
            110deg,
            transparent 30%,
            rgba(255,255,255,.38) 48%,
            transparent 65%
        );

    transform:
        translateX(-120%);

    transition:
        transform .9s ease;

    pointer-events: none;
}

.product-row:hover
.product-image::after {

    transform:
        translateX(120%);
}

.product-image img {

    width: 100%;

    height: 100%;

    object-fit: cover;

    display: block;

    transition:
        transform .9s ease;
}

.product-row:hover
.product-image img {

    transform:
        scale(1.055);
}


/* =========================================================
   IMAGE BADGE
========================================================= */

.image-badge {

    position: absolute;

    top: 28px;

    left: 28px;

    z-index: 5;

    padding:
        8px 13px;

    background:
        rgba(255,255,255,.78);

    backdrop-filter:
        blur(10px);

    border:
        1px solid
        rgba(255,255,255,.7);

    color: #80566a;

    font-family:
        "Montserrat",
        sans-serif;

    font-size: 8px;

    letter-spacing: 2px;
}


/* =========================================================
   PRODUCT INFO
========================================================= */

.product-info {

    position: relative;

    display: flex;

    flex-direction: column;

    justify-content: center;

    padding: 75px;

    background:

        linear-gradient(
            145deg,
            rgba(255,255,255,.88),
            rgba(253,244,248,.68)
        );
}

.product-info::before {

    content: "";

    position: absolute;

    top: 45px;

    left: 0;

    width: 1px;

    height: 95px;

    background:

        linear-gradient(
            transparent,
            #d5a4b8,
            transparent
        );
}

.product-info::after {

    content: "VELOURA";

    position: absolute;

    bottom: 28px;

    right: 35px;

    font-family:
        "Cormorant Garamond",
        serif;

    font-size: 48px;

    letter-spacing: 5px;

    color:
        rgba(180,119,145,.06);

    pointer-events: none;
}

.product-number {

    font-family:
        "Montserrat",
        sans-serif;

    font-size: 9px;

    letter-spacing: 4px;

    color: #b47791;

    margin-bottom: 18px;
}

.product-info h2 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:
        clamp(42px,4vw,64px);

    font-weight: 600;

    color: #3d3036;

    line-height: .95;

    margin-bottom: 22px;

    max-width: 600px;
}

.product-line {

    width: 62px;

    height: 1px;

    background:

        linear-gradient(
            90deg,
            #b47791,
            transparent
        );

    margin-bottom: 25px;
}

.product-description {

    max-width: 500px;

    color: #76666e;

    font-size: 13px;

    line-height: 2;

    font-weight: 300;

    margin-bottom: 22px;
}

.product-price {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size: 29px;

    color: #a56681;

    letter-spacing: 1px;

    margin-bottom: 28px;
}


/* =========================================================
   PRODUCT BUTTON
========================================================= */

.product-button {

    position: relative;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 11px;

    width: fit-content;

    padding:
        14px 28px;

    background:

        linear-gradient(
            135deg,
            #392b31,
            #50333f
        );

    color: white;

    font-family:
        "Montserrat",
        sans-serif;

    font-size: 10px;

    letter-spacing: 1.3px;

    overflow: hidden;

    transition: .35s;
}

.product-button::before {

    content: "";

    position: absolute;

    top: 0;

    left: -110%;

    width: 70%;

    height: 100%;

    background:

        linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,.24),
            transparent
        );

    transition:
        left .55s ease;
}

.product-button i {

    font-size: 9px;

    transition:
        transform .3s ease;
}

.product-button:hover {

    transform:
        translateY(-3px);

    background:

        linear-gradient(
            135deg,
            #a55779,
            #c783a0
        );

    box-shadow:
        0 12px 30px
        rgba(165,87,121,.20);
}

.product-button:hover::before {

    left: 130%;
}

.product-button:hover i {

    transform:
        translateX(4px);
}


/* =========================================================
   REVERSE
========================================================= */

.product-row.reverse
.product-image {

    order: 2;
}

.product-row.reverse
.product-info {

    order: 1;
}


/* =========================================================
   COLLECTION BANNER
========================================================= */

.collection-banner {

    position: relative;

    max-width: 1300px;

    min-height: 380px;

    margin:
        0 auto 105px;

    display: flex;

    align-items: center;

    justify-content: center;

    text-align: center;

    overflow: hidden;

    background:

        linear-gradient(
            rgba(48,33,41,.67),
            rgba(72,44,57,.76)
        ),

        url("images/perfume-hero.jpg")
        center / cover no-repeat;

    box-shadow:
        0 25px 65px
        rgba(86,53,68,.12);
}

.collection-banner::before {

    content: "";

    position: absolute;

    inset: 18px;

    border:
        1px solid
        rgba(255,255,255,.18);
}

.collection-banner::after {

    content: "✦";

    position: absolute;

    bottom: 28px;

    right: 11%;

    font-size: 23px;

    color: #edc3d5;

    opacity: .75;

    animation:
        twinkle 4s infinite;
}

.banner-corner {

    position: absolute;

    top: 28px;

    left: 11%;

    font-size: 24px;

    color: #edc3d5;

    opacity: .75;
}

.banner-content {

    position: relative;

    z-index: 2;

    max-width: 730px;

    padding: 45px 30px;
}

.banner-kicker {

    font-family:
        "Montserrat",
        sans-serif;

    font-size: 9px;

    letter-spacing: 5px;

    color: #e8bdce;

    margin-bottom: 16px;
}

.banner-content h2 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:
        clamp(45px,6vw,70px);

    color: white;

    font-weight: 500;

    line-height: .95;

    margin-bottom: 20px;
}

.banner-content p {

    color:
        rgba(255,255,255,.80);

    font-size: 13px;

    line-height: 2;

    font-weight: 300;
}


/* =========================================================
   FEATURE
========================================================= */

.feature {

    position: relative;

    padding:
        110px 5%;

    background:

        linear-gradient(
            180deg,
            rgba(248,226,236,.55),
            rgba(255,250,252,.85)
        );

    overflow: hidden;
}

.feature::before {

    content: "✧";

    position: absolute;

    top: 60px;

    right: 10%;

    color: #d9a8bc;

    font-size: 28px;

    opacity: .6;
}

.feature-container {

    max-width: 1180px;

    margin: auto;

    display: grid;

    grid-template-columns:
        1fr
        1fr;

    gap: 80px;

    align-items: center;
}


/* =========================================================
   FEATURE IMAGE
========================================================= */

.feature-image {

    position: relative;

    height: 520px;

    overflow: hidden;

    box-shadow:
        0 28px 65px
        rgba(106,68,85,.14);
}

.feature-image img {

    width: 100%;

    height: 100%;

    object-fit: cover;

    display: block;

    transition:
        transform .9s ease;
}

.feature-image:hover img {

    transform:
        scale(1.05);
}

.feature-image::before {

    content: "";

    position: absolute;

    inset: 18px;

    z-index: 2;

    border:
        1px solid
        rgba(255,255,255,.55);

    pointer-events: none;
}

.feature-image::after {

    content: "VELOURA";

    position: absolute;

    bottom: 27px;

    left: 30px;

    z-index: 3;

    font-family:
        "Cormorant Garamond",
        serif;

    font-size: 18px;

    letter-spacing: 4px;

    color: rgba(255,255,255,.75);
}


/* =========================================================
   FEATURE TEXT
========================================================= */

.feature-text {

    padding:
        20px 0;
}

.feature-kicker {

    font-family:
        "Montserrat",
        sans-serif;

    font-size: 9px;

    letter-spacing: 5px;

    color: #b47791;

    margin-bottom: 13px;
}

.feature-text h2 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:
        clamp(44px,5vw,64px);

    font-weight: 600;

    color: #3b2d34;

    line-height: .92;

    margin-bottom: 20px;
}

.feature-text .line {

    margin:
        0 0 26px;
}

.feature-text p {

    color: #76666e;

    font-size: 14px;

    line-height: 2;

    font-weight: 300;

    margin-bottom: 18px;

    max-width: 520px;
}

.feature-button {

    display: inline-flex;

    align-items: center;

    gap: 11px;

    margin-top: 10px;

    padding:
        14px 27px;

    background:

        linear-gradient(
            135deg,
            #3a2c32,
            #523541
        );

    color: white;

    font-family:
        "Montserrat",
        sans-serif;

    font-size: 10px;

    letter-spacing: 1px;

    transition: .35s;
}

.feature-button i {

    transition:
        transform .3s;
}

.feature-button:hover {

    background:

        linear-gradient(
            135deg,
            #a55779,
            #c783a0
        );

    transform:
        translateY(-3px);

    box-shadow:
        0 12px 30px
        rgba(165,87,121,.18);
}

.feature-button:hover i {

    transform:
        translateX(4px);
}


/* =========================================================
   NOTES
========================================================= */

.notes {

    max-width: 1180px;

    margin: auto;

    padding:
        110px 5%
        120px;

    text-align: center;
}

.notes-kicker {

    font-family:
        "Montserrat",
        sans-serif;

    font-size: 9px;

    letter-spacing: 5px;

    color: #b47791;

    margin-bottom: 13px;
}

.notes h2 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:
        clamp(44px,5vw,60px);

    font-weight: 600;

    color: #3a2d33;

    margin-bottom: 10px;
}

.notes-subtitle {

    font-family:
        "Montserrat",
        sans-serif;

    color: #927985;

    font-size: 9px;

    letter-spacing: 3px;

    margin-bottom: 52px;
}

.notes-grid {

    display: grid;

    grid-template-columns:
        repeat(3,1fr);

    gap: 25px;
}


/* =========================================================
   NOTE CARD
========================================================= */

.note-card {

    position: relative;

    min-height: 330px;

    padding:
        48px 30px;

    background:

        linear-gradient(
            145deg,
            rgba(255,255,255,.9),
            rgba(251,238,244,.72)
        );

    border:
        1px solid
        rgba(188,140,161,.18);

    box-shadow:
        0 17px 45px
        rgba(109,70,87,.07);

    overflow: hidden;

    transition:
        transform .4s ease,
        box-shadow .4s ease;
}

.note-card::before {

    content: "";

    position: absolute;

    top: -55px;

    right: -55px;

    width: 145px;

    height: 145px;

    border-radius: 50%;

    background:
        rgba(241,204,219,.30);

    transition:
        transform .5s ease;
}

.note-card::after {

    content: "✦";

    position: absolute;

    bottom: 20px;

    right: 25px;

    color: #d9a8bc;

    opacity: .45;

    font-size: 15px;
}

.note-card:hover {

    transform:
        translateY(-9px);

    box-shadow:
        0 27px 60px
        rgba(109,70,87,.13);
}

.note-card:hover::before {

    transform:
        scale(1.25);
}


/* =========================================================
   NOTE ICON
========================================================= */

.note-icon {

    width: 68px;

    height: 68px;

    margin:
        0 auto 23px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background:

        linear-gradient(
            135deg,
            #f7e1ea,
            #efd0df
        );

    color: #a85f7e;

    font-size: 22px;

    box-shadow:
        0 10px 28px
        rgba(177,116,145,.14);

    transition:
        transform .35s;
}

.note-card:hover
.note-icon {

    transform:
        rotate(-7deg)
        scale(1.08);
}

.note-card h3 {

    position: relative;

    font-family:
        "Cormorant Garamond",
        serif;

    font-size: 31px;

    font-weight: 600;

    color: #46343b;

    margin-bottom: 12px;
}

.note-card p {

    position: relative;

    color: #7d6b73;

    font-size: 13px;

    line-height: 1.9;

    font-weight: 300;
}


/* =========================================================
   FOOTER
========================================================= */

footer {

    position: relative;

    padding:
        75px 6% 30px;

    color: white;

    background:

        linear-gradient(
            135deg,
            #30232a,
            #4b303b,
            #30232a
        );

    overflow: hidden;
}

footer::before {

    content: "";

    position: absolute;

    top: -100px;

    left: 50%;

    width: 400px;

    height: 200px;

    transform:
        translateX(-50%);

    background:
        radial-gradient(
            ellipse,
            rgba(221,169,193,.13),
            transparent 70%
        );
}

.footer-container {

    position: relative;

    z-index: 2;

    max-width: 1200px;

    margin: auto;

    display: grid;

    grid-template-columns:
        2fr
        1fr
        1fr;

    gap: 60px;
}

.footer-logo {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size: 44px;

    font-weight: 600;

    letter-spacing: 7px;

    margin-bottom: 5px;
}

.footer-sub {

    font-family:
        "Montserrat",
        sans-serif;

    font-size: 8px;

    letter-spacing: 4px;

    color: #dcb5c6;

    margin-bottom: 18px;
}

footer p {

    max-width: 350px;

    color: #cfc0c7;

    font-size: 13px;

    line-height: 1.9;

    font-weight: 300;
}

footer h3 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size: 26px;

    font-weight: 500;

    margin-bottom: 18px;
}

footer a {

    display: block;

    color: #c8b8bf;

    font-size: 12px;

    margin-bottom: 12px;

    transition: .3s;
}

footer a:hover {

    color: #f1cddd;

    transform:
        translateX(4px);
}


/* =========================================================
   SOCIAL
========================================================= */

.footer-socials {

    display: flex;

    gap: 10px;

    margin-top: 25px;
}

.footer-socials a {

    width: 38px;

    height: 38px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    border:
        1px solid
        rgba(236,198,214,.25);

    color: #e6c4d2;

    transition: .3s;
}

.footer-socials a:hover {

    color: white;

    background:
        rgba(229,181,201,.15);

    transform:
        translateY(-4px);
}


/* =========================================================
   FOOTER SPARKLES
========================================================= */

.footer-sparkle {

    position: absolute;

    color:
        rgba(238,193,211,.45);

    font-size: 18px;

    animation:
        twinkle 4s ease-in-out infinite;
}

.fs1 {
    left: 12%;
    top: 30%;
}

.fs2 {
    right: 14%;
    top: 20%;
    animation-delay: 1s;
}

.fs3 {
    left: 28%;
    bottom: 25%;
    animation-delay: 2s;
}

.fs4 {
    right: 27%;
    bottom: 30%;
    animation-delay: 1.5s;
}


/* =========================================================
   COPYRIGHT
========================================================= */

.copyright {

    position: relative;

    z-index: 2;

    max-width: 1200px;

    margin:
        45px auto 0;

    padding-top: 20px;

    text-align: center;

    border-top:
        1px solid
        rgba(255,255,255,.12);

    color:
        rgba(255,255,255,.5);

    font-family:
        "Montserrat",
        sans-serif;

    font-size: 9px;

    letter-spacing: 1.5px;
}


/* =========================================================
   BACK TO TOP
========================================================= */

.back-top {

    position: fixed;

    right: 25px;

    bottom: 25px;

    width: 44px;

    height: 44px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background:
        rgba(255,239,246,.94);

    border:
        1px solid
        rgba(183,124,149,.25);

    color: #a35d7a;

    box-shadow:
        0 10px 25px
        rgba(91,55,70,.14);

    opacity: 0;

    visibility: hidden;

    transform:
        translateY(10px);

    transition: .3s;

    z-index: 999;
}

.back-top.show {

    opacity: 1;

    visibility: visible;

    transform:
        translateY(0);
}

.back-top:hover {

    transform:
        translateY(-5px);

    background:
        #f7dce8;
}


/* =========================================================
   MOUSE GLOW
========================================================= */

.mouse-glow {

    position: fixed;

    width: 260px;

    height: 260px;

    border-radius: 50%;

    pointer-events: none;

    background:

        radial-gradient(
            circle,
            rgba(238,184,207,.13),
            transparent 70%
        );

    transform:
        translate(-50%,-50%);

    z-index: 0;
}


/* =========================================================
   RESPONSIVE 1100
========================================================= */

@media (max-width:1100px) {

    nav {

        grid-template-columns:
            180px
            1fr
            150px;

        padding:
            0 3%;
    }

    .nav-links {

        gap: 16px;
    }

    .nav-links a {

        font-size: 9px;
    }

    .product-info {

        padding: 55px;
    }

    .feature-container {

        gap: 50px;
    }
}


/* =========================================================
   RESPONSIVE 900
========================================================= */

@media (max-width:900px) {

    nav {

        grid-template-columns:
            1fr auto;

        min-height: 82px;
    }

    .nav-links {
        display: none;
    }

    .product-row {

        grid-template-columns:
            1fr;

        min-height: auto;
    }

    .product-row.reverse
    .product-image {

        order: 1;
    }

    .product-row.reverse
    .product-info {

        order: 2;
    }

    .product-image {

        height: 460px;
    }

    .product-info {

        padding:
            60px 50px;
    }

    .feature-container {

        grid-template-columns:
            1fr;

        gap: 50px;
    }

    .feature-image {

        max-width: 700px;

        width: 100%;

        margin: auto;
    }

    .feature-text {

        text-align: center;
    }

    .feature-text p {

        margin-left: auto;

        margin-right: auto;
    }

    .feature-text .line {

        margin:
            0 auto 25px;
    }

    .notes-grid {

        grid-template-columns:
            1fr 1fr;
    }

    .footer-container {

        grid-template-columns:
            1fr 1fr;
    }

    .footer-container > div:first-child {

        grid-column:
            1 / -1;
    }
}


/* =========================================================
   RESPONSIVE 600
========================================================= */

@media (max-width:600px) {

    .top-bar {

        height: 34px;

        font-size: 7px;

        letter-spacing: 1px;
    }

    nav {

        padding:
            0 18px;
    }

    .logo-main {

        font-size: 27px;

        letter-spacing: 5px;
    }

    .logo-sub {

        font-size: 6px;

        letter-spacing: 2px;
    }

    .logo-sub::before,
    .logo-sub::after {

        width: 15px;
    }

    .account-btn {

        width: 35px;

        height: 35px;
    }

    .cart-btn {

        min-width: 60px;

        height: 35px;

        padding:
            0 9px;
    }

    .cart-btn span:not(.cart-count) {

        display: none;
    }

    .collection-hero {

        min-height: 430px;
    }

    .collection-hero::after {

        inset: 12px;
    }

    .hero-content h1 {

        font-size: 53px;

        letter-spacing: 4px;
    }

    .hero-kicker {

        font-size: 8px;

        letter-spacing: 3px;
    }

    .hero-content p {

        font-size: 8px;

        letter-spacing: 2px;
    }

    .intro {

        padding:
            75px 22px 55px;
    }

    .intro h2 {

        font-size: 43px;
    }

    .intro p {

        font-size: 13px;

        line-height: 2;
    }

    .products {

        padding:
            10px 18px 75px;
    }

    .product-row {

        margin-bottom: 28px;
    }

    .product-image {

        height: 370px;
    }

    .image-badge {

        top: 20px;

        left: 20px;

        font-size: 7px;
    }

    .product-info {

        padding:
            48px 28px;
    }

    .product-info h2 {

        font-size: 43px;
    }

    .product-description {

        font-size: 12px;
    }

    .product-button {

        padding:
            13px 21px;

        font-size: 9px;
    }

    .collection-banner {

        margin:
            0 18px 75px;

        min-height: 330px;
    }

    .banner-content {

        padding:
            30px 22px;
    }

    .banner-content h2 {

        font-size: 44px;
    }

    .banner-content p {

        font-size: 12px;
    }

    .feature {

        padding:
            75px 20px;
    }

    .feature-image {

        height: 400px;
    }

    .feature-text h2 {

        font-size: 46px;
    }

    .feature-text p {

        font-size: 13px;
    }

    .notes {

        padding:
            80px 20px 90px;
    }

    .notes-grid {

        grid-template-columns:
            1fr;
    }

    .note-card {

        min-height: auto;
    }

    .footer-container {

        grid-template-columns:
            1fr;
    }

    .footer-container > div:first-child {

        grid-column:
            auto;
    }

    footer {

        padding:
            65px 25px 30px;
    }

    .footer-logo {

        font-size: 36px;

        letter-spacing: 5px;
    }

    .back-top {

        right: 15px;

        bottom: 15px;
    }

    .mouse-glow {

        display: none;
    }
}


/* =========================================================
   VERY SMALL MOBILE
========================================================= */

@media (max-width:400px) {

    .logo-main {

        font-size: 24px;

        letter-spacing: 4px;
    }

    .hero-content h1 {

        font-size: 45px;
    }

    .product-info h2 {

        font-size: 38px;
    }

    .feature-text h2 {

        font-size: 40px;
    }
}


/* =========================================================
   REDUCED MOTION
========================================================= */

@media (prefers-reduced-motion: reduce) {

    *,
    *::before,
    *::after {

        animation-duration:
            .01ms !important;

        animation-iteration-count:
            1 !important;

        scroll-behavior:
            auto !important;

        transition-duration:
            .01ms !important;
    }
}

</style>

</head>


<body>


<!-- =========================================================
     SPARKLES
========================================================= -->

<div
    class="sparkle-layer"
    aria-hidden="true"
>

    <span class="sparkle s1">✦</span>
    <span class="sparkle s2">✧</span>
    <span class="sparkle s3">⋆</span>
    <span class="sparkle s4">✦</span>
    <span class="sparkle s5">✧</span>
    <span class="sparkle s6">⋆</span>
    <span class="sparkle s7">✦</span>
    <span class="sparkle s8">✧</span>
    <span class="sparkle s9">⋆</span>
    <span class="sparkle s10">✦</span>

</div>


<!-- =========================================================
     TOP BAR
========================================================= -->

<div class="top-bar">

    ✦ &nbsp;
    จัดส่งฟรี เมื่อสั่งซื้อครบ $50
    &nbsp; ✦

</div>


<!-- =========================================================
     NAVBAR
========================================================= -->

<nav>


    <!-- LOGO -->

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


    <!-- MENU -->

    <div class="nav-links">

        <a href="index.php">
            หน้าแรก
        </a>

        <a href="products.php">
            สินค้า
        </a>

        <a href="about.php">
            เกี่ยวกับเรา
        </a>

        <a
            href="collection.php"
            class="active"
        >
            คอลเลกชัน
        </a>

        <a href="find-scent.php">
            ค้นหากลิ่น
        </a>

        <a href="contact.php">
            ติดต่อเรา
        </a>

    </div>


    <!-- RIGHT -->

    <div class="nav-right">

        <a
            href="profile.php"
            class="account-btn"
            title="บัญชีของฉัน"
        >

            <i class="fa-regular fa-user"></i>

        </a>


        <a
            href="cart.php"
            class="cart-btn"
            title="รถเข็น"
        >

            <i class="fa-solid fa-bag-shopping"></i>

            <span>
                รถเข็น
            </span>

            <span class="cart-count">

                <?= $cart_count ?>

            </span>

        </a>

    </div>

</nav>


<!-- =========================================================
     HERO
========================================================= -->

<section class="collection-hero">

    <div class="hero-content">

        <div class="hero-kicker">

            THE ART OF FRAGRANCE

        </div>

        <h1>

            COLLECTIONS

        </h1>

        <div class="hero-line">

            <span></span>

            <i>✦</i>

            <span></span>

        </div>

        <p>

            DISCOVER YOUR SIGNATURE SCENT

        </p>

    </div>

</section>


<!-- =========================================================
     INTRO
========================================================= -->

<section class="intro">

    <div class="intro-kicker">

        DISCOVER VELOURA

    </div>

    <h2>

        คอลเลกชันของเรา

    </h2>

    <div class="line"></div>

    <p>

        ค้นพบโลกแห่งกลิ่นหอมจาก
        VELOURA PERFUMES
        ที่ถูกคัดสรรมาเพื่อสะท้อนบุคลิก
        อารมณ์ และความงดงามในแบบของคุณ
        เลือกกลิ่นที่ใช่ และสร้าง
        Signature Scent ที่เป็นเอกลักษณ์ของตัวเอง

    </p>

</section>


<!-- =========================================================
     PRODUCTS
========================================================= -->

<section class="products">


<?php if (
    $product_result &&
    $product_result->num_rows > 0
): ?>


    <?php

    $number = 1;

    ?>


    <?php while (
        $product =
        $product_result->fetch_assoc()
    ): ?>


        <?php

        $reverse =
            ($number % 2 === 0);

        ?>


        <article
            class="product-row <?= $reverse ? 'reverse' : '' ?>"
        >


            <!-- IMAGE -->

            <div class="product-image">


                <div class="image-badge">

                    EAU DE PARFUM

                </div>


                <img
                    src="<?= htmlspecialchars($product["image"]) ?>"
                    alt="<?= htmlspecialchars($product["name"]) ?>"
                    onerror="this.src='images/perfume-hero.jpg'"
                >

            </div>


            <!-- INFORMATION -->

            <div class="product-info">


                <div class="product-number">

                    COLLECTION

                    <?= str_pad(
                        $number,
                        2,
                        "0",
                        STR_PAD_LEFT
                    ) ?>

                </div>


                <h2>

                    <?= htmlspecialchars(
                        $product["name"]
                    ) ?>

                </h2>


                <div class="product-line"></div>


                <p class="product-description">

                    <?= htmlspecialchars(
                        $product["description"]
                        ?: "น้ำหอม Eau de Parfum"
                    ) ?>

                </p>


                <div class="product-price">

                    ฿
                    <?= number_format(
                        $product["price"],
                        2
                    ) ?>

                </div>


                <a
                    href="products.php"
                    class="product-button"
                >

                    ดูรายละเอียดสินค้า

                    <i class="fa-solid fa-arrow-right"></i>

                </a>


            </div>

        </article>


        <?php

        $number++;

        ?>


    <?php endwhile; ?>


<?php else: ?>


    <div
        style="
            text-align:center;
            padding:100px 20px;
            color:#806d75;
        "
    >

        <i
            class="fa-regular fa-face-sad-tear"
            style="
                font-size:40px;
                margin-bottom:20px;
                color:#c58da5;
            "
        ></i>

        <p>
            ยังไม่มีสินค้าในระบบ
        </p>

    </div>


<?php endif; ?>


</section>


<!-- =========================================================
     COLLECTION BANNER
========================================================= -->

<section class="collection-banner">

    <span class="banner-corner">
        ✦
    </span>


    <div class="banner-content">

        <div class="banner-kicker">

            YOUR SCENT · YOUR STORY

        </div>

        <h2>

            Find Your Signature

        </h2>

        <p>

            เพราะกลิ่นหอมที่ดีที่สุด
            คือกลิ่นที่ทำให้คุณรู้สึกว่า
            “นี่แหละคือฉัน”

        </p>

    </div>

</section>


<!-- =========================================================
     FEATURE
========================================================= -->

<section class="feature">

    <div class="feature-container">


        <!-- IMAGE -->

        <div class="feature-image">

            <img
                src="images/perfume4.jpg"
                alt="Veloura Perfume Collection"
                onerror="this.src='images/perfume-hero.jpg'"
            >

        </div>


        <!-- TEXT -->

        <div class="feature-text">


            <div class="feature-kicker">

                THE VELOURA EXPERIENCE

            </div>


            <h2>

                Find Your
                <br>
                Signature

            </h2>


            <div class="line"></div>


            <p>

                น้ำหอมไม่จำเป็นต้องมีเพียงหนึ่งกลิ่น
                เพราะแต่ละวันของคุณ
                อาจมีเรื่องราวและความรู้สึกที่แตกต่างกัน

            </p>


            <p>

                VELOURA จึงสร้างสรรค์คอลเลกชัน
                ที่หลากหลาย เพื่อให้คุณสามารถ
                เลือกกลิ่นที่เหมาะกับอารมณ์
                บุคลิก และช่วงเวลาของคุณ

            </p>


            <a
                href="find-scent.php"
                class="feature-button"
            >

                ค้นหากลิ่นที่เหมาะกับคุณ

                <i class="fa-solid fa-arrow-right"></i>

            </a>


        </div>

    </div>

</section>


<!-- =========================================================
     FRAGRANCE NOTES
========================================================= -->

<section class="notes">


    <div class="notes-kicker">

        FRAGRANCE FAMILIES

    </div>


    <h2>

        เลือกกลิ่นที่เป็นคุณ

    </h2>


    <div class="line"></div>


    <p class="notes-subtitle">

        FIND THE SCENT THAT SPEAKS FOR YOU

    </p>


    <div class="notes-grid">


        <!-- FLORAL -->

        <div class="note-card">


            <div class="note-icon">

                <i class="fa-solid fa-spa"></i>

            </div>


            <h3>

                Floral

            </h3>


            <p>

                กลิ่นดอกไม้ที่อ่อนหวาน
                สดใส และมีเสน่ห์
                เหมาะสำหรับคนที่ชื่นชอบ
                ความละมุน โรแมนติก
                และความเป็นผู้หญิง

            </p>


        </div>


        <!-- WOODY -->

        <div class="note-card">


            <div class="note-icon">

                <i class="fa-solid fa-tree"></i>

            </div>


            <h3>

                Woody

            </h3>


            <p>

                กลิ่นไม้ที่อบอุ่น ลุ่มลึก
                และสง่างาม
                เหมาะสำหรับผู้ที่ชอบ
                ความสุขุม มั่นใจ
                และมีเสน่ห์น่าค้นหา

            </p>


        </div>


        <!-- ORIENTAL -->

        <div class="note-card">


            <div class="note-icon">

                <i class="fa-solid fa-moon"></i>

            </div>


            <h3>

                Oriental

            </h3>


            <p>

                กลิ่นหอมเข้มข้น
                อบอุ่น และเย้ายวน
                เต็มไปด้วยความลึกลับ
                สำหรับผู้ที่ต้องการ
                ความโดดเด่นและน่าจดจำ

            </p>


        </div>


    </div>

</section>


<!-- =========================================================
     FOOTER
========================================================= -->

<footer>


    <span class="footer-sparkle fs1">
        ✦
    </span>

    <span class="footer-sparkle fs2">
        ✧
    </span>

    <span class="footer-sparkle fs3">
        ⋆
    </span>

    <span class="footer-sparkle fs4">
        ✦
    </span>


    <div class="footer-container">


        <!-- ABOUT -->

        <div>

            <div class="footer-logo">

                VELOURA

            </div>


            <div class="footer-sub">

                PERFUMES

            </div>


            <p>

                Luxury fragrance crafted
                for those who embrace
                their individuality.

            </p>


            <div class="footer-socials">


                <a
                    href="#"
                    aria-label="Facebook"
                >

                    <i class="fa-brands fa-facebook-f"></i>

                </a>


                <a
                    href="#"
                    aria-label="Instagram"
                >

                    <i class="fa-brands fa-instagram"></i>

                </a>


                <a
                    href="#"
                    aria-label="TikTok"
                >

                    <i class="fa-brands fa-tiktok"></i>

                </a>


            </div>

        </div>


        <!-- MENU -->

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

            <a href="find-scent.php">
                ค้นหากลิ่น
            </a>

        </div>


        <!-- SERVICE -->

        <div>

            <h3>
                บริการ
            </h3>

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
        ALL RIGHTS RESERVED.

    </div>


</footer>


<!-- =========================================================
     BACK TO TOP
========================================================= -->

<a
    href="#"
    class="back-top"
    id="backTop"
    aria-label="กลับด้านบน"
>

    <i class="fa-solid fa-chevron-up"></i>

</a>


<!-- =========================================================
     MOUSE GLOW
========================================================= -->

<div
    class="mouse-glow"
    id="mouseGlow"
></div>


<!-- =========================================================
     JAVASCRIPT
========================================================= -->

<script>


/* =========================================================
   BACK TO TOP
========================================================= */

const backTop =
    document.getElementById("backTop");


window.addEventListener(
    "scroll",
    function () {

        if (window.scrollY > 400) {

            backTop.classList.add("show");

        } else {

            backTop.classList.remove("show");

        }

    }
);


backTop.addEventListener(
    "click",
    function (e) {

        e.preventDefault();

        window.scrollTo({

            top: 0,

            behavior: "smooth"

        });

    }
);


/* =========================================================
   MOUSE GLOW
========================================================= */

const mouseGlow =
    document.getElementById("mouseGlow");


document.addEventListener(
    "mousemove",
    function (e) {

        mouseGlow.style.left =
            e.clientX + "px";

        mouseGlow.style.top =
            e.clientY + "px";

    }
);


/* =========================================================
   MOBILE
========================================================= */

function checkMobile() {

    if (window.innerWidth <= 700) {

        mouseGlow.style.display =
            "none";

    } else {

        mouseGlow.style.display =
            "block";

    }

}


checkMobile();


window.addEventListener(
    "resize",
    checkMobile
);


</script>


</body>

</html>