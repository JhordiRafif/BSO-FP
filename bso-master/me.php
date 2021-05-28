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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
</head>
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
        <br></br>
<body>
<div class="card">
  <h3 class="card-header">Jual barang</h3>
    <div class="card-body">
    <h5 class="card-title">Masukkan Nama</h5>
    <form method="post">
        <input
            id="name"
            name="name"
            placeholder="Nama barang"
            type="text"
        >
        <br></br>
    <div class="card-title">
    <h5 class="card-title">Masukkan banyak</h5>
    <form method="post">
        <input
            id="stock"
            name="stock"
            placeholder="Stock"
            type="number"
        >
        <br></br>
    <div class="card-title">
    <h5 class="card-title">Harga</h5>
        <input
            id="price"
            name="price"
            placeholder="Harga per unit"
            type="number"
        >
    <p class="card-text">Dengan ini saya menyetujui barang saya dijual disini.</p>
    <button name="goods-input" class="btn btn-primary" >Input</button>
    </div>
    </div>    
    </div>
</div>
<br></br>
    </form>

    <form method="GET">
    <div class="card">
    <h3 class="card-header">Barang anda</h3>
    <div class="card-body">
        <input
            id="search"
            name="search"
            placeholder="Masukkan nama barang"
            type="text"
        >
        <?php
            echo "<button name='id' value=$salesId  class='btn btn-primary' >Cari</button>"
        ?>
        <br></br>
    </form>
    <table class='table'>
                        <thead>
                            <tr>
                            <th scope='col'>Nama</th>
                            <th scope='col'>Stock</th>
                            <th scope='col'>Harga</th>
                            <th scope='col'>Delete</th>
                            </tr>
                        </thead>
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
                        <tbody>
                            <tr>
                            <th scope='row'> $goodsName </th>
                            <td>$goodsStock</td>
                            <td>Rp. $goodsPrice</td>
                            <td><form method='POST'>
                            <button name='delete' value='$goodsId' class='btn btn-primary' type='submit'>delete</button>
                        </form>
                        </td>
                            </tr>
                        </tbody>
                        
                    </div>
                ";
            }
        } else {
            echo 'Tidak ditemukan';
        }
    } else {
        echo 'Terjadi kesalahan';
    }

    ?>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

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
        header("Location: me.php?id=$salesId");
        exit();
        return;
    }
}
if (isset($_POST['delete'])) {
    $myId = $_POST['delete'];

    $query = "DELETE FROM goods WHERE id=$myId";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: me.php?id=$salesId");
        exit();
        return;
    }
} 