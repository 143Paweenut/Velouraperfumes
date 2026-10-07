<?php

session_start();
require_once "connect.php";

/* =====================================================
   เตรียม Cart
===================================================== */

if (!isset($_SESSION["cart"]) || !is_array($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}


/* =====================================================
   เพิ่มสินค้า
===================================================== */

if (isset($_GET["add"])) {

    $id = (int)$_GET["add"];

    if ($id > 0) {

        $check = $conn->prepare(
            "SELECT id FROM products WHERE id = ?"
        );

        $check->bind_param("i", $id);
        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows > 0) {

            if (isset($_SESSION["cart"][$id])) {
                $_SESSION["cart"][$id]++;
            } else {
                $_SESSION["cart"][$id] = 1;
            }
        }

        $check->close();
    }

    header("Location: cart.php");
    exit;
}


/* =====================================================
   เพิ่มจำนวน
===================================================== */

if (isset($_GET["plus"])) {

    $id = (int)$_GET["plus"];

    if (
        $id > 0 &&
        isset($_SESSION["cart"][$id])
    ) {

        $_SESSION["cart"][$id]++;

    }

    header("Location: cart.php");
    exit;
}


/* =====================================================
   ลดจำนวน
===================================================== */

if (isset($_GET["minus"])) {

    $id = (int)$_GET["minus"];

    if (
        $id > 0 &&
        isset($_SESSION["cart"][$id])
    ) {

        $_SESSION["cart"][$id]--;

        if ($_SESSION["cart"][$id] <= 0) {
            unset($_SESSION["cart"][$id]);
        }
    }

    header("Location: cart.php");
    exit;
}


/* =====================================================
   ลบสินค้า
===================================================== */

if (isset($_GET["remove"])) {

    $id = (int)$_GET["remove"];

    if ($id > 0) {
        unset($_SESSION["cart"][$id]);
    }

    header("Location: cart.php");
    exit;
}


/* =====================================================
   ล้างรถเข็น
===================================================== */

if (isset($_GET["clear"])) {

    $_SESSION["cart"] = [];

    header("Location: cart.php");
    exit;
}


/* =====================================================
   ดึงสินค้า
===================================================== */

$cart_products = [];

$total = 0;
$total_items = 0;

if (!empty($_SESSION["cart"])) {

    $ids = array_keys($_SESSION["cart"]);

    $id_list = implode(
        ",",
        array_map("intval", $ids)
    );

    $sql = "
        SELECT
            id,
            name,
            price,
            image,
            description
        FROM products
        WHERE id IN ($id_list)
        ORDER BY id DESC
    ";

    $result = $conn->query($sql);

    if ($result) {

        while ($row = $result->fetch_assoc()) {

            $id = (int)$row["id"];

            $quantity =
                (int)($_SESSION["cart"][$id] ?? 0);

            $price =
                (float)$row["price"];

            $item_total =
                $price * $quantity;

            $total += $item_total;

            $total_items += $quantity;

            $cart_products[] = [
                "id" => $id,
                "name" => $row["name"],
                "price" => $price,
                "image" => $row["image"],
                "description" => $row["description"],
                "quantity" => $quantity,
                "item_total" => $item_total
            ];
        }
    }
}

$cart_count = $total_items;

?>

<!DOCTYPE html>
<html lang="th">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>ตะกร้าสินค้า | VELOURA PERFUMES</title>

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

<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
>

<style>

* {
    margin:0;
    padding:0;
    box-sizing:border-box;
}

html {
    scroll-behavior:smooth;
}

body {

    font-family:
        "Montserrat",
        "Noto Sans Thai",
        sans-serif;

    color:#493b38;

    min-height:100vh;

    overflow-x:hidden;

    background:
        radial-gradient(
            circle at 10% 15%,
            rgba(255,210,223,.35),
            transparent 28%
        ),
        radial-gradient(
            circle at 90% 10%,
            rgba(255,228,205,.32),
            transparent 26%
        ),
        radial-gradient(
            circle at 50% 90%,
            rgba(241,190,202,.20),
            transparent 30%
        ),
        #f8f1eb;
}


/* =====================================================
   SPARKLE
===================================================== */

.sparkle-bg {

    position:fixed;
    inset:0;
    pointer-events:none;
    z-index:0;
    overflow:hidden;
}

.sparkle-bg span {

    position:absolute;

    color:#fff;

    text-shadow:
        0 0 5px #fff,
        0 0 10px rgba(222,166,178,.9),
        0 0 20px rgba(222,166,178,.55);

    animation:
        sparkleFloat 5s infinite ease-in-out,
        sparkleGlow 2s infinite alternate;
}

@keyframes sparkleFloat {

    0%,100% {
        transform:
            translateY(0)
            scale(.8)
            rotate(0deg);
    }

    50% {
        transform:
            translateY(-20px)
            scale(1.3)
            rotate(20deg);
    }
}

@keyframes sparkleGlow {

    from {
        opacity:.15;
    }

    to {
        opacity:1;
    }
}


/* =====================================================
   TOP BAR
===================================================== */

.top-bar {

    position:relative;
    z-index:50;

    height:38px;

    display:flex;
    align-items:center;
    justify-content:center;

    background:
        linear-gradient(
            90deg,
            #a9786e,
            #d3a0a2,
            #b9847b,
            #d3a0a2,
            #a9786e
        );

    color:white;

    font-family:"Noto Sans Thai",sans-serif;

    font-size:10px;

    letter-spacing:1.5px;
}


/* =====================================================
   NAVBAR
===================================================== */

.navbar {

    position:relative;
    z-index:50;

    width:100%;
    height:88px;

    display:grid;

    grid-template-columns:
        230px
        1fr
        230px;

    align-items:center;

    padding:0 5%;

    background:
        linear-gradient(
            90deg,
            rgba(255,250,247,.94),
            rgba(255,244,242,.84),
            rgba(255,250,247,.94)
        );

    backdrop-filter:blur(22px);

    border-bottom:
        1px solid
        rgba(170,125,116,.18);

    box-shadow:
        0 8px 35px
        rgba(91,65,58,.08);
}

.logo {

    text-decoration:none;
    color:#493b38;
}

.logo-main {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:38px;

    line-height:.9;

    font-weight:500;

    letter-spacing:8px;
}

.logo-sub {

    margin-top:8px;

    margin-left:4px;

    font-size:7px;

    letter-spacing:5px;

    color:#a27770;
}

.nav-menu {

    display:flex;

    justify-content:center;

    gap:30px;
}

.nav-menu a {

    color:#554540;

    text-decoration:none;

    font-family:"Noto Sans Thai",sans-serif;

    font-size:12px;

    transition:.3s;
}

.nav-menu a:hover {

    color:#a36d68;
}

.nav-right {

    display:flex;

    justify-content:flex-end;

    gap:10px;
}

.nav-account,
.nav-cart {

    position:relative;

    height:40px;

    min-width:82px;

    display:flex;

    align-items:center;

    justify-content:center;

    gap:7px;

    padding:0 13px;

    color:#584541;

    text-decoration:none;

    border:
        1px solid
        rgba(170,125,116,.22);

    border-radius:30px;

    background:
        rgba(255,255,255,.48);
}

.nav-account i,
.nav-cart i {

    color:#9d7069;
}

.nav-account span,
.nav-cart span {

    font-family:"Noto Sans Thai",sans-serif;

    font-size:10px;
}

.cart-badge {

    position:absolute;

    top:-7px;
    right:-5px;

    min-width:18px;
    height:18px;

    display:flex;
    align-items:center;
    justify-content:center;

    border-radius:50%;

    background:
        linear-gradient(
            135deg,
            #c58c88,
            #93605d
        );

    color:white;

    font-size:8px;
}


/* =====================================================
   HERO
===================================================== */

.cart-header {

    position:relative;
    z-index:1;

    height:300px;

    display:flex;
    align-items:center;
    justify-content:center;

    text-align:center;

    overflow:hidden;

    background:
        linear-gradient(
            rgba(64,40,36,.35),
            rgba(64,40,36,.55)
        ),
        url("images/perfume-hero.jpg")
        center/cover no-repeat;
}

.header-content {

    color:white;
}

.header-content small {

    font-size:11px;

    letter-spacing:5px;
}

.header-content h1 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:
        clamp(50px,7vw,82px);

    font-weight:400;

    letter-spacing:8px;

    margin:8px 0;
}

.header-content p {

    font-family:"Noto Sans Thai",sans-serif;

    font-size:13px;
}


/* =====================================================
   MAIN
===================================================== */

.cart-section {

    position:relative;
    z-index:2;

    max-width:1250px;

    margin:auto;

    padding:80px 25px 100px;
}

.section-title {

    text-align:center;

    margin-bottom:50px;
}

.section-title .mini {

    color:#b28379;

    font-size:10px;

    letter-spacing:4px;
}

.section-title h2 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:42px;

    font-weight:500;

    margin-top:5px;
}


/* =====================================================
   CART
===================================================== */

.cart-layout {

    display:grid;

    grid-template-columns:
        1fr
        380px;

    gap:45px;

    align-items:start;
}

.cart-list {

    display:flex;

    flex-direction:column;

    gap:18px;
}

.cart-item {

    display:grid;

    grid-template-columns:
        130px
        1fr
        auto;

    gap:25px;

    align-items:center;

    padding:20px;

    background:
        linear-gradient(
            135deg,
            rgba(255,255,255,.84),
            rgba(255,247,243,.72)
        );

    border:
        1px solid
        rgba(194,150,141,.20);

    border-radius:18px;

    box-shadow:
        0 10px 35px
        rgba(110,76,69,.07);
}

.product-image {

    width:130px;
    height:150px;

    border-radius:13px;

    overflow:hidden;

    background:#f4e7e2;
}

.product-image img {

    width:100%;
    height:100%;

    object-fit:cover;
}

.product-info h3 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:28px;

    color:#493b38;

    margin-bottom:7px;
}

.product-info p {

    font-family:"Noto Sans Thai",sans-serif;

    color:#8b7771;

    font-size:12px;

    line-height:1.8;

    margin-bottom:12px;
}

.product-price {

    font-size:13px;

    color:#ad766e;

    letter-spacing:1px;
}

.quantity-area {

    margin-top:16px;
}

.quantity {

    display:flex;

    align-items:center;

    width:max-content;

    border:
        1px solid
        rgba(179,137,129,.35);

    border-radius:30px;

    overflow:hidden;

    background:rgba(255,255,255,.6);
}

.quantity a {

    width:30px;
    height:30px;

    display:flex;

    align-items:center;
    justify-content:center;

    color:#82645e;

    text-decoration:none;
}

.quantity a:hover {

    background:#bd8b83;

    color:white;
}

.quantity span {

    min-width:35px;

    text-align:center;

    font-size:12px;
}

.item-right {

    text-align:right;

    min-width:115px;
}

.item-total {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:27px;

    color:#654d48;

    white-space:nowrap;
}

.remove-btn {

    display:inline-flex;

    align-items:center;

    gap:5px;

    margin-top:14px;

    color:#b29690;

    text-decoration:none;

    font-size:10px;
}


/* =====================================================
   SUMMARY
===================================================== */

.summary {

    position:sticky;

    top:25px;

    padding:32px;

    border-radius:20px;

    background:
        linear-gradient(
            145deg,
            rgba(255,255,255,.90),
            rgba(250,239,234,.82)
        );

    border:
        1px solid
        rgba(193,143,135,.25);

    box-shadow:
        0 15px 45px
        rgba(91,62,56,.09);
}

.summary h3 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:30px;

    font-weight:500;

    margin-bottom:25px;
}

.summary-row {

    display:flex;

    justify-content:space-between;

    padding:12px 0;

    font-size:12px;

    color:#806c66;
}

.summary-row.total {

    margin-top:12px;

    padding-top:20px;

    border-top:
        1px solid
        rgba(184,139,130,.25);
}

.summary-total {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:34px;

    color:#a96f69;
}


/* =====================================================
   BUTTON
===================================================== */

.checkout-btn {

    width:100%;

    margin-top:22px;

    padding:15px;

    display:flex;

    align-items:center;

    justify-content:center;

    gap:7px;

    border:none;

    border-radius:30px;

    background:
        linear-gradient(
            110deg,
            #b17b73,
            #d39b9b,
            #b17b73
        );

    color:white;

    text-decoration:none;

    font-family:inherit;

    font-size:11px;

    letter-spacing:1.5px;

    cursor:pointer;

    transition:.3s;
}

.checkout-btn:hover {

    transform:translateY(-3px);

    box-shadow:
        0 12px 28px
        rgba(169,111,105,.35);
}

.continue-btn {

    display:flex;

    align-items:center;

    justify-content:center;

    gap:8px;

    margin-top:16px;

    padding:13px;

    border:
        1px solid
        rgba(177,123,115,.35);

    border-radius:30px;

    color:#84645e;

    text-decoration:none;

    font-size:11px;
}

.clear-cart {

    display:block;

    text-align:center;

    margin-top:20px;

    color:#aa918b;

    text-decoration:none;

    font-size:10px;
}


/* =====================================================
   EMPTY
===================================================== */

.empty-cart {

    max-width:650px;

    margin:auto;

    padding:70px 30px;

    text-align:center;

    background:
        rgba(255,255,255,.75);

    border-radius:25px;

    box-shadow:
        0 15px 45px
        rgba(100,70,65,.07);
}

.empty-icon {

    width:85px;
    height:85px;

    display:flex;

    align-items:center;
    justify-content:center;

    margin:0 auto 20px;

    border-radius:50%;

    background:#f3dfdc;

    color:#b9857e;

    font-size:28px;
}

.empty-cart h3 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:34px;

    margin-bottom:8px;
}

.empty-cart p {

    font-family:"Noto Sans Thai",sans-serif;

    font-size:13px;

    line-height:1.8;

    color:#8d7771;

    margin-bottom:25px;
}


/* =====================================================
   FOOTER
===================================================== */

footer {

    position:relative;
    z-index:2;

    padding:55px 6% 30px;

    background:#302522;

    color:#eee2dc;

    text-align:center;
}

.footer-logo {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:38px;

    letter-spacing:8px;
}

.footer-text {

    font-size:11px;

    color:#b9a7a1;

    margin:10px 0 25px;
}

.socials {

    display:flex;

    justify-content:center;

    gap:15px;

    margin-bottom:30px;
}

.socials a {

    width:38px;
    height:38px;

    display:flex;

    align-items:center;
    justify-content:center;

    border:
        1px solid
        rgba(255,255,255,.18);

    border-radius:50%;

    color:#dbc8c1;

    text-decoration:none;
}

.copyright {

    font-size:9px;

    color:#85736e;
}


/* =====================================================
   RESPONSIVE
===================================================== */

@media(max-width:900px) {

    .navbar {

        grid-template-columns:
            1fr
            auto;
    }

    .nav-menu {
        display:none;
    }

    .cart-layout {

        grid-template-columns:1fr;
    }

    .summary {

        position:relative;

        top:0;
    }
}

@media(max-width:650px) {

    .navbar {

        height:72px;

        padding:0 18px;
    }

    .logo-main {

        font-size:27px;

        letter-spacing:5px;
    }

    .nav-account,
    .nav-cart {

        width:36px;

        min-width:36px;

        height:36px;

        padding:0;
    }

    .nav-account span,
    .nav-cart span {

        display:none;
    }

    .cart-header {

        height:250px;
    }

    .cart-section {

        padding:55px 15px 70px;
    }

    .cart-item {

        grid-template-columns:
            90px
            1fr;

        gap:15px;

        padding:15px;
    }

    .product-image {

        width:90px;
        height:115px;
    }

    .product-info h3 {

        font-size:23px;
    }

    .product-info p {

        font-size:10px;
    }

    .item-right {

        grid-column:2;

        display:flex;

        justify-content:space-between;

        width:100%;

        text-align:left;
    }
}

</style>

</head>

<body>


<div class="sparkle-bg">

<?php for($i=0;$i<35;$i++): ?>

<span>
    <?= ["✦","✧","⋆"][rand(0,2)] ?>
</span>

<?php endfor; ?>

</div>


<div class="top-bar">

    ✦ จัดส่งฟรี เมื่อสั่งซื้อครบ ฿1,500 ✦

</div>


<nav class="navbar">

<a href="index.php" class="logo">

    <div class="logo-main">
        VELOURA
    </div>

    <div class="logo-sub">
        PERFUMES
    </div>

</a>


<div class="nav-menu">

    <a href="index.php">หน้าแรก</a>

    <a href="products.php">สินค้า</a>

    <a href="about.php">เกี่ยวกับเรา</a>

    <a href="collection.php">คอลเลกชัน</a>

    <a href="find-scent.php">ค้นหากลิ่น</a>

    <a href="contact.php">ติดต่อเรา</a>

</div>


<div class="nav-right">

<a
    href="profile.php"
    class="nav-account"
>

    <i class="fa-regular fa-user"></i>

    <span>บัญชี</span>

</a>


<a
    href="cart.php"
    class="nav-cart"
>

    <i class="fa-solid fa-bag-shopping"></i>

    <span>รถเข็น</span>

    <?php if($cart_count > 0): ?>

        <b class="cart-badge">
            <?= $cart_count ?>
        </b>

    <?php endif; ?>

</a>

</div>

</nav>


<section class="cart-header">

<div class="header-content">

    <small>
        YOUR SELECTION
    </small>

    <h1>
        Shopping Bag
    </h1>

    <p>
        คอลเลกชันกลิ่นหอมที่คุณเลือกไว้
    </p>

</div>

</section>


<main class="cart-section">


<div class="section-title">

    <div class="mini">
        YOUR SELECTION
    </div>

    <h2>
        รายการสินค้าของคุณ
    </h2>

</div>


<?php if(empty($cart_products)): ?>


<div class="empty-cart">

    <div class="empty-icon">

        <i class="fa-solid fa-bag-shopping"></i>

    </div>

    <h3>
        ยังไม่มีสินค้าในรถเข็น
    </h3>

    <p>

        ตอนนี้รถเข็นของคุณยังว่างอยู่ ✦<br>

        ลองเลือกน้ำหอมกลิ่นที่ใช่สำหรับคุณ

    </p>

    <a
        href="products.php"
        class="checkout-btn"
        style="width:auto;display:inline-flex;padding:14px 30px;"
    >

        ดูสินค้าทั้งหมด

        <i class="fa-solid fa-arrow-right"></i>

    </a>

</div>


<?php else: ?>


<div class="cart-layout">


<div class="cart-list">


<?php foreach($cart_products as $item): ?>


<div class="cart-item">


<div class="product-image">

<?php if(!empty($item["image"])): ?>

<img
    src="<?= htmlspecialchars($item["image"]) ?>"
    alt="<?= htmlspecialchars($item["name"]) ?>"
>

<?php else: ?>

<img
    src="images/perfume-placeholder.jpg"
    alt="Perfume"
>

<?php endif; ?>

</div>


<div class="product-info">

<h3>

<?= htmlspecialchars($item["name"]) ?>

</h3>


<?php if(!empty($item["description"])): ?>

<p>

<?= htmlspecialchars($item["description"]) ?>

</p>

<?php endif; ?>


<div class="product-price">

฿<?= number_format($item["price"],2) ?>

</div>


<div class="quantity-area">

<div class="quantity">

<a
    href="cart.php?minus=<?= $item["id"] ?>"
>
    <i class="fa-solid fa-minus"></i>
</a>

<span>
    <?= $item["quantity"] ?>
</span>

<a
    href="cart.php?plus=<?= $item["id"] ?>"
>
    <i class="fa-solid fa-plus"></i>
</a>

</div>

</div>

</div>


<div class="item-right">

<div>

<div class="item-total">

฿<?= number_format($item["item_total"],2) ?>

</div>

<a
    href="cart.php?remove=<?= $item["id"] ?>"
    class="remove-btn"
    onclick="return confirm('ต้องการลบสินค้านี้ออกจากรถเข็นหรือไม่?');"
>

    <i class="fa-regular fa-trash-can"></i>

    ลบสินค้า

</a>

</div>

</div>


</div>


<?php endforeach; ?>


</div>


<aside class="summary">

<h3>
    สรุปคำสั่งซื้อ
</h3>


<div class="summary-row">

<span>
    จำนวนสินค้า
</span>

<span>
    <?= $total_items ?> ชิ้น
</span>

</div>


<div class="summary-row">

<span>
    ราคาสินค้า
</span>

<span>
    ฿<?= number_format($total,2) ?>
</span>

</div>


<div class="summary-row">

<span>
    ค่าจัดส่ง
</span>

<span>
    ฟรี
</span>

</div>


<div class="summary-row total">

<span>
    ยอดรวม
</span>

<span class="summary-total">

    ฿<?= number_format($total,2) ?>

</span>

</div>


<a
    href="checkout.php"
    class="checkout-btn"
>

    ดำเนินการสั่งซื้อ

    <i class="fa-solid fa-arrow-right"></i>

</a>


<a
    href="products.php"
    class="continue-btn"
>

    <i class="fa-solid fa-arrow-left"></i>

    เลือกซื้อต่อ

</a>


<a
    href="cart.php?clear=1"
    class="clear-cart"
    onclick="return confirm('ต้องการล้างสินค้าทั้งหมดออกจากรถเข็นหรือไม่?');"
>

    ล้างรถเข็นทั้งหมด

</a>

</aside>


</div>


<?php endif; ?>


</main>


<footer>

<div class="footer-logo">
    VELOURA
</div>

<div class="footer-text">
    Fragrance that tells your story.
</div>

<div class="socials">

<a href="#">
    <i class="fa-brands fa-facebook-f"></i>
</a>

<a href="#">
    <i class="fa-brands fa-instagram"></i>
</a>

<a href="#">
    <i class="fa-brands fa-tiktok"></i>
</a>

<a href="#">
    <i class="fa-regular fa-envelope"></i>
</a>

</div>

<div class="copyright">

© <?= date("Y") ?>

VELOURA PERFUMES

— ALL RIGHTS RESERVED.

</div>

</footer>


<script>

const sparkles =
    document.querySelectorAll(".sparkle-bg span");

sparkles.forEach(function(el){

    el.style.left =
        Math.random()*100 + "%";

    el.style.top =
        Math.random()*100 + "%";

    el.style.fontSize =
        (Math.random()*8+7)+"px";

    el.style.animationDelay =
        Math.random()*5+"s";

});

</script>

</body>
</html>