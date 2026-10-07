<?php

session_start();

require_once "../connect.php";


/* =====================================================
   CHECK ADMIN LOGIN
===================================================== */

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {

    header("Location: login.php");
    exit;

}


/* =====================================================
   DELETE PRODUCT
===================================================== */

if (isset($_GET["delete"])) {

    $id = intval($_GET["delete"]);

    if ($id > 0) {

        $stmt = $conn->prepare(
            "DELETE FROM products WHERE id = ?"
        );

        if ($stmt) {

            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();

        }

    }

    header("Location: products.php?deleted=1");
    exit;
}


/* =====================================================
   UPDATE PRODUCT
===================================================== */

if ($_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["update_product"])) {

    $id = intval($_POST["id"] ?? 0);

    $name = trim($_POST["name"] ?? "");

    $price = floatval($_POST["price"] ?? 0);

    $description = trim(
        $_POST["description"] ?? ""
    );


    if ($id > 0 && $name !== "" && $price >= 0) {

        $stmt = $conn->prepare("
            UPDATE products
            SET name = ?,
                price = ?,
                description = ?
            WHERE id = ?
        ");

        if ($stmt) {

            $stmt->bind_param(
                "sdsi",
                $name,
                $price,
                $description,
                $id
            );

            $stmt->execute();

            $stmt->close();

        }

    }

    header("Location: products.php?updated=1");
    exit;
}


/* =====================================================
   SEARCH
===================================================== */

$search = trim($_GET["search"] ?? "");


if ($search !== "") {

    $stmt = $conn->prepare("
        SELECT id, name, price, image, description
        FROM products
        WHERE name LIKE ?
        ORDER BY id DESC
    ");

    $keyword = "%" . $search . "%";

    $stmt->bind_param(
        "s",
        $keyword
    );

    $stmt->execute();

    $result = $stmt->get_result();

} else {

    $result = $conn->query("
        SELECT id, name, price, image, description
        FROM products
        ORDER BY id DESC
    ");

}


/* =====================================================
   STATISTICS
===================================================== */

$countResult = $conn->query("
    SELECT COUNT(*) AS total
    FROM products
");

$totalProducts = 0;

if ($countResult) {

    $row = $countResult->fetch_assoc();

    $totalProducts = $row["total"];

}


$priceResult = $conn->query("
    SELECT AVG(price) AS avg_price
    FROM products
");

$averagePrice = 0;

if ($priceResult) {

    $row = $priceResult->fetch_assoc();

    $averagePrice = $row["avg_price"] ?? 0;

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
    VELOURA | Admin Collection
</title>


<link rel="preconnect" href="https://fonts.googleapis.com">

<link
    rel="preconnect"
    href="https://fonts.gstatic.com"
    crossorigin
>

<link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Kanit:wght@300;400;500&family=Montserrat:wght@300;400;500&display=swap"
    rel="stylesheet"
>


<style>

/* =====================================================
   RESET
===================================================== */

* {

    margin: 0;

    padding: 0;

    box-sizing: border-box;

}


body {

    background:
        #faf7f3;

    color:
        #302b28;

    font-family:
        "Kanit",
        sans-serif;

}


/* =====================================================
   HEADER
===================================================== */

.header {

    height: 86px;

    background:
        rgba(255,255,255,.96);

    border-bottom:
        1px solid #e6ddd4;

    display:
        flex;

    align-items:
        center;

    justify-content:
        space-between;

    padding:
        0 55px;

    position:
        sticky;

    top: 0;

    z-index: 100;

}


.logo {

    text-decoration: none;

    color:
        #302b28;

}


.logo-main {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:
        37px;

    letter-spacing:
        5px;

    line-height:
        28px;

}


.logo-sub {

    font-family:
        "Montserrat",
        sans-serif;

    font-size:
        8px;

    letter-spacing:
        4px;

    color:
        #a18554;

    text-align:
        center;

}


.header-right {

    display:
        flex;

    align-items:
        center;

    gap:
        22px;

}


.admin-name {

    font-size:
        13px;

    color:
        #766c64;

}


.shop-link,
.logout {

    text-decoration:
        none;

    font-size:
        12px;

    padding:
        10px 17px;

    transition:
        .3s;

}


.shop-link {

    color:
        #302b28;

    border:
        1px solid #d8cec4;

}


.shop-link:hover {

    background:
        #302b28;

    color:
        white;

}


.logout {

    color:
        #9a645b;

}


/* =====================================================
   CONTAINER
===================================================== */

.container {

    max-width:
        1250px;

    margin:
        auto;

    padding:
        55px 25px 80px;

}


/* =====================================================
   HERO
===================================================== */

.hero {

    text-align:
        center;

    padding:
        20px 0 45px;

}


.hero-small {

    font-family:
        "Montserrat",
        sans-serif;

    font-size:
        10px;

    letter-spacing:
        5px;

    color:
        #a18554;

    margin-bottom:
        10px;

}


.hero h1 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-weight:
        500;

    font-size:
        55px;

    letter-spacing:
        1px;

}


.hero p {

    color:
        #897e75;

    font-size:
        14px;

    margin-top:
        5px;

}


.hero-line {

    display:
        flex;

    justify-content:
        center;

    align-items:
        center;

    gap:
        12px;

    margin-top:
        18px;

    color:
        #a18554;

}


.hero-line span {

    width:
        65px;

    height:
        1px;

    background:
        #d5c4ae;

}


/* =====================================================
   STAT CARDS
===================================================== */

.stats {

    display:
        grid;

    grid-template-columns:
        repeat(3,1fr);

    gap:
        18px;

    margin-bottom:
        50px;

}


.stat {

    background:
        white;

    border:
        1px solid #e8dfd6;

    padding:
        25px;

    display:
        flex;

    align-items:
        center;

    gap:
        18px;

}


.stat-icon {

    width:
        50px;

    height:
        50px;

    border:
        1px solid #dbcbb8;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    font-size:
        20px;

    color:
        #a18554;

}


.stat small {

    display:
        block;

    color:
        #9b9087;

    font-size:
        11px;

}


.stat strong {

    display:
        block;

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:
        30px;

    font-weight:
        600;

}


/* =====================================================
   COLLECTION HEADER
===================================================== */

.collection-head {

    display:
        flex;

    justify-content:
        space-between;

    align-items:
        end;

    margin-bottom:
        20px;

}


.collection-title small {

    font-family:
        "Montserrat",
        sans-serif;

    color:
        #a18554;

    font-size:
        9px;

    letter-spacing:
        3px;

}


.collection-title h2 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:
        35px;

    font-weight:
        500;

}


.add-button {

    text-decoration:
        none;

    background:
        #302b28;

    color:
        white;

    padding:
        13px 24px;

    font-size:
        13px;

    transition:
        .3s;

}


.add-button:hover {

    background:
        #a18554;

}


/* =====================================================
   SEARCH
===================================================== */

.search-box {

    background:
        white;

    border:
        1px solid #e4dbd2;

    padding:
        8px;

    display:
        flex;

    margin-bottom:
        30px;

}


.search-box input {

    flex:
        1;

    height:
        43px;

    border:
        none;

    outline:
        none;

    padding:
        0 15px;

    font-family:
        "Kanit",
        sans-serif;

    background:
        transparent;

}


.search-box button {

    border:
        none;

    background:
        #302b28;

    color:
        white;

    padding:
        0 25px;

    cursor:
        pointer;

    font-family:
        "Kanit",
        sans-serif;

}


/* =====================================================
   PRODUCT GRID
===================================================== */

.product-grid {

    display:
        grid;

    grid-template-columns:
        repeat(3,1fr);

    gap:
        25px;

}


/* =====================================================
   PRODUCT CARD
===================================================== */

.product-card {

    background:
        white;

    border:
        1px solid #e7ddd4;

    transition:
        .35s;

    position:
        relative;

    overflow:
        hidden;

}


.product-card:hover {

    transform:
        translateY(-5px);

    box-shadow:
        0 18px 40px
        rgba(65,48,35,.10);

}


/* IMAGE */

.product-image {

    height:
        330px;

    background:
        #f5f0eb;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    overflow:
        hidden;

}


.product-image img {

    width:
        100%;

    height:
        100%;

    object-fit:
        cover;

    transition:
        .5s;

}


.product-card:hover
.product-image img {

    transform:
        scale(1.04);

}


.no-image {

    color:
        #b6a99d;

    text-align:
        center;

}


.no-image div {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:
        45px;

}


/* PRODUCT INFO */

.product-info {

    padding:
        22px;

}


.product-type {

    font-family:
        "Montserrat",
        sans-serif;

    color:
        #a18554;

    font-size:
        9px;

    letter-spacing:
        2px;

    margin-bottom:
        5px;

}


.product-name {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:
        27px;

    font-weight:
        600;

}


.product-description {

    color:
        #8c8178;

    font-size:
        12px;

    line-height:
        1.7;

    margin-top:
        7px;

    height:
        42px;

    overflow:
        hidden;

}


.product-bottom {

    display:
        flex;

    justify-content:
        space-between;

    align-items:
        center;

    margin-top:
        18px;

    padding-top:
        15px;

    border-top:
        1px solid #eee7e1;

}


.price {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:
        25px;

    color:
        #302b28;

}


.actions {

    display:
        flex;

    gap:
        6px;

}


.action-btn {

    border:
        1px solid #ddd2c8;

    background:
        white;

    width:
        36px;

    height:
        34px;

    cursor:
        pointer;

    transition:
        .25s;

}


.action-btn:hover {

    background:
        #302b28;

    color:
        white;

}


.delete-btn:hover {

    background:
        #a85f56;

    border-color:
        #a85f56;

}


/* =====================================================
   MODAL
===================================================== */

.modal {

    display:
        none;

    position:
        fixed;

    inset:
        0;

    background:
        rgba(40,32,27,.58);

    z-index:
        500;

    align-items:
        center;

    justify-content:
        center;

    padding:
        20px;

}


.modal.active {

    display:
        flex;

}


.modal-box {

    background:
        #fffdfa;

    width:
        100%;

    max-width:
        520px;

    padding:
        35px;

    box-shadow:
        0 25px 80px
        rgba(0,0,0,.2);

}


.modal-head {

    display:
        flex;

    justify-content:
        space-between;

    align-items:
        center;

    margin-bottom:
        25px;

}


.modal-head h2 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:
        32px;

    font-weight:
        500;

}


.close {

    border:
        none;

    background:
        none;

    font-size:
        25px;

    cursor:
        pointer;

    color:
        #897c72;

}


.field {

    margin-bottom:
        17px;

}


.field label {

    display:
        block;

    font-size:
        12px;

    color:
        #6f655e;

    margin-bottom:
        6px;

}


.field input,
.field textarea {

    width:
        100%;

    border:
        1px solid #ddd2c8;

    background:
        white;

    padding:
        12px;

    outline:
        none;

    font-family:
        "Kanit",
        sans-serif;

}


.field textarea {

    height:
        100px;

    resize:
        vertical;

}


.field input:focus,
.field textarea:focus {

    border-color:
        #a18554;

}


.modal-buttons {

    display:
        flex;

    gap:
        10px;

    margin-top:
        25px;

}


.save-btn {

    flex:
        1;

    height:
        46px;

    border:
        none;

    background:
        #302b28;

    color:
        white;

    cursor:
        pointer;

    font-family:
        "Kanit",
        sans-serif;

}


.cancel-btn {

    width:
        120px;

    border:
        1px solid #d9cec3;

    background:
        white;

    cursor:
        pointer;

    font-family:
        "Kanit",
        sans-serif;

}


/* =====================================================
   EMPTY
===================================================== */

.empty {

    grid-column:
        1 / -1;

    background:
        white;

    border:
        1px solid #e5dbd1;

    padding:
        70px 20px;

    text-align:
        center;

}


.empty-icon {

    font-size:
        45px;

    color:
        #b49b78;

}


.empty h3 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:
        30px;

    margin:
        10px 0;

}


.empty p {

    color:
        #968a81;

}


/* =====================================================
   ALERT
===================================================== */

.alert {

    position:
        fixed;

    top:
        105px;

    right:
        25px;

    background:
        #302b28;

    color:
        white;

    padding:
        14px 25px;

    z-index:
        600;

    box-shadow:
        0 10px 30px
        rgba(0,0,0,.15);

    animation:
        slideIn .4s ease;

}


@keyframes slideIn {

    from {

        transform:
            translateX(120%);

    }

    to {

        transform:
            translateX(0);

    }

}


/* =====================================================
   RESPONSIVE
===================================================== */

@media(max-width:900px) {

    .product-grid {

        grid-template-columns:
            repeat(2,1fr);

    }

}


@media(max-width:650px) {

    .header {

        padding:
            0 20px;

    }

    .admin-name {

        display:
            none;

    }

    .stats {

        grid-template-columns:
            1fr;

    }

    .product-grid {

        grid-template-columns:
            1fr;

    }

    .collection-head {

        align-items:
            flex-start;

        flex-direction:
            column;

        gap:
            15px;

    }

    .hero h1 {

        font-size:
            42px;

    }

    .product-image {

        height:
            300px;

    }

}

</style>

</head>


<body>


<!-- =====================================================
     HEADER
===================================================== -->

<header class="header">


    <a
        href="products.php"
        class="logo"
    >

        <div class="logo-main">
            VELOURA
        </div>

        <div class="logo-sub">
            PERFUME BOUTIQUE
        </div>

    </a>


    <div class="header-right">

        <span class="admin-name">

            ✦
            สวัสดี
            <?= htmlspecialchars(
                $_SESSION["admin_username"] ?? "Admin"
            ) ?>

        </span>


        <a
            href="../index.php"
            class="shop-link"
        >
            🛍 หน้าร้าน
        </a>


        <a
            href="logout.php"
            class="logout"
        >
            ออกจากระบบ
        </a>

    </div>

</header>



<main class="container">


<!-- =====================================================
     HERO
===================================================== -->

<section class="hero">

    <div class="hero-small">
        THE ART OF FRAGRANCE
    </div>

    <h1>
        VELOURA Collection
    </h1>

    <p>
        จัดการคอลเลกชันน้ำหอมของคุณอย่างมีสไตล์
    </p>

    <div class="hero-line">

        <span></span>

        ✦

        <span></span>

    </div>

</section>



<!-- =====================================================
     STATISTICS
===================================================== -->

<section class="stats">


    <div class="stat">

        <div class="stat-icon">
            ♡
        </div>

        <div>

            <small>
                FRAGRANCES
            </small>

            <strong>
                <?= number_format($totalProducts) ?>
            </strong>

        </div>

    </div>



    <div class="stat">

        <div class="stat-icon">
            ✦
        </div>

        <div>

            <small>
                COLLECTION
            </small>

            <strong>
                VELOURA
            </strong>

        </div>

    </div>



    <div class="stat">

        <div class="stat-icon">
            ฿
        </div>

        <div>

            <small>
                AVERAGE PRICE
            </small>

            <strong>
                ฿<?= number_format(
                    $averagePrice,
                    0
                ) ?>
            </strong>

        </div>

    </div>


</section>



<!-- =====================================================
     COLLECTION HEADER
===================================================== -->

<div class="collection-head">


    <div class="collection-title">

        <small>
            OUR SIGNATURE
        </small>

        <h2>
            Fragrance Collection
        </h2>

    </div>


    <a
        href="add_product.php"
        class="add-button"
    >

        ＋ เพิ่มน้ำหอม

    </a>


</div>



<!-- =====================================================
     SEARCH
===================================================== -->

<form
    method="GET"
    class="search-box"
>

    <input
        type="text"
        name="search"
        placeholder="ค้นหาชื่อน้ำหอม..."
        value="<?= htmlspecialchars(
            $search
        ) ?>"
    >

    <button type="submit">
        ค้นหา
    </button>

</form>



<!-- =====================================================
     PRODUCTS
===================================================== -->

<div class="product-grid">


<?php if ($result && $result->num_rows > 0): ?>


<?php while ($product = $result->fetch_assoc()): ?>


    <article class="product-card">


        <!-- IMAGE -->

        <div class="product-image">


            <?php

            $image = trim(
                $product["image"] ?? ""
            );


            if ($image !== ""):

                /*
                 * รองรับรูปที่เก็บเป็น
                 * images/xxx.jpg
                 * uploads/xxx.jpg
                 * หรือชื่อไฟล์อย่างเดียว
                 */

                if (
                    str_starts_with(
                        $image,
                        "http://"
                    )
                    ||
                    str_starts_with(
                        $image,
                        "https://"
                    )
                ) {

                    $imageUrl = $image;

                } elseif (
                    str_starts_with(
                        $image,
                        "../"
                    )
                ) {

                    $imageUrl = $image;

                } elseif (
                    str_starts_with(
                        $image,
                        "images/"
                    )
                ) {

                    $imageUrl = "../" . $image;

                } elseif (
                    str_starts_with(
                        $image,
                        "uploads/"
                    )
                ) {

                    $imageUrl = "../" . $image;

                } else {

                    $imageUrl =
                        "../images/" . $image;

                }

            ?>


                <img
                    src="<?= htmlspecialchars(
                        $imageUrl
                    ) ?>"
                    alt="<?= htmlspecialchars(
                        $product["name"]
                    ) ?>"
                    onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'no-image\'><div>✦</div>VELOURA<br>PERFUME</div>';"
                >


            <?php else: ?>


                <div class="no-image">

                    <div>✦</div>

                    VELOURA
                    <br>
                    PERFUME

                </div>


            <?php endif; ?>


        </div>



        <!-- INFO -->

        <div class="product-info">


            <div class="product-type">

                EAU DE PARFUM

            </div>


            <h3 class="product-name">

                <?= htmlspecialchars(
                    $product["name"]
                ) ?>

            </h3>


            <p class="product-description">

                <?= htmlspecialchars(
                    $product["description"] ?? ""
                ) ?>

            </p>


            <div class="product-bottom">


                <div class="price">

                    ฿<?= number_format(
                        $product["price"],
                        2
                    ) ?>

                </div>


                <div class="actions">


                    <!-- EDIT -->

                    <button
                        type="button"
                        class="action-btn"
                        title="แก้ไข"
                        onclick='openEdit(
                            <?= json_encode(
                                $product["id"]
                            ) ?>,
                            <?= json_encode(
                                $product["name"]
                            ) ?>,
                            <?= json_encode(
                                $product["price"]
                            ) ?>,
                            <?= json_encode(
                                $product["description"] ?? ""
                            ) ?>
                        )'
                    >

                        ✎

                    </button>



                    <!-- DELETE -->

                    <button
                        type="button"
                        class="action-btn delete-btn"
                        title="ลบสินค้า"
                        onclick="deleteProduct(
                            <?= (int)$product["id"] ?>
                        )"
                    >

                        ♡

                    </button>


                </div>


            </div>


        </div>


    </article>


<?php endwhile; ?>


<?php else: ?>


    <div class="empty">

        <div class="empty-icon">
            ✦
        </div>

        <h3>
            ยังไม่มีน้ำหอมใน Collection
        </h3>

        <p>
            เพิ่มน้ำหอมชิ้นแรกของ VELOURA ได้เลย
        </p>

        <br>

        <a
            href="add_product.php"
            class="add-button"
        >
            ＋ เพิ่มน้ำหอม
        </a>

    </div>


<?php endif; ?>


</div>


</main>



<!-- =====================================================
     EDIT MODAL
===================================================== -->

<div
    class="modal"
    id="editModal"
>


    <div class="modal-box">


        <div class="modal-head">

            <h2>
                Edit Fragrance
            </h2>

            <button
                class="close"
                onclick="closeEdit()"
            >
                ×
            </button>

        </div>


        <form
            method="POST"
        >


            <input
                type="hidden"
                name="id"
                id="edit_id"
            >


            <input
                type="hidden"
                name="update_product"
                value="1"
            >


            <div class="field">

                <label>
                    ชื่อน้ำหอม
                </label>

                <input
                    type="text"
                    name="name"
                    id="edit_name"
                    required
                >

            </div>


            <div class="field">

                <label>
                    ราคา
                </label>

                <input
                    type="number"
                    name="price"
                    id="edit_price"
                    step="0.01"
                    min="0"
                    required
                >

            </div>


            <div class="field">

                <label>
                    รายละเอียดกลิ่น
                </label>

                <textarea
                    name="description"
                    id="edit_description"
                ></textarea>

            </div>


            <div class="modal-buttons">


                <button
                    type="button"
                    class="cancel-btn"
                    onclick="closeEdit()"
                >
                    ยกเลิก
                </button>


                <button
                    type="submit"
                    class="save-btn"
                >
                    ✦ บันทึกการแก้ไข
                </button>


            </div>


        </form>


    </div>


</div>



<script>

/* =====================================================
   EDIT
===================================================== */

function openEdit(
    id,
    name,
    price,
    description
) {

    document.getElementById(
        "edit_id"
    ).value = id;


    document.getElementById(
        "edit_name"
    ).value = name;


    document.getElementById(
        "edit_price"
    ).value = price;


    document.getElementById(
        "edit_description"
    ).value = description;


    document.getElementById(
        "editModal"
    ).classList.add("active");

}


function closeEdit() {

    document.getElementById(
        "editModal"
    ).classList.remove("active");

}


/* =====================================================
   DELETE
===================================================== */

function deleteProduct(id) {

    const confirmDelete =
        confirm(
            "ต้องการลบน้ำหอมชิ้นนี้ออกจาก Collection หรือไม่?"
        );


    if (confirmDelete) {

        window.location.href =
            "products.php?delete=" + id;

    }

}


/* =====================================================
   CLICK OUTSIDE MODAL
===================================================== */

document
    .getElementById("editModal")
    .addEventListener(
        "click",
        function(e) {

            if (e.target === this) {

                closeEdit();

            }

        }
    );


/* =====================================================
   SUCCESS MESSAGE
===================================================== */

const params =
    new URLSearchParams(
        window.location.search
    );


if (
    params.has("updated")
    ||
    params.has("deleted")
) {

    const alert =
        document.createElement("div");

    alert.className = "alert";

    alert.textContent =
        params.has("updated")
        ? "✦ แก้ไขน้ำหอมเรียบร้อยแล้ว"
        : "✦ ลบน้ำหอมเรียบร้อยแล้ว";


    document.body.appendChild(alert);


    setTimeout(
        () => alert.remove(),
        2500
    );

}

</script>


</body>

</html>