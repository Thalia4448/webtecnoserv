<?php
    class Connection {
        // Conección local
        // var $_Server = 'localhost';
        // var $_UserName = 'root';
        // var $_UserPass = '';
        // var $_DataBase = 'bdtecnoserv';

        // Conección web
        var $_Server = 'localhost';
        var $_UserName = 'root';
        var $_UserPass = '';
        var $_DataBase = 'bd2';

        function getConnection() {
            if (!($conexion = new mysqli($this -> _Server, $this -> _UserName, $this -> _UserPass, $this -> _DataBase)))
            {
                echo "Error Conectando la base de Datos";
                exit();
            }
            return $conexion;
        }
    }
?>