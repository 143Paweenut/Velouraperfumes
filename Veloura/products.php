<?php

session_start();

require_once "connect.php";

if (!isset($_SESSION["cart"]) || !is_array($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

/* เพิ่มสินค้าเข้าตะกร้า */
if (isset($_GET["add"])) {
    $id = intval($_GET["add"]);

    if ($id > 0) {
        $stmt = $conn->prepare("
            SELECT id FROM products WHERE id = ? LIMIT 1
        ");

        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result_add = $stmt->get_result();

            if ($result_add->num_rows > 0) {
                $_SESSION["cart"][$id] = ($_SESSION["cart"][$id] ?? 0) + 1;
            }

            $stmt->close();
        }
    }

    header("Location: products.php");
    exit();
}

/* จำนวนสินค้าในรถเข็น */
$cart_count = 0;
foreach ($_SESSION["cart"] as $quantity) {
    $cart_count += intval($quantity);
}

/* ดึงสินค้าจากฐานข้อมูล */
$result = $conn->query("
    SELECT id, name, price, image, description
    FROM products
    ORDER BY id ASC
");

?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>สินค้า | Veloura Perfumes</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&family=Noto+Sans+Thai:wght@300;400;500;600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
*{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth}
body{
    font-family:"Noto Sans Thai","Montserrat",sans-serif;
    background:
        radial-gradient(circle at 8% 18%,rgba(241,190,213,.16),transparent 28%),
        radial-gradient(circle at 92% 72%,rgba(221,184,204,.14),transparent 30%),
        #fffafc;
    color:#33282d;
    overflow-x:hidden;
}

/* ===== sparkle layer ===== */
.sparkle-layer{position:fixed;inset:0;pointer-events:none;z-index:9999;overflow:hidden}
.sparkle{
    position:absolute;
    color:#dca9c1;
    text-shadow:0 0 8px rgba(255,255,255,.95),0 0 18px rgba(214,154,184,.65);
    animation:twinkle 3.2s ease-in-out infinite;
    opacity:.55;
}
.s1{left:4%;top:18%;font-size:14px;animation-delay:.2s}
.s2{left:13%;top:65%;font-size:9px;animation-delay:1.1s}
.s3{left:28%;top:9%;font-size:12px;animation-delay:1.7s}
.s4{left:45%;top:28%;font-size:8px;animation-delay:.6s}
.s5{left:58%;top:13%;font-size:15px;animation-delay:2s}
.s6{left:72%;top:62%;font-size:10px;animation-delay:.9s}
.s7{left:87%;top:24%;font-size:13px;animation-delay:1.4s}
.s8{left:95%;top:80%;font-size:9px;animation-delay:2.2s}
.s9{left:37%;top:78%;font-size:11px;animation-delay:.4s}
.s10{left:79%;top:90%;font-size:14px;animation-delay:1.8s}
@keyframes twinkle{
    0%,100%{opacity:.18;transform:scale(.7) rotate(0)}
    50%{opacity:.95;transform:scale(1.35) rotate(18deg)}
}

/* ===== top bar ===== */
.top-bar{
    height:36px;
    display:flex;
    align-items:center;
    justify-content:center;
    position:relative;
    overflow:hidden;
    background:linear-gradient(90deg,#33262d,#4a303b,#33262d);
    color:#fff;
    font:500 10px "Montserrat","Noto Sans Thai",sans-serif;
    letter-spacing:2px;
}
.top-bar:after{
    content:"";
    position:absolute;
    top:0;left:-25%;
    width:20%;height:100%;
    background:linear-gradient(90deg,transparent,rgba(255,255,255,.38),transparent);
    transform:skewX(-22deg);
    animation:shine 4.5s linear infinite;
}
@keyframes shine{0%{left:-25%}100%{left:125%}}

/* ===== navbar ===== */
.navbar{
    position:sticky;top:0;z-index:1000;
    background:rgba(255,255,255,.88);
    backdrop-filter:blur(18px);
    -webkit-backdrop-filter:blur(18px);
    border-bottom:1px solid rgba(210,184,196,.35);
    box-shadow:0 8px 30px rgba(93,61,74,.06);
}
.navbar>nav{
    min-height:92px;
    display:grid;
    grid-template-columns:1fr auto 1fr;
    align-items:center;
    padding:0 5%;
}
.logo{text-decoration:none;text-align:center;color:#352a30}
.logo-main{
    display:block;
    font:600 34px/1 "Cormorant Garamond",Georgia,serif;
    letter-spacing:7px;
    text-shadow:0 2px 15px rgba(202,150,176,.16);
}
.logo-sub{
    display:flex;align-items:center;justify-content:center;gap:8px;
    margin-top:8px;
    font:500 8px "Montserrat",sans-serif;
    letter-spacing:4px;color:#a98798;
}
.logo-sub:before,.logo-sub:after{
    content:"";width:24px;height:1px;background:#d7b9c7
}
.nav-links{display:flex;gap:27px;align-items:center;justify-content:center}
.nav-links a,.nav-right a{
    text-decoration:none;color:#594a51;
    font-size:11px;transition:.3s;white-space:nowrap;
}
.nav-links a{position:relative}
.nav-links a:after{
    content:"";position:absolute;left:50%;bottom:-8px;width:0;height:1px;
    background:#b77798;transition:.3s;transform:translateX(-50%)
}
.nav-links a:hover:after,.nav-links a.active:after{width:100%}
.nav-links a:hover,.nav-links a.active,.nav-right a:hover{color:#b06f91}
.nav-right{display:flex;align-items:center;justify-content:flex-end;gap:18px}
.nav-right a i{font-size:12px}
.cart{position:relative}
.cart-count{
    position:absolute;top:-12px;right:-14px;
    min-width:18px;height:18px;padding:0 4px;border-radius:50%;
    background:linear-gradient(135deg,#c17c9c,#8e5573);
    color:#fff;font:600 9px "Montserrat",sans-serif;
    display:flex;align-items:center;justify-content:center;
    box-shadow:0 3px 10px rgba(155,93,121,.3)
}

/* ===== hero ===== */
.shop-hero{
    min-height:460px;
    position:relative;
    display:flex;align-items:center;justify-content:center;
    text-align:center;overflow:hidden;
    background:
      linear-gradient(180deg,rgba(48,32,40,.28),rgba(48,32,40,.62)),
      url("images/perfume-hero.jpg") center/cover no-repeat;
}
.shop-hero:before{
    content:"";position:absolute;inset:0;
    background:
      radial-gradient(circle at 50% 35%,rgba(255,226,239,.18),transparent 30%),
      linear-gradient(90deg,transparent,rgba(255,255,255,.04),transparent);
}
.hero-orb{
    position:absolute;width:420px;height:420px;border-radius:50%;
    background:radial-gradient(circle,rgba(255,210,230,.12),transparent 68%);
    filter:blur(5px);animation:orb 7s ease-in-out infinite;
}
@keyframes orb{50%{transform:scale(1.12);opacity:.65}}
.shop-hero-content{position:relative;z-index:2;color:#fff;padding:70px 20px;animation:heroRise 1s ease both}
@keyframes heroRise{from{opacity:0;transform:translateY(25px)}to{opacity:1;transform:none}}
.hero-kicker{font:500 10px "Montserrat",sans-serif;letter-spacing:5px;margin-bottom:20px;color:#f8e9f0}
.shop-hero h2{
    font:400 clamp(48px,6vw,76px)/.92 "Cormorant Garamond",Georgia,serif;
    text-shadow:0 8px 35px rgba(0,0,0,.28);
}
.hero-line{display:flex;justify-content:center;align-items:center;gap:14px;margin:24px 0 17px;color:#f3cada}
.hero-line span{width:75px;height:1px;background:linear-gradient(90deg,transparent,#e8b9ce)}
.hero-line span:last-child{background:linear-gradient(90deg,#e8b9ce,transparent)}
.hero-line b{font-size:15px;font-weight:400;text-shadow:0 0 15px #fff}
.hero-description{max-width:580px;margin:auto;color:#f4e9ee;font-size:12px;line-height:2}

/* ===== shop ===== */
.shop-container{max-width:1380px;margin:auto;padding:85px 5% 105px}
.shop-heading{
    display:flex;justify-content:space-between;align-items:end;
    padding-bottom:27px;margin-bottom:42px;
    border-bottom:1px solid #eadde4;
}
.small-heading{
    font:500 10px "Montserrat",sans-serif;letter-spacing:3px;color:#b06f91;margin-bottom:8px
}
.shop-heading h2{font:400 47px/1 "Cormorant Garamond",Georgia,serif;color:#382d33}
.sort-box{
    display:flex;align-items:center;gap:11px;padding:8px 0;
    border-bottom:1px solid #ccb7c2
}
.sort-box label{font-size:10px;color:#9b858f}
.sort-box select{
    border:0;outline:0;background:transparent;
    color:#55454d;font:400 11px "Noto Sans Thai",sans-serif;cursor:pointer
}

/* ===== products ===== */
.products{display:grid;grid-template-columns:repeat(4,1fr);gap:58px 28px}
.product{
    position:relative;min-width:0;
    animation:productIn .7s ease both;
}
.product:nth-child(2){animation-delay:.06s}.product:nth-child(3){animation-delay:.12s}
.product:nth-child(4){animation-delay:.18s}.product:nth-child(5){animation-delay:.24s}
@keyframes productIn{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:none}}
.product-image{
    position:relative;overflow:hidden;aspect-ratio:3/4;
    background:linear-gradient(145deg,#f8eef3,#eee0e8);
    border:1px solid rgba(196,153,173,.18);
    box-shadow:0 18px 45px rgba(82,48,63,.09);
}
.product-image:before{
    content:"";position:absolute;z-index:2;inset:0;
    background:linear-gradient(115deg,transparent 35%,rgba(255,255,255,.27) 48%,transparent 58%);
    transform:translateX(-120%);
    transition:transform .8s ease;pointer-events:none;
}
.product:hover .product-image:before{transform:translateX(120%)}
.product-image:after{
    content:"✦";position:absolute;right:14px;top:12px;z-index:3;
    color:rgba(255,255,255,.9);font-size:14px;
    text-shadow:0 0 12px rgba(255,255,255,.9);
    opacity:0;transition:.35s
}
.product:hover .product-image:after{opacity:1}
.product-image img{
    width:100%;height:100%;object-fit:cover;display:block;
    transition:transform .8s cubic-bezier(.2,.7,.2,1),filter .5s
}
.product:hover .product-image img{transform:scale(1.075);filter:saturate(1.04)}
.product:after{
    content:"";position:absolute;left:8%;right:8%;bottom:-18px;height:35px;
    background:rgba(185,115,146,.18);filter:blur(22px);opacity:0;transition:.4s
}
.product:hover:after{opacity:1}

/* badge */
.badge{
    position:absolute;top:14px;left:14px;z-index:4;
    padding:6px 12px;border-radius:30px;
    background:rgba(55,39,47,.88);color:#fff;
    font-size:9px;letter-spacing:1px;
    box-shadow:0 5px 15px rgba(0,0,0,.14)
}

/* quick add */
.quick-add{
    position:absolute;z-index:5;left:50%;bottom:17px;
    transform:translate(-50%,16px);opacity:0;
    width:calc(100% - 30px);padding:13px 12px;
    border:1px solid rgba(255,255,255,.25);
    border-radius:2px;
    background:linear-gradient(135deg,rgba(58,40,49,.96),rgba(117,69,91,.96));
    color:#fff;text-decoration:none;text-align:center;
    font-size:10px;letter-spacing:.6px;
    box-shadow:0 10px 25px rgba(45,27,36,.22);
    transition:.35s
}
.quick-add i{margin-right:6px}
.product:hover .quick-add{opacity:1;transform:translate(-50%,0)}
.quick-add:hover{background:linear-gradient(135deg,#b97899,#8f5774);box-shadow:0 0 20px rgba(188,112,148,.35)}

/* info */
.product-info{text-align:center;padding:19px 8px 0}
.product-info h3{
    color:#352a30;font:500 22px/1.1 "Cormorant Garamond",Georgia,serif;
    letter-spacing:1.5px;transition:.3s
}
.product:hover .product-info h3{color:#aa6688}
.product-info p{margin-top:7px;color:#a08c96;font-size:10px;line-height:1.6;min-height:18px}
.price{
    display:block;margin-top:9px;color:#7f5268;
    font:500 11px "Montserrat",sans-serif;letter-spacing:1.2px
}

/* ===== empty ===== */
.empty-products{
    grid-column:1/-1;text-align:center;padding:90px 20px;
    color:#a08c96
}
.empty-icon{
    width:70px;height:70px;border:1px solid #dfc8d3;border-radius:50%;
    margin:0 auto 18px;display:flex;align-items:center;justify-content:center;
    color:#b67895;font-size:24px;box-shadow:0 0 35px rgba(190,125,154,.12)
}
.empty-products h3{font:400 32px "Cormorant Garamond",Georgia,serif;color:#54434c}
.empty-products p{margin-top:7px;font-size:11px}

/* ===== no result ===== */
.no-result{text-align:center;padding:70px 20px;color:#a08c96}
.no-result i{
    font-size:22px;color:#b87897;margin-bottom:15px;
    filter:drop-shadow(0 0 8px rgba(190,121,154,.3))
}
.no-result h3{font:400 31px "Cormorant Garamond",Georgia,serif;color:#54434c}
.no-result p{margin-top:6px;font-size:11px}

/* ===== footer ===== */
footer{
    position:relative;overflow:hidden;text-align:center;
    padding:70px 20px 28px;
    color:#d9cdd3;
    background:
      radial-gradient(circle at 50% 0,rgba(203,128,159,.13),transparent 32%),
      linear-gradient(135deg,#30242b,#3d2c35,#291f25);
}
footer:before,footer:after{
    content:"✦  ✧  ⋆  ✧  ✦";
    position:absolute;color:rgba(242,193,212,.32);letter-spacing:22px;
    font-size:13px;white-space:nowrap
}
footer:before{top:20px;left:50%;transform:translateX(-50%)}
footer:after{bottom:74px;left:50%;transform:translateX(-50%);font-size:9px;letter-spacing:14px}
.footer-logo{
    color:#fff;font:500 39px "Cormorant Garamond",Georgia,serif;
    letter-spacing:8px;text-shadow:0 0 22px rgba(255,214,231,.2)
}
footer>p{margin-top:9px;color:#bdaeb6;font-size:11px}
.footer-social{display:flex;justify-content:center;gap:12px;margin:25px 0 34px}
.footer-social a{
    width:34px;height:34px;border:1px solid #77636e;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    text-decoration:none;color:#ded2d8;font-size:11px;transition:.35s
}
.footer-social a:hover{
    background:#fff;color:#3a2932;border-color:#fff;
    box-shadow:0 0 20px rgba(255,220,235,.3);transform:translateY(-3px)
}
.copyright{border-top:1px solid #50404a;padding-top:19px;color:#897983;font-size:10px}

/* ===== floating back-to-top ===== */
.to-top{
    position:fixed;right:24px;bottom:24px;z-index:500;
    width:42px;height:42px;border-radius:50%;
    border:1px solid rgba(177,112,143,.4);
    background:rgba(255,250,252,.82);backdrop-filter:blur(10px);
    color:#a76685;text-decoration:none;display:flex;align-items:center;justify-content:center;
    opacity:0;visibility:hidden;transform:translateY(10px);transition:.3s;
    box-shadow:0 8px 25px rgba(82,48,63,.12)
}
.to-top.show{opacity:1;visibility:visible;transform:none}
.to-top:hover{background:#b97899;color:#fff;box-shadow:0 0 22px rgba(185,120,153,.35)}

/* ===== responsive ===== */
@media(max-width:1100px){
    .navbar>nav{grid-template-columns:auto 1fr auto;padding:0 3%}
    .nav-links{gap:18px}
    .products{grid-template-columns:repeat(3,1fr)}
}
@media(max-width:850px){
    .navbar>nav{display:flex;flex-direction:column;gap:18px;padding:20px}
    .nav-links{flex-wrap:wrap;gap:13px 19px}
    .nav-right{justify-content:center}
    .shop-heading{align-items:flex-start;flex-direction:column;gap:22px}
    .products{grid-template-columns:repeat(2,1fr)}
    .sort-box{width:max-content}
}
@media(max-width:550px){
    .top-bar{font-size:8px;letter-spacing:1px}
    .logo-main{font-size:30px}
    .shop-hero{min-height:420px}
    .shop-hero h2{font-size:47px}
    .shop-container{padding:60px 20px 75px}
    .shop-heading h2{font-size:38px}
    .products{grid-template-columns:1fr;max-width:370px;margin:auto}
    .quick-add{opacity:1;transform:translate(-50%,0)}
    .to-top{right:15px;bottom:15px}
}
@media(prefers-reduced-motion:reduce){
    *,*:before,*:after{animation:none!important;transition:none!important}
}
</style>
</head>

<body>

<div class="sparkle-layer" aria-hidden="true">
    <span class="sparkle s1">✦</span><span class="sparkle s2">✧</span>
    <span class="sparkle s3">⋆</span><span class="sparkle s4">✦</span>
    <span class="sparkle s5">✧</span><span class="sparkle s6">⋆</span>
    <span class="sparkle s7">✦</span><span class="sparkle s8">✧</span>
    <span class="sparkle s9">⋆</span><span class="sparkle s10">✦</span>
</div>

<div class="top-bar">✦ &nbsp; จัดส่งฟรี เมื่อสั่งซื้อครบ ฿50 &nbsp; ✦</div>

<header class="navbar">
<nav>
    <a href="index.php" class="logo">
        <span class="logo-main">VELOURA</span>
        <span class="logo-sub">PERFUMES</span>
    </a>

    <div class="nav-links">
        <a href="index.php">หน้าแรก</a>
        <a href="products.php" class="active">สินค้า</a>
        <a href="about.php">เกี่ยวกับเรา</a>
        <a href="collection.php">คอลเลกชัน</a>
        <a href="find-scent.php">ค้นหากลิ่น</a>
        <a href="contact.php">ติดต่อเรา</a>
    </div>

    <div class="nav-right">
        <a href="profile.php"><i class="fa-regular fa-user"></i> บัญชี</a>
        <a href="cart.php" class="cart">
            <i class="fa-solid fa-bag-shopping"></i> รถเข็น
            <?php if($cart_count>0): ?>
                <span class="cart-count"><?php echo $cart_count; ?></span>
            <?php endif; ?>
        </a>
    </div>
</nav>
</header>

<section class="shop-hero">
    <div class="hero-orb"></div>
    <div class="shop-hero-content">
        <p class="hero-kicker">VELOURA PERFUMES</p>
        <h2>ค้นพบกลิ่นหอม<br>ที่เป็นเอกลักษณ์ของคุณ</h2>
        <div class="hero-line"><span></span><b>✦</b><span></span></div>
        <p class="hero-description">
            ค้นพบกลิ่นหอมที่สร้างสรรค์ขึ้นเพื่อสะท้อนตัวตน<br class="desktop-break">
            และเสน่ห์ที่เป็นเอกลักษณ์ของคุณ
        </p>
    </div>
</section>

<main class="shop-container">
    <div class="shop-heading">
        <div>
            <p class="small-heading">DISCOVER VELOURA</p>
            <h2>น้ำหอมของเรา</h2>
        </div>

        <div class="sort-box">
            <label for="sort">เรียงตาม</label>
            <select id="sort">
                <option value="default">สินค้าแนะนำ</option>
                <option value="low">ราคาต่ำไปสูง</option>
                <option value="high">ราคาสูงไปต่ำ</option>
                <option value="name">ชื่อสินค้า</option>
            </select>
        </div>
    </div>

    <section class="products" id="products">
<?php
if($result && $result->num_rows>0):
    while($product=$result->fetch_assoc()):
        $product_id=(int)$product["id"];
        $product_name=htmlspecialchars($product["name"],ENT_QUOTES,"UTF-8");
        $product_price_number=(float)$product["price"];
        $product_price=number_format($product_price_number,2);
        $product_image=htmlspecialchars($product["image"],ENT_QUOTES,"UTF-8");
        $product_description=htmlspecialchars($product["description"] ?? "น้ำหอม Eau de Parfum",ENT_QUOTES,"UTF-8");

        $badge="";
        if($product_id===1 || $product_id===5) $badge="ขายดี";
        elseif($product_id===2 || $product_id===8) $badge="ใหม่";
?>
        <article class="product" data-name="<?php echo $product_name; ?>" data-price="<?php echo $product_price_number; ?>">
            <div class="product-image">
                <?php if($badge!==""): ?>
                    <span class="badge"><?php echo $badge; ?></span>
                <?php endif; ?>

                <img src="<?php echo $product_image; ?>" alt="<?php echo $product_name; ?>" loading="lazy">

                <a href="products.php?add=<?php echo $product_id; ?>" class="quick-add">
                    <i class="fa-solid fa-bag-shopping"></i> เพิ่มลงรถเข็น
                </a>
            </div>

            <div class="product-info">
                <h3><?php echo strtoupper($product_name); ?></h3>
                <p><?php echo $product_description; ?></p>
                <span class="price">฿<?php echo $product_price; ?></span>
            </div>
        </article>
<?php
    endwhile;
else:
?>
        <div class="empty-products">
            <div class="empty-icon"><i class="fa-solid fa-box-open"></i></div>
            <h3>ยังไม่มีสินค้า</h3>
            <p>กรุณาเพิ่มสินค้าจากหน้าแอดมิน</p>
        </div>
<?php endif; ?>
    </section>

    <div id="noResult" class="no-result" style="display:none">
        <i class="fa-solid fa-magnifying-glass"></i>
        <h3>ไม่พบกลิ่นหอมที่ค้นหา</h3>
        <p>ลองค้นหาน้ำหอมรายการอื่น</p>
    </div>
</main>

<footer>
    <div class="footer-logo">VELOURA</div>
    <p>กำหนดกลิ่นหอม กำหนดตัวตนของคุณ</p>

    <div class="footer-social">
        <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
        <a href="#" aria-label="Pinterest"><i class="fa-brands fa-pinterest-p"></i></a>
    </div>

    <div class="copyright">© <?php echo date("Y"); ?> Veloura Perfumes. สงวนลิขสิทธิ์</div>
</footer>

<a href="#" class="to-top" id="toTop" aria-label="กลับขึ้นด้านบน">
    <i class="fa-solid fa-chevron-up"></i>
</a>

<script>
/* ===== sort ===== */
const sortSelect=document.getElementById("sort");
const container=document.getElementById("products");
const noResult=document.getElementById("noResult");

sortSelect.addEventListener("change",function(){
    const items=Array.from(container.querySelectorAll(".product"));

    if(this.value==="low"){
        items.sort((a,b)=>Number(a.dataset.price)-Number(b.dataset.price));
    }else if(this.value==="high"){
        items.sort((a,b)=>Number(b.dataset.price)-Number(a.dataset.price));
    }else if(this.value==="name"){
        items.sort((a,b)=>a.dataset.name.localeCompare(b.dataset.name,"th"));
    }else{
        window.location.reload();
        return;
    }

    items.forEach((item,i)=>{
        item.style.display="";
        item.style.animationDelay=(i*.04)+"s";
        container.appendChild(item);
    });
    noResult.style.display="none";
});

/* ===== back to top ===== */
const toTop=document.getElementById("toTop");
window.addEventListener("scroll",()=>{
    toTop.classList.toggle("show",window.scrollY>450);
});
toTop.addEventListener("click",(e)=>{
    e.preventDefault();
    window.scrollTo({top:0,behavior:"smooth"});
});

/* ===== subtle mouse glow ===== */
const glow=document.createElement("div");
glow.style.cssText=`
position:fixed;width:260px;height:260px;border-radius:50%;
pointer-events:none;z-index:0;
background:radial-gradient(circle,rgba(221,161,187,.07),transparent 68%);
transform:translate(-50%,-50%);transition:left .15s ease,top .15s ease;
`;
document.body.appendChild(glow);
window.addEventListener("mousemove",e=>{
    glow.style.left=e.clientX+"px";
    glow.style.top=e.clientY+"px";
});
</script>

</body>
</html>
