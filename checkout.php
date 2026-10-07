<?php

session_start();
require_once "connect.php";

/* =====================================================
   PHPMailer
===================================================== */

require_once __DIR__ . "/PHPMailer/src/Exception.php";
require_once __DIR__ . "/PHPMailer/src/PHPMailer.php";
require_once __DIR__ . "/PHPMailer/src/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


/* =====================================================
   FUNCTION
===================================================== */

function e($value)
{
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}


/* =====================================================
   LOGIN
===================================================== */

if (
    !isset($_SESSION["logged_in"]) ||
    $_SESSION["logged_in"] !== true
) {

    header("Location: login.php?redirect=checkout.php");
    exit;
}


/* =====================================================
   CUSTOMER SESSION
===================================================== */

$user_id = (int)(
    $_SESSION["user_id"] ?? 0
);

$username = trim(
    $_SESSION["username"] ?? ""
);

$email = trim(
    $_SESSION["email"] ?? ""
);

$fullname = trim(
    $_SESSION["fullname"] ?? $username
);

$address = trim(
    $_SESSION["address"] ?? ""
);

$phone = trim(
    $_SESSION["phone"] ?? ""
);


if (
    $user_id <= 0 ||
    $email === ""
) {

    session_destroy();

    header(
        "Location: login.php?redirect=checkout.php"
    );

    exit;
}


/* =====================================================
   CART
===================================================== */

if (
    !isset($_SESSION["cart"]) ||
    !is_array($_SESSION["cart"]) ||
    empty($_SESSION["cart"])
) {

    header("Location: cart.php");
    exit;
}


/* =====================================================
   GET PRODUCTS FROM CART
===================================================== */

$cart_products = [];

$subtotal = 0;

$total_items = 0;

$ids = array_keys(
    $_SESSION["cart"]
);

$ids = array_filter(
    array_map("intval", $ids),
    function ($id) {
        return $id > 0;
    }
);


if (empty($ids)) {

    header("Location: cart.php");
    exit;
}


$id_list = implode(",", $ids);


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


if (!$result) {

    die(
        "เกิดข้อผิดพลาดในการดึงข้อมูลสินค้า: "
        . e($conn->error)
    );
}


while ($row = $result->fetch_assoc()) {

    $id = (int)$row["id"];

    $quantity = (int)(
        $_SESSION["cart"][$id] ?? 0
    );

    if ($quantity <= 0) {
        continue;
    }


    $price = (float)$row["price"];

    $item_total =
        $price * $quantity;


    $subtotal += $item_total;

    $total_items += $quantity;


    $cart_products[] = [

        "id" =>
            $id,

        "name" =>
            $row["name"],

        "price" =>
            $price,

        "image" =>
            $row["image"],

        "description" =>
            $row["description"],

        "quantity" =>
            $quantity,

        "item_total" =>
            $item_total

    ];
}


if (empty($cart_products)) {

    header("Location: cart.php");
    exit;
}


/* =====================================================
   COUPONS
===================================================== */

$coupons = [

    "VELOURA10" => [
        "type" => "percent",
        "value" => 10,
        "min" => 500
    ],

    "VELOURA20" => [
        "type" => "percent",
        "value" => 20,
        "min" => 1000
    ],

    "WELCOME500" => [
        "type" => "fixed",
        "value" => 500,
        "min" => 1500
    ]

];


$discount = 0;

$discount_code = "";

$discount_message = "";

$discount_success = false;


/* =====================================================
   OLD COUPON
===================================================== */

if (
    isset($_SESSION["coupon_code"]) &&
    isset(
        $coupons[
            $_SESSION["coupon_code"]
        ]
    )
) {

    $old_code =
        $_SESSION["coupon_code"];

    $coupon =
        $coupons[$old_code];


    if (
        $subtotal >=
        $coupon["min"]
    ) {

        $discount_code =
            $old_code;


        if (
            $coupon["type"] ===
            "percent"
        ) {

            $discount =
                $subtotal *
                (
                    $coupon["value"]
                    / 100
                );

        } else {

            $discount =
                $coupon["value"];
        }


        if (
            $discount >
            $subtotal
        ) {

            $discount =
                $subtotal;
        }


        $discount_success =
            true;
    }
}


/* =====================================================
   APPLY COUPON
===================================================== */

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["apply_coupon"])
) {

    $discount_code =
        strtoupper(
            trim(
                $_POST["discount_code"] ?? ""
            )
        );


    if (
        $discount_code === ""
    ) {

        $discount = 0;

        $discount_success =
            false;

        $discount_message =
            "กรุณากรอกโค้ดส่วนลด";

        unset(
            $_SESSION["coupon_code"]
        );

    }

    elseif (
        !isset(
            $coupons[$discount_code]
        )
    ) {

        $discount = 0;

        $discount_success =
            false;

        $discount_message =
            "ไม่พบโค้ดส่วนลดนี้";

        unset(
            $_SESSION["coupon_code"]
        );

    }

    else {

        $coupon =
            $coupons[$discount_code];


        if (
            $subtotal <
            $coupon["min"]
        ) {

            $discount = 0;

            $discount_success =
                false;

            $discount_message =
                "ยอดซื้อขั้นต่ำสำหรับโค้ดนี้คือ ฿"
                . number_format(
                    $coupon["min"],
                    2
                );

            unset(
                $_SESSION["coupon_code"]
            );

        }

        else {

            if (
                $coupon["type"] ===
                "percent"
            ) {

                $discount =
                    $subtotal *
                    (
                        $coupon["value"]
                        / 100
                    );

            } else {

                $discount =
                    $coupon["value"];
            }


            if (
                $discount >
                $subtotal
            ) {

                $discount =
                    $subtotal;
            }


            $discount_success =
                true;


            $discount_message =
                "ใช้โค้ด "
                . e($discount_code)
                . " สำเร็จ ✦";


            $_SESSION["coupon_code"] =
                $discount_code;
        }
    }
}


/* =====================================================
   SHIPPING
===================================================== */

$shipping = 0;


/* =====================================================
   TOTAL
===================================================== */

$total =
    $subtotal
    - $discount
    + $shipping;


if ($total < 0) {
    $total = 0;
}


/* =====================================================
   ORDER ERROR
===================================================== */

$order_error = "";


/* =====================================================
   PLACE ORDER
===================================================== */

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["place_order"])
) {

    $customer_name =
        trim(
            $_POST["customer_name"] ?? ""
        );

    $customer_email =
        trim(
            $_POST["customer_email"] ?? ""
        );

    $customer_phone =
        trim(
            $_POST["customer_phone"] ?? ""
        );

    $customer_address =
        trim(
            $_POST["customer_address"] ?? ""
        );


    /* =================================================
       VALIDATE
    ================================================= */

    if (
        $customer_name === ""
    ) {

        $order_error =
            "กรุณากรอกชื่อผู้สั่งซื้อ";

    }

    elseif (
        $customer_email === "" ||
        !filter_var(
            $customer_email,
            FILTER_VALIDATE_EMAIL
        )
    ) {

        $order_error =
            "กรุณากรอกอีเมลให้ถูกต้อง";

    }

    elseif (
        $customer_phone === ""
    ) {

        $order_error =
            "กรุณากรอกเบอร์โทรศัพท์";

    }

    elseif (
        $customer_address === ""
    ) {

        $order_error =
            "กรุณากรอกที่อยู่จัดส่ง";

    }

    else {


        /* =================================================
           ORDER CODE
        ================================================= */

        $order_code =
            "VL"
            . date("YmdHis")
            . rand(10, 99);


        /* =================================================
           SAVE ORDER INFORMATION TO SESSION
           
           จุดสำคัญ:
           เก็บ cart_products ไว้ด้วย
           เพื่อให้ order_success.php
           รู้ว่าสั่งสินค้าอะไร
        ================================================= */

        $_SESSION["checkout_customer"] = [

            "name" =>
                $customer_name,

            "email" =>
                $customer_email,

            "phone" =>
                $customer_phone,

            "address" =>
                $customer_address

        ];


        $_SESSION["checkout_total"] =
            $total;


        $_SESSION["checkout_subtotal"] =
            $subtotal;


        $_SESSION["checkout_discount"] =
            $discount;


        $_SESSION["checkout_code"] =
            $discount_code;


        $_SESSION["checkout_order_code"] =
            $order_code;


        /* =================================================
           สำคัญมาก
           เก็บรายการสินค้าที่ซื้อ
        ================================================= */

        $_SESSION["checkout_products"] =
            $cart_products;


        $_SESSION["checkout_total_items"] =
            $total_items;


        /* =================================================
           CREATE EMAIL PRODUCTS
        ================================================= */

        $email_products = "";


        foreach (
            $cart_products
            as $item
        ) {

            $email_products .= "

                <tr>

                    <td style='padding:14px 10px;border-bottom:1px solid #eee;'>

                        "
                        . e(
                            $item["name"]
                        )
                        . "

                    </td>


                    <td style='padding:14px 10px;border-bottom:1px solid #eee;text-align:center;'>

                        "
                        . $item["quantity"]
                        . "

                    </td>


                    <td style='padding:14px 10px;border-bottom:1px solid #eee;text-align:right;'>

                        ฿"
                        . number_format(
                            $item["price"],
                            2
                        )
                        . "

                    </td>


                    <td style='padding:14px 10px;border-bottom:1px solid #eee;text-align:right;'>

                        ฿"
                        . number_format(
                            $item["item_total"],
                            2
                        )
                        . "

                    </td>

                </tr>

            ";
        }


        /* =================================================
           DISCOUNT EMAIL
        ================================================= */

        $discount_email = "";


        if ($discount > 0) {

            $discount_email = "

                <tr>

                    <td
                        colspan='3'
                        style='padding:10px;text-align:right;'
                    >

                        ส่วนลด

                    </td>


                    <td
                        style='padding:10px;text-align:right;color:#9b6b52;'
                    >

                        - ฿"
                        . number_format(
                            $discount,
                            2
                        )
                        . "

                    </td>

                </tr>

            ";
        }


        /* =================================================
           EMAIL BODY
        ================================================= */

        $email_body = "

<!DOCTYPE html>

<html lang='th'>

<head>

<meta charset='UTF-8'>

</head>


<body
style='
margin:0;
padding:0;
background:#f7f3ef;
font-family:Arial,sans-serif;
'
>


<div
style='
max-width:700px;
margin:30px auto;
background:#ffffff;
border:1px solid #eadfd7;
'
>


<div
style='
background:#302925;
padding:35px;
text-align:center;
'
>

<div
style='
font-family:Georgia,serif;
font-size:32px;
letter-spacing:7px;
color:#ffffff;
'
>

VELOURA

</div>


<div
style='
font-size:10px;
letter-spacing:4px;
color:#d5c4b7;
margin-top:8px;
'
>

PERFUMES

</div>

</div>


<div style='padding:35px;'>


<h2
style='
font-family:Georgia,serif;
font-weight:normal;
color:#302925;
'
>

ขอบคุณสำหรับคำสั่งซื้อ ✦

</h2>


<p
style='
color:#665c56;
font-size:14px;
line-height:1.8;
'
>

สวัสดีคุณ

<strong>
"
. e($customer_name)
. "
</strong>

<br>

เราได้รับคำสั่งซื้อของคุณเรียบร้อยแล้ว

</p>


<div
style='
background:#faf7f4;
padding:18px;
margin:25px 0;
'
>

<p>

<strong>
เลขที่คำสั่งซื้อ:
</strong>

"
. e($order_code)
. "

</p>


<p>

<strong>
วันที่:
</strong>

"
. date("d/m/Y H:i")
. "

</p>

</div>


<h3>

รายการสินค้า

</h3>


<table
width='100%'
cellpadding='0'
cellspacing='0'
style='border-collapse:collapse;font-size:13px;'
>


<thead>

<tr style='background:#f5efea;'>

<th style='padding:12px;text-align:left;'>
สินค้า
</th>

<th style='padding:12px;text-align:center;'>
จำนวน
</th>

<th style='padding:12px;text-align:right;'>
ราคา
</th>

<th style='padding:12px;text-align:right;'>
รวม
</th>

</tr>

</thead>


<tbody>

"
. $email_products
. "

</tbody>


<tfoot>


<tr>

<td
colspan='3'
style='padding:12px;text-align:right;'
>

ยอดสินค้า

</td>


<td
style='padding:12px;text-align:right;'
>

฿"
. number_format(
    $subtotal,
    2
)
. "

</td>

</tr>


"
. $discount_email
. "


<tr>

<td
colspan='3'
style='padding:15px;text-align:right;font-weight:bold;'
>

ยอดสุทธิ

</td>


<td
style='padding:15px;text-align:right;font-weight:bold;font-size:18px;color:#9b6b52;'
>

฿"
. number_format(
    $total,
    2
)
. "

</td>

</tr>


</tfoot>

</table>


<div
style='
margin-top:30px;
padding-top:20px;
border-top:1px solid #eee;
'
>

<h3>
ข้อมูลจัดส่ง
</h3>


<p
style='
font-size:13px;
line-height:1.8;
color:#665c56;
'
>

<strong>ชื่อ:</strong>

"
. e($customer_name)
. "

<br>


<strong>อีเมล:</strong>

"
. e($customer_email)
. "

<br>


<strong>โทรศัพท์:</strong>

"
. e($customer_phone)
. "

<br>


<strong>ที่อยู่:</strong>

"
. nl2br(
    e($customer_address)
)
. "

</p>

</div>


<div
style='
margin-top:30px;
padding:20px;
background:#302925;
color:#ffffff;
text-align:center;
'
>

<div
style='
font-family:Georgia,serif;
font-size:18px;
'
>

Thank you for choosing Veloura.

</div>


<div
style='
font-size:11px;
color:#d8c8bd;
margin-top:8px;
'
>

Discover your signature scent.

</div>

</div>


</div>

</div>


</body>

</html>
";


        /* =================================================
           SEND EMAIL
        ================================================= */

        $email_sent = false;

        $mail_error = "";


        try {

            $mail =
                new PHPMailer(true);


            $mail->isSMTP();

            $mail->Host =
                "smtp.gmail.com";

            $mail->SMTPAuth =
                true;


            /*
            ================================================
            Gmail ร้าน
            ================================================
            */

            $mail->Username =
                "paweenutnamdaeng009@gmail.com";


            /*
            ================================================
            Gmail App Password
            ================================================
            */

            $mail->Password =
                "ddzs fejn nvna vboe";


            $mail->SMTPSecure =
                PHPMailer::ENCRYPTION_STARTTLS;

            $mail->Port =
                587;

            $mail->CharSet =
                "UTF-8";


            /*
            ================================================
            FROM
            ================================================
            */

            $mail->setFrom(
                "paweenutnamdaeng009@gmail.com",
                "Veloura Perfumes"
            );


            /*
            ================================================
            TO CUSTOMER
            ================================================
            */

            $mail->addAddress(
                $customer_email,
                $customer_name
            );


            $mail->isHTML(true);


            $mail->Subject =
                "Veloura Perfumes | ยืนยันคำสั่งซื้อ #"
                . $order_code;


            $mail->Body =
                $email_body;


            $mail->AltBody =
                "ขอบคุณสำหรับคำสั่งซื้อจาก Veloura Perfumes "
                . $order_code;


            $mail->send();


            $email_sent = true;


        } catch (Exception $ex) {

            $email_sent = false;

            $mail_error =
                $mail->ErrorInfo;
        }


        /* =================================================
           ถึง Email จะส่งไม่ได้
           ก็ยังให้ไปหน้า Success ได้
        ================================================= */

        $_SESSION["order_email_sent"] =
            $email_sent;


        $_SESSION["order_email_error"] =
            $mail_error;


        /*
        =================================================
        ล้าง Coupon
        =================================================
        */

        unset(
            $_SESSION["coupon_code"]
        );


        /*
        =================================================
        ล้าง CART หลังจาก
        เก็บ checkout_products แล้ว
        =================================================
        */

        unset(
            $_SESSION["cart"]
        );


        /*
        =================================================
        สำคัญที่สุด
        REDIRECT
        =================================================
        */

        header(
            "Location: order_success.php"
        );

        exit;
    }
}


/* =====================================================
   COUPON MESSAGE
===================================================== */

if (
    $discount_success &&
    $discount_code !== ""
) {

    $discount_message =
        "ใช้โค้ด "
        . e($discount_code)
        . " สำเร็จ ✦";
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
Checkout | Veloura Perfumes
</title>


<link rel="preconnect"
      href="https://fonts.googleapis.com">

<link rel="preconnect"
      href="https://fonts.gstatic.com"
      crossorigin>


<link
href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Montserrat:wght@300;400;500;600&family=Noto+Sans+Thai:wght@300;400;500&display=swap"
rel="stylesheet"
>


<style>

* {
    box-sizing:border-box;
}


body {

    margin:0;

    background:
        radial-gradient(
            circle at top left,
            #eee4dc,
            transparent 35%
        ),
        #f8f5f1;

    color:#302925;

    font-family:
        'Montserrat',
        'Noto Sans Thai',
        sans-serif;
}


.container {

    max-width:1150px;

    margin:auto;

    padding:50px 20px;
}


.header {

    text-align:center;

    margin-bottom:40px;
}


.header-small {

    font-size:10px;

    letter-spacing:4px;

    color:#9d7b61;

    margin-bottom:10px;
}


.header h1 {

    margin:0;

    font-family:
        'Cormorant Garamond',
        serif;

    font-size:55px;

    font-weight:400;
}


.header p {

    color:#91857d;

    font-size:12px;
}


.checkout-grid {

    display:grid;

    grid-template-columns:
        1fr 400px;

    gap:30px;

    align-items:start;
}


.box {

    background:#fff;

    border:
        1px solid #e7ddd5;

    padding:30px;

    box-shadow:
        0 20px 50px
        rgba(48,41,37,.06);

    margin-bottom:25px;
}


.box-title {

    font-family:
        'Cormorant Garamond',
        serif;

    font-size:28px;

    margin-bottom:25px;
}


.form-group {

    margin-bottom:20px;
}


label {

    display:block;

    font-size:10px;

    letter-spacing:1px;

    color:#655b55;

    margin-bottom:8px;
}


input,
textarea {

    width:100%;

    border:
        1px solid #ddd2ca;

    background:#fdfbf9;

    padding:13px;

    font-family:
        'Montserrat',
        'Noto Sans Thai',
        sans-serif;

    font-size:12px;

    color:#302925;

    outline:none;
}


textarea {

    min-height:110px;

    resize:vertical;
}


input:focus,
textarea:focus {

    border-color:#9d7b61;

    background:#fff;
}


.coupon {

    display:flex;

    gap:10px;
}


.coupon input {

    flex:1;
}


.coupon button {

    width:110px;

    border:none;

    background:#302925;

    color:#fff;

    cursor:pointer;

    font-size:10px;

    letter-spacing:1px;
}


.coupon button:hover {

    background:#9d7b61;
}


.coupon-message {

    margin-top:10px;

    font-size:11px;

    color:#9b6b52;
}


.product {

    display:flex;

    gap:15px;

    padding:15px 0;

    border-bottom:
        1px solid #eee6e0;
}


.product img {

    width:75px;

    height:75px;

    object-fit:cover;

    background:#f5f0ec;
}


.product-info {

    flex:1;
}


.product-name {

    font-family:
        'Cormorant Garamond',
        serif;

    font-size:20px;
}


.product-qty {

    font-size:11px;

    color:#92867f;

    margin-top:5px;
}


.product-price {

    font-size:12px;

    margin-top:7px;
}


.summary {

    margin-top:25px;
}


.summary-row {

    display:flex;

    justify-content:space-between;

    padding:9px 0;

    font-size:12px;

    color:#6e635d;
}


.summary-row.discount {

    color:#9b6b52;
}


.summary-total {

    display:flex;

    justify-content:space-between;

    border-top:
        1px solid #ddd2ca;

    padding-top:18px;

    margin-top:10px;
}


.summary-total span:first-child {

    font-size:13px;
}


.summary-total span:last-child {

    font-family:
        'Cormorant Garamond',
        serif;

    font-size:28px;

    color:#9b6b52;
}


.order-button {

    width:100%;

    height:55px;

    margin-top:25px;

    border:none;

    background:#302925;

    color:#fff;

    font-family:
        'Montserrat',
        'Noto Sans Thai',
        sans-serif;

    font-size:10px;

    letter-spacing:2px;

    cursor:pointer;

    transition:.3s;
}


.order-button:hover {

    background:#9d7b61;

    transform:translateY(-1px);
}


.error {

    background:#fff4f2;

    border:
        1px solid #ead4cf;

    color:#8a5c5c;

    padding:14px;

    margin-bottom:20px;

    font-size:11px;

    line-height:1.7;
}


.back {

    text-align:center;

    margin-top:25px;
}


.back a {

    color:#8b8079;

    text-decoration:none;

    font-size:11px;
}


@media(max-width:850px) {

    .checkout-grid {

        grid-template-columns:1fr;
    }

}

</style>

</head>


<body>


<div class="container">


<div class="header">

<div class="header-small">
VELOURA PERFUMES
</div>


<h1>
Checkout
</h1>


<p>
Complete your order and discover your signature scent.
</p>

</div>


<?php if ($order_error !== ""): ?>

<div class="error">

<?= $order_error ?>

</div>

<?php endif; ?>


<div class="checkout-grid">


<!-- =================================================
     CUSTOMER
================================================= -->

<div>

<div class="box">

<div class="box-title">
Shipping Information
</div>


<form
    method="POST"
    action="checkout.php"
>


<div class="form-group">

<label>
ชื่อผู้สั่งซื้อ
</label>

<input
    type="text"
    name="customer_name"
    value="<?= e($fullname) ?>"
    required
>

</div>


<div class="form-group">

<label>
Email
</label>

<input
    type="email"
    name="customer_email"
    value="<?= e($email) ?>"
    required
>

</div>


<div class="form-group">

<label>
เบอร์โทรศัพท์
</label>

<input
    type="text"
    name="customer_phone"
    value="<?= e($phone) ?>"
    required
>

</div>


<div class="form-group">

<label>
ที่อยู่จัดส่ง
</label>

<textarea
    name="customer_address"
    required
><?= e($address) ?></textarea>

</div>


<div class="form-group">

<label>
Discount Code
</label>


<div class="coupon">

<input
    type="text"
    name="discount_code"
    placeholder="เช่น VELOURA10"
    value="<?= e($discount_code) ?>"
>


<button
    type="submit"
    name="apply_coupon"
>

APPLY

</button>

</div>


<?php if ($discount_message !== ""): ?>

<div class="coupon-message">

<?= $discount_message ?>

</div>

<?php endif; ?>

</div>


<button
    type="submit"
    name="place_order"
    class="order-button"
>

✦ CONFIRM & PLACE ORDER ✦

</button>


</form>


<div class="back">

<a href="cart.php">

← กลับไปแก้ไขตะกร้า

</a>

</div>

</div>

</div>


<!-- =================================================
     ORDER SUMMARY
================================================= -->

<div class="box">

<div class="box-title">
Your Order
</div>


<?php foreach (
    $cart_products
    as $item
): ?>


<div class="product">


<?php

$image =
    trim(
        (string)$item["image"]
    );

if ($image === "") {

    $image =
        "images/no-image.jpg";
}

?>


<img
    src="<?= e($image) ?>"
    alt="<?= e($item["name"]) ?>"
    onerror="this.src='images/no-image.jpg';"
>


<div class="product-info">

<div class="product-name">

<?= e($item["name"]) ?>

</div>


<div class="product-qty">

จำนวน
<?= $item["quantity"] ?>
ชิ้น

</div>


<div class="product-price">

฿<?= number_format(
    $item["item_total"],
    2
) ?>

</div>

</div>


</div>


<?php endforeach; ?>


<div class="summary">


<div class="summary-row">

<span>
สินค้าทั้งหมด
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

<?php if ($discount_code): ?>

(<?= e($discount_code) ?>)

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


<div class="summary-row">

<span>
ค่าจัดส่ง
</span>

<span>
ฟรี
</span>

</div>


<div class="summary-total">

<span>
TOTAL
</span>


<span>

฿<?= number_format(
    $total,
    2
) ?>

</span>

</div>


</div>

</div>


</div>


</div>


</body>

</html>