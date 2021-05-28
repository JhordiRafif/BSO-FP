<?php
    include 'database.php';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <title>Cashflow</title>
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
                    <a class="nav-link active" aria-current="page" href="#">Daftar Cashflow</a>
                    </li>
                </ul>
                <span class="navbar-text">
                <a href="index.php">Kembali</a>
                </span>
                </div>
            </div>
        </nav>
        <div class="card">
        <div class="card-body">
        <h5 class="card-header">Cashflow</h5>
        <div class="card-body">
       
    <p>Berbagai arus kas / uang untuk setiap pembeli dan penjual</p>
    <form method="GET">
        <label for="min">Minimum arus kas</label>
        <input
            name="min"
            type="number"
            id="min"
        >
        <button type="submit" class="btn btn-primary">Cari</button>
        </div>
        </div>
        </div>
    </form>
    <br>
</br>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>


    <?php
        $having = '';

        if (isset($_GET['min'])) {
            $min = $_GET['min'];
            echo $min;
            $having = "HAVING total_cashflow>$min";
        }

        $query = "
        SELECT
            buyers.username,
            SUM(transactions.quantity * (-goods.price)) AS total_cashflow
        FROM transactions
        JOIN goods
            ON transactions.goods_id=goods.id
        JOIN buyers
            ON transactions.buyer_id=buyers.id
        GROUP BY buyers.username
        $having
        UNION
        SELECT
            sales.username,
            SUM(transactions.quantity * goods.price) AS total_cashflow
        FROM transactions
        JOIN goods
            ON transactions.goods_id=goods.id
        JOIN sales
            ON transactions.sales_id=sales.id
        GROUP BY sales.username
        $having
        ";

    if ($result = mysqli_query($conn, $query)) {
        if (mysqli_num_rows($result) > 0) {
            $fetchedData = mysqli_fetch_all($result, MYSQLI_ASSOC);
            foreach ($fetchedData as $cashflow) {
                $username = $cashflow['username'];
                $totalCashflow = $cashflow['total_cashflow'];
                echo "
                    <div>
                    <div class='card'>
                    <div class='card-body'>
                   
                        <p>Username: $username</p>
                        <p>Total cashflow: $totalCashflow</p>
                    </div>
                    </div>
                    </div>
                    <hr class='dashed'>
                    ";
            }
        }
    }
    ?>
    </div>
</body>
</html>

<!-- Footer -->
<div class="fixed-bottom">
<div class="container">
  <footer class="text-dark py-3 shadow-sm" style="background-color:#e3f2fd;" >
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
    </div>
  </footer>
  <!-- Akhir Footer -->