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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
</head>
<body>
<div class="container">
        <nav class="navbar navbar-expand-lg navbar-light "style="background-color: #e3f2fd;">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">BSO</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="transactions.php">Daftar transaksi</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="cashflow.php">Daftar Cashflow</a>
                    </li>
                </ul>
                <span class="navbar-text">
                <a href="index.php">kembali</a>
                </span>
                </div>
            </div>
        </nav>
        <!-- Header --><br></br>
  <section class="bg-custom-5 height-4">
    <div class="container">
      <div class="row">
        <div class="col my-6">
          <div class="d-flex justify-content-center align-items-center flex-column">
            <h2 class="text-warning mt-5 font-weight-light text-monospace">BSO Otoparts</h2>
            <span class="lead text-dark d-block">Solusi Kendaraan Anda :))</span>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Akhir Header -->
    <?php
        if ($userRole != 'buyers') {
            echo "Hanya pembeli yang dapat membeli barang";
        }
        
        echo "
        <br>
        <p>Nama barang:</p>
        <h1>$goodsName</h1>
        <p>Stok barang:</p>
        <h3>$goodsStock</h3>
        <p>Harga per stok</p>
        <h3>$goodsPrice</h3>
        <p>Nama penjual:</p>
        <h3>$salesName</h3>
        ".($userRole != 'buyers' ? "<p>hanya pembeli yang dapat membeli barang!</p>" : ($goodsStock < 1 ? "<p>stok habis, tidak bisa membeli!</p>" : "
            <br><h2>pembelian</h2>
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
                <button type='submit'>Beli</button>
            </form>
        "));
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
</body>
</html>
<br></br>
<div class="container">
  <footer class="text-dark py-3 shadow-sm" style="background-color:#e3f2fd;">
    <div class="container-fluid">
      <div class="row">
        <div class="col">
          <div class="d-flex justify-content-center align-items-center text-center">
            <span class="font-weight-light">Created By <a href="https://www.instagram.com/dongkank19/" class="text-warning">Jamet & Guru Spirital</a></span>
          </div>
        </div>
      </div>
    </div>
    </div>
  </footer>
  <!-- Akhir Footer -->
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
