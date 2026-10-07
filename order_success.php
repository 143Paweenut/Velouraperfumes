<?php

session_start();
require_once "connect.php";


/*
=====================================================
FUNCTION
=====================================================
*/

function e($value)
{
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}


/*
=====================================================
ตรวจสอบว่ามีข้อมูลคำสั่งซื้อหรือไม่
=====================================================
*/

if (
    !isset($_SESSION["checkout_customer"]) ||
    !isset($_SESSION["checkout_order_code"]) ||
    !isset($_SESSION["checkout_products"])
) {

    header("Location: index.php");
    exit;
}


/*
=====================================================
ข้อมูลคำสั่งซื้อ
=====================================================
*/

$customer =
    $_SESSION["checkout_customer"];

$order_code =
    $_SESSION["checkout_order_code"];

$cart_products =
    $_SESSION["checkout_products"];


$subtotal =
    (float)(
        $_SESSION["checkout_subtotal"]
        ?? 0
    );

$discount =
    (float)(
        $_SESSION["checkout_discount"]
        ?? 0
    );

$shipping =
    (float)(
        $_SESSION["checkout_shipping"]
        ?? 0
    );

$total =
    (float)(
        $_SESSION["checkout_total"]
        ?? 0
    );

$discount_code =
    $_SESSION["checkout_code"]
    ?? "";


/*
=====================================================
จำนวนสินค้า
=====================================================
*/

$total_items = 0;

foreach ($cart_products as $item) {

    $total_items +=
        (int)(
            $item["quantity"]
            ?? 0
        );
}


/*
=====================================================
วันที่
=====================================================
*/

$order_date =
    date("d/m/Y");

$order_time =
    date("H:i");


/*
=====================================================
สถานะ Email
=====================================================
*/

$email_sent =
    !empty($_SESSION["order_email_sent"]);


/*
=====================================================
หลังจากข้อมูลถูกเก็บไว้แล้ว
ล้าง Session checkout
เพื่อป้องกันการเปิดซ้ำ
=====================================================
*/

$session_customer =
    $customer;

$session_products =
    $cart_products;

$session_order_code =
    $order_code;


/*
=====================================================
ล้างข้อมูลหลังจากเตรียมข้อมูลครบ
=====================================================
*/

unset($_SESSION["checkout_customer"]);

unset($_SESSION["checkout_products"]);

unset($_SESSION["checkout_subtotal"]);

unset($_SESSION["checkout_discount"]);

unset($_SESSION["checkout_shipping"]);

unset($_SESSION["checkout_total"]);

unset($_SESSION["checkout_total_items"]);

unset($_SESSION["checkout_code"]);

unset($_SESSION["checkout_order_code"]);

unset($_SESSION["order_email_sent"]);

unset($_SESSION["coupon_code"]);

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
    Order Confirmed | Veloura Perfumes
</title>


<link rel="preconnect" href="https://fonts.googleapis.com">

<link
    rel="preconnect"
    href="https://fonts.gstatic.com"
    crossorigin
>

<link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&family=Noto+Sans+Thai:wght@300;400;500&display=swap"
    rel="stylesheet"
>


<style>

/* =====================================================
   RESET
===================================================== */

* {
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {

    margin: 0;

    min-height: 100vh;

    font-family:
        'Montserrat',
        'Noto Sans Thai',
        sans-serif;

    color: #302925;

    background:

        radial-gradient(
            circle at 10% 10%,
            rgba(211,180,158,.35),
            transparent 25%
        ),

        radial-gradient(
            circle at 90% 80%,
            rgba(192,157,137,.25),
            transparent 25%
        ),

        linear-gradient(
            135deg,
            #f5eee9,
            #fffaf7,
            #f0e4dc
        );

}


/* =====================================================
   DECORATION
===================================================== */

.decor {

    position: fixed;

    border-radius: 50%;

    pointer-events: none;

    z-index: 0;

}

.decor.one {

    width: 400px;

    height: 400px;

    top: -220px;

    left: -180px;

    background:
        radial-gradient(
            circle,
            rgba(184,143,118,.28),
            transparent 70%
        );

}

.decor.two {

    width: 500px;

    height: 500px;

    right: -280px;

    bottom: -250px;

    background:
        radial-gradient(
            circle,
            rgba(163,122,100,.20),
            transparent 70%
        );

}


/* =====================================================
   SPARKLES
===================================================== */

.sparkle {

    position: fixed;

    color: #b9947a;

    opacity: .55;

    z-index: 1;

    font-size: 18px;

    animation:
        sparkle 3s ease-in-out infinite;

}

.sparkle.s1 {

    top: 15%;

    left: 8%;

}

.sparkle.s2 {

    top: 28%;

    right: 10%;

    animation-delay: 1s;

}

.sparkle.s3 {

    bottom: 20%;

    left: 12%;

    animation-delay: 2s;

}

.sparkle.s4 {

    bottom: 12%;

    right: 15%;

    animation-delay: .5s;

}


@keyframes sparkle {

    0%,
    100% {

        transform:
            scale(.8)
            rotate(0deg);

        opacity: .25;

    }

    50% {

        transform:
            scale(1.3)
            rotate(20deg);

        opacity: .9;

    }

}


/* =====================================================
   MAIN
===================================================== */

.container {

    width: 100%;

    max-width: 1050px;

    margin: auto;

    padding: 60px 20px;

    position: relative;

    z-index: 2;

}


/* =====================================================
   SUCCESS HEADER
===================================================== */

.success-card {

    background:
        linear-gradient(
            135deg,
            #ffffff,
            #fcf8f5
        );

    border:
        1px solid #e5d6cc;

    padding: 55px 30px;

    text-align: center;

    box-shadow:
        0 30px 80px
        rgba(73,51,40,.13);

    position: relative;

    overflow: hidden;

}


.success-card::before {

    content: "";

    position: absolute;

    width: 250px;

    height: 250px;

    border-radius: 50%;

    right: -130px;

    top: -130px;

    background:
        radial-gradient(
            circle,
            rgba(196,159,137,.22),
            transparent 70%
        );

}


.success-icon {

    width: 82px;

    height: 82px;

    margin:
        0 auto 25px;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

    background:
        linear-gradient(
            135deg,
            #302925,
            #604b3f
        );

    color: #fff;

    font-size: 32px;

    box-shadow:
        0 12px 30px
        rgba(48,41,37,.22);

}


.brand {

    font-size: 10px;

    letter-spacing: 6px;

    color: #9b745b;

    margin-bottom: 12px;

}


h1 {

    margin: 0;

    font-family:
        'Cormorant Garamond',
        serif;

    font-size: 58px;

    font-weight: 500;

    color: #302925;

}


.success-text {

    margin:
        18px auto 0;

    max-width: 600px;

    color: #81756e;

    font-size: 13px;

    line-height: 1.9;

}


.order-code {

    display: inline-block;

    margin-top: 24px;

    padding:
        12px 24px;

    background: #f8f1ec;

    border:
        1px solid #dfcfc3;

    color: #80634f;

    font-size: 11px;

    letter-spacing: 2px;

}


/* =====================================================
   EMAIL SUCCESS
===================================================== */

.email-success {

    margin-top: 18px;

    display: inline-block;

    padding:
        9px 18px;

    border-radius: 30px;

    background: #f4eee9;

    color: #876b58;

    font-size: 10px;

}


/* =====================================================
   CARD
===================================================== */

.card {

    background: rgba(255,255,255,.96);

    border:
        1px solid #e7dbd3;

    box-shadow:
        0 18px 50px
        rgba(65,45,36,.07);

    margin-top: 25px;

    padding: 32px;

}


.section-title {

    font-family:
        'Cormorant Garamond',
        serif;

    font-size: 31px;

    font-weight: 600;

    margin-bottom: 22px;

    color: #332a25;

}


.section-subtitle {

    font-size: 9px;

    letter-spacing: 3px;

    color: #a0806c;

    margin-bottom: 5px;

}


/* =====================================================
   CUSTOMER
===================================================== */

.customer-grid {

    display: grid;

    grid-template-columns:
        repeat(2, 1fr);

    gap: 15px;

}


.customer-item {

    background:
        linear-gradient(
            135deg,
            #fcfaf8,
            #f7f0eb
        );

    padding: 16px 18px;

    border:
        1px solid #eee3dc;

}


.customer-item.full {

    grid-column:
        1 / -1;

}


.customer-label {

    display: block;

    font-size: 9px;

    letter-spacing: 1.5px;

    text-transform: uppercase;

    color: #9c8b80;

    margin-bottom: 7px;

}


.customer-value {

    font-size: 12px;

    line-height: 1.8;

    color: #413832;

}


/* =====================================================
   PRODUCTS
===================================================== */

.product {

    display: grid;

    grid-template-columns:
        100px 1fr auto;

    gap: 22px;

    align-items: center;

    padding:
        20px 0;

    border-bottom:
        1px solid #eee6e0;

}


.product:last-child {

    border-bottom: none;

}


.product-image {

    width: 100px;

    height: 100px;

    background:
        linear-gradient(
            135deg,
            #f5eee9,
            #eee2d9
        );

    overflow: hidden;

    border:
        1px solid #eaded6;

}


.product-image img {

    width: 100%;

    height: 100%;

    object-fit: cover;

    display: block;

}


.product-name {

    font-family:
        'Cormorant Garamond',
        serif;

    font-size: 25px;

    font-weight: 600;

    color: #352c27;

}


.product-description {

    font-size: 10px;

    color: #988a82;

    margin:
        5px 0 8px;

    line-height: 1.6;

}


.product-qty {

    font-size: 11px;

    color: #81756e;

}


.product-price {

    text-align: right;

    font-size: 14px;

    font-weight: 500;

    color: #473b34;

}


/* =====================================================
   SUMMARY
===================================================== */

.summary {

    background:
        linear-gradient(
            135deg,
            #302925,
            #4a3930
        );

    color: #fff;

    padding: 32px;

    margin-top: 25px;

    box-shadow:
        0 20px 50px
        rgba(48,41,37,.16);

}


.summary-row {

    display: flex;

    justify-content:
        space-between;

    align-items: center;

    padding: 10px 0;

    font-size: 12px;

    color: #d8cec7;

}


.summary-row.discount {

    color: #e6bfa6;

}


.summary-row.shipping {

    color: #d8cec7;

}


.summary-total {

    display: flex;

    justify-content:
        space-between;

    align-items: center;

    border-top:
        1px solid rgba(255,255,255,.18);

    margin-top: 15px;

    padding-top: 22px;

}


.total-label {

    font-family:
        'Cormorant Garamond',
        serif;

    font-size: 30px;

}


.total-price {

    font-size: 27px;

    color: #fff;

}


/* =====================================================
   BUTTONS
===================================================== */

.buttons {

    display: flex;

    justify-content:
        center;

    gap: 12px;

    flex-wrap: wrap;

    margin-top: 30px;

}


.btn {

    min-width: 190px;

    height: 50px;

    display: flex;

    align-items: center;

    justify-content: center;

    text-decoration: none;

    font-size: 10px;

    letter-spacing: 2px;

    transition: .3s;

}


.btn-primary {

    background:
        linear-gradient(
            135deg,
            #302925,
            #5a4539
        );

    color: #fff;

    box-shadow:
        0 10px 25px
        rgba(48,41,37,.16);

}


.btn-primary:hover {

    transform:
        translateY(-3px);

    box-shadow:
        0 15px 30px
        rgba(48,41,37,.22);

}


.btn-secondary {

    border:
        1px solid #d8c8bd;

    background: #fff;

    color: #685950;

}


.btn-secondary:hover {

    border-color: #9d7b61;

    color: #9d7b61;

    transform:
        translateY(-2px);

}


/* =====================================================
   FOOTER
===================================================== */

.footer {

    text-align: center;

    margin-top: 35px;

    color: #9a8d84;

    font-size: 10px;

    line-height: 1.8;

}


.footer strong {

    color: #80634f;

    letter-spacing: 2px;

}


/* =====================================================
   RESPONSIVE
===================================================== */

@media (max-width: 700px) {

    .container {

        padding:
            30px 15px;

    }

    .success-card {

        padding:
            40px 20px;

    }

    h1 {

        font-size: 43px;

    }

    .card {

        padding: 22px;

    }

    .customer-grid {

        grid-template-columns: 1fr;

    }

    .customer-item.full {

        grid-column: auto;

    }

    .product {

        grid-template-columns:
            75px 1fr;

        gap: 15px;

    }

    .product-image {

        width: 75px;

        height: 75px;

    }

    .product-price {

        grid-column: 2;

        text-align: left;

    }

    .summary {

        padding: 25px 20px;

    }

    .total-label {

        font-size: 25px;

    }

    .total-price {

        font-size: 22px;

    }

}

</style>

</head>


<body>


<div class="decor one"></div>

<div class="decor two"></div>


<div class="sparkle s1">✦</div>

<div class="sparkle s2">✧</div>

<div class="sparkle s3">✦</div>

<div class="sparkle s4">✧</div>


<div class="container">


    <!-- =================================================
         SUCCESS
    ================================================= -->

    <div class="success-card">

        <div class="success-icon">
            ✓
        </div>


        <div class="brand">
            VELOURA PERFUMES
        </div>


        <h1>
            Order Confirmed
        </h1>


        <div class="success-text">

            ขอบคุณสำหรับการสั่งซื้อจาก Veloura Perfumes<br>

            คำสั่งซื้อของคุณได้รับการยืนยันเรียบร้อยแล้ว ✦

        </div>


        <div class="order-code">

            ORDER #
            <?= e($order_code) ?>

        </div>


        <?php if ($email_sent): ?>

            <div class="email-success">

                ✦ ส่งรายละเอียดคำสั่งซื้อไปยัง
                <?= e($customer["email"] ?? "") ?>
                แล้ว

            </div>

        <?php endif; ?>

    </div>



    <!-- =================================================
         CUSTOMER
    ================================================= -->

    <div class="card">

        <div class="section-subtitle">
            SHIPPING INFORMATION
        </div>

        <div class="section-title">
            ข้อมูลการจัดส่ง
        </div>


        <div class="customer-grid">


            <div class="customer-item">

                <span class="customer-label">
                    ชื่อผู้สั่งซื้อ
                </span>

                <div class="customer-value">

                    <?= e(
                        $customer["name"] ?? ""
                    ) ?>

                </div>

            </div>


            <div class="customer-item">

                <span class="customer-label">
                    Email
                </span>

                <div class="customer-value">

                    <?= e(
                        $customer["email"] ?? ""
                    ) ?>

                </div>

            </div>


            <div class="customer-item">

                <span class="customer-label">
                    เบอร์โทรศัพท์
                </span>

                <div class="customer-value">

                    <?= e(
                        $customer["phone"] ?? ""
                    ) ?>

                </div>

            </div>


            <div class="customer-item">

                <span class="customer-label">
                    วันที่สั่งซื้อ
                </span>

                <div class="customer-value">

                    <?= e($order_date) ?>

                    เวลา

                    <?= e($order_time) ?>

                </div>

            </div>


            <div class="customer-item full">

                <span class="customer-label">
                    ที่อยู่จัดส่ง
                </span>

                <div class="customer-value">

                    <?= nl2br(
                        e(
                            $customer["address"] ?? ""
                        )
                    ) ?>

                </div>

            </div>


        </div>

    </div>



    <!-- =================================================
         PRODUCTS
    ================================================= -->

    <div class="card">

        <div class="section-subtitle">
            YOUR SIGNATURE SCENT
        </div>

        <div class="section-title">
            รายการสินค้าที่สั่งซื้อ
        </div>


        <?php if (!empty($cart_products)): ?>


            <?php foreach (
                $cart_products
                as $product
            ): ?>


                <?php

                $image =
                    trim(
                        (string)(
                            $product["image"]
                            ?? ""
                        )
                    );

                if ($image === "") {

                    $image =
                        "images/no-image.jpg";

                }

                ?>


                <div class="product">


                    <!-- IMAGE -->

                    <div class="product-image">

                        <img
                            src="<?= e($image) ?>"
                            alt="<?= e(
                                $product["name"]
                            ) ?>"
                            onerror="
                                this.onerror=null;
                                this.src='images/no-image.jpg';
                            "
                        >

                    </div>


                    <!-- INFO -->

                    <div>

                        <div class="product-name">

                            <?= e(
                                $product["name"]
                            ) ?>

                        </div>


                        <?php if (
                            !empty(
                                $product["description"]
                            )
                        ): ?>

                            <div class="product-description">

                                <?= e(
                                    $product["description"]
                                ) ?>

                            </div>

                        <?php endif; ?>


                        <div class="product-qty">

                            จำนวน
                            <?= (int)(
                                $product["quantity"]
                                ?? 0
                            ) ?>
                            ชิ้น

                            ×

                            ฿<?= number_format(
                                (float)(
                                    $product["price"]
                                    ?? 0
                                ),
                                2
                            ) ?>

                        </div>

                    </div>


                    <!-- TOTAL -->

                    <div class="product-price">

                        ฿<?= number_format(
                            (float)(
                                $product["item_total"]
                                ?? 0
                            ),
                            2
                        ) ?>

                    </div>


                </div>


            <?php endforeach; ?>


        <?php else: ?>


            <div style="
                text-align:center;
                padding:40px 10px;
                color:#958981;
                font-size:13px;
            ">

                ไม่พบรายการสินค้า

            </div>


        <?php endif; ?>


    </div>



    <!-- =================================================
         SUMMARY
    ================================================= -->

    <div class="summary">


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
                ยอดสินค้า
            </span>

            <span>

                ฿<?= number_format(
                    $subtotal,
                    2
                ) ?>

            </span>

        </div>


        <?php if ($discount > 0): ?>

            <div class="summary-row discount">

                <span>

                    ส่วนลด

                    <?php if (
                        $discount_code !== ""
                    ): ?>

                        (
                        <?= e(
                            $discount_code
                        ) ?>
                        )

                    <?php endif; ?>

                </span>


                <span>

                    - ฿<?= number_format(
                        $discount,
                        2
                    ) ?>

                </span>

            </div>

        <?php endif; ?>


        <div class="summary-row shipping">

            <span>
                ค่าจัดส่ง
            </span>

            <span>

                <?php if ($shipping <= 0): ?>

                    ฟรี

                <?php else: ?>

                    ฿<?= number_format(
                        $shipping,
                        2
                    ) ?>

                <?php endif; ?>

            </span>

        </div>


        <div class="summary-total">

            <div class="total-label">
                Total
            </div>

            <div class="total-price">

                ฿<?= number_format(
                    $total,
                    2
                ) ?>

            </div>

        </div>


    </div>



    <!-- =================================================
         BUTTONS
    ================================================= -->

    <div class="buttons">


        <a
            href="products.php"
            class="btn btn-primary"
        >
            SHOP MORE
        </a>


        <a
            href="index.php"
            class="btn btn-secondary"
        >
            BACK TO HOME
        </a>


    </div>



    <!-- =================================================
         FOOTER
    ================================================= -->

    <div class="footer">

        <strong>
            VELOURA PERFUMES
        </strong>

        <br>

        Thank you for choosing your signature scent with us. ✦

    </div>


</div>


</body>

</html>