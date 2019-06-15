<?php
    require 'php/get.php';
    $get = new get();

    $result = $get->getSP("get_Sedes()");
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
        <h1 class="text-center m-5">Estadios</h1>
        <div class="row">
            <div class="col-md-12 d-flex flex-wrap justify-content-around">
               

              

                <?php while ($row = $result->fetch_array()) { ?>
                    <div class="card text-center" style="width: 18rem;">
                        <img src="data:image/jpeg; base64, <?php echo  base64_encode($row["imgSede"]); ?>" class="card-img-top estadio" id="<?php echo $row['idSede'] ?>" style="cursor:pointer">
                        <div class="card-body">
                            <h4 class="card-title"><?php echo $row['DireccionSede']; ?></h4>
                        </div>
                    </div>
                <?php } $result->free_result(); ?>
            </div>
        </div>
    </div>

    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        $('.estadio').click(function (e) { 
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "sede.php",
                data: {id: $(this).attr('id')},
                success: function (response) {
                    window.location.href = "sede.php";
                }
            });
            console.log($(this).attr('id'));
        });    
    </script>
</body>
</html>