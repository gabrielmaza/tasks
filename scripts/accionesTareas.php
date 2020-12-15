<?php 
    // ============================ //
    // ======= PARA AGREGAR ======= //
    // ============================ //

    function insertarTarea($id, $tareaInput)
    {
        
        require_once("funciones.php");
        $errores = 0; // contador de errores
        
        $errorTarea = ValidarComoCadena($tareaInput);
        $errores += $errorTarea;
        
        
        if ($errores == 0)
        {
            require_once("conexionPDO.php");
            try
            {
                
                $contador = 0;
                $sql = "INSERT INTO tb_tareas (usuarioId, tarea, completada) VALUES (:userId, :nuevaTarea, :comp)";
                $resultado = $db -> prepare($sql);
                $resultado->execute(array(":userId" => $id, ":nuevaTarea" => $tareaInput, ":comp" => 0));
                $errorTarea = 0;
                // vacío btn
                
                $resultado -> closeCursor();
               
                header('Location: tareas.php');
            }
            catch (PDOException $e)
            {
                $s = "<h3>Error!: " . $e->getMessage() . "</h3><br/>";
                die($s);
            }
            
        }

    }

    

    // ============================ //
    // ====== PARA MODIFICAR ====== //
    // ============================ //

    function modificarTarea($idTarea, $btnPresionado){
        if($btnPresionado)
        {
            $tareaModificar = $idTarea;
            require_once("scripts/funciones.php");
            $errores = 0; // contador de errores
            $tareaCorregida = $_POST["textoModificar"];
            $errorTareaCorregida = ValidarComoCadena($tareaCorregida);
            $errores += $errorTareaCorregida;
            if ($errores == 0)
            {

                require_once("scripts/conexionPDO.php");
                try
                {
                    
                    $contador = 0;
                    $sql = "UPDATE tb_tareas SET tarea = :tareaModificada WHERE id = :idTarea";
                    $resultado = $db -> prepare($sql);
                    $resultado->execute(array(":tareaModificada" => $tareaCorregida, ":idTarea" => $tareaModificar));
                    $tareaCorregida = "";
                    $errorTarea = 0;
                    // vacío btn
                    
                    $resultado -> closeCursor();
                
                    header('Location: tareas.php');
                }
                catch (PDOException $e)
                {
                    $s = "<h3>Error!: " . $e->getMessage() . "</h3><br/>";
                    die($s);
                }
                
            }
        }
    }
    // ====================================== //


    // ============================ //
    // ====== PARA ELIMINAR ======= //
    // ============================ //

  
    function eliminarTarea($idTarea){
        require_once("scripts/conexionPDO.php");
        try
        {
        $sqlEliminar = "DELETE FROM tb_tareas WHERE id = :idTarea";
        $resultadoLista = $db -> prepare($sqlEliminar);
        $resultadoLista->execute(array(":idTarea" => $idTarea));
        $resultadoLista -> closeCursor();
        }
        catch (PDOException $e)
        {
            $s = "<h3>Error!: " . $e->getMessage() . "</h3><br/>";
            die($s);
        }
        // Actualizo página
        header('Location: tareas.php');
    }

        // ====================================== //

        // ============================= //
        // ====== PARA COMPLETAR ======= //
        // ============================= //

        function completarTarea($idTarea){
            require_once("scripts/conexionPDO.php");
            try
            {
                //
                $tareaCompletada;
                $sqlLista = "SELECT completada FROM tb_tareas WHERE id = :idTarea";
                $resultadoLista = $db -> prepare($sqlLista);
                $resultadoLista->execute(array(":idTarea" => $idTarea));
               
                while($registro = $resultadoLista ->fetch(PDO::FETCH_ASSOC))
                {
                    
                    if($registro['completada'] != 1)
                    {
                        $tareaCompletada = 1;
                    }
                    else
                    {
                        $tareaCompletada = 0;
                    }
                }
                $resultadoLista -> closeCursor();

                //

                $sql = "UPDATE tb_tareas SET completada = :tareaCompletada WHERE id = :idTarea";
                $resultado = $db -> prepare($sql);
                $resultado->execute(array(":tareaCompletada" => $tareaCompletada, ":idTarea" => $idTarea));
                $resultado -> closeCursor();
            
                header('Location: tareas.php');
            }
            catch (PDOException $e)
            {
                $s = "<h3>Error!: " . $e->getMessage() . "</h3><br/>";
                die($s);
            }
        }

    // ========================================== //
    // ====== PARA MARCAR 'EN PROGRESO' ========= //
    // ========================================== //

        function enProgresoTarea($idTarea){
            require_once("scripts/conexionPDO.php");
            try
            {
                //
                $tareaEnProgreso;
                $sqlLista = "SELECT completada FROM tb_tareas WHERE id = :idTarea";
                $resultadoLista = $db -> prepare($sqlLista);
                $resultadoLista->execute(array(":idTarea" => $idTarea));
               
                while($registro = $resultadoLista ->fetch(PDO::FETCH_ASSOC))
                {
                    
                    if($registro['completada'] != 2)
                    {
                        // En progreso...
                        $tareaEnProgreso = 2;
                    }
                    else
                    {
                        // Sin completar
                        $tareaEnProgreso = 0;
                    }
                }
                $resultadoLista -> closeCursor();

                //

                $sql = "UPDATE tb_tareas SET completada = :tareaCompletada WHERE id = :idTarea";
                $resultado = $db -> prepare($sql);
                $resultado->execute(array(":tareaCompletada" => $tareaEnProgreso, ":idTarea" => $idTarea));
                $resultado -> closeCursor();
            
                header('Location: tareas.php');
            }
            catch (PDOException $e)
            {
                $s = "<h3>Error!: " . $e->getMessage() . "</h3><br/>";
                die($s);
            }
        }
    ?>