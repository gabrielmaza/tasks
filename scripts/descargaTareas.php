<?php


// =================== //
// == OBTENGO DATOS == //
// =================== //
$idUsuario = $_GET['idUsuario'];
$nombreUsuario;

$listaTareas = array();
$tareaCompletada = array();

require_once("conexionPDO.php");
    try
    {
    // CONSULTA A LA TABLA LOGIN
    $sqlLogin = "SELECT * FROM tb_login WHERE id = :usuarioId";
    $resultadoLogin = $db -> prepare($sqlLogin);
    $resultadoLogin->execute(array(":usuarioId" => $idUsuario));
   
    while($registro = $resultadoLogin ->fetch(PDO::FETCH_ASSOC))
    {
        $nombreUsuario = $registro['nombre'];
        
    }
    $resultadoLogin -> closeCursor();
    // CONSULTA A LA TABLA TAREAS
    $sqlLista = "SELECT * FROM tb_tareas WHERE usuarioId = :usuarioId";
    $resultadoLista = $db -> prepare($sqlLista);
    $resultadoLista->execute(array(":usuarioId" => $idUsuario));
   
    while($registro = $resultadoLista ->fetch(PDO::FETCH_ASSOC))
    {
        array_push($listaTareas, $registro['tarea']);
        array_push($tareaCompletada, $registro['completada']);
        
    }
    $resultadoLista -> closeCursor();
    }
    catch (PDOException $e)
    {
        $s = "<h3>Error!: " . $e->getMessage() . "</h3><br/>";
        die($s);
    }

// ================ //
// == GENERO PDF=== //
// ================ //

require('pdfBasico/cmpfpdf17/fpdf.php');
class PDF extends FPDF
{
    function Header()
    {
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Movernos a la derecha
        $this->Cell(80);
        // Título
        $tituloPDF = 'Tareas de ' . $GLOBALS['nombreUsuario'];
        $this->Cell(30,10, $tituloPDF, 0, 0, 'C');
        // Salto de línea
        $this->Ln(20);
        
    }
}
$fpdf = new PDF();

$fpdf -> AddPage();
$fpdf -> SetFont('Arial', '', 12);
$fpdf -> Cell(10, 5, '#');
$fpdf -> Cell(150, 5, 'Tarea');
$fpdf -> Cell(30, 5, 'Completada');
$fpdf -> Ln(10);

// Contador tareas
$item = 0;

foreach($listaTareas as $tarea)
{
    $item++;
    $numIndexTarea = array_search($tarea, $listaTareas);
    switch($tareaCompletada[$numIndexTarea])
    {
        case 0:
            $completada = 'NO';
        break;
        case 1:
            $completada = 'SI';
        break;
        case 2:
            $completada = 'EN PROGRESO';
        break;
    }
    $tareaCaracteres = iconv('UTF-8', 'windows-1252', $tarea);
    $fpdf -> Cell(10, 5, $item);
    $fpdf -> Cell(150, 5, $tareaCaracteres);
    $fpdf -> Cell(30, 5, $completada);
    $fpdf -> Ln(10);
}
$fpdf -> Output();

?>