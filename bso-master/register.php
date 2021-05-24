<?php
    include 'database.php';
    session_start();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <title>BSO Register</title>
</head>
<body>
    <div class="container">
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

    <br><br>
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input
                    id="username"
                    name="username"
                    placeholder="Masukkan Username..."
                    type="text"
                    class="form-control"
                >
            </div>
            <div class="mb-3">
                <label for="password">Password</label>
                <input
                    id="password"
                    name="password"
                    placeholder="Masukkan Password..."
                    type="password"
                    class="form-control"
                >
            </div>
            <div class="col-6 col-md-4">
                <div class="form-floating">
                    <select class="form-select" id="floatingSelectGrid" aria-label="Floating label select example" name="role" id="role">
                    <option selected>...</option>
                        <option value="buyers">Buyers</option>
                        <option value="sales">Sales</option>
                    </select>
                    <label for="role">Pilih Peran anda</label>
                    <a href="index.php">Kembali</a>
                </div>
            </div>
            <br>
            <br> </br>
            <button type="submit" class="btn " style="background-color: #e3f2fd;">register</button>
        </form>
        <a href="login.php">Sudah memiliki akun ?</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>


<?php
if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $result = mysqli_query($conn, "INSERT INTO $role (username, password) VALUES ('$username', '$password')");
    if ($result) {
        $_SESSION['id'] = mysqli_insert_id($conn);
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        header("Location: index.php");
    }
    mysqli_close($conn);
}