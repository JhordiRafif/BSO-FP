<?php
    include 'database.php';
    session_start();
    if (!isset($_SESSION['id']) || !isset($_GET['id'])) {
        header("Location: login.php");
    }
    $userId = $_SESSION['id'];
    $salesId = $_GET['id'];
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BSO Me</title>
</head>
<body>
    <a href="index.php">kembali</a>
    <form method="post">
        <label for="name">nama</label>
        <input
            id="Name"
            name="name"
            placeholder="masukkan nama barang"
            type="text"
        >
        <label for="stock">stock</label>
        <input
            id="stock"
            name="stock"
            placeholder="masukkan stock"
            type="number"
        >
        <label for="price">harga unit</label>
        <input
            id="price"
            name="price"
            placeholder="masukkan harga unit"
            type="number"
        >
        <button name="goods-input">input</button>
    </form>
    <form method="GET">
        <label for="search">pencarian</label>
        <input
            id="search"
            name="search"
            placeholder="masukkan nama barang"
            type="text"
        >
        <?php
            echo "<button name='id' value=$salesId>cari</button>"
        ?>
    </form>
    <?php
    $query = "SELECT * FROM goods WHERE sales_id=$salesId";
    if (isset($_GET['search'])) {
        $searchQuery = $_GET['search'];
        $query = "$query AND (name LIKE '$searchQuery%' OR name LIKE '% $searchQuery%')";
    }

    if ($result = mysqli_query($conn, $query)) {
        if (mysqli_num_rows($result) > 0) {
            $fetchedData = mysqli_fetch_all($result, MYSQLI_ASSOC);
            foreach ($fetchedData as $goods) {
                $goodsId = $goods['id'];
                $goodsName = $goods['name'];
                $goodsStock = $goods['stock'];
                $goodsPrice = $goods['price'];
                echo "
                    <div>
                        <p>$goodsName (stok $goodsStock) Rp.$goodsPrice</p>
                        <form method='POST'>
                            <button name='delete' value='$goodsId' type='submit'>delete</button>
                        </form>
                    </div>
                ";
            }
        } else {
            echo 'gk ditemuin';
        }
    } else {
        echo 'gagal connect database wokwkwkwk';
    }

    ?>
</body>
</html>

<?php
if (isset($_POST['goods-input'])) {
    $name = $_POST['name'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];

    $query = "INSERT INTO goods (name, stock, price, sales_id) VALUES ('$name', $stock, $price, $userId)";

    $result = mysqli_query($conn, $query);
    if ($result) {
        Header("Location: me.php?id=$salesId");
        exit();
    }
}

if (isset($_POST['delete'])) {
    $myId = $_POST['delete'];

    $query = "DELETE FROM goods WHERE id=$myId";
    $result = mysqli_query($conn, $query);

    if ($result) {
        Header("Location: me.php?id=$salesId");
        exit();
    }
}
