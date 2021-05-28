<?php
    include 'database.php';
    session_start();
    $userLoggedIn = false;
    $userRole = 'a';
    $userId = 1;
    if (isset($_SESSION['id'])) {
        $userRole = $_SESSION['role'];
        $userId = $_SESSION['id'];
        $userLoggedIn = true;
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
    <title>BSO</title>
</head>
<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light "style="background-color: #e3f2fd;">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">BSO</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="transactions.php">Daftar transaksi</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" href="cashflow.php">Daftar Cashflow</a>
                    </li>
                    <li class="nav-item">
                    <?php
                    if (isset($_SESSION['id'])) {
                        $id = $_SESSION['id'];
                        $username = $_SESSION['username'];
                        $role = $_SESSION['role'];

                        if ($_SESSION['role'] == 'sales') {
                                    echo "
                                        <a class='nav-link active' href='me.php?id=$id'>Barang saya</a>
                                    ";
                        }
                    }
                    ?>
                    </li>
                </ul>
                <span class="navbar-text bebas">
                    <?php
                        if (isset($_SESSION['id'])) {
                            $id = $_SESSION['id'];
                            $username = $_SESSION['username'];
                            $role = $_SESSION['role'];
                            echo "
                            <p class='akun'>id $id: $username ($role)</p>
                                <form method='POST'>
                                    <button name='logout' type='submit'>Logout</button>
                                </form>
                            ";

                            }
                         else {
                            echo "<a href='login.php'>Login</a>";
                        }
                    ?>
                </span>
                </div>
            </div>
        </nav>
        <br>
        <br/>


        <!-- Header -->
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
  <br>
        </br>
  <div class="card">
        <div class="card-body">
        <h5 class="card-header">Cari Barang</h5>
        <div class="card-body">
        <form method="GET">
            <label for="range-min">Rentang harga</label>
            <input
                id="range-min"
                name="rangeMin"
            >
            <label for="range-max">Hingga</label>
            <input
                id="range-max"
                name="rangeMax"
            >
            <button type="submit" class="btn btn-primary" >Cari</button>
        </form>
        </div>
        </div>
        </div>
        <br>
        </br>
        <div class="card">
        <div class="card-body">
        <h3 class="card-header">Daftar Barang</h3>
        <div class="card-body">
        
        <?php
            $query = "SELECT goods.*, sales.username AS sales_name FROM goods
                    JOIN sales
                        ON goods.sales_id=sales.id";
            if (isset($_GET['rangeMin']) && isset($_GET['rangeMax'])) {
                $rangeMin = $_GET['rangeMin'];
                $rangeMax = $_GET['rangeMax'];
                $query = "$query WHERE goods.price BETWEEN $rangeMin AND $rangeMax";
            }
            $query = "$query ORDER BY goods.id ASC";
            if ($result = mysqli_query($conn, $query)) {
                if (mysqli_num_rows($result) > 0) {
                    $fetchedData = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    foreach ($fetchedData as $goods) {
                        $goodsId = $goods['id'];
                        $goodsName = $goods['name'];
                        $goodsStock = $goods['stock'];
                        $goodsPrice = $goods['price'];
                        $salesName = $goods['sales_name'];

                        echo "
                        <div>
                            <p>$goodsName (stok $goodsStock) Rp.$goodsPrice</p>
                            <p>Penjual: $salesName</p>
                        ".($userLoggedIn == true && $userRole == 'buyers' ? "<a href='goods.php?id=$goodsId'>Selengkapnya</a>" : "")."
                        </div>
                        <hr class='dashed'>
                        ";
                    }
                }
            }
        ?>
        </div>
    </div>
    </div>
    
    <br></br>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
</body>
</html>

<!-- Clients -->
<div class="container">
<nav class="navbar navbar-expand-lg navbar-light "style="background-color: #e3f2fd;">
<section class="clients my-5" id="clients">
    
      <div class="row mx-3">
        <div class="col">
          <h2 class="font-weight-light mb-5">Anggota <strong class="text-primary">Kelompok</strong></h2>
        </div>
      </div>
      <div class="row mx-3">
        <div class="col-md-7 my-3 mx-auto">
          <div class="d-flex justify-content-center align-items-center flex-column text-center">
            <img src="assets/img/profile6.jpg" width="120" alt="" class="rounded-circle img-thumbnail shadow-sm mb-3">
            <h3 class="font-weight-normal d-block m-0">Rafif Jhordi</h3>
            <span class="lead d-block m-0">(1908561102)</span>
            <span class="d-block text-black-50">Kelas E</span>
          </div>
        </div>
        <div class="col-md-7 my-3 mx-auto">
          <div class="d-flex justify-content-center align-items-center flex-column text-center">
            <img src="assets/img/profile5.jpg" width="120" alt="" class="rounded-circle img-thumbnail shadow-sm mb-3">
            <h3 class="font-weight-normal d-block m-0">Krishna Aryawan</h3>
            <span class="lead d-block mb-0">1908561097</span>
            <span class="d-block text-black-50">Kelas E</span>
          </div>
        </div>
        <div class="col-md-7 my-3 mx-auto">
          <div class="d-flex justify-content-center align-items-center flex-column text-center">
            <img src="assets/img/profile7.jpg" width="120" alt="" class="rounded-circle img-thumbnail shadow-sm mb-3">
            <h3 class="font-weight-normal d-block m-0">Fahmi Ahmad Arum Pratama</h3>
            <span class="lead d-block mb-0">1908561088</span>
            <span class="d-block text-black-50">Kelas E</span>
          </div>
        </div>
        <div class="col-md-7 my-3 mx-auto">
          <div class="d-flex justify-content-center align-items-center flex-column text-center">
            <img src="assets/img/profile8.jpg" width="120" alt="" class="rounded-circle img-thumbnail shadow-sm mb-3">
            <h3 class="font-weight-normal d-block m-0">Udha Krisna Yasa</h3>
            <span class="lead d-block mb-0">1908561089</span>
            <span class="d-block text-black-50">Kelas E</span>
          </div>
        </div>
        <div class="col-md-7 my-3 mx-auto">
          <div class="d-flex justify-content-center align-items-center flex-column text-center">
            <img src="assets/img/profile10.png" width="120" alt="" class="rounded-circle img-thumbnail shadow-sm mb-3">
            <h3 class="font-weight-normal d-block m-0">Anita Dewi</h3>
            <span class="lead d-block mb-4">1908561104</span>
            <span class="d-block text-black-50">Terima kasih, ini merupakan tugas final project dari mata kuliah praktikum basis data tahun 2021!</span>
          </div>
        </div>
      </div>
  </section>
  </nav>
  </div>
  <!-- Akhir Clients -->


<!-- Footer -->
<br>
</br>
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
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
}