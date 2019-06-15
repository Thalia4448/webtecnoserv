<?php
    session_start();
    if(isset($_POST['id'])) {
        $_SESSION['IdCategoria'] = $_POST['id'];
    } else {
        require 'php/get.php';
        $get = new get();

        $IdCategoria = $_SESSION['IdCategoria'];
        $IdSede = $_SESSION['IdSede'];
        $resultC = $get->getSP("get_CategoriaAsientoxidCategoriaAsiento(".$IdCategoria.")");
        while ($rowC = $resultC->fetch_array()) {
            $Categoria = $rowC['NombreCategoriaA'];
        }
        $resultC->free_result();
        $resultS = $get->getSP("get_SedexIdSede(".$IdSede.")");
        while ($rowS = $resultS->fetch_array()) {
            $Sede = $rowS['DireccionSede'];
        }
        $resultS->free_result();
        $result = $get->getSP("get_AsientosxIdCategoriaxIdSede(".$IdCategoria.",".$IdSede.")");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.2/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.2/build/css/themes/default.min.css"/>
    
    <link rel="stylesheet" href="css/paypal.css">
</head>
<body>
    
    <div class="container">
        <div class="row">
            <div class="col-md-6 mt-5">
                <div class="m-2">
                    <label class="text-white bg-success text-center p-2 col-md-3">Libre</label>
                    <label class="text-white bg-danger text-center p-2 col-md-3">Ocupado</label>
                    <label class="text-white bg-info text-center p-2 col-md-3">Seleccionado</label>
                </div>
            </div>
            <div class="col-md-12 mt-5 d-flex flex-wrap justify-content-around border" id="asientos">
                <?php while ($row = $result->fetch_array()) {
                    if($row['IdEstado'] == 1) {
                        $estado = "success";
                        $disabled = "";
                    } else {
                        $estado = "danger";
                        $disabled = "disabled";
                    }
                    
                    ?>
                    <button data-id="<?php echo $row['idAsiento']; ?>" data-precio="<?php echo $row['precio']; ?>" <?php echo $disabled; ?> style="width: 50px" class="btn btn-<?php echo $estado; ?> m-2 asientos"><?php echo $row['numero']; ?></button>
                <?php } $result->free_result(); ?>
            </div>
            <div class="card text-left mt-5 col-md-12">
                <div class="card-body">
                    <h4 class="card-title text-center">COMPRAR ENTRADA</h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-inline">
                                <div class="form-group col-md-6 offset-md-3 mb-2">
                                    <label for="Categoria" class="col-md-4">Categoria</label> 
                                    <input type="text" name="Categoria" id="Categoria" class="form-control col-md-8" value="<?php echo $Categoria; ?>" readonly >
                                </div>
                                <div class="form-group col-md-6 offset-md-3 mb-2">
                                    <label for="Sede" class="col-md-4">Sede</label> 
                                    <input type="text" name="Sede" id="Sede" class="form-control col-md-8" value="<?php echo $Sede; ?>" readonly>
                                </div>
                                <div class="form-group col-md-6 offset-md-3 mb-2">
                                    <label for="MontoTotal" class="col-md-4">Monto Total</label> 
                                    <input type="text" name="MontoTotal" id="MontoTotal" class="form-control col-md-8" value="S/. 0" readonly>
                                    <span id="TotalPago" hidden></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <h4 class="text-center">Asientos Seleccionados</h4>
                            <div class="asiento_r"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="paypal-button-container" class="mt-5"></div>
        </div>
        <!-- <button class="btn btn-primary" id="Enviar">Enviar</button> -->
    </div>
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/alertify.min.js"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=AZyceNz3mWPNGCkd2NjI3Dx3ul9Y54DgP76nbPn0U3tqFMjfsIBoPjn6wsL9o2XPfZfujqKzpisIL9EM&currency=USD"></script>
    <script>
        $('.asientos').click(function (e) { 
            e.preventDefault();
            if($(this).hasClass('btn-success')) {
                $(this).removeClass('btn-success');
                $(this).addClass('btn-info');
                var id = $(this).data('id');
                var precio = $(this).data('precio');
                var numero = $(this).text();
                var button = '<button id="'+id+'" data-precio="'+precio+'" disabled style="width: 50px" class="btn btn-warning m-2 asientos">'+numero+'</button>';
                $('.asiento_r').append(button);
            } else if($(this).hasClass('btn-info')) {
                $(this).removeClass('btn-info');
                $(this).addClass('btn-success');
                var id = $(this).data('id');
                $('#'+id).remove();
            }
            MontoTotal ();
        });

        function MontoTotal () {
            var MontoTotal = 0;
            $('.asiento_r button').each(function () {
                MontoTotal = MontoTotal + $(this).data('precio');
            });
            $('#MontoTotal').val('S/. '+MontoTotal);
            $('#TotalPago').text(MontoTotal * 0.329);
        }

        // $('#Enviar').click(function (e) { 
        //     e.preventDefault();
        //     var asientos = new Array(); 
        //     $('.asiento_r button').each(function () {
        //         asientos.push($(this).attr('id'));
        //     });
        //     // console.log(asientos);
        //     $.ajax({
        //         type: "POST",
        //         url: "php/comprar.php",
        //         data: {asientos: JSON.stringify(asientos)},
        //         success: function (response) {
        //             alert(response);
        //         }
        //     });
        // });

        // Render the PayPal button into #paypal-button-container
        paypal.Buttons({

            // Set up the transaction
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: $('#TotalPago').text()
                        }
                    }]
                });
            },

            // Finalize the transaction
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    ComprarAhora();
                    alertify.alert("Compra concluida con éxito", function(){
                        location.reload();
                    });
                
                    // Call your server to save the transaction
                    return fetch('/paypal-transaction-complete', {
                        method: 'post',
                        headers: {
                            'content-type': 'application/json'
                        },
                        body: JSON.stringify({
                            orderID: data.orderID
                        })
                    });
                });
            }

        }).render('#paypal-button-container');


        // Funcion para comprar
        function ComprarAhora() {
            var asientos = new Array(); 
            $('.asiento_r button').each(function () {
                asientos.push($(this).attr('id'));
            });
            
            $.ajax({
                type: "POST",
                url: "php/comprar.php",
                data: {asientos: JSON.stringify(asientos)},
                success: function (response) {
                }
            });
        }
    
    </script>
    
</body>
</html>

<?php } ?>