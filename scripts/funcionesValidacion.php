<?php


function ValidarComoCadenaGenerica($cadena)
{
    $resultado = 1;
    
    if ( preg_match('/^[A-Z a-z]{2,}/', $cadena) )
    {
        $resultado = 0;
    }
    
    return $resultado;
}


function ValidarComoCadena($cadena)
{
    $resultado = 1;
    
    if ( preg_match('/^[A-Z a-z 0-9]{2,}/', $cadena) )
    {
        $resultado = 0;
    }
    
    return $resultado;
}


function eliminarEspaciosEnBlanco($cadena)
{
	return preg_replace("/\s+/", " ", $cadena);
}
	


function eliminarTodosEspaciosEnBlanco($cadena)
{
	return preg_replace("/\s+/", "", $cadena);
}



function claveSegura($clave)
{

	$regexMayusculas = "/[A-Z]{1,}/";
	$regexMinusculas = "/[a-z]{1,}/";
	$regexNumeros = "/[0-9]{1,}/";

	$mayusculas = false;
	$minusculas = false;
	$numeros = false;

	if ( preg_match( $regexMayusculas, $clave ) ) { $mayusculas = true; }
	if ( preg_match( $regexMinusculas, $clave ) ) { $minusculas = true; }
	if ( preg_match( $regexNumeros, $clave ) ) { $numeros = true; }

	if ( ($mayusculas && $minusculas && $numeros) && ( strlen($clave) >= 8 ) && ( strlen($clave) <= 15 ) )
	{
		return true;
	}
	else
	{
		return false;
	}

}


function validarFecha($fecha)
{
	
	$regexFecha_ddmmaaaa = "~^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$~";
	
	if ( preg_match($regexFecha_ddmmaaaa, $fecha) )
	{
		$fechaValida = true;
	}
	else
	{
		$fechaValida = false;
	}
	
	return $fechaValida;

}


function validarEntero($cadena)
{

	$regexENTERO = "/^[0-9]{1,8}$/";
		
	if ( preg_match($regexENTERO, $cadena ) )
	{
		$enteroValido = true;
	}
	else
	{
		$enteroValido = false;
	}

	return $enteroValido;
	
}


function validarMoneda($cadena)
{

	$regexMoneda = "/^\d{1,4}(\.\d\d)?$/";
	
	if ( preg_match($regexMoneda, $cadena) )
	{
		$monedaValida = true;
	}
	else
	{
		$monedaValida = false;
	}
	
	
	return $monedaValida;
}


function validarFloat($cadena)
{

	$regexFloat = "/^\d{1,10}(\.\d{0,6})?$/";
	
	if ( preg_match($regexFloat, $cadena) )
	{
		$floatValido = true;
	}
	else
	{
		$floatValido = false;
	}
	
	
	return $floatValido;
}


function validarDomicilio($cadena)
{
	
	$regexDomicilio = "/^[������������0-9a-zA-Z\s\'\.\-]{2,}$/";
		
	if ( preg_match($regexDomicilio, $cadena) )
	{
		$domicilioValido = true;
	}
	else
	{
		$domicilioValido = false;
	}
	
	return $domicilioValido;
	
}


function validarNombrePropio($cadena)
{
	
	$regexNombrePropio = "/^[������������0-9a-zA-Z\s\'\.\-]{2,}$/";
		
	if ( preg_match($regexNombrePropio, $cadena) )
	{
		$nombrePropioValido = true;
	}
	else
	{
		$nombrePropioValido = false;
	}
	
	return $nombrePropioValido;
	
}



function validarEmail($cadena)
{

	$emailValido = filter_var($cadena, FILTER_VALIDATE_EMAIL);
	
	return $emailValido;

}


function validarMatricula($cadena)
{
	
	$regexMatricula = "/^[A-Z]{3}[0-9]{3}$/";

	if ( preg_match($regexMatricula, $cadena) )
	{
		$matriculaValida = true;
	}
	else
	{
		$matriculaValida = false;
	}
	
	return $matriculaValida;
	
}	


function validarMatriculaNueva($cadena)
{ 
	return ( preg_match("/^[A-Z]{2}[0-9]{3}[A-Z]{2}$/", $cadena) ); 
}	



?>