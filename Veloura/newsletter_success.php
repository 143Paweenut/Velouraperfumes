<?php

session_start();

require_once "connect.php";

/* =====================================================
   รับ Email จาก URL
===================================================== */

$email = trim($_GET["email"] ?? "");

$subscriber = null;

/* =====================================================
   ตรวจสอบ Email
===================================================== */

if ($email !== "" && filter_var($email, FILTER_VALIDATE_EMAIL)) {

    /*
    =====================================================
    ดึงข้อมูลจาก newsletter_subscribers
    ใช้เฉพาะ Column ที่มีอยู่จริง
    =====================================================
    */

    $stmt = $conn->prepare("
        SELECT
            id,
            email,
            fullname,
            favorite_notes,
            perfume_time,
            weather_preference,
            priority,
            budget,
            buying_style,
            consent,
            status,
            subscribed_at,
            updated_at
        FROM newsletter_subscribers
        WHERE email = ?
        LIMIT 1
    ");

    if ($stmt) {

        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $subscriber = $result->fetch_assoc();
            }
        }

        $stmt->close();
    }
}

/* =====================================================
   ถ้าไม่พบข้อมูล
===================================================== */

if (!$subscriber) {

    $subscriber = [
        "email" => $email,
        "fullname" => "",
        "favorite_notes" => "",
        "perfume_time" => "",
        "weather_preference" => "",
        "priority" => "",
        "budget" => "",
        "buying_style" => "",
        "status" => "subscribed"
    ];
}


/* =====================================================
   ป้องกัน XSS
===================================================== */

function e($value)
{
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        "UTF-8"
    );
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
    Veloura | Subscription Complete
</title>


<!-- GOOGLE FONT -->

<link rel="preconnect"
      href="https://fonts.googleapis.com">

<link rel="preconnect"
      href="https://fonts.gstatic.com"
      crossorigin>

<link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Montserrat:wght@400;500;600&family=Noto+Sans+Thai:wght@300;400;500;600&display=swap"
    rel="stylesheet"
>


<!-- FONT AWESOME -->

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

    min-height:100vh;

    background:

        radial-gradient(
            circle at 10% 10%,
            rgba(224,157,180,.20),
            transparent 25%
        ),

        radial-gradient(
            circle at 90% 15%,
            rgba(255,255,255,.9),
            transparent 25%
        ),

        linear-gradient(
            135deg,
            #f5e9ee,
            #f9f1f4,
            #eadbe2
        );

    color:#35272d;

    font-family:
        "Noto Sans Thai",
        "Montserrat",
        sans-serif;

    overflow-x:hidden;
}


/* =====================================================
   SPARKLES
===================================================== */

.sparkles{

    position:fixed;

    inset:0;

    pointer-events:none;

    z-index:1;

    overflow:hidden;
}


.sparkles span{

    position:absolute;

    color:#c8899d;

    opacity:0;

    animation:
        sparkle 4s infinite ease-in-out;
}


.sparkles span:nth-child(1){
    left:8%;
    top:20%;
    animation-delay:.5s;
}

.sparkles span:nth-child(2){
    left:18%;
    top:70%;
    animation-delay:1.4s;
}

.sparkles span:nth-child(3){
    left:30%;
    top:15%;
    animation-delay:2.1s;
}

.sparkles span:nth-child(4){
    left:45%;
    top:80%;
    animation-delay:.8s;
}

.sparkles span:nth-child(5){
    left:60%;
    top:25%;
    animation-delay:2.5s;
}

.sparkles span:nth-child(6){
    left:75%;
    top:65%;
    animation-delay:1.1s;
}

.sparkles span:nth-child(7){
    left:88%;
    top:18%;
    animation-delay:2.8s;
}

.sparkles span:nth-child(8){
    left:95%;
    top:75%;
    animation-delay:.2s;
}


@keyframes sparkle{

    0%,100%{
        opacity:0;
        transform:
            scale(.3)
            rotate(0deg);
    }

    40%,60%{
        opacity:.8;
        transform:
            scale(1.2)
            rotate(25deg);
    }

}


/* =====================================================
   TOP BAR
===================================================== */

.top-bar{

    height:38px;

    display:flex;

    align-items:center;

    justify-content:center;

    background:

        linear-gradient(
            90deg,
            #21191d,
            #563944,
            #21191d
        );

    color:#fff;

    font-family:"Montserrat";

    font-size:9px;

    letter-spacing:2px;

    text-align:center;

    padding:0 10px;

    position:relative;

    z-index:5;
}


/* =====================================================
   MAIN
===================================================== */

.page{

    min-height:
        calc(100vh - 38px);

    display:flex;

    align-items:center;

    justify-content:center;

    padding:
        50px 20px 70px;

    position:relative;

    z-index:3;
}


/* =====================================================
   SUCCESS CARD
===================================================== */

.success-card{

    width:100%;

    max-width:850px;

    background:

        linear-gradient(
            135deg,
            rgba(255,255,255,.97),
            rgba(255,247,250,.96)
        );

    border:
        1px solid
        rgba(201,139,157,.3);

    box-shadow:

        0 30px 90px
        rgba(79,46,60,.18);

    padding:
        65px 60px;

    text-align:center;

    position:relative;

    overflow:hidden;
}


.success-card:before{

    content:"";

    position:absolute;

    width:280px;

    height:280px;

    border-radius:50%;

    background:
        rgba(221,164,181,.12);

    top:-130px;

    left:-100px;
}


.success-card:after{

    content:"";

    position:absolute;

    width:350px;

    height:350px;

    border-radius:50%;

    background:
        rgba(214,158,177,.08);

    right:-170px;

    bottom:-170px;
}


/* =====================================================
   ICON
===================================================== */

.success-icon{

    width:82px;

    height:82px;

    margin:
        0 auto 25px;

    border-radius:50%;

    display:flex;

    align-items:center;

    justify-content:center;

    background:

        linear-gradient(
            135deg,
            #392930,
            #704858
        );

    color:#fff;

    font-size:30px;

    box-shadow:

        0 15px 35px
        rgba(61,40,48,.22);

    position:relative;

    z-index:2;
}


/* =====================================================
   KICKER
===================================================== */

.kicker{

    color:#b8758c;

    font-family:"Montserrat";

    font-size:9px;

    letter-spacing:4px;

    margin-bottom:12px;

    position:relative;

    z-index:2;
}


/* =====================================================
   TITLE
===================================================== */

h1{

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:
        clamp(45px,7vw,70px);

    font-weight:500;

    line-height:.95;

    color:#35272d;

    position:relative;

    z-index:2;
}


h1 em{

    color:#c27d94;

    font-style:normal;
}


/* =====================================================
   DESCRIPTION
===================================================== */

.description{

    max-width:600px;

    margin:
        22px auto 30px;

    color:#75636b;

    font-size:12px;

    line-height:2;

    position:relative;

    z-index:2;
}


/* =====================================================
   DIVIDER
===================================================== */

.divider{

    width:100px;

    height:1px;

    margin:
        0 auto 30px;

    background:

        linear-gradient(
            90deg,
            transparent,
            #c8899d,
            transparent
        );

    position:relative;

    z-index:2;
}


/* =====================================================
   PROFILE
===================================================== */

.profile{

    max-width:650px;

    margin:0 auto 30px;

    padding:25px;

    background:
        rgba(255,255,255,.72);

    border:
        1px solid
        #ead7de;

    text-align:left;

    position:relative;

    z-index:2;
}


.profile-title{

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:25px;

    color:#47333c;

    margin-bottom:18px;

    text-align:center;
}


.profile-row{

    display:grid;

    grid-template-columns:
        170px 1fr;

    gap:15px;

    padding:
        10px 0;

    border-bottom:
        1px solid
        #f0e2e7;

    font-size:11px;
}


.profile-row:last-child{

    border-bottom:none;
}


.profile-label{

    color:#98717f;

    font-weight:500;
}


.profile-value{

    color:#514149;

    word-break:break-word;
}


/* =====================================================
   STATUS
===================================================== */

.status{

    display:inline-flex;

    align-items:center;

    gap:6px;

    padding:
        5px 12px;

    background:#e1efe3;

    color:#35603c;

    border:
        1px solid #c8dfcb;

    font-size:9px;

    letter-spacing:.5px;
}


/* =====================================================
   BUTTONS
===================================================== */

.actions{

    display:flex;

    justify-content:center;

    gap:12px;

    flex-wrap:wrap;

    position:relative;

    z-index:2;
}


.btn{

    display:inline-flex;

    align-items:center;

    justify-content:center;

    gap:9px;

    min-width:190px;

    padding:
        14px 22px;

    text-decoration:none;

    font-family:"Montserrat";

    font-size:9px;

    letter-spacing:1.8px;

    transition:.3s;
}


.btn-primary{

    background:

        linear-gradient(
            110deg,
            #302329,
            #63424e,
            #302329
        );

    color:#fff;

    box-shadow:
        0 12px 25px
        rgba(48,35,41,.18);
}


.btn-primary:hover{

    transform:
        translateY(-3px);

    box-shadow:
        0 17px 32px
        rgba(48,35,41,.28);
}


.btn-secondary{

    border:
        1px solid #cfa0af;

    color:#805366;

    background:#fff;
}


.btn-secondary:hover{

    background:#fff5f8;

    border-color:#b9798f;

    transform:
        translateY(-3px);
}


/* =====================================================
   BOTTOM
===================================================== */

.bottom-note{

    margin-top:30px;

    color:#aa929c;

    font-size:8px;

    letter-spacing:2px;

    position:relative;

    z-index:2;
}


/* =====================================================
   FOOTER
===================================================== */

.footer{

    text-align:center;

    padding:
        0 20px 30px;

    color:#a18b94;

    font-size:8px;

    letter-spacing:1.5px;

    position:relative;

    z-index:3;
}


.footer-line{

    width:90px;

    height:1px;

    margin:
        0 auto 12px;

    background:

        linear-gradient(
            90deg,
            transparent,
            #c8899d,
            transparent
        );
}


/* =====================================================
   RESPONSIVE
===================================================== */

@media(max-width:700px){

    .success-card{

        padding:
            45px 25px;
    }


    .profile-row{

        grid-template-columns:1fr;

        gap:4px;
    }


    .actions{

        flex-direction:column;
    }


    .btn{

        width:100%;
    }

}


@media(max-width:450px){

    .top-bar{

        font-size:7px;

        letter-spacing:1px;
    }


    .page{

        padding:
            30px 12px 50px;
    }


    .success-card{

        padding:
            40px 18px;
    }


    h1{

        font-size:45px;
    }


    .description{

        font-size:10px;
    }

}

</style>

</head>


<body>


<!-- =====================================================
     SPARKLES
===================================================== -->

<div class="sparkles">

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

    ✦ VELOURA PERFUMES
    &nbsp; • &nbsp;
    YOUR SIGNATURE · YOUR STORY ✦

</div>


<!-- =====================================================
     MAIN
===================================================== -->

<main class="page">

<div class="success-card">


    <!-- ICON -->

    <div class="success-icon">

        <i class="fa-solid fa-check"></i>

    </div>


    <!-- KICKER -->

    <div class="kicker">

        WELCOME TO VELOURA ✦

    </div>


    <!-- TITLE -->

    <h1>

        You're <em>In.</em>

    </h1>


    <!-- DESCRIPTION -->

    <p class="description">

        ขอบคุณที่เข้าร่วมกับ Veloura
        ✦
        เราจะส่งข่าวสาร โปรโมชั่น
        คอลเลกชันใหม่
        และคำแนะนำเกี่ยวกับน้ำหอม
        ที่เหมาะกับสไตล์ของคุณ
        ไปยังอีเมลของคุณ

    </p>


    <div class="divider"></div>


    <!-- =================================================
         PROFILE
    ================================================= -->

    <div class="profile">

        <div class="profile-title">

            Your Veloura Profile

        </div>


        <?php if($subscriber["fullname"] !== ""): ?>

        <div class="profile-row">

            <div class="profile-label">

                <i class="fa-regular fa-user"></i>
                ชื่อ

            </div>

            <div class="profile-value">

                <?= e($subscriber["fullname"]) ?>

            </div>

        </div>

        <?php endif; ?>


        <div class="profile-row">

            <div class="profile-label">

                <i class="fa-regular fa-envelope"></i>
                อีเมล

            </div>

            <div class="profile-value">

                <?= e($subscriber["email"]) ?>

            </div>

        </div>


        <?php if($subscriber["favorite_notes"] !== ""): ?>

        <div class="profile-row">

            <div class="profile-label">

                <i class="fa-solid fa-sparkles"></i>
                โน้ตที่ชอบ

            </div>

            <div class="profile-value">

                <?= e($subscriber["favorite_notes"]) ?>

            </div>

        </div>

        <?php endif; ?>


        <?php if($subscriber["perfume_time"] !== ""): ?>

        <div class="profile-row">

            <div class="profile-label">

                <i class="fa-regular fa-clock"></i>
                ช่วงเวลาที่ใช้

            </div>

            <div class="profile-value">

                <?= e($subscriber["perfume_time"]) ?>

            </div>

        </div>

        <?php endif; ?>


        <?php if($subscriber["weather_preference"] !== ""): ?>

        <div class="profile-row">

            <div class="profile-label">

                <i class="fa-solid fa-cloud-sun"></i>
                สภาพอากาศ

            </div>

            <div class="profile-value">

                <?= e($subscriber["weather_preference"]) ?>

            </div>

        </div>

        <?php endif; ?>


        <?php if($subscriber["priority"] !== ""): ?>

        <div class="profile-row">

            <div class="profile-label">

                <i class="fa-solid fa-star"></i>
                สิ่งที่ให้ความสำคัญ

            </div>

            <div class="profile-value">

                <?= e($subscriber["priority"]) ?>

            </div>

        </div>

        <?php endif; ?>


        <?php if($subscriber["budget"] !== ""): ?>

        <div class="profile-row">

            <div class="profile-label">

                <i class="fa-solid fa-wallet"></i>
                งบประมาณ

            </div>

            <div class="profile-value">

                <?= e($subscriber["budget"]) ?>

            </div>

        </div>

        <?php endif; ?>


        <?php if($subscriber["buying_style"] !== ""): ?>

        <div class="profile-row">

            <div class="profile-label">

                <i class="fa-solid fa-wand-magic-sparkles"></i>
                สไตล์การเลือก

            </div>

            <div class="profile-value">

                <?= e($subscriber["buying_style"]) ?>

            </div>

        </div>

        <?php endif; ?>


        <div class="profile-row">

            <div class="profile-label">

                <i class="fa-solid fa-circle-check"></i>
                สถานะ

            </div>

            <div class="profile-value">

                <span class="status">

                    <i class="fa-solid fa-check"></i>

                    สมัครรับข่าวสารแล้ว

                </span>

            </div>

        </div>

    </div>


    <!-- =================================================
         ACTIONS
    ================================================= -->

    <div class="actions">

        <a
            href="products.php"
            class="btn btn-primary"
        >

            <i class="fa-solid fa-spray-can-sparkles"></i>

            SHOP PERFUMES

        </a>


        <a
            href="index.php"
            class="btn btn-secondary"
        >

            <i class="fa-solid fa-house"></i>

            กลับหน้าหลัก

        </a>

    </div>


    <div class="bottom-note">

        YOUR SCENT · YOUR STORY · YOUR VELOURA

    </div>


</div>

</main>


<!-- =====================================================
     FOOTER
===================================================== -->

<footer class="footer">

    <div class="footer-line"></div>

    VELOURA PERFUMES
    &nbsp; ✦ &nbsp;
    YOUR SIGNATURE, YOUR STORY

</footer>


</body>

</html>