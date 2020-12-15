<?php
    $advertencia = "";
    session_start();
    if(isset($_SESSION['sesionUsuario'])){
        header('Location: tareas.php');
    }
    else{
        // Para iniciar sesión
        if ( isset($_POST["btnEnviar"]) )
        {
            require_once("scripts/funciones.php");
            $errores = 0; // contador de errores
            
           
            $usuario = $_POST["usuarioInput"];
            $clave =$_POST["claveInput"];
            
             $errorUsuario = ValidarComoCadena($usuario);
             $errores += $errorUsuario;
            
             $errorClave = ValidarComoCadena($clave);
            $errores += $errorClave;
            
            if ($errores == 0)
            {
    
                require_once("scripts/conexionPDO.php");
                try
                {
                    $contador = 0;
                    $sql = "SELECT * FROM tb_login WHERE usuario = :usuarioLogin";
    
                    $resultado = $db -> prepare($sql);
                    $resultado->execute(array(":usuarioLogin" => $usuario));
                    while($registro = $resultado ->fetch(PDO::FETCH_ASSOC))
                    {
                        // clave -> sin cifrar, la que introduce el usuario
                        if(password_verify($clave, $registro['clave']))
                        {
                            session_start();
                            $_SESSION['sesionUsuario'] = $registro['nombre'] . '-' . $registro['id'];
                            $contador++;
                        }
                    }
                    if($contador > 0)
                    {
                        header('Location: tareas.php');
                    }
                    else
                    {
                        $advertencia = "Usuario o contraseña incorrectos";
                    }
                    $resultado -> closeCursor();
                }
                catch (PDOException $e)
                {
                    $s = "<h3>Error!: " . $e->getMessage() . "</h3><br/>";
                    die($s);
                }
                
            }
        }
        else 
        {
            
            $campoUsuario = "";
            $campoClave = "";
            
            $errorUsuario = 0;
            $errorClave = 0;
        }
    }
      
?>




<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de tareas</title>

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
    <div class="container tasks-home">
        <div class="row col-12 mx-0 px-0">
            <div class="row col-md-6 tasks-row-principal tasks-home-background">
                <div class="col-12 mx-2 my-4 tasks-logo">
                    <img src="img/tasks-logo-dark.svg" alt="tasks logo">
                </div>
                <div class="rulito-tasks-home">
                    <img src="img/rulito-tasks.svg" alt="tasks rulito">
                </div>
                <div class="col-12 mx-2 mt-2 py-2 text-center text-white justify-content-center">
                    <h1 class="h3 px-2">La forma más simple de organizar tu día y tu tiempo.</h1>
                </div>
                <div class="col-12 mx-auto mt-2 p-4 d-flex justify-content-center tasks-home-img">
                    <img src="img/boy-tasks.png" alt="">
                </div>
            </div>
            <div class="row col-md-6 justify-content-center align-items-center tasks-row-login">
                <!-- Formulario para iniciar sesión -->
                <div class="col-12 col-lg-8 justify-content-center align-items-center text-white">
                    <h2 class="text-white">Iniciar sesión</h2>
                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']?>">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Usuario</label>
                            <input type="text" class="form-control" name="usuarioInput" id="usuarioInput"
                                placeholder="Ingrese usuario">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Contraseña</label>
                            <input type="password" class="form-control" name="claveInput" id="claveInput"
                                placeholder="Ingrese contraseña">
                        </div>
                        <div class="row px-3">
                            <button type="submit" name="btnEnviar" id="btnEnviar"
                                class="col-5 mr-3 btn btn-success">Acceder</button>
                            <a class="col-6 btn btn-primary" href="registro.php">Registrarse</a>
                        </div>
                        <p><?php echo $advertencia ?></p>
                    </form>
                </div>
                <footer class="footer-home">
                    <div class="container">
                        <div class="col-12 justify-content-center text-white tasks-login-footer">
                            <p>Copyright © 2020 | Creado por Camilo Sanchez y Gabriel Maza | Versión 1.0</p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <!-- End container -->
    </div>
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
