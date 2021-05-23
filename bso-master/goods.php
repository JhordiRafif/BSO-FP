<?php
    include 'database.php';
    session_start();
    $userId = 0;
    $userRole = '';

    if (isset($_SESSION['id'])) {
        $userId = $_SESSION['id'];
        $userRole = $_SESSION['role'];
    }

    if (!isset($_GET['id'])) {
        echo "Tidak ada barang ditemukan";
        return;
    }

    $goodsId = $_GET['id'];

    $query = "SELECT goods.*, sales.username AS sales_name FROM goods
              JOIN sales
                ON goods.sales_id=sales.id
              WHERE goods.id=$goodsId";
    $goodsName = '';
    $goodsId = 0;
    $goodsStock = 0;
    $goodsPrice = 0;
    $salesName = '';
    $salesId = 0;
    if ($result = mysqli_query($conn, $query)) {
        if (mysqli_num_rows($result) > 0) {
            $fetchedData = mysqli_fetch_all($result, MYSQLI_ASSOC);
            foreach ($fetchedData as $goods) {
                $goodsId = $goods['id'];
                $goodsName = $goods['name'];
                $goodsStock = $goods['stock'];
                $goodsPrice = $goods['price'];
                $salesName = $goods['sales_name'];
                $salesId = $goods['sales_id'];
            }
        } else {
            echo 'Barang tidak ditemukan';
            return;
        }
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Goods</title>
</head>
<body>

    <a href="index.php">Kembali</a>
    <?php
        if ($userRole != 'buyers') {
            echo "Hanya pembeli yang dapat membeli barang";
        }
        echo "
        <p>nama barang:</p>
        <h1>$goodsName</h1>
        <p>stok barang:</p>
        <h3>$goodsStock</h3>
        <p>harga per stok</p>
        <h3>$goodsPrice</h3>
        <p>nama penjual:</p>
        <h3>$salesName</h3>
        ".($userRole != 'buyers' ? "<p>hanya pembeli yang dapat membeli barang!</p>" : ($goodsStock < 1 ? "<p>stok habis, tidak bisa membeli!</p>" : "
            <h2>pembelian</h2>
            <form method='POST'>
                <label for='stock'>stok</label>
                <input
                    type='number'
                    name='stock'
                    id='stock'
                    min='1'
                    max='$goodsStock'
                    value='1'
                >
                <button type='submit'>beli</button>
            </form>
        "));
    ?>
</body>
</html>

<?php
if (isset($_POST['stock'])) {
    $buyStocks = $_POST['stock'];
    $buyerId = $userId;
    $query = "INSERT INTO transactions (buyer_id, sales_id, goods_id, quantity)
              VALUES ($buyerId, $salesId, $goodsId, $buyStocks)";
    $result = mysqli_query($conn, $query);
    if ($result) {
        Header("Location: goods.php?id=$goodsId");
        exit();
    }
}