<?php
    session_start();
    // Si el usuario no inició sesión, se lo devuelve al inicio
    if(!isset($_SESSION['sesionUsuario'])){
        header('Location: index.php');
    }



    // Del inicio de sesión traigo el nombre de la sesión
    // Nombre de la sesión = nombreUsuario-idUsuario
    // Spliteo para tener un array de los dos valores
    $nombre = explode('-', $_SESSION['sesionUsuario']);


    require_once('scripts/accionesTareas.php');


    // ============================ //
    // ====== PARA MODIFICAR ====== //
    // ============================ //

    $tareaModificar;

    // Para crear la caja de modificación
    if(isset($_GET['idModificar']))
    {
        $tareaModificar = $_GET['idModificar'];
    }
    else
    {
        $tareaModificar = 0;
    }
    // Para ejecutar el UPDATE en el SQL
   
    if(isset($_POST['btnModificar']))
    {
        modificarTarea($_GET['idModificar'], true);
    }
    else 
    {
        $tareaCorregida = "";
        $errorTarea = 0;
    }

    // =====================================================//




    // ============================ //
    // ====== PARA INSERTAR ======= //
    // ============================ //
    $advertencia = "";
    if ( isset($_POST["btnNuevaTarea"]) )
    {
        $tarea = $_POST["tareaInput"];

        // Compruebo la extensión de las tareas a agregar 
        if(strlen($tarea) > 77 || strlen($tarea) < 2)
        {
            $advertencia = "Insertar una tarea de menos de 77 caracteres";
            
        }
        else
        {
            $advertencia = "";
            insertarTarea($nombre[1], $tarea);
        }
        
    }
 
    // ============================ //
    // ====== PARA ELIMINAR ======= //
    // ============================ //

    if(isset($_GET['idTarea']))
    {
        eliminarTarea($_GET['idTarea']);
    }

    // ============================= //
    // ====== PARA COMPLETAR ======= //
    // ============================= //
    if(isset($_GET['idCompletada']))
    {
        completarTarea($_GET['idCompletada']);
    }
    // ========================================== //
    // ====== PARA MARCAR 'EN PROGRESO' ========= //
    // ========================================== //
    if(isset($_GET['idEnProgreso']))
    {
        enProgresoTarea($_GET['idEnProgreso']);
    }

   // =============== CONTADORES ========================= //
    
    $porhacer = 0;
    $enprogreso = 0;
    $hecho = 0;

    // ============================ //
    // ===== PARA ACTUALIZAR ====== //
    // ============================ //

    $listaTareas = array();
    $idTareas = array();
    $tareaCompletada = array();
    require_once("scripts/conexionPDO.php");
    try
    {
    $sqlLista = "SELECT * FROM tb_tareas WHERE usuarioId = :usuarioId ORDER BY id DESC";
    $resultadoLista = $db -> prepare($sqlLista);
    $resultadoLista->execute(array(":usuarioId" => $nombre[1]));
   
    while($registro = $resultadoLista ->fetch(PDO::FETCH_ASSOC))
    {
        array_push($listaTareas, $registro['tarea']);
        array_push($idTareas, $registro['id']);
        array_push($tareaCompletada, $registro['completada']);
        switch($registro['completada'])
        {
            case 0:
                $porhacer++;
            break;
            case 1:
                $hecho++;
            break;
            case 2:
                $enprogreso++;
            break;
        }
        
    }
    $resultadoLista -> closeCursor();
    }
    catch (PDOException $e)
    {
        $s = "<h3>Error!: " . $e->getMessage() . "</h3><br/>";
        die($s);
    }
 

?>


<!DOCTYPE html>
<!-- PARA ELIMINAR UNA TAREA -->
<?php


    ?>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de tareas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"
        integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA=="
        crossorigin="anonymous" />
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
    <header>
        <nav class="navbar navbar-expand tasks-app-navbar">
            <a class="navbar-brand" href="./index.php">
                <img src="img/tasks-logo-dark.svg" alt="tasks logo" width="130" height="30">
            </a>
            <ul class="navbar-nav ml-auto hidden-sm-up">
                <li class="nav-item">
                    <a class="nav-link text-white" href="scripts/descargaTareas.php?idUsuario=<?php echo $nombre[1] ?>"
                        target="_blank"><i class="fas fa-download" title="Descargar en PDF"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="scripts/cerrarSesion.php" title="Cerrar Sesión"><i
                            class="fas fa-user-slash"></i></a>
                </li>
            </ul>
        </nav>
    </header>
    <div class="tasks-app-header">
        <div class="tasks-tittle-header col-12">
            <h1 class="h3">Tareas de <?php echo $nombre[0]; ?></h1>
        </div>
        <div class="row col-12 tasks-tittle-data align-items-center mx-auto">
            <div class="col-4 px-0 tasks-tittle-d-item">
                <p class="h2"><?php echo  sprintf('%02d', $porhacer); ?></p>
                <p class="tasks-tittle-p"><i class="fas fa-exclamation mr-2"></i>Por hacer</p>
            </div>
            <div class="col-4 px-0 tasks-tittle-d-item">
                <p class="h2"><?php echo sprintf('%02d', $enprogreso); ?></p>
                <p class="tasks-tittle-p"><i class="fas fa-check mr-2"></i>En progreso</p>
            </div>
            <div class="col-4 px-0 tasks-tittle-d-item">
                <p class="h2"><?php echo sprintf('%02d', $hecho); ?></p>
                <p class="tasks-tittle-p"><i class="fas fa-check-double mr-2"></i>Hecho</p>
            </div>
        </div>
    </div>
    <div class="container">
        <!-- Formulario para añadir nueva tarea -->
        <div class="row col-md-6 justify-content-center align-items-center mx-auto">
            <div id="formulario">
                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']?>">
                    <div class="form-group">
                        <input type="text" class="form-control" name="tareaInput" id="tareaInput"
                            placeholder="Nueva tarea">
                    </div>
                    <button type="submit" name="btnNuevaTarea" class="btn btn-success">Añadir</button>
                </form>
                <p><?php echo $advertencia ?></p>
            </div>
        </div>

        <!-- Listado de tareas -->
        <div class="col-md-6 mx-auto px-0" id="tareas">
            <!-- Tarea 1 -->
            <?php

            foreach ($idTareas as $tareaId) {
               $numIndexTarea = array_search($tareaId, $idTareas);
                // $tareaEstilo =  ($tareaCompletada[$numIndexTarea] == 0) ? 'tarea': 'tarea completada';
                switch($tareaCompletada[$numIndexTarea])
                {
                    case 0:
                        $tareaEstilo = 'tarea';
                    break;
                    case 1:
                        $tareaEstilo = 'tarea completada';
                    break;
                    case 2:
                        $tareaEstilo = 'tarea enprogreso';
                    break;
                }
                echo '<div class="' .$tareaEstilo.'">';
                    
                    //Trash
                    echo '<div class="col-12 m-0 pr-2 d-flex justify-content-end">';
                    echo '<a href="tareas.php?idTarea=' . $tareaId . '" class="trash-item"><i class="fas fa-times" title="Eliminar"></i></a>';
                    echo '</div>';
                    // texto de la tarea
                    echo '<div class="col-12 pb-1 texto-tarea">';
                    echo '<p>' . $listaTareas[$numIndexTarea] . '</p>';
                    echo '</div>';
                    // botones
                    echo '<div class="d-flex col-12 justify-content-end">';
                    echo '<a href="tareas.php?idModificar=' . $tareaId . '" class="btn btn-personalizado" title="Modificar"><i class="fas fa-edit"></i></a>';
                    echo '<a href="tareas.php?idEnProgreso=' . $tareaId . '" class="btn btn-personalizado" title="En progreso"><i class="fas fa-check"></i></a>';
                    echo '<a href="tareas.php?idCompletada=' . $tareaId . '" class="mr-0 btn btn-personalizado" title="Completada"><i class="fas fa-check-double"></i></a>';
                    echo '</div>';
                    // end tarea
                    echo '</div>';       
                    if($idTareas[$numIndexTarea] == $tareaModificar)
                    {
                        echo '<div class="modificarTarea"><form method="POST" action="' . $_SERVER['PHP_SELF'] .'?idModificar=' . $tareaId . '">';
                        echo '<div class="form-group">';
                        echo '<input type="text" class="form-control" name="textoModificar" id="textoModificar" value="' . $listaTareas[$numIndexTarea] . '">';
                        echo '</div>';
                        echo '<button name="btnModificar" class="btn btn-success">Modificar</button>';
                    echo '</form></div>';
                    }
                    // 92 caracteres límite de tarea
            
            }
        ?>

            <!-- End lista tareas -->
        </div>
        <div class="navegacion-tareas justify-content-center align-items-center">
        </div>
        <!-- End container -->
    </div>
    <nav class="navbar navbar-light col-12 tasks-b-nav hidden-sm-down">
        <div class="b-nav-item justify-content-center align-items-center text-center">
            <a href="scripts/cerrarSesion.php" class="b-nav-item-a">
                <div class="flex text-center fbox-icon">
                    <i class="fas fa-user-slash"></i>
                    <p class="nav-item-p">Cerrar sesión</p>
                </div>
            </a>
        </div>
        <div class="b-nav-item">
            <a href="scripts/descargaTareas.php?idUsuario=<?php echo $nombre[1] ?>" target="_blank" class="b-nav-item-a"
                title="Descargar PDF">
                <div class="flex text-center fbox-icon">
                    <i class="fas fa-download"></i>
                    <p class="nav-item-p">Descargar PDF</p>
                </div>
            </a>
        </div>
    </nav>
    <footer class="footer-tareas">
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