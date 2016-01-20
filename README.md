
# Agenda DAW
Agenda DAW es un script para guardar y mostrar las tareas de las distintas materias del ciclo formativo de Grado Superior de Desarrollo de aplicaciones WEB.

Este script consta de 3 archivos.

#Requerimientos
Servidor Apache 

Es necesario tener un archivo llamado 'agenda' creado en el directorio contenedor del script, y concederle todos los permisos mediante el comando:

 \#chmod 777 agenda

 #Uso
Ejecutar el script 'TP1.php' a traves del servidor apache.

Mostrara un formulario, con tres campos y en el que se ofrecen tres opciones:

Guardar:

Para guardar una tarea es necesario cumplimentar la fecha en formato 'dd-MM-yyyy', seleccionar la materia, y describir la tarea.

Una vez validado y guardado, aparecera un mensaje de exito.


Mostrar:

Tenemos la posibilidad de generar una tabla html, de las tareas de una fecha concreta.
Para esta opcion, el unico campo requerido y tenido en cuenta es el campo fecha.


Mostrar todas:

Esta opcion nos permite visualizar una tabla de todas las tareas registradas.
Para esta opcion ningun campo es requerido.


**Al visualizar las tablas, tenemos las opciones de volver al formulario principal o la de generar el texto JSON correspondiente a la tabla visualizada.



