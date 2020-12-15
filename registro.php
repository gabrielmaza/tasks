<?php
    $advertencia = "";
    session_start();
    // Redirecciono a la lista de tareas si el usuario ya está loggeado
    if(isset($_SESSION['sesionUsuario'])){
        header('Location: tareas.php');
    }
    else{
        if ( isset($_POST["btnEnviar"]) )
        {
            require_once("scripts/funciones.php");
            $campoNombre = $_POST["nombreInput"];
            $campoUsuario = $_POST["usuarioInput"];
            $campoClave = $_POST["claveInput"];
            $claveCifrada = password_hash($campoClave, PASSWORD_DEFAULT);
            
            $errores = 0; // contador de errores
            $errorUsuario = ValidarComoCadena($campoUsuario);
            $errores += $errorUsuario;
            
            $errorClave = ValidarComoCadena($campoClave);
            $errores += $errorClave;
            
            $errorNombre = ValidarComoCadena($campoNombre);
            $errores += $errorNombre;

            // Compruebo que el usuario elegido no esté registrado
            $usuarioRepetido = false;
            require_once('scripts/conexionPDO.php');
            $sqlComprobacion = "SELECT * FROM tb_login WHERE usuario = :usuarioLogin";
    
            $resultadoCompr = $db -> prepare($sqlComprobacion);
            $resultadoCompr->execute(array(":usuarioLogin" => $campoUsuario));
            while($registro = $resultadoCompr ->fetch(PDO::FETCH_ASSOC))
            {
                // clave -> sin cifrar, la que introduce el usuario
                if($registro['usuario'] == $campoUsuario)
                {
                    $usuarioRepetido = true;
                }
            }
            if($usuarioRepetido)
            {
                // Aviso que está repetido
                $advertencia = "El usuario elegido ya se encuentra en uso";
            }
            else
            {
                if ($errores == 0)
                {
                try{
                    require_once('scripts/conexionPDO.php');

                    $sql = "INSERT INTO tb_login (nombre, usuario, clave) VALUES (:nomb, :usu, :contra)";
                    $resultado = $db -> prepare($sql);
                    $resultado->execute(array(":nomb" => $campoNombre, ":usu" => $campoUsuario, ":contra" => $claveCifrada));

                    // logeo al usuario creado
                    $contador = 0;
                    $sqlLogeo = "SELECT * FROM tb_login WHERE usuario = :usuarioLogin";
        
                        $resultado = $db -> prepare($sqlLogeo);
                        $resultado->execute(array(":usuarioLogin" => $campoUsuario));
                        while($registro = $resultado ->fetch(PDO::FETCH_ASSOC))
                        {
                            // clave -> sin cifrar, la que introduce el usuario
                            if(password_verify($campoClave, $registro['clave']))
                            {
                                
                            $_SESSION['sesionUsuario'] = $registro['nombre'] . '-' . $registro['id'];
                                $contador++;
                            }
                        }
                    // Redirecciono al usuario a la lista de tareas
                    header('Location: tareas.php');
                    $campoUsuario = "";
                    $campoClave = "";
                    $campoNombre = "";

                    $errorUsuario = 0;
                    $errorClave = 0;
                    $errorNombre = 0;
        
                } catch (Exception $e){
                    echo "Linea del error: " . $e->getLine();
                }
                    // Termina el IF
                }
                else{
                    // Error > 0
                    $advertencia = "Datos inválidos, intente nuevamente.";
                }
            }
            
        }
        else 
        {
            
            $campoUsuario = "";
            $campoClave = "";
            $campoNombre = "";

            $errorUsuario = 0;
            $errorClave = 0;
            $errorNombre = 0;
        }
    }

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <!-- Hoja de estilos Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <!-- Hoja de estilos personalizada -->
    <link rel="stylesheet" href="style.css">
    <!-- Fuente Roboto -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="img/favicon.png">
</head>

<body>
    <div class="container">
        <div class="col-12 mx-2 my-4 tasks-logo">
            <a href="./index.php"><img src="img/tasks-logo.svg" alt="tasks logo"></a>
        </div>
        <!-- Formulario para iniciar sesión -->
        <div id="form-iniciarsesion" class="col-md-6 mx-auto">
            <h1 class="h2 row col-12">Regístrate</h1>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']?>">
                <div class="form-group">
                    <div class="form-group">
                        <label for="nombreInput">Nombre</label>
                        <input type="text" class="form-control" name="nombreInput" id="nombreInput"
                            placeholder="Ingrese nombre">
                    </div>
                    <div class="form-group">
                        <label for="usuarioInput">Usuario</label>
                        <input type="text" class="form-control" name="usuarioInput" id="usuarioInput"
                            placeholder="Ingrese usuario">
                    </div>
                    <div class="form-group">
                        <label for="claveInput">Contraseña</label>
                        <input type="password" class="form-control" name="claveInput" id="claveInput"
                            placeholder="Ingrese contraseña">
                    </div>
                    <button type="submit" name="btnEnviar" id="btnEnviar" class="btn btn-success col-12">Enviar</button>
                    <p><?php echo $advertencia ?></p>
            </form>
            <div class="row mt-2 justify-content-center align-items-center mx-auto navegacion">
                <a href="./index.php">Volver al inicio</a>
            </div>
        </div>
        <!-- End container -->
    </div>
    <footer class="footer-home">
        <div class="container">
            <div class="col-12 justify-content-center tasks-login-footer">
                <p>Copyright © 2020 | Creado por Camilo Sanchez y Gabriel Maza | Versión 1.0</p>
            </div>
        </div>
    </footer>
    <!-- Js Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"
        integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous">
    </script>
</body>

</html>