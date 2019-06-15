<?php
    session_start();

    if(isset($_POST['id'])) {
        $_SESSION['IdSede'] = $_POST['id'];
    } else {
        require 'php/get.php';
        $get = new get();

        $result = $get->getSP("get_CategoriaAsiento()");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <?php while ($row = $result->fetch_array()) { ?>
                    <div class="m-2">
                        <label for="" class="col-md-6"><?php echo $row['NombreCategoriaA'] ?></label>
                        <button class="btn btn-primary ver" id="<?php echo $row['idCategoriaAsiento']; ?>">Ver</button>
                    </div>
                <?php } $result->free_result(); ?>
            </div>
            <div class="col-md-8">
                <img src="img/2.PNG" alt="">
            </div>
        </div>
    </div>

    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        $('.ver').click(function (e) { 
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "tribunas.php",
                data: {id: $(this).attr('id')},
                success: function (response) {
                    window.location.href = "tribunas.php";
                }
            });
        });
    </script>
</body>
</html>
<?php } ?>