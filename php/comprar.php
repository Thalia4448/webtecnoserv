<?php
    session_start();
    require 'get.php';

    $con = new Connection();
    $cmd = $con->getConnection();

    $IdSede = $_SESSION['IdSede'];
    $ArrayAsientos = json_decode($_POST['asientos']);

    for($i = 0; $i <count($ArrayAsientos); $i++) {
        do {
            $res = 0;
            $asiento = $ArrayAsientos[$i];
            $resp = $cmd->query("CALL set_CompraEntrada (null, '$IdSede', null, null, null, '$asiento', null)");

            if($resp) {
                $res = 1;
            }
            
        } while($res == 0);
    }
    // echo var_dump($ArrayAsientos);

?>