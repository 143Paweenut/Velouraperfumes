<?php
session_start();

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
?>

<!DOCTYPE html>
<html lang="th">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>เกี่ยวกับเรา | Veloura Perfumes</title>

    <!-- ================= GOOGLE FONTS ================= -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&family=Noto+Sans+Thai:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <!-- ================= FONT AWESOME ================= -->

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>

        /* =====================================================
           RESET
        ===================================================== */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: "Noto Sans Thai", "Montserrat", sans-serif;
            color: #33282d;
            background:
                radial-gradient(circle at 10% 15%, rgba(242, 192, 211, 0.20), transparent 25%),
                radial-gradient(circle at 90% 30%, rgba(232, 184, 205, 0.16), transparent 28%),
                radial-gradient(circle at 50% 90%, rgba(247, 210, 225, 0.18), transparent 30%),
                #fffafc;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button {
            font-family: inherit;
        }


        /* =====================================================
           SPARKLE BACKGROUND
        ===================================================== */

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
            font-family: "Cormorant Garamond", serif;
            font-size: 17px;
            opacity: 0.45;
            text-shadow:
                0 0 5px rgba(220, 169, 193, 0.7),
                0 0 12px rgba(220, 169, 193, 0.45);
            animation: twinkle 4s ease-in-out infinite;
        }

        .s1 {
            top: 12%;
            left: 8%;
            animation-delay: 0s;
        }

        .s2 {
            top: 23%;
            left: 19%;
            animation-delay: 1s;
        }

        .s3 {
            top: 15%;
            right: 16%;
            animation-delay: 2s;
        }

        .s4 {
            top: 38%;
            right: 7%;
            animation-delay: 1.4s;
        }

        .s5 {
            top: 51%;
            left: 5%;
            animation-delay: 2.4s;
        }

        .s6 {
            top: 63%;
            right: 18%;
            animation-delay: 0.8s;
        }

        .s7 {
            top: 75%;
            left: 13%;
            animation-delay: 3s;
        }

        .s8 {
            top: 82%;
            right: 8%;
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
                opacity: 0.2;
                transform: scale(0.8) rotate(0deg);
            }

            50% {
                opacity: 0.85;
                transform: scale(1.35) rotate(18deg);
            }
        }


        /* =====================================================
           TOP BAR
        ===================================================== */

        .top-bar {
            position: relative;
            height: 36px;

            display: flex;
            align-items: center;
            justify-content: center;

            background:
                linear-gradient(
                    90deg,
                    #33262d,
                    #4a303b,
                    #33262d
                );

            color: #fff;

            font-family: "Montserrat", sans-serif;
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;

            overflow: hidden;
        }

        .top-bar::after {
            content: "";

            position: absolute;
            top: 0;
            left: -100%;

            width: 60%;
            height: 100%;

            background:
                linear-gradient(
                    90deg,
                    transparent,
                    rgba(255, 255, 255, 0.15),
                    transparent
                );

            animation: shineBar 5s infinite;
        }

        @keyframes shineBar {

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


        /* =====================================================
           NAVBAR
        ===================================================== */

        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;

            min-height: 92px;

            display: grid;
            grid-template-columns: 220px 1fr 220px;
            align-items: center;

            padding: 0 5%;

            background: rgba(255, 250, 252, 0.88);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);

            border-bottom: 1px solid rgba(183, 137, 157, 0.15);
        }


        /* ================= LOGO ================= */

        .logo {
            text-align: left;
        }

        .logo-main {
            font-family: "Cormorant Garamond", serif;

            font-size: 32px;
            line-height: 0.8;

            font-weight: 600;

            letter-spacing: 7px;

            color: #382b31;
        }

        .logo-sub {
            display: flex;
            align-items: center;
            gap: 8px;

            margin-top: 7px;

            font-family: "Montserrat", sans-serif;
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


        /* ================= NAV LINKS ================= */

        .nav-links {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 27px;
        }

        .nav-links a {
            position: relative;

            padding: 10px 0;

            font-family: "Montserrat", sans-serif;
            font-size: 10px;
            font-weight: 500;

            letter-spacing: 0.8px;

            color: #725d65;

            transition: 0.3s ease;
        }

        .nav-links a::after {
            content: "";

            position: absolute;

            bottom: 3px;
            left: 50%;

            width: 0;
            height: 1px;

            background: #b77894;

            transform: translateX(-50%);

            transition: width 0.3s ease;
        }

        .nav-links a:hover {
            color: #a55779;
        }

        .nav-links a:hover::after,
        .nav-links a.active::after {
            width: 100%;
        }

        .nav-links a.active {
            color: #a55779;
        }


        /* ================= NAV RIGHT ================= */

        .nav-right {
            display: flex;
            justify-content: flex-end;
            align-items: center;

            gap: 12px;
        }

        .account-btn,
        .cart-btn {
            display: flex;
            align-items: center;
            justify-content: center;

            transition: 0.3s ease;
        }

        .account-btn {
            width: 38px;
            height: 38px;

            border-radius: 50%;

            color: #725d65;
            background: rgba(255, 255, 255, 0.75);

            border: 1px solid rgba(190, 143, 164, 0.22);
        }

        .account-btn:hover {
            color: #a55779;
            transform: translateY(-2px);
        }

        .cart-btn {
            gap: 8px;

            min-width: 78px;
            height: 38px;

            padding: 0 12px;

            border-radius: 30px;

            background: rgba(245, 218, 229, 0.65);

            border: 1px solid rgba(190, 143, 164, 0.25);

            color: #725d65;

            font-size: 13px;
        }

        .cart-btn:hover {
            transform: translateY(-2px);
            background: rgba(239, 199, 216, 0.8);
        }

        .cart-count {
            min-width: 19px;
            height: 19px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: linear-gradient(
                135deg,
                #a55779,
                #d493af
            );

            color: white;

            font-family: "Montserrat", sans-serif;
            font-size: 9px;
        }


        /* =====================================================
           HERO
        ===================================================== */

        .about-hero {
            position: relative;

            min-height: 460px;

            display: flex;
            align-items: center;
            justify-content: center;

            text-align: center;

            background:
                linear-gradient(
                    rgba(46, 33, 39, 0.58),
                    rgba(46, 33, 39, 0.62)
                ),
                url("images/perfume-hero.jpg")
                center / cover no-repeat;

            overflow: hidden;
        }

        .about-hero::before {
            content: "";

            position: absolute;
            width: 450px;
            height: 450px;

            border-radius: 50%;

            background:
                radial-gradient(
                    circle,
                    rgba(255, 210, 226, 0.20),
                    transparent 70%
                );

            filter: blur(4px);

            animation: orbMove 7s ease-in-out infinite;
        }

        @keyframes orbMove {

            0%,
            100% {
                transform: translate(-80px, -20px);
            }

            50% {
                transform: translate(80px, 30px);
            }
        }

        .hero-content {
            position: relative;
            z-index: 2;

            max-width: 800px;

            padding: 40px 25px;

            color: white;
        }

        .hero-kicker {
            margin-bottom: 12px;

            font-family: "Montserrat", sans-serif;

            font-size: 11px;
            letter-spacing: 5px;

            color: #f7d9e6;
        }

        .hero-title {
            font-family: "Cormorant Garamond", serif;

            font-size: clamp(58px, 8vw, 105px);

            font-weight: 500;

            line-height: 0.9;

            letter-spacing: 5px;

            text-shadow:
                0 5px 25px rgba(0, 0, 0, 0.3);
        }

        .hero-line {
            display: flex;

            align-items: center;
            justify-content: center;

            gap: 15px;

            margin: 25px auto;

            width: min(350px, 80%);
        }

        .hero-line span {
            height: 1px;
            flex: 1;

            background:
                linear-gradient(
                    90deg,
                    transparent,
                    rgba(255, 216, 231, 0.8)
                );
        }

        .hero-line span:last-child {
            background:
                linear-gradient(
                    90deg,
                    rgba(255, 216, 231, 0.8),
                    transparent
                );
        }

        .hero-line i {
            font-size: 13px;
            color: #f5cadc;
        }

        .hero-description {
            max-width: 620px;

            margin: auto;

            font-size: 14px;
            line-height: 2;

            font-weight: 300;

            color: rgba(255, 255, 255, 0.88);
        }


        /* =====================================================
           BRAND STORY
        ===================================================== */

        .story-section {
            max-width: 1380px;

            margin: auto;

            padding: 100px 5%;
        }

        .story-grid {
            display: grid;

            grid-template-columns: 1fr 1fr;

            align-items: center;

            gap: 85px;
        }

        .story-image {
            position: relative;

            overflow: hidden;

            aspect-ratio: 4 / 5;

            background: #f4e4ea;

            box-shadow:
                0 25px 60px rgba(116, 74, 92, 0.13);
        }

        .story-image img {
            width: 100%;
            height: 100%;

            display: block;

            object-fit: cover;

            transition: transform 0.8s ease;
        }

        .story-image:hover img {
            transform: scale(1.04);
        }

        .story-image::after {
            content: "VELOURA";

            position: absolute;

            right: -25px;
            bottom: 28px;

            padding: 10px 20px;

            background: rgba(255, 250, 252, 0.90);

            color: #9e6b82;

            font-family: "Montserrat", sans-serif;
            font-size: 9px;

            letter-spacing: 4px;

            box-shadow:
                0 8px 25px rgba(70, 42, 54, 0.12);
        }

        .story-content {
            padding: 20px 0;
        }

        .section-kicker {
            font-family: "Montserrat", sans-serif;

            font-size: 10px;

            letter-spacing: 4px;

            color: #b47791;

            margin-bottom: 12px;
        }

        .section-title {
            font-family: "Cormorant Garamond", serif;

            font-size: clamp(42px, 5vw, 64px);

            font-weight: 600;

            line-height: 0.95;

            color: #382b31;

            margin-bottom: 25px;
        }

        .section-title span {
            display: block;

            color: #bd8099;
        }

        .story-content p {
            color: #725f67;

            font-size: 14px;

            line-height: 2;

            margin-bottom: 18px;

            font-weight: 300;
        }

        .story-signature {
            margin-top: 30px;

            font-family: "Cormorant Garamond", serif;

            font-size: 28px;

            font-style: italic;

            color: #a56780;
        }


        /* =====================================================
           VALUES
        ===================================================== */

        .values-section {
            position: relative;

            padding: 100px 5% 110px;

            background:
                linear-gradient(
                    180deg,
                    rgba(249, 228, 237, 0.48),
                    rgba(255, 250, 252, 0.65)
                );
        }

        .values-container {
            max-width: 1250px;

            margin: auto;

            text-align: center;
        }

        .values-heading {
            max-width: 700px;

            margin: 0 auto 55px;
        }

        .values-heading .section-title {
            margin-bottom: 15px;
        }

        .values-heading p {
            color: #806b73;

            font-size: 14px;
            line-height: 2;

            font-weight: 300;
        }

        .values-grid {
            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 25px;
        }

        .value-card {
            position: relative;

            padding: 50px 30px;

            background: rgba(255, 255, 255, 0.72);

            border: 1px solid rgba(186, 137, 158, 0.18);

            box-shadow:
                0 18px 45px rgba(112, 72, 90, 0.07);

            transition:
                transform 0.35s ease,
                box-shadow 0.35s ease,
                border-color 0.35s ease;

            overflow: hidden;
        }

        .value-card::before {
            content: "✦";

            position: absolute;

            top: 15px;
            right: 20px;

            color: #e2b7c9;

            font-size: 14px;

            opacity: 0.7;
        }

        .value-card:hover {
            transform: translateY(-8px);

            border-color: rgba(181, 120, 148, 0.35);

            box-shadow:
                0 25px 55px rgba(112, 72, 90, 0.12);
        }

        .value-icon {
            width: 68px;
            height: 68px;

            margin: 0 auto 25px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background:
                linear-gradient(
                    135deg,
                    #f8e2eb,
                    #efd0df
                );

            color: #a96280;

            font-size: 23px;

            box-shadow:
                0 10px 25px rgba(178, 119, 146, 0.12);
        }

        .value-card h3 {
            font-family: "Cormorant Garamond", serif;

            font-size: 30px;

            font-weight: 600;

            color: #45343b;

            margin-bottom: 12px;
        }

        .value-card p {
            color: #806b73;

            font-size: 13px;

            line-height: 1.9;

            font-weight: 300;
        }


        /* =====================================================
           QUOTE SECTION
        ===================================================== */

        .quote-section {
            position: relative;

            padding: 120px 5%;

            text-align: center;

            background:
                linear-gradient(
                    rgba(55, 40, 47, 0.72),
                    rgba(55, 40, 47, 0.75)
                ),
                url("images/perfume-hero.jpg")
                center / cover no-repeat;

            overflow: hidden;
        }

        .quote-section::before {
            content: "“";

            position: absolute;

            top: 25px;
            left: 8%;

            font-family: "Cormorant Garamond", serif;

            font-size: 180px;

            color: rgba(255, 220, 232, 0.10);
        }

        .quote-content {
            position: relative;

            max-width: 900px;

            margin: auto;
        }

        .quote-small {
            font-family: "Montserrat", sans-serif;

            font-size: 10px;

            letter-spacing: 4px;

            color: #edc8d8;

            margin-bottom: 20px;
        }

        .quote-text {
            font-family: "Cormorant Garamond", serif;

            font-size: clamp(35px, 5vw, 58px);

            line-height: 1.15;

            color: white;

            font-weight: 500;
        }

        .quote-author {
            margin-top: 25px;

            font-family: "Montserrat", sans-serif;

            font-size: 10px;

            letter-spacing: 3px;

            color: rgba(255, 225, 235, 0.75);
        }


        /* =====================================================
           MINI FEATURE
        ===================================================== */

        .feature-section {
            max-width: 1100px;

            margin: auto;

            padding: 85px 5%;
        }

        .feature-grid {
            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 45px;

            text-align: center;
        }

        .feature-item {
            position: relative;
        }

        .feature-item:not(:last-child)::after {
            content: "";

            position: absolute;

            right: -22px;
            top: 10px;

            width: 1px;
            height: 70px;

            background:
                linear-gradient(
                    transparent,
                    #dfbccb,
                    transparent
                );
        }

        .feature-number {
            font-family: "Cormorant Garamond", serif;

            font-size: 42px;

            color: #b57991;
        }

        .feature-label {
            margin-top: 4px;

            font-family: "Montserrat", sans-serif;

            font-size: 9px;

            letter-spacing: 3px;

            color: #8a7079;
        }


        /* =====================================================
           FOOTER
        ===================================================== */

        footer {
            position: relative;

            padding: 70px 5% 35px;

            text-align: center;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #33262d,
                    #4b303b,
                    #33262d
                );

            overflow: hidden;
        }

        .footer-logo {
            font-family: "Cormorant Garamond", serif;

            font-size: 42px;

            font-weight: 600;

            letter-spacing: 7px;
        }

        .footer-sub {
            margin-top: 7px;

            font-family: "Montserrat", sans-serif;

            font-size: 8px;

            letter-spacing: 4px;

            color: #dcb5c6;
        }

        .footer-line {
            width: 80px;
            height: 1px;

            margin: 25px auto;

            background:
                linear-gradient(
                    90deg,
                    transparent,
                    #d8aabd,
                    transparent
                );
        }

        .socials {
            display: flex;

            justify-content: center;
            align-items: center;

            gap: 13px;

            margin-bottom: 35px;
        }

        .socials a {
            width: 38px;
            height: 38px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            border: 1px solid rgba(236, 198, 214, 0.25);

            color: #e6c4d2;

            transition: 0.3s ease;
        }

        .socials a:hover {
            color: white;

            background: rgba(229, 181, 201, 0.15);

            transform: translateY(-4px);
        }

        .copyright {
            font-family: "Montserrat", sans-serif;

            font-size: 9px;

            letter-spacing: 1.5px;

            color: rgba(255, 255, 255, 0.55);
        }

        .footer-sparkle {
            position: absolute;

            color: rgba(235, 190, 208, 0.45);

            font-size: 18px;
        }

        .footer-s1 {
            left: 12%;
            top: 35%;
        }

        .footer-s2 {
            right: 14%;
            top: 22%;
        }

        .footer-s3 {
            left: 25%;
            bottom: 25%;
        }

        .footer-s4 {
            right: 27%;
            bottom: 30%;
        }


        /* =====================================================
           BACK TO TOP
        ===================================================== */

        .back-top {
            position: fixed;

            right: 25px;
            bottom: 25px;

            width: 43px;
            height: 43px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background:
                rgba(255, 239, 246, 0.92);

            border: 1px solid rgba(183, 124, 149, 0.25);

            color: #a35d7a;

            box-shadow:
                0 10px 25px rgba(91, 55, 70, 0.12);

            opacity: 0;
            visibility: hidden;

            transform: translateY(10px);

            transition: 0.3s ease;

            z-index: 999;
        }

        .back-top.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .back-top:hover {
            transform: translateY(-4px);
        }


        /* =====================================================
           MOUSE GLOW
        ===================================================== */

        .mouse-glow {
            position: fixed;

            width: 250px;
            height: 250px;

            border-radius: 50%;

            pointer-events: none;

            background:
                radial-gradient(
                    circle,
                    rgba(238, 184, 207, 0.12),
                    transparent 70%
                );

            transform: translate(-50%, -50%);

            z-index: 0;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 1100px) {

            .navbar {
                grid-template-columns: 180px 1fr 150px;
                padding: 0 3%;
            }

            .nav-links {
                gap: 17px;
            }

            .nav-links a {
                font-size: 9px;
            }

            .story-grid {
                gap: 50px;
            }

        }


        @media (max-width: 900px) {

            .navbar {
                grid-template-columns: 1fr auto;
                min-height: 78px;
            }

            .logo {
                text-align: left;
            }

            .nav-links {
                display: none;
            }

            .nav-right {
                gap: 8px;
            }

            .story-grid {
                grid-template-columns: 1fr;

                gap: 50px;
            }

            .story-image {
                max-width: 600px;

                width: 100%;

                margin: auto;
            }

            .story-content {
                text-align: center;
            }

            .values-grid {
                grid-template-columns: 1fr 1fr;
            }

        }


        @media (max-width: 600px) {

            .top-bar {
                font-size: 8px;
                letter-spacing: 1.2px;
            }

            .navbar {
                padding: 0 18px;
            }

            .logo-main {
                font-size: 26px;
                letter-spacing: 5px;
            }

            .logo-sub {
                font-size: 6px;
                letter-spacing: 2px;
            }

            .account-btn {
                width: 35px;
                height: 35px;
            }

            .cart-btn {
                min-width: 60px;
                height: 35px;
                padding: 0 9px;
            }

            .cart-btn span:not(.cart-count) {
                display: none;
            }

            .about-hero {
                min-height: 400px;
            }

            .hero-title {
                font-size: 58px;
                letter-spacing: 3px;
            }

            .hero-description {
                font-size: 12px;
            }

            .story-section {
                padding: 70px 6%;
            }

            .section-title {
                font-size: 46px;
            }

            .values-section {
                padding: 75px 6%;
            }

            .values-grid {
                grid-template-columns: 1fr;
            }

            .value-card {
                padding: 40px 25px;
            }

            .feature-section {
                padding: 65px 6%;
            }

            .feature-grid {
                grid-template-columns: 1fr;

                gap: 35px;
            }

            .feature-item:not(:last-child)::after {
                display: none;
            }

            .quote-section {
                padding: 90px 7%;
            }

            .quote-text {
                font-size: 37px;
            }

            footer {
                padding: 60px 20px 30px;
            }

            .footer-logo {
                font-size: 34px;
            }

            .back-top {
                right: 15px;
                bottom: 15px;
            }

        }


        /* =====================================================
           REDUCED MOTION
        ===================================================== */

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: 0.01ms !important;
            }

        }


        /* =====================================================
           VELOURA COLLECTION THEME POLISH
           ปรับเฉพาะการแสดงผล ไม่เปลี่ยน PHP / HTML / JS
        ===================================================== */
        body {
            background:
                radial-gradient(circle at 8% 12%, rgba(242,192,211,.24), transparent 24%),
                radial-gradient(circle at 92% 25%, rgba(232,184,205,.18), transparent 27%),
                radial-gradient(circle at 50% 88%, rgba(247,210,225,.20), transparent 32%),
                #fffafc;
        }

        .navbar {
            box-shadow: 0 8px 30px rgba(92,55,72,.035);
        }

        .about-hero {
            min-height: 500px;
            background:
                linear-gradient(180deg, rgba(48,34,41,.48), rgba(48,34,41,.72)),
                url("images/perfume-hero.jpg") center / cover no-repeat;
        }

        .about-hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,220,233,.06), transparent);
            pointer-events: none;
        }

        .hero-content {
            animation: velouraFadeUp .9s ease both;
        }

        .story-section {
            position: relative;
        }

        .story-image {
            border: 1px solid rgba(186,137,158,.14);
        }

        .value-card {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 2px;
        }

        .quote-section {
            background:
                linear-gradient(180deg, rgba(49,35,42,.66), rgba(49,35,42,.80)),
                url("images/perfume-hero.jpg") center / cover no-repeat;
        }

        footer {
            border-top: 1px solid rgba(235,190,208,.12);
        }

        @keyframes velouraFadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: translateY(0); }
        }

    </style>

</head>


<body>


    <!-- =====================================================
         SPARKLES
    ===================================================== -->

    <div class="sparkle-layer" aria-hidden="true">

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


    <!-- =====================================================
         TOP BAR
    ===================================================== -->

    <div class="top-bar">

        ✦ &nbsp; จัดส่งฟรี เมื่อสั่งซื้อครบ $50 &nbsp; ✦

    </div>


    <!-- =====================================================
         NAVBAR
    ===================================================== -->

    <nav class="navbar">

        <!-- LOGO -->

        <a href="index.php" class="logo">

            <div class="logo-main">
                VELOURA
            </div>

            <div class="logo-sub">
                PERFUMES
            </div>

        </a>


        <!-- MENU -->

        <div class="nav-links">

            <a href="index.php">
                หน้าแรก
            </a>

            <a href="products.php">
                สินค้า
            </a>

            <a href="about.php" class="active">
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


        <!-- RIGHT -->

        <div class="nav-right">

            <a href="profile.php"
                class="account-btn"
                title="บัญชี">

                <i class="fa-regular fa-user"></i>

            </a>


            <a href="cart.php"
                class="cart-btn"
                title="รถเข็น">

                <i class="fa-solid fa-bag-shopping"></i>

                <span>
                    รถเข็น
                </span>

                <span class="cart-count">
                    <?php echo $cart_count; ?>
                </span>

            </a>

        </div>

    </nav>


    <!-- =====================================================
         HERO
    ===================================================== -->

    <section class="about-hero">

        <div class="hero-content">

            <div class="hero-kicker">
                THE ART OF BEAUTIFUL FRAGRANCE
            </div>

            <h1 class="hero-title">
                ABOUT VELOURA
            </h1>

            <div class="hero-line">

                <span></span>

                <i>✦</i>

                <span></span>

            </div>

            <p class="hero-description">

                เรื่องราวของกลิ่นหอมที่ถูกสร้างขึ้น
                เพื่อสะท้อนความงดงาม ความมั่นใจ
                และตัวตนที่เป็นเอกลักษณ์ของคุณ

            </p>

        </div>

    </section>


    <!-- =====================================================
         BRAND STORY
    ===================================================== -->

    <section class="story-section">

        <div class="story-grid">


            <!-- IMAGE -->

            <div class="story-image">

                <img
                    src="images/perfume1.jpg"
                    alt="Veloura Perfumes">

            </div>


            <!-- CONTENT -->

            <div class="story-content">

                <div class="section-kicker">
                    OUR STORY
                </div>

                <h2 class="section-title">

                    กลิ่นหอม
                    <span>ที่เป็นตัวคุณ</span>

                </h2>

                <p>

                    VELOURA PERFUMES
                    เกิดขึ้นจากความหลงใหลในศิลปะของน้ำหอม
                    และความเชื่อว่า “กลิ่นหอม”
                    สามารถบอกเล่าเรื่องราวของคนคนหนึ่งได้
                    โดยไม่จำเป็นต้องเอ่ยคำพูด

                </p>

                <p>

                    เราตั้งใจคัดสรรน้ำหอมที่มีเอกลักษณ์
                    ให้แต่ละกลิ่นสามารถถ่ายทอดอารมณ์
                    บุคลิก และช่วงเวลาที่น่าจดจำ
                    เพื่อให้ทุกครั้งที่คุณฉีดน้ำหอม
                    คุณสามารถรู้สึกถึงตัวตนของตัวเองได้อย่างชัดเจน

                </p>

                <p>

                    ไม่ว่าจะเป็นวันที่อยากดูอ่อนหวาน
                    วันที่ต้องการความมั่นใจ
                    หรือวันที่อยากโดดเด่นเหนือใคร
                    VELOURA พร้อมเป็นส่วนหนึ่ง
                    ของทุกช่วงเวลาของคุณ

                </p>

                <div class="story-signature">
                    With love, Veloura ✦
                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         VALUES
    ===================================================== -->

    <section class="values-section">

        <div class="values-container">


            <div class="values-heading">

                <div class="section-kicker">
                    WHY VELOURA
                </div>

                <h2 class="section-title">
                    สิ่งที่เราใส่ใจ
                </h2>

                <p>

                    เพราะน้ำหอมที่ดีไม่ได้มีเพียงกลิ่นที่หอม
                    แต่ยังต้องสามารถสร้างความรู้สึก
                    และความทรงจำให้กับผู้ที่ได้สัมผัส

                </p>

            </div>


            <div class="values-grid">


                <!-- CARD 1 -->

                <div class="value-card">

                    <div class="value-icon">

                        <i class="fa-solid fa-gem"></i>

                    </div>

                    <h3>
                        Quality
                    </h3>

                    <p>

                        เราให้ความสำคัญกับคุณภาพ
                        ตั้งแต่การเลือกกลิ่น
                        ไปจนถึงประสบการณ์ที่ลูกค้าได้รับ
                        เพื่อให้น้ำหอมแต่ละขวด
                        เป็นสิ่งที่คุณรู้สึกภูมิใจเมื่อได้ใช้

                    </p>

                </div>


                <!-- CARD 2 -->

                <div class="value-card">

                    <div class="value-icon">

                        <i class="fa-solid fa-heart"></i>

                    </div>

                    <h3>
                        Individuality
                    </h3>

                    <p>

                        เราเชื่อว่าทุกคนมีเสน่ห์ในแบบของตัวเอง
                        น้ำหอมจึงไม่ควรมีคำตอบเพียงแบบเดียว
                        แต่ควรเป็นกลิ่นที่สะท้อนตัวตน
                        และความรู้สึกของคุณ

                    </p>

                </div>


                <!-- CARD 3 -->

                <div class="value-card">

                    <div class="value-icon">

                        <i class="fa-solid fa-wand-magic-sparkles"></i>

                    </div>

                    <h3>
                        Elegance
                    </h3>

                    <p>

                        เราสร้างประสบการณ์ที่ผสมผสาน
                        ความหรูหรา ความอ่อนหวาน
                        และความทันสมัย
                        ให้ทุกครั้งที่ใช้น้ำหอม
                        รู้สึกเหมือนเป็นช่วงเวลาพิเศษ

                    </p>

                </div>


            </div>

        </div>

    </section>


    <!-- =====================================================
         FEATURE
    ===================================================== -->

    <section class="feature-section">

        <div class="feature-grid">


            <div class="feature-item">

                <div class="feature-number">
                    01
                </div>

                <div class="feature-label">
                    BEAUTIFUL SCENTS
                </div>

            </div>


            <div class="feature-item">

                <div class="feature-number">
                    02
                </div>

                <div class="feature-label">
                    TIMELESS ELEGANCE
                </div>

            </div>


            <div class="feature-item">

                <div class="feature-number">
                    03
                </div>

                <div class="feature-label">
                    MADE FOR YOU
                </div>

            </div>


        </div>

    </section>


    <!-- =====================================================
         QUOTE
    ===================================================== -->

    <section class="quote-section">

        <div class="quote-content">

            <div class="quote-small">
                THE VELOURA PHILOSOPHY
            </div>

            <div class="quote-text">

                “น้ำหอมไม่ใช่เพียงกลิ่นที่คุณสวมใส่
                แต่คือความรู้สึกที่คุณทิ้งไว้
                เมื่อเดินผ่านใครสักคน”

            </div>

            <div class="quote-author">
                — VELOURA PERFUMES —
            </div>

        </div>

    </section>


    <!-- =====================================================
         FOOTER
    ===================================================== -->

    <footer>


        <span class="footer-sparkle footer-s1">
            ✦
        </span>

        <span class="footer-sparkle footer-s2">
            ✧
        </span>

        <span class="footer-sparkle footer-s3">
            ⋆
        </span>

        <span class="footer-sparkle footer-s4">
            ✦
        </span>


        <div class="footer-logo">
            VELOURA
        </div>

        <div class="footer-sub">
            PERFUMES
        </div>


        <div class="footer-line"></div>


        <!-- SOCIAL -->

        <div class="socials">

            <a href="#" title="Facebook">

                <i class="fa-brands fa-facebook-f"></i>

            </a>

            <a href="#" title="Instagram">

                <i class="fa-brands fa-instagram"></i>

            </a>

            <a href="#" title="TikTok">

                <i class="fa-brands fa-tiktok"></i>

            </a>

            <a href="contact.php" title="ติดต่อเรา">

                <i class="fa-regular fa-envelope"></i>

            </a>

        </div>


        <div class="copyright">

            © <?php echo date("Y"); ?> VELOURA PERFUMES.
            ALL RIGHTS RESERVED.

        </div>

    </footer>


    <!-- =====================================================
         BACK TO TOP
    ===================================================== -->

    <a href="#"
        class="back-top"
        id="backTop"
        aria-label="กลับด้านบน">

        <i class="fa-solid fa-chevron-up"></i>

    </a>


    <!-- =====================================================
         MOUSE GLOW
    ===================================================== -->

    <div class="mouse-glow" id="mouseGlow"></div>


    <!-- =====================================================
         JAVASCRIPT
    ===================================================== -->

    <script>

        /* =========================
           BACK TO TOP
        ========================= */

        const backTop = document.getElementById("backTop");

        window.addEventListener("scroll", function () {

            if (window.scrollY > 400) {

                backTop.classList.add("show");

            } else {

                backTop.classList.remove("show");

            }

        });


        backTop.addEventListener("click", function (e) {

            e.preventDefault();

            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });

        });


        /* =========================
           MOUSE GLOW
        ========================= */

        const mouseGlow =
            document.getElementById("mouseGlow");

        document.addEventListener("mousemove", function (e) {

            mouseGlow.style.left = e.clientX + "px";
            mouseGlow.style.top = e.clientY + "px";

        });


        /* =========================
           HIDE GLOW ON MOBILE
        ========================= */

        function checkMobile() {

            if (window.innerWidth <= 700) {

                mouseGlow.style.display = "none";

            } else {

                mouseGlow.style.display = "block";

            }

        }

        checkMobile();

        window.addEventListener("resize", checkMobile);

    </script>


</body>

</html>