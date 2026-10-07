<?php

session_start();

require_once "../connect.php";


/* =====================================================
   CHECK LOGIN
===================================================== */

if (
    !isset($_SESSION["admin_logged_in"])
    ||
    $_SESSION["admin_logged_in"] !== true
) {

    header("Location: login.php");
    exit;

}


$error = "";
$success = "";


/* =====================================================
   ADD PRODUCT
===================================================== */

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    $name = trim(
        $_POST["name"] ?? ""
    );


    $price = floatval(
        $_POST["price"] ?? 0
    );


    $description = trim(
        $_POST["description"] ?? ""
    );


    /* =================================================
       IMAGE
    ================================================= */

    $imageName = "";


    if (
        isset($_FILES["image"])
        &&
        $_FILES["image"]["error"] === UPLOAD_ERR_OK
    ) {


        $file = $_FILES["image"];


        $allowed = [
            "jpg",
            "jpeg",
            "png",
            "webp"
        ];


        $extension =
            strtolower(
                pathinfo(
                    $file["name"],
                    PATHINFO_EXTENSION
                )
            );


        if (
            !in_array(
                $extension,
                $allowed,
                true
            )
        ) {

            $error =
                "รองรับเฉพาะ JPG, JPEG, PNG และ WEBP";

        } elseif (
            $file["size"] > 5 * 1024 * 1024
        ) {

            $error =
                "รูปภาพต้องมีขนาดไม่เกิน 5MB";

        } else {


            $uploadDir =
                __DIR__ . "/uploads/";


            if (
                !is_dir($uploadDir)
            ) {

                mkdir(
                    $uploadDir,
                    0777,
                    true
                );

            }


            $imageName =
                uniqid(
                    "veloura_",
                    true
                )
                . "."
                . $extension;


            $target =
                $uploadDir
                . $imageName;


            if (
                !move_uploaded_file(
                    $file["tmp_name"],
                    $target
                )
            ) {

                $error =
                    "ไม่สามารถอัปโหลดรูปภาพได้";

            }

        }

    }


    /* =================================================
       INSERT
    ================================================= */

    if (
        $error === ""
        &&
        $name !== ""
        &&
        $price >= 0
    ) {


        $stmt = $conn->prepare("
            INSERT INTO products
            (
                name,
                price,
                image,
                description
            )
            VALUES (?, ?, ?, ?)
        ");


        if (!$stmt) {

            $error =
                "เกิดข้อผิดพลาด: "
                . $conn->error;

        } else {


            $stmt->bind_param(
                "sdss",
                $name,
                $price,
                $imageName,
                $description
            );


            if ($stmt->execute()) {

                header(
                    "Location: products.php?added=1"
                );

                exit;

            } else {

                $error =
                    "ไม่สามารถเพิ่มสินค้าได้";

            }


            $stmt->close();

        }

    } elseif ($error === "") {

        $error =
            "กรุณากรอกชื่อน้ำหอมและราคาให้ถูกต้อง";

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
    Add Fragrance | VELOURA
</title>


<link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Kanit:wght@300;400;500&family=Montserrat:wght@300;400;500&display=swap"
    rel="stylesheet"
>


<style>

* {

    margin:
        0;

    padding:
        0;

    box-sizing:
        border-box;

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


.header {

    height:
        86px;

    background:
        white;

    border-bottom:
        1px solid #e6ddd4;

    display:
        flex;

    justify-content:
        space-between;

    align-items:
        center;

    padding:
        0 55px;

}


.logo {

    text-decoration:
        none;

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


.back {

    text-decoration:
        none;

    color:
        #756b63;

    font-size:
        13px;

}


.container {

    max-width:
        1050px;

    margin:
        auto;

    padding:
        60px 25px;

}


.heading {

    text-align:
        center;

    margin-bottom:
        45px;

}


.heading small {

    font-family:
        "Montserrat",
        sans-serif;

    font-size:
        10px;

    letter-spacing:
        5px;

    color:
        #a18554;

}


.heading h1 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:
        52px;

    font-weight:
        500;

    margin-top:
        7px;

}


.heading p {

    color:
        #8c8178;

    font-size:
        13px;

}


.form-card {

    background:
        white;

    border:
        1px solid #e4dad0;

    display:
        grid;

    grid-template-columns:
        390px 1fr;

    box-shadow:
        0 20px 60px
        rgba(60,45,35,.07);

}


.image-area {

    background:
        #f4eee8;

    min-height:
        580px;

    padding:
        30px;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

}


.image-upload {

    width:
        100%;

    height:
        480px;

    border:
        1px dashed #cbb9a5;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    text-align:
        center;

    cursor:
        pointer;

    position:
        relative;

    overflow:
        hidden;

    background:
        #faf7f3;

}


.image-upload:hover {

    border-color:
        #a18554;

}


.upload-content {

    color:
        #9a8b7d;

}


.upload-icon {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:
        60px;

    color:
        #b09672;

}


.upload-content strong {

    display:
        block;

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:
        25px;

    color:
        #554c46;

}


.upload-content span {

    display:
        block;

    font-size:
        11px;

    margin-top:
        7px;

}


#preview {

    display:
        none;

    position:
        absolute;

    width:
        100%;

    height:
        100%;

    object-fit:
        cover;

}


.form-area {

    padding:
        50px;

}


.form-title {

    margin-bottom:
        30px;

}


.form-title small {

    color:
        #a18554;

    font-family:
        "Montserrat",
        sans-serif;

    font-size:
        9px;

    letter-spacing:
        3px;

}


.form-title h2 {

    font-family:
        "Cormorant Garamond",
        serif;

    font-size:
        35px;

    font-weight:
        500;

}


.field {

    margin-bottom:
        22px;

}


.field label {

    display:
        block;

    font-size:
        12px;

    color:
        #655c55;

    margin-bottom:
        8px;

}


.field input,
.field textarea {

    width:
        100%;

    border:
        1px solid #ddd2c8;

    background:
        #fffdfa;

    padding:
        13px 15px;

    font-family:
        "Kanit",
        sans-serif;

    font-size:
        13px;

    outline:
        none;

}


.field input {

    height:
        48px;

}


.field textarea {

    height:
        130px;

    resize:
        vertical;

}


.field input:focus,
.field textarea:focus {

    border-color:
        #a18554;

}


.price-box {

    position:
        relative;

}


.price-box span {

    position:
        absolute;

    left:
        15px;

    top:
        13px;

    color:
        #a18554;

}


.price-box input {

    padding-left:
        35px;

}


.error {

    background:
        #f9e9e6;

    color:
        #a04f45;

    border:
        1px solid #ead0ca;

    padding:
        12px;

    font-size:
        12px;

    margin-bottom:
        20px;

}


.buttons {

    display:
        flex;

    gap:
        10px;

    margin-top:
        30px;

}


.cancel {

    width:
        130px;

    height:
        50px;

    border:
        1px solid #d8cec4;

    background:
        white;

    text-decoration:
        none;

    color:
        #6e645c;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    font-size:
        13px;

}


.submit {

    flex:
        1;

    height:
        50px;

    border:
        none;

    background:
        #302b28;

    color:
        white;

    font-family:
        "Kanit",
        sans-serif;

    cursor:
        pointer;

    transition:
        .3s;

}


.submit:hover {

    background:
        #a18554;

}


@media(max-width:800px) {

    .form-card {

        grid-template-columns:
            1fr;

    }

    .image-area {

        min-height:
            400px;

    }

    .image-upload {

        height:
            350px;

    }

    .form-area {

        padding:
            30px;

    }

}


@media(max-width:500px) {

    .header {

        padding:
            0 20px;

    }

    .container {

        padding:
            40px 15px;

    }

    .heading h1 {

        font-size:
            42px;

    }

}

</style>

</head>


<body>


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


    <a
        href="products.php"
        class="back"
    >

        ← กลับ Collection

    </a>


</header>



<main class="container">


    <div class="heading">

        <small>
            CREATE YOUR SIGNATURE
        </small>

        <h1>
            Add New Fragrance
        </h1>

        <p>
            เพิ่มกลิ่นใหม่เข้าสู่คอลเลกชัน VELOURA
        </p>

    </div>



    <?php if ($error !== ""): ?>

        <div class="error">

            <?= htmlspecialchars(
                $error
            ) ?>

        </div>

    <?php endif; ?>



    <form
        method="POST"
        enctype="multipart/form-data"
        class="form-card"
    >


        <!-- IMAGE -->

        <div class="image-area">

            <label
                for="image"
                class="image-upload"
            >


                <div
                    class="upload-content"
                    id="uploadText"
                >

                    <div class="upload-icon">
                        ✦
                    </div>

                    <strong>
                        Your Fragrance
                    </strong>

                    <span>
                        คลิกเพื่อเลือกรูปขวดน้ำหอม
                    </span>

                    <span>
                        JPG · PNG · WEBP
                    </span>

                </div>


                <img
                    id="preview"
                    alt="Preview"
                >


                <input
                    type="file"
                    name="image"
                    id="image"
                    accept="image/jpeg,image/png,image/webp"
                    hidden
                >

            </label>

        </div>



        <!-- FORM -->

        <div class="form-area">


            <div class="form-title">

                <small>
                    FRAGRANCE DETAILS
                </small>

                <h2>
                    New Collection
                </h2>

            </div>



            <div class="field">

                <label>
                    ชื่อน้ำหอม
                </label>

                <input
                    type="text"
                    name="name"
                    placeholder="เช่น Veloura Bloom"
                    required
                >

            </div>



            <div class="field">

                <label>
                    ราคา
                </label>

                <div class="price-box">

                    <span>
                        ฿
                    </span>

                    <input
                        type="number"
                        name="price"
                        placeholder="1590"
                        step="0.01"
                        min="0"
                        required
                    >

                </div>

            </div>



            <div class="field">

                <label>
                    รายละเอียดกลิ่น
                </label>

                <textarea
                    name="description"
                    placeholder="อธิบายกลิ่น เช่น กลิ่นดอกไม้หวานละมุน ผสมความสดชื่น..."
                ></textarea>

            </div>



            <div class="buttons">


                <a
                    href="products.php"
                    class="cancel"
                >
                    ยกเลิก
                </a>


                <button
                    type="submit"
                    class="submit"
                >

                    ✦ เพิ่มเข้าสู่ Collection

                </button>


            </div>


        </div>


    </form>


</main>



<script>

const image =
    document.getElementById("image");

const preview =
    document.getElementById("preview");

const uploadText =
    document.getElementById("uploadText");


image.addEventListener(
    "change",
    function() {

        const file =
            this.files[0];


        if (!file) {

            return;

        }


        const reader =
            new FileReader();


        reader.onload =
            function(e) {

                preview.src =
                    e.target.result;

                preview.style.display =
                    "block";

                uploadText.style.display =
                    "none";

            };


        reader.readAsDataURL(file);

    }
);

</script>


</body>

</html>