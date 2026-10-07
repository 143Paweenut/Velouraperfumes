<?php

session_start();

// ===============================
// จำนวนสินค้าในรถเข็น
// ===============================

$cart_count = 0;

if (isset($_SESSION["cart"]) && is_array($_SESSION["cart"])) {
    $cart_count = array_sum($_SESSION["cart"]);
}


// ===============================
// รับข้อความจากฟอร์ม
// ===============================

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $message = trim($_POST["message"] ?? "");

    if ($name === "" || $email === "" || $subject === "" || $message === "") {

        $error = "กรุณากรอกข้อมูลให้ครบทุกช่อง";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "กรุณากรอกอีเมลให้ถูกต้อง";

    } else {

        $success = "ส่งข้อความเรียบร้อยแล้ว ขอบคุณที่ติดต่อ VELOURA";

    }
}

?>

<!DOCTYPE html>

<html lang="th">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>ติดต่อเรา | VELOURA PERFUMES</title>


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

:root {

    --pink-dark: #74465a;
    --pink-deep: #9d5875;

    --pink: #d986a5;
    --pink-main: #e59ab5;

    --pink-light: #f5cfdd;
    --pink-pale: #fff1f6;

    --rose: #c87595;

    --gold: #c7a06b;

    --cream: #fffafd;

    --text: #49363e;

    --muted: #8e737d;
}


/* =========================================================
   BODY
========================================================= */

body {

    font-family:
        "Segoe UI",
        Tahoma,
        Arial,
        sans-serif;

    color: var(--text);

    background:

        radial-gradient(
            circle at 10% 20%,
            rgba(255, 190, 215, .35),
            transparent 24%
        ),

        radial-gradient(
            circle at 90% 35%,
            rgba(241, 173, 200, .30),
            transparent 25%
        ),

        radial-gradient(
            circle at 50% 90%,
            rgba(255, 214, 229, .40),
            transparent 30%
        ),

        linear-gradient(
            135deg,
            #fffafd,
            #fff2f7 45%,
            #fffafd
        );

    overflow-x: hidden;
}


/* =========================================================
   FLOATING GLOW
========================================================= */

body::before {

    content: "";

    position: fixed;

    width: 450px;
    height: 450px;

    border-radius: 50%;

    background: rgba(255, 180, 210, .20);

    filter: blur(100px);

    left: -200px;
    top: 20%;

    pointer-events: none;

    z-index: -1;
}

body::after {

    content: "";

    position: fixed;

    width: 400px;
    height: 400px;

    border-radius: 50%;

    background: rgba(218, 153, 181, .18);

    filter: blur(100px);

    right: -180px;
    bottom: 10%;

    pointer-events: none;

    z-index: -1;
}


/* =========================================================
   SPARKLE
========================================================= */

.sparkle {

    position: fixed;

    width: 5px;
    height: 5px;

    background: white;

    border-radius: 50%;

    box-shadow:

        0 0 5px #fff,

        0 0 10px #fff,

        0 0 18px #eca6c1,

        0 0 30px #eca6c1;

    pointer-events: none;

    z-index: 9999;

    animation:
        sparkleAnimation
        3s
        ease-in-out
        infinite;
}


.sparkle::before {

    content: "";

    position: absolute;

    width: 20px;
    height: 1px;

    background: white;

    left: 50%;
    top: 50%;

    transform:
        translate(-50%, -50%);
}


.sparkle::after {

    content: "";

    position: absolute;

    width: 1px;
    height: 20px;

    background: white;

    left: 50%;
    top: 50%;

    transform:
        translate(-50%, -50%);
}


@keyframes sparkleAnimation {

    0% {

        opacity: 0;

        transform:
            scale(.2)
            rotate(0deg);
    }

    25% {

        opacity: 1;

        transform:
            scale(1.2)
            rotate(45deg);
    }

    50% {

        opacity: .8;

        transform:
            scale(.8)
            rotate(90deg);
    }

    75% {

        opacity: 1;

        transform:
            scale(1.1)
            rotate(135deg);
    }

    100% {

        opacity: 0;

        transform:
            scale(.2)
            rotate(180deg);
    }
}


/* =========================================================
   TOP BAR
========================================================= */

.top-bar {

    position: relative;

    background:

        linear-gradient(
            90deg,
            #684052,
            #9b5b76,
            #c47696,
            #9b5b76,
            #684052
        );

    color: white;

    text-align: center;

    padding: 9px 10px;

    font-size: 12px;

    letter-spacing: 2px;

    box-shadow:

        0 2px 20px
        rgba(116, 63, 88, .30);

    overflow: hidden;
}


/* แสงวิ่งบน Top Bar */

.top-bar::after {

    content: "";

    position: absolute;

    width: 150px;
    height: 100%;

    top: 0;
    left: -200px;

    background:

        linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,.5),
            transparent
        );

    transform: skewX(-20deg);

    animation:
        topLight
        5s
        infinite;
}


@keyframes topLight {

    0% {
        left: -200px;
    }

    45% {
        left: 110%;
    }

    100% {
        left: 110%;
    }
}


/* =========================================================
   NAVBAR
========================================================= */

nav {

    height: 85px;

    background:
        rgba(255, 250, 252, .94);

    backdrop-filter:
        blur(18px);

    -webkit-backdrop-filter:
        blur(18px);

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 0 7%;

    border-bottom:
        1px solid
        rgba(213, 133, 162, .25);

    position: sticky;

    top: 0;

    z-index: 1000;

    box-shadow:

        0 7px 30px
        rgba(145, 75, 104, .08);
}


/* =========================================================
   LOGO
========================================================= */

.logo {

    text-decoration: none;

    color: #563745;

    text-align: center;

    line-height: 1;

    position: relative;
}


.logo::before {

    content: "✦";

    position: absolute;

    left: -22px;
    top: 0;

    font-size: 10px;

    color: var(--pink);

    animation:
        twinkle
        2s
        infinite;
}


.logo::after {

    content: "✦";

    position: absolute;

    right: -22px;
    bottom: 2px;

    font-size: 10px;

    color: var(--pink);

    animation:
        twinkle
        2.5s
        infinite;
}


.logo-main {

    display: block;

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: 30px;

    letter-spacing: 6px;
}


.logo-sub {

    display: block;

    color: #bd7893;

    font-size: 9px;

    letter-spacing: 5px;

    margin-top: 7px;
}


/* =========================================================
   NAV LINKS
========================================================= */

.nav-links {

    display: flex;

    gap: 35px;

    align-items: center;
}


.nav-links a {

    text-decoration: none;

    color: #5f4b53;

    font-size: 13px;

    position: relative;

    transition: .35s ease;
}


.nav-links a::after {

    content: "";

    position: absolute;

    width: 0;

    height: 2px;

    background:

        linear-gradient(
            90deg,
            transparent,
            #d889a7,
            transparent
        );

    left: 50%;

    bottom: -10px;

    transform:
        translateX(-50%);

    transition: .35s;
}


.nav-links a:hover,
.nav-links a.active {

    color: #bd6688;

    text-shadow:
        0 0 12px
        rgba(216, 137, 167, .25);
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

    align-items: center;

    gap: 15px;
}


.nav-right > a:first-child {

    text-decoration: none;

    color: #5f4b53;

    font-size: 13px;

    padding: 9px 12px;

    border-radius: 50%;

    border: 1px solid #e7c8d4;

    transition: .3s;
}


.nav-right > a:first-child:hover {

    background: #f7dce7;

    color: #a95d7b;

    box-shadow:
        0 0 15px
        rgba(214, 128, 158, .2);
}


/* =========================================================
   CART
========================================================= */

.cart {

    position: relative;

    text-decoration: none;

    color: #684653;

    background:

        linear-gradient(
            135deg,
            #f9e4ec,
            #f5d5e1
        );

    padding: 10px 18px;

    border-radius: 30px;

    border: 1px solid
        rgba(215, 132, 162, .25);

    transition: .35s;

    box-shadow:
        0 4px 12px
        rgba(178, 91, 124, .08);
}


.cart:hover {

    transform:
        translateY(-2px);

    background:
        linear-gradient(
            135deg,
            #f4cbd9,
            #edb9cd
        );

    box-shadow:
        0 8px 20px
        rgba(178, 91, 124, .20);
}


.cart-count {

    position: absolute;

    top: -7px;
    right: -7px;

    background:

        linear-gradient(
            135deg,
            #e18ca9,
            #b75d80
        );

    color: white;

    width: 21px;
    height: 21px;

    border-radius: 50%;

    font-size: 10px;

    display: flex;

    align-items: center;
    justify-content: center;

    box-shadow:

        0 0 8px
        rgba(218, 125, 157, .55),

        0 0 18px
        rgba(218, 125, 157, .25);
}


/* =========================================================
   HERO
========================================================= */

.contact-hero {

    height: 480px;

    position: relative;

    background:

        linear-gradient(
            rgba(92, 46, 63, .35),
            rgba(92, 46, 63, .48)
        ),

        url("images/perfume-hero.jpg");

    background-size: cover;

    background-position: center;

    display: flex;

    align-items: center;

    justify-content: center;

    text-align: center;

    color: white;

    overflow: hidden;
}


/* แสงฟุ้ง */

.contact-hero::before {

    content: "";

    position: absolute;

    inset: 0;

    background:

        radial-gradient(
            circle at 15% 30%,
            rgba(255,255,255,.35),
            transparent 15%
        ),

        radial-gradient(
            circle at 85% 25%,
            rgba(255,220,233,.3),
            transparent 20%
        ),

        radial-gradient(
            circle at 50% 80%,
            rgba(255,210,228,.20),
            transparent 25%
        );

    pointer-events: none;
}


/* กรอบ */

.contact-hero::after {

    content: "";

    position: absolute;

    inset: 20px;

    border:
        1px solid
        rgba(255,255,255,.42);

    box-shadow:

        inset 0 0 35px
        rgba(255,255,255,.08);

    pointer-events: none;
}


/* =========================================================
   HERO CONTENT
========================================================= */

.hero-content {

    position: relative;

    z-index: 5;

    text-shadow:

        0 3px 20px
        rgba(70, 30, 46, .5);
}


.hero-content::before {

    content: "✦";

    display: block;

    font-size: 22px;

    color: white;

    margin-bottom: 15px;

    animation:
        twinkle
        1.8s
        infinite;
}


.hero-content h1 {

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: 65px;

    font-weight: normal;

    letter-spacing: 10px;

    margin-bottom: 18px;
}


.hero-content p {

    font-size: 13px;

    letter-spacing: 6px;

    color: #ffeef5;
}


/* =========================================================
   CONTACT SECTION
========================================================= */

.contact-section {

    max-width: 1200px;

    margin: auto;

    padding: 100px 30px;

    position: relative;
}


.contact-section::before {

    content: "✦";

    position: absolute;

    left: 5%;

    top: 60px;

    color: #dfa0b8;

    font-size: 20px;

    animation:
        twinkle
        2s
        infinite;
}


.contact-section::after {

    content: "✧";

    position: absolute;

    right: 5%;

    top: 140px;

    color: #dfa0b8;

    font-size: 28px;

    animation:
        twinkle
        2.4s
        infinite;
}


/* =========================================================
   CONTACT TITLE
========================================================= */

.contact-title {

    text-align: center;

    margin-bottom: 65px;
}


.contact-title h2 {

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: 40px;

    font-weight: normal;

    color: #563744;

    margin-bottom: 18px;
}


.line {

    width: 75px;

    height: 2px;

    margin:
        0 auto
        23px;

    background:

        linear-gradient(
            90deg,
            transparent,
            #d887a5,
            #c8a16e,
            #d887a5,
            transparent
        );

    position: relative;
}


.line::before {

    content: "✦";

    position: absolute;

    left: 50%;
    top: 50%;

    transform:
        translate(-50%, -50%);

    color: #d889a7;

    background: #fff8fb;

    padding: 5px;

    font-size: 12px;
}


.contact-title p {

    color: #8c737d;

    font-size: 14px;

    line-height: 1.9;
}


/* =========================================================
   CONTACT GRID
========================================================= */

.contact-grid {

    display: grid;

    grid-template-columns:
        .9fr 1.4fr;

    gap: 45px;

    align-items: stretch;
}


/* =========================================================
   CONTACT INFO
========================================================= */

.contact-info {

    background:

        linear-gradient(
            145deg,
            rgba(255,255,255,.95),
            rgba(250,222,233,.72)
        );

    padding: 45px 40px;

    border:

        1px solid
        rgba(210, 132, 160, .32);

    box-shadow:

        0 18px 50px
        rgba(159, 82, 113, .10),

        inset 0 0 35px
        rgba(255,255,255,.7);

    position: relative;

    overflow: hidden;
}


.contact-info::before {

    content: "✧";

    position: absolute;

    right: 22px;
    top: 15px;

    font-size: 35px;

    color: #dfa0b8;

    animation:
        twinkle
        2s
        infinite;
}


.contact-info::after {

    content: "";

    position: absolute;

    width: 160px;
    height: 160px;

    border-radius: 50%;

    right: -80px;
    bottom: -80px;

    background: #f3cbd9;

    filter: blur(35px);

    opacity: .5;
}


.contact-info h3 {

    font-family:
        Georgia,
        serif;

    font-size: 29px;

    font-weight: normal;

    color: #593846;

    margin-bottom: 32px;
}


/* =========================================================
   INFO ITEM
========================================================= */

.info-item {

    display: flex;

    gap: 18px;

    margin-bottom: 29px;

    align-items: flex-start;

    position: relative;

    z-index: 2;
}


.info-icon {

    width: 45px;
    height: 45px;

    border:
        1px solid
        #dba0b6;

    background:
        rgba(255,255,255,.75);

    color: #bd6e8e;

    display: flex;

    align-items: center;
    justify-content: center;

    flex-shrink: 0;

    border-radius: 50%;

    font-size: 17px;

    transition: .35s;

    box-shadow:

        0 0 15px
        rgba(212, 131, 160, .12);
}


.info-item:hover .info-icon {

    transform:
        scale(1.1)
        rotate(8deg);

    background: #f7d8e4;

    box-shadow:

        0 0 20px
        rgba(210, 126, 157, .3);
}


.info-text h4 {

    font-size: 14px;

    font-weight: 600;

    color: #654653;

    margin-bottom: 7px;
}


.info-text p {

    color: #8b747d;

    font-size: 13px;

    line-height: 1.7;
}


/* =========================================================
   SOCIAL
========================================================= */

.social-title {

    margin-top: 35px;

    padding-top: 28px;

    border-top:
        1px solid
        #e3cbd4;

    font-family:
        Georgia,
        serif;

    font-size: 20px;

    color: #654250;
}


.socials {

    display: flex;

    gap: 10px;

    margin-top: 18px;
}


.socials a {

    width: 40px;
    height: 40px;

    border:
        1px solid
        #dba0b7;

    background:
        rgba(255,255,255,.7);

    display: flex;

    align-items: center;
    justify-content: center;

    text-decoration: none;

    color: #b76887;

    font-size: 12px;

    border-radius: 50%;

    transition: .35s;
}


.socials a:hover {

    background:

        linear-gradient(
            135deg,
            #df8eab,
            #b75f80
        );

    color: white;

    transform:
        translateY(-5px);

    box-shadow:

        0 10px 25px
        rgba(183, 95, 128, .3);
}


/* =========================================================
   CONTACT FORM
========================================================= */

.contact-form {

    background:
        rgba(255,255,255,.92);

    padding: 45px;

    border:
        1px solid
        rgba(216, 137, 167, .28);

    box-shadow:

        0 20px 55px
        rgba(160, 90, 120, .10);

    position: relative;

    overflow: hidden;
}


.contact-form::before {

    content: "✦";

    position: absolute;

    right: 25px;
    top: 18px;

    color: #dda0b8;

    font-size: 23px;

    animation:
        twinkle
        1.7s
        infinite;
}


.contact-form::after {

    content: "";

    position: absolute;

    width: 200px;
    height: 200px;

    right: -100px;
    bottom: -100px;

    border-radius: 50%;

    background: #f6d4e1;

    filter: blur(45px);

    opacity: .65;
}


.contact-form h3 {

    font-family:
        Georgia,
        serif;

    font-size: 29px;

    font-weight: normal;

    color: #583846;

    margin-bottom: 30px;
}


/* =========================================================
   FORM ROW
========================================================= */

.form-row {

    display: grid;

    grid-template-columns:
        1fr 1fr;

    gap: 20px;
}


.form-group {

    margin-bottom: 20px;

    position: relative;

    z-index: 2;
}


.form-group label {

    display: block;

    font-size: 12px;

    color: #684c57;

    margin-bottom: 8px;

    letter-spacing: .5px;
}


.form-group input,
.form-group textarea,
.form-group select {

    width: 100%;

    border:
        1px solid
        #e3ccd6;

    background:
        #fffafd;

    padding: 14px;

    font-family: inherit;

    font-size: 13px;

    color: #4e3942;

    outline: none;

    transition: .35s;

    border-radius: 4px;
}


.form-group input::placeholder,
.form-group textarea::placeholder {

    color: #bca5ae;
}


.form-group input:hover,
.form-group textarea:hover,
.form-group select:hover {

    border-color:
        #d998b2;
}


.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {

    border-color:
        #cf7e9e;

    background: white;

    box-shadow:

        0 0 0 3px
        rgba(211, 130, 159, .10),

        0 7px 20px
        rgba(211, 130, 159, .08);
}


.form-group textarea {

    min-height: 140px;

    resize: vertical;
}


/* =========================================================
   BUTTON
========================================================= */

.submit-button {

    width: 100%;

    border: none;

    background:

        linear-gradient(
            100deg,
            #744258,
            #d17e9e,
            #a65d7c
        );

    color: white;

    padding: 15px;

    font-size: 13px;

    letter-spacing: 2px;

    cursor: pointer;

    transition: .35s;

    position: relative;

    overflow: hidden;

    box-shadow:

        0 8px 25px
        rgba(153, 82, 112, .25);

    z-index: 5;
}


.submit-button::before {

    content: "";

    position: absolute;

    width: 50%;
    height: 100%;

    left: -100%;
    top: 0;

    background:

        linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,.55),
            transparent
        );

    transform:
        skewX(-20deg);

    transition: .7s;
}


.submit-button:hover::before {

    left: 150%;
}


.submit-button:hover {

    transform:
        translateY(-3px);

    box-shadow:

        0 13px 35px
        rgba(153, 82, 112, .35);
}


/* =========================================================
   ALERT
========================================================= */

.alert {

    padding: 14px 18px;

    margin-bottom: 25px;

    font-size: 13px;

    line-height: 1.6;

    border-radius: 5px;

    position: relative;

    z-index: 5;
}


.success {

    background:
        #f0f8ef;

    color:
        #527052;

    border:
        1px solid
        #cce2cd;
}


.error {

    background:
        #fff0f4;

    color:
        #9b4d68;

    border:
        1px solid
        #edcbd7;
}


/* =========================================================
   FAQ
========================================================= */

.faq-section {

    position: relative;

    background:

        radial-gradient(
            circle at 15% 20%,
            rgba(255,255,255,.9),
            transparent 20%
        ),

        radial-gradient(
            circle at 90% 80%,
            rgba(255,255,255,.8),
            transparent 22%
        ),

        linear-gradient(
            135deg,
            #f8e1ea,
            #fff5f9,
            #f7dfe8
        );

    padding: 100px 30px;

    border-top:
        1px solid
        #f0d2de;

    border-bottom:
        1px solid
        #f0d2de;

    overflow: hidden;
}


.faq-section::before {

    content:
        "✦     ✧     ✦     ✧     ✦";

    position: absolute;

    top: 25px;

    left: 50%;

    transform:
        translateX(-50%);

    color:
        #d99ab1;

    font-size: 15px;

    letter-spacing: 20px;

    white-space: nowrap;

    animation:
        twinkle
        3s
        infinite;
}


.faq-container {

    max-width: 900px;

    margin: auto;
}


.faq-title {

    text-align: center;

    margin-bottom: 50px;
}


.faq-title h2 {

    font-family:
        Georgia,
        serif;

    font-size: 38px;

    font-weight: normal;

    color: #593b48;

    margin-bottom: 15px;
}


.faq-title p {

    color: #a07686;

    font-size: 12px;

    letter-spacing: 3px;
}


.faq-item {

    background:
        rgba(255,255,255,.90);

    margin-bottom: 13px;

    padding: 23px 27px;

    border:
        1px solid
        rgba(213, 140, 166, .22);

    border-radius: 5px;

    box-shadow:

        0 7px 25px
        rgba(164, 87, 118, .06);

    transition: .35s;

    position: relative;
}


.faq-item::before {

    content: "✧";

    position: absolute;

    right: 20px;
    top: 18px;

    color: #d998b0;

    font-size: 17px;

    animation:
        twinkle
        2.5s
        infinite;
}


.faq-item:hover {

    transform:
        translateY(-4px);

    border-color:
        #dda0b8;

    box-shadow:

        0 15px 35px
        rgba(164, 87, 118, .12);
}


.faq-item h3 {

    font-family:
        Georgia,
        serif;

    font-size: 18px;

    font-weight: normal;

    color: #644250;

    margin-bottom: 10px;

    padding-right: 30px;
}


.faq-item p {

    color: #89727b;

    font-size: 13px;

    line-height: 1.8;
}


/* =========================================================
   HOURS
========================================================= */

.hours-section {

    max-width: 850px;

    margin: auto;

    padding: 95px 30px;

    text-align: center;

    position: relative;
}


.hours-section::before {

    content: "✦";

    position: absolute;

    left: 4%;

    top: 80px;

    color: #dda0b7;

    font-size: 20px;

    animation:
        twinkle
        2s
        infinite;
}


.hours-section::after {

    content: "✧";

    position: absolute;

    right: 5%;

    bottom: 80px;

    color: #dda0b7;

    font-size: 25px;

    animation:
        twinkle
        2.5s
        infinite;
}


.hours-section h2 {

    font-family:
        Georgia,
        serif;

    font-size: 38px;

    font-weight: normal;

    color: #593b47;

    margin-bottom: 15px;
}


.hours-subtitle {

    color: #a17787;

    font-size: 12px;

    letter-spacing: 3px;

    margin-bottom: 40px;
}


.hours {

    display: grid;

    grid-template-columns:
        1fr 1fr;

    gap: 1px;

    background:
        #e1c3cf;

    border:
        1px solid
        #e1c3cf;

    box-shadow:

        0 12px 35px
        rgba(157, 83, 113, .10);
}


.hour-item {

    background:
        rgba(255,255,255,.93);

    padding: 25px;

    transition: .3s;
}


.hour-item:hover {

    background:
        #fff0f6;

    transform:
        translateY(-2px);
}


.hour-item h3 {

    font-family:
        Georgia,
        serif;

    font-size: 18px;

    font-weight: normal;

    color: #63414e;

    margin-bottom: 8px;
}


.hour-item p {

    color: #8d747e;

    font-size: 13px;
}


/* =========================================================
   FOOTER
========================================================= */

footer {

    background:

        linear-gradient(
            135deg,
            #4d303d,
            #704258,
            #512f40
        );

    color: white;

    padding:
        65px
        7%
        25px;

    position: relative;

    overflow: hidden;
}


footer::before {

    content:
        "✦     ✧     ✦     ✧     ✦     ✧";

    position: absolute;

    top: 20px;

    left: 50%;

    transform:
        translateX(-50%);

    color:
        rgba(255,255,255,.55);

    letter-spacing: 15px;

    white-space: nowrap;

    animation:
        twinkle
        3s
        infinite;
}


footer::after {

    content: "";

    position: absolute;

    width: 300px;
    height: 300px;

    border-radius: 50%;

    right: -150px;
    bottom: -200px;

    background:
        rgba(238, 157, 190, .15);

    filter:
        blur(50px);
}


.footer-container {

    display: grid;

    grid-template-columns:
        2fr 1fr 1fr;

    gap: 60px;

    max-width: 1200px;

    margin: auto;

    position: relative;

    z-index: 2;
}


.footer-logo {

    font-family:
        Georgia,
        serif;

    font-size: 28px;

    letter-spacing: 6px;

    margin-bottom: 15px;
}


footer p {

    color:
        #e4cbd5;

    font-size: 13px;

    line-height: 1.8;
}


footer h3 {

    font-size: 14px;

    margin-bottom: 20px;

    letter-spacing: 1px;

    color:
        #ffeaf2;
}


footer a {

    display: block;

    color:
        #d9bdc8;

    text-decoration: none;

    font-size: 13px;

    margin-bottom: 12px;

    transition: .3s;
}


footer a:hover {

    color: white;

    transform:
        translateX(5px);
}


.copyright {

    text-align: center;

    border-top:
        1px solid
        rgba(255,255,255,.15);

    margin-top: 45px;

    padding-top: 20px;

    color:
        #bda4af;

    font-size: 12px;

    position: relative;

    z-index: 2;
}


/* =========================================================
   TWINKLE
========================================================= */

@keyframes twinkle {

    0%,
    100% {

        opacity: .35;

        transform:
            scale(.8);
    }

    50% {

        opacity: 1;

        transform:
            scale(1.3);

        filter:

            drop-shadow(
                0 0 5px white
            )

            drop-shadow(
                0 0 12px #efa6c0
            );
    }
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1000px) {

    nav {

        padding: 0 4%;
    }

    .nav-links {

        gap: 18px;
    }

    .contact-grid {

        grid-template-columns:
            1fr;
    }

    .footer-container {

        grid-template-columns:
            1fr 1fr;

        gap: 40px;
    }

}


@media (max-width: 700px) {

    nav {

        height: auto;

        padding: 20px;

        flex-direction: column;

        gap: 20px;
    }


    .nav-links {

        flex-wrap: wrap;

        justify-content: center;

        gap: 13px;
    }


    .nav-right {

        margin-top: 5px;
    }


    .contact-hero {

        height: 380px;
    }


    .contact-hero::after {

        inset: 12px;
    }


    .hero-content h1 {

        font-size: 40px;

        letter-spacing: 5px;
    }


    .hero-content p {

        font-size: 10px;

        letter-spacing: 3px;
    }


    .contact-section {

        padding: 65px 20px;
    }


    .contact-title h2 {

        font-size: 31px;
    }


    .contact-title p {

        font-size: 13px;
    }


    .contact-info {

        padding: 35px 25px;
    }


    .contact-form {

        padding: 30px 20px;
    }


    .contact-form h3 {

        font-size: 25px;
    }


    .form-row {

        grid-template-columns:
            1fr;

        gap: 0;
    }


    .faq-section {

        padding: 70px 20px;
    }


    .faq-title h2 {

        font-size: 31px;
    }


    .hours-section {

        padding: 65px 20px;
    }


    .hours {

        grid-template-columns:
            1fr;
    }


    .footer-container {

        grid-template-columns:
            1fr;

        gap: 35px;
    }

}


/* =========================================================
   REDUCE MOTION
========================================================= */

@media (prefers-reduced-motion: reduce) {

    *,
    *::before,
    *::after {

        animation-duration: .01ms !important;

        animation-iteration-count: 1 !important;

        scroll-behavior: auto !important;

        transition-duration: .01ms !important;
    }
}

</style>

</head>


<body>


<!-- =====================================================
     ✨ STATIC SPARKLES
===================================================== -->

<div class="sparkle" style="left:5%; top:15%; animation-delay:.2s;"></div>

<div class="sparkle" style="left:12%; top:35%; animation-delay:1.3s;"></div>

<div class="sparkle" style="left:20%; top:70%; animation-delay:2s;"></div>

<div class="sparkle" style="left:28%; top:20%; animation-delay:.8s;"></div>

<div class="sparkle" style="left:35%; top:55%; animation-delay:2.5s;"></div>

<div class="sparkle" style="left:43%; top:15%; animation-delay:1.1s;"></div>

<div class="sparkle" style="left:50%; top:75%; animation-delay:2.2s;"></div>

<div class="sparkle" style="left:58%; top:35%; animation-delay:.5s;"></div>

<div class="sparkle" style="left:65%; top:65%; animation-delay:1.8s;"></div>

<div class="sparkle" style="left:72%; top:18%; animation-delay:2.7s;"></div>

<div class="sparkle" style="left:80%; top:45%; animation-delay:1.4s;"></div>

<div class="sparkle" style="left:88%; top:25%; animation-delay:.7s;"></div>

<div class="sparkle" style="left:95%; top:70%; animation-delay:2.4s;"></div>

<div class="sparkle" style="left:15%; top:90%; animation-delay:1.7s;"></div>

<div class="sparkle" style="left:40%; top:90%; animation-delay:3s;"></div>

<div class="sparkle" style="left:70%; top:88%; animation-delay:1s;"></div>


<!-- =====================================================
     TOP BAR
===================================================== -->

<div class="top-bar">

    ✦ &nbsp; จัดส่งฟรี เมื่อสั่งซื้อครบ $50 &nbsp; ✦

</div>


<!-- =====================================================
     NAVBAR
===================================================== -->

<nav>


    <a href="index.php" class="logo">

        <span class="logo-main">
            VELOURA
        </span>

        <span class="logo-sub">
            PERFUMES
        </span>

    </a>


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

        <a href="collection.php">
            คอลเลกชัน
        </a>

        <a href="find-scent.php">
            ค้นหากลิ่น
        </a>

        <a href="contact.php" class="active">
            ติดต่อเรา
        </a>

    </div>


    <div class="nav-right">

        <a href="profile.php">
            ♡
        </a>


        <a href="cart.php" class="cart">

            🛍 รถเข็น

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

<section class="contact-hero">


    <!-- HERO SPARKLES -->

    <div
        class="sparkle"
        style="left:10%; top:25%; animation-delay:.3s;"
    ></div>

    <div
        class="sparkle"
        style="left:23%; top:70%; animation-delay:1.5s;"
    ></div>

    <div
        class="sparkle"
        style="left:39%; top:18%; animation-delay:2s;"
    ></div>

    <div
        class="sparkle"
        style="left:55%; top:75%; animation-delay:.8s;"
    ></div>

    <div
        class="sparkle"
        style="left:70%; top:25%; animation-delay:1.7s;"
    ></div>

    <div
        class="sparkle"
        style="left:88%; top:60%; animation-delay:2.5s;"
    ></div>


    <div class="hero-content">

        <h1>
            CONTACT US
        </h1>

        <p>
            WE'D LOVE TO HEAR FROM YOU
        </p>

    </div>


</section>


<!-- =====================================================
     CONTACT SECTION
===================================================== -->

<section class="contact-section">


    <div class="contact-title">

        <h2>
            ติดต่อ VELOURA
        </h2>

        <div class="line"></div>

        <p>

            หากคุณมีคำถามเกี่ยวกับสินค้า การสั่งซื้อ
            <br>

            หรืออยากสอบถามข้อมูลเพิ่มเติม
            สามารถติดต่อเราได้ทุกเมื่อ

        </p>

    </div>


    <div class="contact-grid">


        <!-- =================================================
             CONTACT INFO
        ================================================== -->

        <div class="contact-info">


            <h3>
                ติดต่อเรา
            </h3>


            <!-- EMAIL -->

            <div class="info-item">

                <div class="info-icon">
                    ✉
                </div>

                <div class="info-text">

                    <h4>
                        อีเมล
                    </h4>

                    <p>
                        support@veloura.com
                    </p>

                </div>

            </div>


            <!-- PHONE -->

            <div class="info-item">

                <div class="info-icon">
                    ☎
                </div>

                <div class="info-text">

                    <h4>
                        โทรศัพท์
                    </h4>

                    <p>
                        02-XXX-XXXX
                    </p>

                </div>

            </div>


            <!-- ADDRESS -->

            <div class="info-item">

                <div class="info-icon">
                    ◇
                </div>

                <div class="info-text">

                    <h4>
                        ที่อยู่
                    </h4>

                    <p>

                        VELOURA PERFUMES
                        <br>

                        ประเทศไทย

                    </p>

                </div>

            </div>


            <!-- TIME -->

            <div class="info-item">

                <div class="info-icon">
                    ◷
                </div>

                <div class="info-text">

                    <h4>
                        เวลาทำการ
                    </h4>

                    <p>

                        จันทร์ - ศุกร์
                        <br>

                        09:00 - 18:00 น.

                    </p>

                </div>

            </div>


            <!-- SOCIAL -->

            <div class="social-title">

                ติดตามเรา

            </div>


            <div class="socials">

                <a href="#" title="Facebook">
                    FB
                </a>

                <a href="#" title="Instagram">
                    IG
                </a>

                <a href="#" title="TikTok">
                    TT
                </a>

            </div>


        </div>


        <!-- =================================================
             CONTACT FORM
        ================================================== -->

        <div class="contact-form">


            <h3>
                ส่งข้อความถึงเรา
            </h3>


            <?php if ($success !== ""): ?>

                <div class="alert success">

                    <?php
                    echo htmlspecialchars($success);
                    ?>

                </div>

            <?php endif; ?>


            <?php if ($error !== ""): ?>

                <div class="alert error">

                    <?php
                    echo htmlspecialchars($error);
                    ?>

                </div>

            <?php endif; ?>


            <form
                method="POST"
                action="contact.php"
            >


                <!-- NAME + EMAIL -->

                <div class="form-row">


                    <div class="form-group">

                        <label>
                            ชื่อ
                        </label>

                        <input
                            type="text"
                            name="name"
                            placeholder="กรอกชื่อของคุณ"
                            value="<?php
                            echo htmlspecialchars(
                                $_POST["name"] ?? ""
                            );
                            ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            อีเมล
                        </label>

                        <input
                            type="email"
                            name="email"
                            placeholder="example@email.com"
                            value="<?php
                            echo htmlspecialchars(
                                $_POST["email"] ?? ""
                            );
                            ?>"
                            required
                        >

                    </div>


                </div>


                <!-- SUBJECT -->

                <div class="form-group">

                    <label>
                        หัวข้อ
                    </label>

                    <select
                        name="subject"
                        required
                    >

                        <option value="">
                            เลือกหัวข้อ
                        </option>

                        <option value="สอบถามสินค้า">
                            สอบถามสินค้า
                        </option>

                        <option value="การสั่งซื้อ">
                            การสั่งซื้อ
                        </option>

                        <option value="การจัดส่ง">
                            การจัดส่ง
                        </option>

                        <option value="การชำระเงิน">
                            การชำระเงิน
                        </option>

                        <option value="อื่นๆ">
                            อื่นๆ
                        </option>

                    </select>

                </div>


                <!-- MESSAGE -->

                <div class="form-group">

                    <label>
                        ข้อความ
                    </label>

                    <textarea
                        name="message"
                        placeholder="เขียนข้อความของคุณ..."
                        required
                    ><?php
                    echo htmlspecialchars(
                        $_POST["message"] ?? ""
                    );
                    ?></textarea>

                </div>


                <!-- BUTTON -->

                <button
                    type="submit"
                    class="submit-button"
                >

                    ✦ &nbsp; ส่งข้อความ &nbsp; ✦

                </button>


            </form>


        </div>


    </div>


</section>


<!-- =====================================================
     FAQ
===================================================== -->

<section class="faq-section">


    <div class="faq-container">


        <div class="faq-title">

            <h2>
                คำถามที่พบบ่อย
            </h2>

            <p>
                FREQUENTLY ASKED QUESTIONS
            </p>

        </div>


        <!-- FAQ 1 -->

        <div class="faq-item">

            <h3>
                สามารถเปลี่ยนหรือคืนสินค้าได้หรือไม่?
            </h3>

            <p>

                หากสินค้ามีปัญหาหรือได้รับสินค้า
                ไม่ตรงตามรายการ กรุณาติดต่อเราภายใน
                7 วันหลังจากได้รับสินค้า
                เพื่อให้ทีมงานตรวจสอบและดำเนินการต่อไป

            </p>

        </div>


        <!-- FAQ 2 -->

        <div class="faq-item">

            <h3>
                ใช้เวลาจัดส่งกี่วัน?
            </h3>

            <p>

                โดยปกติการจัดส่งภายในประเทศไทย
                ใช้เวลาประมาณ 2 - 5 วันทำการ
                ทั้งนี้อาจแตกต่างกันตามพื้นที่จัดส่ง

            </p>

        </div>


        <!-- FAQ 3 -->

        <div class="faq-item">

            <h3>
                มีบริการห่อของขวัญหรือไม่?
            </h3>

            <p>

                VELOURA มีบริการห่อของขวัญ
                สำหรับโอกาสพิเศษ
                สามารถติดต่อทีมงานเพื่อสอบถาม
                รายละเอียดเพิ่มเติมได้

            </p>

        </div>


        <!-- FAQ 4 -->

        <div class="faq-item">

            <h3>
                สามารถสอบถามเกี่ยวกับกลิ่นน้ำหอมก่อนได้หรือไม่?
            </h3>

            <p>

                ได้ คุณสามารถติดต่อทีมงานเพื่อขอคำแนะนำ
                เกี่ยวกับกลิ่นและลักษณะของน้ำหอม
                เพื่อช่วยเลือกกลิ่นที่เหมาะกับคุณ

            </p>

        </div>


    </div>


</section>


<!-- =====================================================
     BUSINESS HOURS
===================================================== -->

<section class="hours-section">


    <h2>
        เราพร้อมให้บริการคุณ
    </h2>


    <p class="hours-subtitle">
        CONTACT VELOURA
    </p>


    <div class="hours">


        <div class="hour-item">

            <h3>
                วันจันทร์ - ศุกร์
            </h3>

            <p>
                09:00 - 18:00 น.
            </p>

        </div>


        <div class="hour-item">

            <h3>
                วันเสาร์ - อาทิตย์
            </h3>

            <p>
                10:00 - 17:00 น.
            </p>

        </div>


    </div>


</section>


<!-- =====================================================
     FOOTER
===================================================== -->

<footer>


    <div class="footer-container">


        <!-- BRAND -->

        <div>

            <div class="footer-logo">
                VELOURA
            </div>

            <p>

                Luxury fragrance crafted for
                those who embrace their individuality.

            </p>

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

        </div>


        <!-- SERVICES -->

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

        ✦ &nbsp;

        © 2026 VELOURA PERFUMES.
        All Rights Reserved.

        &nbsp; ✦

    </div>


</footer>


<!-- =====================================================
     DYNAMIC SPARKLES
===================================================== -->

<script>

function createSparkle() {

    const sparkle =
        document.createElement("div");

    sparkle.className =
        "sparkle";


    sparkle.style.left =
        Math.random() * 100 + "%";


    sparkle.style.top =
        Math.random() * 100 + "%";


    sparkle.style.animationDuration =
        (2 + Math.random() * 3) + "s";


    sparkle.style.animationDelay =
        Math.random() * 2 + "s";


    const size =
        3 + Math.random() * 4;


    sparkle.style.width =
        size + "px";


    sparkle.style.height =
        size + "px";


    document.body.appendChild(
        sparkle
    );


    setTimeout(
        function() {

            sparkle.remove();

        },
        5000
    );

}


/* =====================================================
   สร้างดาววิ้งใหม่เรื่อย ๆ
===================================================== */

setInterval(
    createSparkle,
    500
);


/* =====================================================
   เพิ่มดาวทันทีตอนโหลด
===================================================== */

for (
    let i = 0;
    i < 15;
    i++
) {

    setTimeout(
        createSparkle,
        i * 180
    );

}

</script>


</body>

</html>