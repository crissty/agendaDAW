
<?php
	#Es necesario la creacion de un archivo llamado 'agenda' en la carpeta donde esta situado este script.
	#Is necessary to create a file called 'agenda' in the folder where the script is located.

	#Comprobar si el parametro 'fecha' no existe
	#Check the parameter 'fecha' does not exist
	if(!isset($_POST['fecha'])){
		#Comprobar si existe el parametro 'generar'
		#Check whether the parameter 'generar'
		if(isset($_POST['generar'])){
			generarJSON();
		#Comprobar si existe el parametro 'generarTodo'
		#Check whether the parameter 'generarTodo'
		}else if(isset($_POST['generarTodo'])){
			generarTodoJSON();
		#Comprobar si existe el parametro 'mostrarTodo'
		#Check whether the parameter 'mostrarTodo'
		}else if(isset($_POST['mostrarTodo'])){
			mostrarTodo();
		#Si niguna de las condiciones se cumple es una nueva ejecucion
		#If neither condition is met is a new execution
		}else{
			#Cargamos el formulario
			#We loaded the form
			header("Location: formulario.html");
		}
	#Si el parametro 'fecha' existe
	#If the parameter 'fecha' exists
	}else{
		#Comprobar si existe el parametro 'enviar'
		#Check whether the parameter 'enviar'
		if(isset($_POST['enviar'])){
			guardar();
		#Comprobar si existe el parametro 'mostrar'
		#Check whether the parameter 'mostrar'
		}else if(isset($_POST['mostrar'])){
			mostrar($_POST['fecha']);
		#Comprobar si existe el parametro 'mostrarTodo'
		#Check whether the parameter 'mostrarTodo'
		}else if(isset($_POST['mostrarTodo'])){
			mostrarTodo();
		}
	}

	#Funcion que genera html de todas las tareas guardadas
	#Function that generates an HTML with a date reguisters introduced by arguments
	function mostrarTodo(){
		#Guardamos contenido de la agenda
		#Save the contents of the agenda
		$mostrar=file_get_contents('agenda');
		#Separador de registros doble salto de linea
		#Separador de registros doble salto de linea
		$mostrarChop=explode("\n\n", $mostrar);
		$agendaTotal;
		#Recorremos los registros
		#Browse registers
		foreach ($mostrarChop as $key => $value) {
			#Se obvia el ultimo registro que es una linea vacia
			#Se obvia el ultimo registro que es una linea vacia
			if($key != count($mostrarChop)-1){
				$valueTrim=trim($value);
				#El separador de campos de un registro es '*'
				#The separator of fields in a registers is '*'
				$entrada = explode('*', $valueTrim);
				#Generar un array asociativo
				#Generate an associative array
				$agendaTotal[$key]=array(
										"fecha" => $entrada[0],
										"asignatura" => $entrada[1],
										"descripcion" => $entrada[2],);
			}
		}
		$html = generarTabla($agendaTotal,'generarTodo');
		echo $html;

		#La funcion devuelve el array creado para generar JSON
		#The function returns the array created to generate JSON
		return $agendaTotal;

	}
	#Funcion que genera html de las tareas de una determinada fecha, que se le pasa por argumento
	#Function that generates html with a date registers introduced by arguments
	function mostrar($fecha){
		#Guardamos contenido de la agenda
		#Save the contents of the agenda
		$mostrar=file_get_contents('agenda');
		#Separador de registros doble salto de linea
		#Separador de registros doble salto de linea
		$mostrarChop=explode("\n\n", $mostrar);
		$agendaTotal;
		$pos=0;
		#Recorremos los registros
		#Browse registers
		foreach ($mostrarChop as $key => $value) {
			#Se obvia el ultimo registro que es una linea vacia
			#Se obvia el ultimo registro que es una linea vacia
			if($key != count($mostrarChop)-1){
				$valueTrim=trim($value);
				#El separador de campos de un registro es '*'
				#The separator of fields in a registers is '*'
				$entrada = explode('*', $valueTrim);
				#Comprobar que la fecha coincide con el registro, para guardar el registro en el array
				#Check that the date coincides with the registration, to save the record in the array
				if($fecha==$entrada[0]){
					$agendaTotal[$pos++]=array(
										"fecha" => $entrada[0],
										"asignatura" => $entrada[1],
										"descripcion" => $entrada[2],);
				}
			}
		}		
		$html = generarTabla($agendaTotal, 'generar');
		echo $html;

		#Si la cookie no existe o es distinta a la fecha buscada, se genera una nueva cookie con la fecha buscada. Para poder recuperar el JSON de la busqueda en caso de pedirlo.
		#If the cookie does not exist or is different from the required date, a new cookie with the required date is generated. In order to retrieve the JSON if the search request it.
		if(!$_COOKIE['JSON'] || $_COOKIE['JSON'] != $fecha){
			setcookie('JSON',$_POST['fecha']);
		}
		#La funcion devuelve el array creado para generar JSON
		#The function returns the array created to generate JSON
		return $agendaTotal;
	}
	function guardar(){
		#Guardar los parametros en variables
		#Guardar los parametros en variables
		$fecha=$_POST['fecha'];
		$asignatura=$_POST['asignatura'];
		$nota=$_POST['nota'];
		
		#Fichero 'agenda' con permisos de escritura.
		#File 'agenda', writable.
		$fh=fopen('agenda','a');

		#Escribimos en el fichero, delimitando los campos con '*'
		#We write to the file, defining fields with '*'
		fwrite($fh, $fecha."*".$asignatura."*".$nota."\n\n");
		fclose($fh);

		#Generar el html del formulario y añadimos un mensaje de exito, en el div preparado previamente
		#Generate html form and add a message of success in previously prepared div
		$formulario=file_get_contents('formulario.html');
		$formuChop=trim($formulario);
		$elementos=explode("\n", $formuChop);
		foreach ($elementos as $key => $value) {
			 $valueTrim=trim($value);
			if($valueTrim == "<div>"){
				echo $valueTrim."<br/><b>Ha sido guardado</b>";

			}else{
				echo $valueTrim;
			}
		}
	}

	#Funcion para mostrar JSON de una busqueda
	#Function to display JSON of a search
	function generarJSON(){
		#Comprobar que la cookie 'JSON' existe
		#Function to display JSON
		if($_COOKIE['JSON']){
			$json=mostrar($_COOKIE['JSON']);
			#Comprobar que el array sea distinto a null
			#Check that the array is different from null
			if($json){
				echo "<p>".json_encode($json)."</p>";
			}
		}
	}
	#Funcion para mostrar JSON de todos los registros
	#Function to display JSON of all registers
	function generarTodoJSON(){
		$json=mostrarTodo();
			#Comprobar que el array sea distinto a null
			#Check that the array is different from null
			if($json){
				echo "<p>".json_encode($json)."</p>";
			}
	}

	#Funcion de comparacion para ordenacion con usort
	#Comparison function for ordination with usort
	function compareFecha($a,$b){
		#Recupar el campo fecha de cada parametro de comparacion
		#Recupar the date of each parameter field comparison
		$dateA=$a['fecha'];
		$dateB=$b['fecha'];

		#Generar array de fecha(dia,mes,año)
		#Generate array of date (day, month, year)
		$aFecha=explode("-", $dateA);
		$bFecha=explode("-", $dateB);

		#Cambiar orden de fecha(año,mes,dia)
		#Change order of date (year, month, day)
		$fechaDefA=$aFecha[2]."-".$aFecha[1]."-".$aFecha[0];
		$fechaDefB=$bFecha[2]."-".$bFecha[1]."-".$bFecha[0];

		#Generar objetos 'datetime' con string de cada fecha, es necesario el formato: año-mes-dia
		#Generate objects 'datetime' with each date string, the format required: year-month-day
		$fechaA=new DateTime($fechaDefA);
		$fechaB= new DateTime($fechaDefB);

		#Comparacion
		#Comparacion
		if($fechaA > $fechaB){
			return -1;
		}else if($fechaB > $fechaA){
			return 1;
		}else{
			return 0;
		}
	}
	#Generar tabla html mostrando los registros
	#Generate HTML table displaying registers
	function generarTabla($agenda,$tipoGenerar){
		$agendaTotal = $agenda;
		#Ordenar arrays
		#Sort arrays
		usort($agendaTotal, 'compareFecha');
		$html = "<table border=\"1\"><tr><th>Fecha</th><th>Asignatura</th><th>Descripcion</th></tr>";
		foreach ($agendaTotal as $key => $value) {
			$html = $html."<tr><td>".$agendaTotal[$key]['fecha']."</td><td>".$agendaTotal[$key]['asignatura']."</td><td>".$agendaTotal[$key]['descripcion']."</td></tr>";
		}
		$html = $html."</table><form method=\"POST\"><input type=\"submit\" value=\"Volver\"/><input type=\"submit\" name=\"".$tipoGenerar."\" value=\"Generar JSON\"/></form>";
		return $html;
	}
?>