# Sistema de Archivos
Los nombres de los archivos y carpetas serán escritos en minusculas y separados por guión.

**Ejemplo:** icon-white.png
- - - -
# Clases
* Prefijo en el nombre de las clases *"DFW_"*.
* Nombre de la clase en camelCase.

`<?php class DFW_miClase {...} ?>`

**Ejemplo:** class myClass
- - - -
# Base de datos
* Campos con nombre en camelCase.
* Prefijos en tablas *"dfw_"*.
* Nombre de la tabla en camelCase.
* Identificadores de tablas:
  * Tipo entero (opcional).
  * Autoincrementable (opcional).
* Todas las tablas tienen que contener los siguientes 2 campos: 

Campo | Tipo | Nulo
------------- | ------------- | -------------
timeRegistrer  | datetime | NOT NULL
timeUpdate  | datetime | NULL

- - - -
# Funciones
* Prefijo en el nombre de las funciones *"dfw_"*.
* Nombre de la función en camelCase
* Constantes con mayúscula compacta y separadas por "_".
* Argumentos en camelCase
* Documentación tipo [JavaDoc](http://www.oracle.com/technetwork/articles/java/index-137868.html)


```php
<?php
/**
* Esta función devuelve el área de un circulo.
* El argumento es el radio del circulo, debe ser de tipo númerico
* @param radioCirculo variable tipo númerica
* @return devuelve el área de un circulo en tipo númerico
* @throws String si el argumento es de tipo texto
*/
function dfw_miFuncion($radioCirculo){
  $NUMERO_PI = 3.14159;
  return $radioCirculo * $NUMERO_PI * radioCirculo;
}
?>
