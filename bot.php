<?php
require_once 'bbddbecaria.php';
$botToken = "token telegram";
$rutaweb = "ruta externa http de la web";
$website = "https://api.telegram.org/bot".$botToken;

$update = file_get_contents('php://input');
$update = json_decode($update, TRUE);
$modo = 0;

$chatId = $update["message"]["chat"]["id"];
$chatType = $update["message"]["chat"]["type"];
$userId = $update["message"]['from']['id'];
$firstname = $update["message"]['from']['username'];
if ($firstname=="") {
	$modo=1;
	$firstname = $update["message"]['from']['first_name'];
}

if ($modo == 0) {
	$firstname = "@".$firstname;
}

$message = $update["message"]["text"];
$idmensaje = $update["message"]["message_id"];
$agg = json_encode($update, JSON_PRETTY_PRINT);




//Extraemos el Comando
$arr = explode(' ',trim($message));
$command = $arr[0];

$messagesinprimera = substr(strstr($message," "), 1);

//No requieren variables del usuario.

//Es la primera palabra
/*$response = "El comando es ".$command ;
sendMessage($chatId, $response);

//Es todo
*/
/*$response = "El message es ".$message ;
sendMessage($chatId, $response);*/
/*

//Son las palabras a partir de la segunda
$response = "El mensajes sin la primera palabra es ".$messagesinprimera ;
sendMessage($chatId, $response);*/

//$command es la primera palabra
/*$response = "La posicion de LCD es ".strpos(strtolower($message),"lcd");
sendMessage($chatId, $response);*/

//Detectar palabra y lanzar un gif y un mensaje.
//En una base de datos pondremos muchas palabras.
//respondepalabras($message,$chatId,$rutaweb);

$encontrado = False;

if(strpos(strtolower(eliminar_tildes($message)),strtolower(eliminar_tildes('becaria')))!==false)
{
	//Se trata de un comando becaria
	$encontrado = respondecomandosbecaria($message,$chatId,$rutaweb);
}

if(!$encontrado)
{
	$encontrado = respondepalabrasconjunto($message,$chatId,$rutaweb);
}

if(!$encontrado)
{
	$encontrado = responran($message,$chatId,$rutaweb);
}

if(!$encontrado)
{
	//mostrar un Video
	if(strpos(strtolower($message),"/rompe lcd")!==false)
	{
			//sendVideo($chatId, "https://www.youtube.com/watch?v=YOI9pXSySR4");
			sendMessage($chatId, "https://www.youtube.com/watch?v=YOI9pXSySR4");
	}


	if(strpos(strtolower($message),"/hora")!==false)
	{
		 $hoy = getdate();
		 $response = "Son las ".$hoy[hours]." y ".$hoy[minutes];

		 sendMessagerespuesta($chatId, $response,$idmensaje);
	}



	//Responder a una palabrota. Podremos poner una lista.
	if(strpos(strtolower($message),"/hijoputa")!==false)
	{

		$response = "Eso lo serás tu.";
		sendMessagerespuesta($chatId, $response,$idmensaje);
	}

	//Mandar un video cuando capte una palabra
	/*if(strpos(strtolower($message),"cracker")!==false)
	{
		sendVideo($chatId, "http://www.mortaca.com/becariabot/resources/kraker.mp4");
	}*/

}




function mostrarvideorandom($chatId,$rutaweb)
{

	$directorio = './random';
	$ficheros  = scandir($directorio);
  $cont=0;





	foreach($ficheros as $fichero)
	{
		if($fichero!=="." && $fichero!=="..")
		{
			$archivos[$cont]=$fichero;
			$cont++;
		}
	}

	$elegido = rand(0,count($archivos)-1);

	sendVideo($chatId,$rutaweb.$archivos[$elegido]);

}

function responran($message,$chatId,$rutaweb)
{

			$palabrasbd = leerandom();
			$salran = False;


			while(!$salran)
			{
				if($fila = mysqli_fetch_assoc($palabrasbd)){
							extract($fila);
							$palabrautf = utf8_encode($palabra);
							$conjunto = explode(' ',trim($palabrautf));



							$sal = False;
							$cont = 0;
							$todas = False;



							if(count($conjunto)>0)
							{
								while(!$sal)
								{

									if($cont<count($conjunto))
									{

											if(strpos(strtolower(eliminar_tildes($message)),strtolower(eliminar_tildes($conjunto[$cont])))!==false)
											{

												$cont++;

											}
											else {
												$sal =True;
											}
									}
									else
									{

										$sal = True;
										$todas = True;
									}
								}
							}

							if($todas)
							{
									mostrarvideorandom($chatId,$rutaweb);
									$salran = True;
							}
				}
				else {

					$salran = True;
				}
			}


}

function respondecomandosbecaria($message,$chatId,$rutaweb)
{
	$palabrasbd = leecomandosbecaria();
  $salbec = False;
  $encontrado = False;
	while(!$salbec)
	{
		if($fila = mysqli_fetch_assoc($palabrasbd))
		{
			extract ($fila);
			$palabrautf = utf8_encode($palabra);
			$respuestautf = utf8_encode($respuesta);

			$conjunto = explode(' ',trim($palabrautf));

			$sal = False;
			$cont = 0;
			$todas = False;



			if(count($conjunto)>0)
			{
				while(!$sal)
				{

					if($cont<count($conjunto))
					{

							if(strpos(strtolower(eliminar_tildes($message)),strtolower(eliminar_tildes($conjunto[$cont])))!==false)
							{

								$cont++;

							}
							else {
								$sal =True;
							}
					}
					else
					{

						$sal = True;
						$todas = True;
					}
				}
			}

			if($todas)
			{
				$salbec = True;
				$encontrado = True;
				if($respuestautf!=="")
				{
					sendMessage($chatId, $respuestautf);
				}
				if($documento!=="")
				{
						$rutadocumento = $rutaweb.$documento;
						sendDocument($chatId, $rutadocumento);
				}
			}
		}
		else {
			$salbec = True;
		}
	}
	return $encontrado;
}

function respondepalabrasconjunto($message,$chatId,$rutaweb)
{
	$palabrasbd = leepalabras();
	$salbec = False;
	$encontrado = False;
	while(!$salbec)
	{
		if($fila = mysqli_fetch_assoc($palabrasbd))
		{
			extract ($fila);
			$palabrautf = utf8_encode($palabra);
			$respuestautf = utf8_encode($respuesta);

			$conjunto = explode(' ',trim($palabrautf));


			$sal = False;
			$cont = 0;
			$todas = False;



			if(count($conjunto)>0)
			{
				while(!$sal)
				{

					if($cont<count($conjunto))
					{

							if(strpos(strtolower(eliminar_tildes($message)),strtolower(eliminar_tildes($conjunto[$cont])))!==false)
							{

								$cont++;

							}
							else {
								$sal =True;
							}
					}
					else
					{

						$sal = True;
						$todas = True;
					}
				}
			}

			if($todas)
			{
				$salbec = True;
				$encontrado = True;
				if($respuestautf!=="")
				{
					sendMessage($chatId, $respuestautf);
				}
				if($imagen!=="")
				{
						$rutaimagen = $rutaweb.$imagen;
						sendVideo($chatId, $rutaimagen);
				}
			}
		}
		else {
				$salbec = True;
		}
	}
	return $encontrado;
}



function respondepalabras($message,$chatId,$rutaweb)
{
	$palabrasbd = leepalabras();
	while ($fila = mysqli_fetch_assoc($palabrasbd)) {
			extract ($fila);
			$palabrautf = utf8_encode($palabra);
			$respuestautf = utf8_encode($respuesta);

			if(strpos(strtolower(eliminar_tildes($message)),strtolower(eliminar_tildes($palabrautf)))!==false)
		  {
					if($respuestautf!=="")
					{
						sendMessage($chatId, $respuestautf);
					}
			    if($imagen!=="")
					{
							$rutaimagen = $rutaweb.$imagen;
							sendVideo($chatId, $rutaimagen);
					}
		  }
	}
}

function sendVideo($chatId,$archivo)
{
	$url = $GLOBALS[website].'/sendVideo?chat_id='.$chatId.'&video='.urlencode($archivo);
	file_get_contents($url);
}

function sendDocument($chatId,$archivo)
{
	$url = $GLOBALS[website].'/sendDocument?chat_id='.$chatId.'&document='.urlencode($archivo);
	file_get_contents($url);
}

function sendMessagerespuesta($chatId,$response,$idmensaje,$keyboard = NULL)
{
	if (isset($keyboard)) {
		$teclado = '&reply_markup={"keyboard":['.$keyboard.'], "resize_keyboard":true, "one_time_keyboard":true}';
	}
	$url = $GLOBALS[website].'/sendMessage?chat_id='.$chatId.'&reply_to_message='.$idmensaje.'&parse_mode=HTML&text='.urlencode($response).$teclado;
	file_get_contents($url);
}

function sendMessage($chatId, $response, $keyboard = NULL){
	if (isset($keyboard)) {
		$teclado = '&reply_markup={"keyboard":['.$keyboard.'], "resize_keyboard":true, "one_time_keyboard":true}';
	}
	$url = $GLOBALS[website].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response).$teclado;
	file_get_contents($url);
}

function getNoticias($chatId){

	//include("simple_html_dom.php");

	$context = stream_context_create(array('http' =>  array('header' => 'Accept: application/xml')));
	$url = "http://www.europapress.es/rss/rss.aspx";

	$xmlstring = file_get_contents($url, false, $context);

	$xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
	$json = json_encode($xml);
	$array = json_decode($json, TRUE);

	for ($i=0; $i < 9; $i++) {
		$titulos = $titulos."\n\n".$array['channel']['item'][$i]['title']."<a href='".$array['channel']['item'][$i]['link']."'> +info</a>";
	}

	sendMessage($chatId, $titulos);
}

function eliminar_tildes($cadena){

    //Codificamos la cadena en formato utf8 en caso de que nos de errores
  //  $cadena = utf8_encode($cadena);

    //Ahora reemplazamos las letras
    $cadena = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $cadena
    );

    $cadena = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $cadena );

    $cadena = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $cadena );

    $cadena = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $cadena );

    $cadena = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $cadena );

    $cadena = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C'),
        $cadena
    );

    return $cadena;
}

?>
