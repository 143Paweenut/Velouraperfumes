<?php

session_start();

require_once "connect.php";

if (
    !isset($_SESSION["logged_in"]) ||
    $_SESSION["logged_in"] !== true
) {

    header("Location: login.php?redirect=profile.php");

    exit();

}


if (!isset($_SESSION["user_id"])) {

    session_destroy();

    header("Location: login.php");

    exit();

}

$user_id = $_SESSION["user_id"];


$sql = "
    SELECT
        id,
        username,
        email,
        fullname,
        address,
        phone,
        scent,
        newsletter,
        created_at
    FROM users
    WHERE id = ?
    LIMIT 1
";


$stmt = $conn->prepare($sql);

if (!$stmt) {

    die("
        <div style='
            font-family:Arial;
            padding:50px;
            text-align:center;
            color:#4b3935;
            background:#fff8f8;
        '>

            <h2>เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล</h2>

            <p>
                กรุณาตรวจสอบว่าตาราง
                <b>users</b>
                มีคอลัมน์
                <b>scent</b>
                และ
                <b>newsletter</b>
                แล้วหรือยัง
            </p>

            <p style='color:#a77c5d;'>
                {$conn->error}
            </p>

            <pre style='
                display:inline-block;
                text-align:left;
                padding:20px;
                background:#fff;
                border:1px solid #ead8d3;
                border-radius:12px;
            '>ALTER TABLE users
ADD COLUMN scent TEXT NULL,
ADD COLUMN newsletter TINYINT(1) DEFAULT 0;</pre>

        </div>
    ");

}


$stmt->bind_param(
    "i",
    $user_id
);


$stmt->execute();


$result = $stmt->get_result();


if ($result->num_rows !== 1) {

    $stmt->close();

    session_destroy();

    header("Location: login.php");

    exit();

}


$user = $result->fetch_assoc();


$stmt->close();


/*
=====================================================
จัดการกลิ่นที่ชอบ
=====================================================
*/

$scent_list = array();


if (!empty($user["scent"])) {

    $scent_list = array_filter(

        array_map(
            "trim",
            explode(",", $user["scent"])
        )

    );

}


/*
=====================================================
Newsletter
=====================================================
*/

$newsletter_status = "";

if (
    isset($user["newsletter"]) &&
    (int)$user["newsletter"] === 1
) {

    $newsletter_status =
        "รับข่าวสารและโปรโมชั่นจาก VELOURA";

} else {

    $newsletter_status =
        "ไม่รับข่าวสารและโปรโมชั่น";

}


/*
=====================================================
Avatar
=====================================================
*/

$avatar_letter = mb_substr(
    $user["username"],
    0,
    1
);

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
    บัญชีของฉัน | VELOURA PERFUMES
</title>


<!-- =====================================================
     GOOGLE FONTS
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

* {

    box-sizing: border-box;

    margin: 0;

    padding: 0;

}

html {

    scroll-behavior: smooth;

}

body {

    min-height: 100vh;

    overflow-x: hidden;

    font-family:
        "Montserrat",
        "Noto Sans Thai",
        sans-serif;

    color: #493b38;

    background:

        radial-gradient(
            circle at 10% 10%,
            rgba(255, 202, 216, .55),
            transparent 25%
        ),

        radial-gradient(
            circle at 90% 15%,
            rgba(246, 220, 180, .30),
            transparent 25%
        ),

        radial-gradient(
            circle at 50% 75%,
            rgba(255, 226, 233, .55),
            transparent 35%
        ),

        linear-gradient(
            135deg,
            #fffafa,
            #fff4f6,
            #fffaf5
        );

}


/* =====================================================
   SCROLLBAR
===================================================== */

::-webkit-scrollbar {

    width: 8px;

}

::-webkit-scrollbar-track {

    background: #fff6f7;

}

::-webkit-scrollbar-thumb {

    background:
        linear-gradient(
            #e7b5bd,
            #c49b62
        );

    border-radius: 20px;

}


/* =====================================================
   SPARKLE BACKGROUND
===================================================== */

.sparkles {

    position: fixed;

    inset: 0;

    z-index: 0;

    pointer-events: none;

    overflow: hidden;

}

.sparkles span {

    position: absolute;

    color: #d5ad68;

    font-size: 10px;

    opacity: .5;

    text-shadow:

        0 0 5px #fff,

        0 0 10px rgba(213,173,104,.7),

        0 0 20px rgba(246,205,214,.9);

    animation:
        sparkleFloat
        5s
        ease-in-out
        infinite;

}

@keyframes sparkleFloat {

    0% {

        opacity: .15;

        transform:
            translateY(0)
            scale(.7)
            rotate(0deg);

    }

    50% {

        opacity: 1;

        transform:
            translateY(-25px)
            scale(1.4)
            rotate(25deg);

    }

    100% {

        opacity: .15;

        transform:
            translateY(0)
            scale(.7)
            rotate(50deg);

    }

}


/* =====================================================
   TOP BAR
===================================================== */

.top-bar {

    position: relative;

    z-index: 100;

    height: 36px;

    display: flex;

    align-items: center;

    justify-content: center;

    color: white;

    font-size: 10px;

    letter-spacing: 2px;

    background:

        linear-gradient(
            90deg,
            #a87970,
            #d6a8ad,
            #c8a36c,
            #d6a8ad,
            #a87970
        );

    box-shadow:

        0 3px 20px
        rgba(122,80,76,.12);

}


/* =====================================================
   NAVBAR
===================================================== */

.navbar {

    position: sticky;

    top: 0;

    z-index: 100;

    height: 88px;

    display: grid;

    grid-template-columns:
        230px
        1fr
        230px;

    align-items: center;

    padding: 0 5%;

    background:

        linear-gradient(
            90deg,
            rgba(255,255,255,.88),
            rgba(255,240,244,.72),
            rgba(255,255,255,.88)
        );

    backdrop-filter:
        blur(25px);

    -webkit-backdrop-filter:
        blur(25px);

    border-bottom:
        1px solid
        rgba(190,145,108,.22);

    box-shadow:

        0 8px 35px
        rgba(113,74,69,.08);

    overflow: hidden;

}


/* Navbar Shine */

.navbar::after {

    content: "";

    position: absolute;

    top: 0;

    left: -100%;

    width: 50%;

    height: 100%;

    background:

        linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,.5),
            transparent
        );

    transform:
        skewX(-25deg);

    animation:
        navShine
        7s
        infinite;

}

@keyframes navShine {

    0% {

        left: -100%;

    }

    25%,
    100% {

        left: 150%;

    }

}


/* =====================================================
   LOGO
===================================================== */

.logo {

    position: relative;

    z-index: 5;

    width: max-content;

    text-decoration: none;

}

.logo-main {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size: 38px;

    font-weight: 600;

    letter-spacing: 8px;

    line-height: .8;

    background:

        linear-gradient(
            135deg,
            #352825,
            #916b59,
            #c59b5c,
            #6e4c43
        );

    -webkit-background-clip: text;

    -webkit-text-fill-color:
        transparent;

}

.logo-sub {

    margin-top: 9px;

    margin-left: 5px;

    font-size: 7px;

    letter-spacing: 5px;

    color: #b18b68;

}


/* =====================================================
   CENTER MENU
===================================================== */

.nav-menu {

    position: relative;

    z-index: 5;

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 30px;

}

.nav-menu a {

    position: relative;

    color: #5a4742;

    text-decoration: none;

    font-family:
        "Noto Sans Thai",
        sans-serif;

    font-size: 11px;

    transition: .3s ease;

}

.nav-menu a::before {

    content: "✦";

    position: absolute;

    top: -12px;

    left: 50%;

    transform:
        translateX(-50%)
        scale(.2);

    color: #c9a15f;

    opacity: 0;

    transition: .3s ease;

}

.nav-menu a::after {

    content: "";

    position: absolute;

    bottom: -7px;

    left: 50%;

    width: 0;

    height: 1px;

    transform:
        translateX(-50%);

    background:

        linear-gradient(
            90deg,
            transparent,
            #c39b61,
            transparent
        );

    transition: .35s ease;

}

.nav-menu a:hover {

    color: #a4746d;

    transform:
        translateY(-2px);

}

.nav-menu a:hover::before {

    opacity: 1;

    transform:
        translateX(-50%)
        scale(1);

}

.nav-menu a:hover::after {

    width: 100%;

}


/* =====================================================
   NAV RIGHT
===================================================== */

.nav-right {

    position: relative;

    z-index: 5;

    display: flex;

    justify-content: flex-end;

    gap: 9px;

}

.nav-button {

    position: relative;

    height: 40px;

    min-width: 82px;

    padding: 0 13px;

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 7px;

    color: #604a44;

    text-decoration: none;

    border:
        1px solid
        rgba(190,145,108,.25);

    border-radius: 30px;

    background:
        rgba(255,255,255,.5);

    backdrop-filter:
        blur(10px);

    transition: .3s ease;

}

.nav-button i {

    color: #b38b58;

    font-size: 12px;

}

.nav-button span {

    font-family:
        "Noto Sans Thai",
        sans-serif;

    font-size: 10px;

}

.nav-button:hover {

    color: #a66f70;

    background:
        rgba(250,216,224,.55);

    border-color:
        rgba(190,145,108,.5);

    transform:
        translateY(-2px);

    box-shadow:

        0 8px 22px
        rgba(181,128,130,.15);

}


/* =====================================================
   NAV SPARKLES
===================================================== */

.nav-sparkle {

    position: absolute;

    color: #d5ad68;

    text-shadow:

        0 0 6px #fff,

        0 0 15px
        rgba(213,173,104,.8);

    animation:
        navTwinkle
        2s
        ease-in-out
        infinite;

    pointer-events: none;

}

.ns1 {

    top: 15px;

    left: 29%;

    font-size: 9px;

}

.ns2 {

    bottom: 12px;

    left: 54%;

    font-size: 7px;

    animation-delay: .7s;

}

.ns3 {

    top: 18px;

    right: 24%;

    font-size: 12px;

    animation-delay: 1.2s;

}

@keyframes navTwinkle {

    0%,
    100% {

        opacity: .2;

        transform:
            scale(.7)
            rotate(0);

    }

    50% {

        opacity: 1;

        transform:
            scale(1.4)
            rotate(25deg);

    }

}


/* =====================================================
   PAGE HEADER
===================================================== */

.page-header {

    position: relative;

    z-index: 2;

    text-align: center;

    padding:
        75px 20px 45px;

}

.page-header::before {

    content:
        "✦   ✧   ⋆   ✦   ✧";

    display: block;

    margin-bottom: 20px;

    color: #c7a263;

    font-size: 12px;

    letter-spacing: 10px;

    text-shadow:

        0 0 7px #fff,

        0 0 15px
        rgba(221,182,126,.8);

    animation:
        titleSparkle
        2.5s
        infinite
        alternate;

}

@keyframes titleSparkle {

    from {

        opacity: .35;

    }

    to {

        opacity: 1;

    }

}

.small-title {

    color: #b48672;

    font-size: 10px;

    letter-spacing: 5px;

    margin-bottom: 10px;

}

.page-header h1 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:
        clamp(50px, 7vw, 75px);

    font-weight: 500;

    letter-spacing: 5px;

    margin: 0;

    background:

        linear-gradient(
            135deg,
            #473530,
            #a4776b,
            #c39a5b,
            #694b44
        );

    -webkit-background-clip: text;

    -webkit-text-fill-color:
        transparent;

    text-shadow:
        0 10px 25px
        rgba(135,91,87,.08);

}

.page-header p {

    margin-top: 10px;

    color: #947f79;

    font-size: 12px;

}


/* =====================================================
   CONTAINER
===================================================== */

.container {

    position: relative;

    z-index: 2;

    max-width: 980px;

    margin:
        0 auto 100px;

    padding:
        0 20px;

}


/* =====================================================
   PROFILE CARD
===================================================== */

.profile-card {

    position: relative;

    overflow: hidden;

    border-radius: 28px;

    border:
        1px solid
        rgba(191,146,105,.25);

    background:

        linear-gradient(
            145deg,
            rgba(255,255,255,.86),
            rgba(255,244,247,.76),
            rgba(255,251,246,.84)
        );

    backdrop-filter:
        blur(20px);

    -webkit-backdrop-filter:
        blur(20px);

    box-shadow:

        0 25px 70px
        rgba(106,70,67,.10),

        0 0 0 1px
        rgba(255,255,255,.6)
        inset;

}


/* Card Decorative Glow */

.profile-card::before {

    content: "";

    position: absolute;

    width: 350px;

    height: 350px;

    top: -200px;

    right: -150px;

    border-radius: 50%;

    background:

        radial-gradient(
            circle,
            rgba(248,203,214,.55),
            transparent 70%
        );

}

.profile-card::after {

    content: "";

    position: absolute;

    width: 300px;

    height: 300px;

    bottom: -190px;

    left: -140px;

    border-radius: 50%;

    background:

        radial-gradient(
            circle,
            rgba(235,211,173,.30),
            transparent 70%
        );

}


/* =====================================================
   PROFILE TOP
===================================================== */

.profile-top {

    position: relative;

    z-index: 2;

    padding:
        42px 50px;

    display: flex;

    align-items: center;

    gap: 25px;

    background:

        linear-gradient(
            120deg,
            rgba(255,232,238,.7),
            rgba(255,255,255,.5),
            rgba(248,233,205,.45)
        );

    border-bottom:
        1px solid
        rgba(190,145,108,.18);

}


/* =====================================================
   AVATAR
===================================================== */

.avatar {

    position: relative;

    width: 82px;

    height: 82px;

    flex-shrink: 0;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    font-family:
        "Cormorant Garamond",
        serif;

    font-size: 36px;

    font-weight: 600;

    text-transform: uppercase;

    color: white;

    background:

        linear-gradient(
            145deg,
            #493530,
            #806158,
            #c4a16a
        );

    border:
        3px solid
        rgba(255,255,255,.85);

    box-shadow:

        0 8px 25px
        rgba(112,74,68,.20),

        0 0 0 5px
        rgba(215,171,119,.12),

        0 0 25px
        rgba(225,183,191,.25);

}


/* Avatar Star */

.avatar::before {

    content: "✦";

    position: absolute;

    right: -8px;

    top: -7px;

    color: #c69c5c;

    font-size: 18px;

    text-shadow:

        0 0 5px white,

        0 0 12px
        #dfc28d;

    animation:
        avatarStar
        1.7s
        infinite
        alternate;

}

@keyframes avatarStar {

    from {

        transform:
            scale(.7)
            rotate(0);

        opacity: .4;

    }

    to {

        transform:
            scale(1.2)
            rotate(20deg);

        opacity: 1;

    }

}


/* =====================================================
   WELCOME
===================================================== */

.welcome h2 {

    margin-bottom: 5px;

    font-family:
        "Cormorant Garamond",
        serif;

    font-size: 32px;

    font-weight: 600;

    color: #4c3934;

}

.welcome p {

    color: #927d76;

    font-size: 12px;

}


/* =====================================================
   ACCOUNT CONTENT
===================================================== */

.account-content {

    position: relative;

    z-index: 2;

    padding:
        20px 50px 45px;

}


/* =====================================================
   SECTION TITLE
===================================================== */

.section-title {

    display: flex;

    align-items: center;

    gap: 12px;

    margin:
        28px 0 5px;

    font-family:
        "Cormorant Garamond",
        serif;

    font-size: 27px;

    font-weight: 600;

    color: #55413c;

}

.section-title::before {

    content: "✦";

    color: #c49a5e;

    font-size: 12px;

    text-shadow:
        0 0 8px
        rgba(216,177,117,.7);

}

.section-line {

    width: 55px;

    height: 1px;

    margin-bottom: 10px;

    background:

        linear-gradient(
            90deg,
            #c29a61,
            rgba(194,154,97,.1)
        );

}


/* =====================================================
   INFORMATION ROW
===================================================== */

.row {

    position: relative;

    min-height: 67px;

    display: flex;

    align-items: center;

    padding: 13px 5px;

    border-bottom:
        1px solid
        rgba(196,166,151,.16);

    transition: .3s ease;

}

.row:hover {

    padding-left: 12px;

    background:
        rgba(255,221,229,.18);

}

.label {

    width: 190px;

    flex-shrink: 0;

    color: #a28b84;

    font-size: 10px;

    letter-spacing: 1.3px;

    text-transform: uppercase;

}

.value {

    flex: 1;

    color: #4e403c;

    font-size: 13px;

    line-height: 1.8;

}


/* =====================================================
   SCENT TAGS
===================================================== */

.scent-tags {

    display: flex;

    flex-wrap: wrap;

    gap: 8px;

}

.scent-tag {

    position: relative;

    display: inline-flex;

    align-items: center;

    padding:
        8px 14px;

    border:
        1px solid
        rgba(193,157,111,.35);

    border-radius: 20px;

    color: #795e57;

    background:

        linear-gradient(
            135deg,
            rgba(255,246,248,.9),
            rgba(250,237,216,.65)
        );

    font-size: 10px;

    transition: .3s ease;

    overflow: hidden;

}

.scent-tag::before {

    content: "✦";

    margin-right: 5px;

    color: #c49b5f;

    font-size: 8px;

}

.scent-tag:hover {

    color: white;

    border-color: #b88c53;

    background:

        linear-gradient(
            135deg,
            #7b5b53,
            #a77a70,
            #c19b62
        );

    transform:
        translateY(-2px);

    box-shadow:

        0 6px 15px
        rgba(164,117,99,.2);

}

.no-data {

    color: #a99a94;

    font-style: italic;

}


/* =====================================================
   NEWSLETTER
===================================================== */

.newsletter-yes {

    color: #a27a46;

    display: inline-flex;

    align-items: center;

    gap: 7px;

}

.newsletter-yes::before {

    content: "✦";

    color: #c39a5e;

}

.newsletter-no {

    color: #a19691;

}


/* =====================================================
   BUTTONS
===================================================== */

.buttons {

    margin-top: 42px;

    display: flex;

    justify-content: center;

    gap: 13px;

}


/* Back Home */

.back-home,
.logout {

    position: relative;

    min-width: 180px;

    height: 48px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 8px;

    text-decoration: none;

    font-family:
        "Noto Sans Thai",
        sans-serif;

    font-size: 11px;

    letter-spacing: 1px;

    border-radius: 30px;

    overflow: hidden;

    transition: .35s ease;

}


/* Back */

.back-home {

    color: #765b55;

    border:
        1px solid
        rgba(184,142,107,.35);

    background:
        rgba(255,255,255,.6);

}

.back-home:hover {

    color: #a1746b;

    border-color:
        #c79b62;

    transform:
        translateY(-3px);

    box-shadow:

        0 10px 25px
        rgba(170,124,108,.13);

}


/* Logout */

.logout {

    color: white;

    background:

        linear-gradient(
            110deg,
            #493631,
            #75534b,
            #b18a58,
            #6d4c45
        );

    background-size:
        250% 100%;

    box-shadow:

        0 8px 22px
        rgba(105,72,65,.20);

}

.logout:hover {

    background-position:
        100% 0;

    transform:
        translateY(-3px);

    box-shadow:

        0 13px 30px
        rgba(105,72,65,.28);

}


/* =====================================================
   FOOTER
===================================================== */

.footer {

    position: relative;

    z-index: 2;

    padding:
        55px 20px 35px;

    text-align: center;

    color: #e9d8d3;

    background:

        radial-gradient(
            circle at 50% 0,
            rgba(202,157,96,.18),
            transparent 30%
        ),

        #332624;

    overflow: hidden;

}

.footer::before {

    content:
        "✦   ✧   ⋆   ✦   ✧   ⋆   ✦";

    display: block;

    margin-bottom: 20px;

    color:
        rgba(226,192,139,.7);

    font-size: 10px;

    letter-spacing: 9px;

    text-shadow:

        0 0 10px
        rgba(226,192,139,.5);

}

.footer-logo {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size: 40px;

    letter-spacing: 8px;

    background:

        linear-gradient(
            90deg,
            #ead4c8,
            #e2bf86,
            #fff1e8
        );

    -webkit-background-clip: text;

    -webkit-text-fill-color:
        transparent;

}

.footer-text {

    margin-top: 8px;

    color: #b9a6a0;

    font-size: 10px;

    letter-spacing: 2px;

}

.footer-copy {

    margin-top: 25px;

    color: #81706b;

    font-size: 8px;

    letter-spacing: 1px;

}


/* =====================================================
   BACK TO TOP
===================================================== */

#scrollTop {

    position: fixed;

    right: 25px;

    bottom: 25px;

    z-index: 200;

    width: 44px;

    height: 44px;

    display: flex;

    align-items: center;

    justify-content: center;

    border:
        1px solid
        rgba(192,151,98,.4);

    border-radius: 50%;

    background:
        rgba(255,250,248,.82);

    backdrop-filter:
        blur(12px);

    color: #a67c54;

    cursor: pointer;

    opacity: 0;

    visibility: hidden;

    transform:
        translateY(15px);

    transition: .3s;

    box-shadow:

        0 8px 25px
        rgba(110,76,70,.12);

}

#scrollTop.show {

    opacity: 1;

    visibility: visible;

    transform:
        translateY(0);

}

#scrollTop:hover {

    color: white;

    background:
        linear-gradient(
            135deg,
            #9b6e65,
            #c49a5e
        );

    transform:
        translateY(-4px);

}


/* =====================================================
   RESPONSIVE
===================================================== */

@media (max-width: 1100px) {

    .navbar {

        grid-template-columns:
            190px
            1fr
            190px;

        padding:
            0 3%;

    }

    .nav-menu {

        gap: 17px;

    }

    .nav-menu a {

        font-size: 10px;

    }

    .logo-main {

        font-size: 31px;

    }

    .nav-button {

        min-width: 42px;

        width: 42px;

        padding: 0;

    }

    .nav-button span {

        display: none;

    }

}


@media (max-width: 900px) {

    .navbar {

        grid-template-columns:
            1fr
            auto;

    }

    .nav-menu {

        display: none;

    }

}


@media (max-width: 700px) {

    .top-bar {

        font-size: 8px;

        letter-spacing: 1px;

    }

    .navbar {

        height: 72px;

        padding:
            0 18px;

    }

    .logo-main {

        font-size: 27px;

        letter-spacing: 5px;

    }

    .logo-sub {

        font-size: 6px;

        letter-spacing: 3px;

    }

    .nav-button {

        width: 36px;

        min-width: 36px;

        height: 36px;

    }

    .page-header {

        padding:
            55px 15px 35px;

    }

    .page-header h1 {

        font-size: 46px;

        letter-spacing: 2px;

    }

    .container {

        padding:
            0 12px;

    }

    .profile-top {

        padding:
            30px 25px;

        gap: 17px;

    }

    .avatar {

        width: 65px;

        height: 65px;

        font-size: 29px;

    }

    .welcome h2 {

        font-size: 25px;

    }

    .welcome p {

        font-size: 10px;

    }

    .account-content {

        padding:
            15px 25px 35px;

    }

    .section-title {

        font-size: 24px;

    }

    .row {

        display: block;

        padding:
            15px 5px;

    }

    .label {

        width: auto;

        margin-bottom: 5px;

        display: block;

    }

    .value {

        font-size: 12px;

    }

    .buttons {

        flex-direction: column;

    }

    .back-home,
    .logout {

        width: 100%;

    }

}


@media (max-width: 400px) {

    .logo-main {

        font-size: 23px;

        letter-spacing: 4px;

    }

    .profile-top {

        padding:
            25px 18px;

    }

    .account-content {

        padding:
            10px 18px 30px;

    }

    .page-header h1 {

        font-size: 40px;

    }

}

</style>

</head>


<body>


<div class="sparkles">

    <?php

    $sparkle_symbols =
        ["✦", "✧", "⋆"];

    for ($i = 0; $i < 45; $i++):

        $symbol =
            $sparkle_symbols[
                rand(
                    0,
                    count($sparkle_symbols) - 1
                )
            ];

    ?>

        <span>

            <?= $symbol ?>

        </span>

    <?php endfor; ?>

</div>


<div class="top-bar">

    ✦ ยินดีต้อนรับสู่ VELOURA PERFUMES · FRAGRANCE THAT TELLS YOUR STORY ✦

</div>


<nav class="navbar">


    <a
        href="index.php"
        class="logo"
    >

        <div class="logo-main">
            VELOURA
        </div>

        <div class="logo-sub">
            PERFUMES
        </div>

    </a>

    <div class="nav-menu">

        <a href="index.php" class="active">หน้าแรก</a>
        <a href="products.php">สินค้า</a>
        <a href="about.php">เกี่ยวกับเรา</a>
        <a href="collection.php">คอลเลกชัน</a>
        <a href="find-scent.php">ค้นหากลิ่น</a>
        <a href="contact.php">ติดต่อเรา</a>

    </div>


    <div class="nav-right">


        <a
            href="profile.php"
            class="nav-button"
            title="บัญชีของฉัน"
        >

            <i class="fa-regular fa-user"></i>

            <span>
                บัญชี
            </span>

        </a>


        <a
            href="cart.php"
            class="nav-button"
            title="รถเข็น"
        >

            <i class="fa-solid fa-bag-shopping"></i>

            <span>
                รถเข็น
            </span>

        </a>


    </div>


    <!-- SPARKLES -->

    <div class="nav-sparkle ns1">
        ✦
    </div>

    <div class="nav-sparkle ns2">
        ✧
    </div>

    <div class="nav-sparkle ns3">
        ⋆
    </div>


</nav>

<header class="page-header">


    <div class="small-title">

        MY ACCOUNT

    </div>


    <h1>

        บัญชีของฉัน

    </h1>


    <p>

        จัดการข้อมูลส่วนตัวและกลิ่นหอมที่คุณชื่นชอบ

    </p>


</header>


<!-- =====================================================
     MAIN
===================================================== -->

<main class="container">


    <div class="profile-card">


        <!-- =================================================
             PROFILE TOP
        ================================================== -->

        <div class="profile-top">


            <div class="avatar">

                <?= htmlspecialchars(
                    strtoupper($avatar_letter)
                ) ?>

            </div>


            <div class="welcome">

                <h2>

                    ยินดีต้อนรับ,
                    <?= htmlspecialchars(
                        $user["username"]
                    ) ?>

                </h2>


                <p>

                    สมาชิกของ VELOURA PERFUMES ✦

                </p>

            </div>


        </div>


        <!-- =================================================
             CONTENT
        ================================================== -->

        <div class="account-content">


            <!-- PERSONAL -->

            <div class="section-title">

                ข้อมูลส่วนตัว

            </div>


            <div class="section-line"></div>


            <!-- USERNAME -->

            <div class="row">

                <div class="label">

                    Username

                </div>


                <div class="value">

                    <?= htmlspecialchars(
                        $user["username"]
                    ) ?>

                </div>

            </div>


            <!-- EMAIL -->

            <div class="row">

                <div class="label">

                    Email

                </div>


                <div class="value">

                    <?= htmlspecialchars(
                        $user["email"]
                    ) ?>

                </div>

            </div>


            <!-- FULLNAME -->

            <div class="row">

                <div class="label">

                    ชื่อ-นามสกุล

                </div>


                <div class="value">

                    <?= !empty($user["fullname"])
                        ? htmlspecialchars(
                            $user["fullname"]
                        )
                        : '<span class="no-data">ยังไม่ได้ระบุ</span>'
                    ?>

                </div>

            </div>


            <!-- ADDRESS -->

            <div class="row">

                <div class="label">

                    ที่อยู่

                </div>


                <div class="value">

                    <?= !empty($user["address"])
                        ? nl2br(
                            htmlspecialchars(
                                $user["address"]
                            )
                        )
                        : '<span class="no-data">ยังไม่ได้ระบุ</span>'
                    ?>

                </div>

            </div>


            <!-- PHONE -->

            <div class="row">

                <div class="label">

                    เบอร์โทรศัพท์

                </div>


                <div class="value">

                    <?= !empty($user["phone"])
                        ? htmlspecialchars(
                            $user["phone"]
                        )
                        : '<span class="no-data">ยังไม่ได้ระบุ</span>'
                    ?>

                </div>

            </div>


            <!-- CREATED -->

            <div class="row">

                <div class="label">

                    วันที่สมัคร

                </div>


                <div class="value">

                    <?= htmlspecialchars(
                        $user["created_at"] ?? ""
                    ) ?>

                </div>

            </div>


            <!-- =================================================
                 FRAGRANCE
            ================================================== -->

            <div class="section-title">

                กลิ่นหอมที่คุณชื่นชอบ

            </div>


            <div class="section-line"></div>


            <div class="row">

                <div class="label">

                    กลิ่นที่ชอบ

                </div>


                <div class="value">


                    <?php if (!empty($scent_list)): ?>


                        <div class="scent-tags">


                            <?php foreach (
                                $scent_list
                                as $scent
                            ): ?>


                                <span class="scent-tag">

                                    <?= htmlspecialchars(
                                        $scent
                                    ) ?>

                                </span>


                            <?php endforeach; ?>


                        </div>


                    <?php else: ?>


                        <span class="no-data">

                            ยังไม่ได้เลือกกลิ่นที่ชอบ

                        </span>


                    <?php endif; ?>


                </div>

            </div>


            <!-- =================================================
                 NEWSLETTER
            ================================================== -->

            <div class="row">

                <div class="label">

                    Newsletter

                </div>


                <div class="value">


                    <?php if (
                        (int)$user["newsletter"] === 1
                    ): ?>


                        <span class="newsletter-yes">

                            รับข่าวสารและโปรโมชั่นจาก VELOURA

                        </span>


                    <?php else: ?>


                        <span class="newsletter-no">

                            ไม่รับข่าวสารและโปรโมชั่น

                        </span>


                    <?php endif; ?>


                </div>

            </div>


            <!-- =================================================
                 BUTTONS
            ================================================== -->

            <div class="buttons">


                <a
                    href="index.php"
                    class="back-home"
                >

                    <i
                        class="fa-solid fa-house"
                    ></i>

                    กลับหน้าหลัก

                </a>


                <a
                    href="logout.php"
                    class="logout"
                >

                    <i
                        class="fa-solid fa-right-from-bracket"
                    ></i>

                    ออกจากระบบ

                </a>


            </div>


        </div>


    </div>


</main>


<!-- =====================================================
     FOOTER
===================================================== -->

<footer class="footer">


    <div class="footer-logo">

        VELOURA

    </div>


    <div class="footer-text">

        PERFUMES · FRAGRANCE THAT TELLS YOUR STORY

    </div>


    <div class="footer-copy">

        © <?= date("Y") ?>

        VELOURA PERFUMES

        · ALL RIGHTS RESERVED

    </div>


</footer>


<!-- =====================================================
     BACK TO TOP
===================================================== -->

<button
    id="scrollTop"
    title="กลับด้านบน"
>

    <i
        class="fa-solid fa-chevron-up"
    ></i>

</button>


<script>

/* =====================================================
   RANDOM SPARKLES
===================================================== */

const sparkleBox =
    document.querySelector(".sparkles");

const sparkleItems =
    sparkleBox.querySelectorAll("span");


sparkleItems.forEach(function(item) {

    item.style.left =
        Math.random() * 100 + "%";

    item.style.top =
        Math.random() * 100 + "%";

    item.style.fontSize =
        (Math.random() * 8 + 6) + "px";

    item.style.animationDelay =
        (Math.random() * 5) + "s";

    item.style.animationDuration =
        (Math.random() * 4 + 4) + "s";

});


/* =====================================================
   BACK TO TOP
===================================================== */

const scrollTop =
    document.getElementById("scrollTop");


window.addEventListener(
    "scroll",
    function() {

        if (window.scrollY > 350) {

            scrollTop.classList.add("show");

        } else {

            scrollTop.classList.remove("show");

        }

    }
);


scrollTop.addEventListener(
    "click",
    function() {

        window.scrollTo({

            top: 0,

            behavior: "smooth"

        });

    }
);

</script>


</body>

</html>