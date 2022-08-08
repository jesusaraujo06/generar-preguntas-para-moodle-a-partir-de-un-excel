<?php

require 'vendor/autoload.php';

// Indicar que usaremos el IOFactory
use TdTrung\Chalk\Chalk;
// Libreria para tener estilos en la consola
use PhpOffice\PhpSpreadsheet\IOFactory;

$chalk = new Chalk();

/**
 * CODIGO DE LA LIBRERIA: phpspreadsheet
 */
$rutaArchivo = "src/docs/matriz-preguntas.xlsx";
$documento = IOFactory::load($rutaArchivo);

// Obtener el numero total de hojas
$totalDeHojas = $documento->getSheetCount();
// ID de la hoja del documento (en excel las hojas comienzan desde 1, pero el id es 0)
$hojaAEscoger = 1;
// Obtener la hoja del documento
$hojaActual = $documento->getSheet($hojaAEscoger);
// Obtener el numero de la ultima fila, recibimos como respuesta un valor numerico
$numeroMayorDeFila = $hojaActual->getHighestRow();

/**
 * VARIABLES PARA RECORRER EL ARCHIVO EXCEL
 * 
 * $indiceFila = fila donde va a empezar a recorrer
 * $indiceColumna = columna donde va a empezar a recorrer
 * $numeroMayorDeColumna = columna hasta donde se va a recorrer
 * $idBancoDePreguntas = id que identifica el banco de preguntas
 * $obtenerPregunta = variable booleana para identificar si la celda recorrrida contiene una pregunta
 */
$indiceFila = 3;
$indiceColumna = 2;
$numeroMayorDeColumna = 3;
$idBancoDePreguntas = 25;
$obtenerPregunta = false;

$stringCadenaGlobal = "";

// Saltos de linea
print "\n\n------------------------------------------------\n";
print "GENERAR PREGUNTAS PARA MOODLE...";
print "\n------------------------------------------------ \n\n";


/**
 * Primero se recorren las filas del excel
 * Es decir, se va a iterar verticalmente
 */
for ($indiceFila; $indiceFila <= $numeroMayorDeFila; $indiceFila++) {

    /**
     * Ahora se recorren las columnas del excel
     * Es decir, se va a iterar horizontalmente 
     * Esto con el fin de obtener el id del banco de preguntas y luego la pregunta
     */
    for ($indiceColumna; $indiceColumna <= $numeroMayorDeColumna; $indiceColumna++) {

        /**
         * Obtener la celda
         * @param $indiceColumna
         * @param $indiceFila
         */
        $celda = $hojaActual->getCellByColumnAndRow($indiceColumna, $indiceFila);

        // Obtener el valor de la celda seleccionada
        $valorRaw = $celda->getValue();

        /**
         * Teniendo el valor de la celda, validamos si ese valor es un numero
         * Si es numero, lo comparamos con la variable $idBancoDePreguntas, para ver si
         * es una pregunta perteneciente al banco de preguntas que estamos necesitando,
         * si esto es correcto entonces a la variable booleana $obtenerPregunta
         * le cambiamos el valor a true
         */
        if(is_numeric($valorRaw) and $valorRaw == $idBancoDePreguntas){
            $obtenerPregunta = true;
        }
        
    }

    /**
     * Si la variable booleana $obtenerPregunta es igual a true, quiere decir que
     * el valor de la celda actual es una pregunta y corresponde al $idBancoDePreguntas
     *  
     */
    if($obtenerPregunta == true){

        /**
         * Obtener fla de la celda actual,
         * Las filas comienzan en 1, luego 2 y así...
         */
        $fila = $celda->getRow();

        /**
         * Obtener la columna de la celda actual, 
         * Las columnas comienzan en A, B, C y así...
         */
        $columna = $celda->getColumn();

        /**
         * Ahora verificamos si la variable $valorRaw es un objeto, debido a que
         * el metodo "getValue()" nos retorna un objeto tipo spreadsheet cuando
         * el valor de la celda tiene texto enriquecido.
         * en caso de que el valor de la celda sea un texto plano, retornara un string
         */
        if(is_object($valorRaw)){

            // Convertimos el objeto en un string
            $stringCadena = $valorRaw->__toString();

            /**
             * Contruimos un array dividiendo el string, por medio de la funcion preg_split()
             * y una expresión regular que detecta los saltos de linea
             */
            $itemsCadena = array();
            $itemsCadena[] = preg_split('/\n|\r\n?/', $stringCadena);


            // Variables para iterar el array de string
            $caracteresAIdentificar = ["{{F}}", "{{V}}"];
            $preguntasComienzanDesde = 0;
            $preguntasConFormatoString = "";
            $respuestasConFormatoString = "";

            /**
             * Ahora vamos a iterar el array de string y verificamos si alguno tiene falso o verdadero
             * con la ayuda de una funcion auxiliar strpos_array(), para saber desde que indice comienzan 
             * las respuestas, ya que, el array de string que construimos con preg_split() viene con la 
             * pregunta y las respuestas.
             */
            for ($i=0; $i < count($itemsCadena[0]); $i++) {

                $cadena = $itemsCadena[0][$i];

                $validarRespuestas = strpos_array($cadena, $caracteresAIdentificar);

                if($validarRespuestas == true){
                    $preguntasComienzanDesde = $i;
                    break;
                }

            }

            /**
             * Verificamos que tipo de pregunta
             */
            $tipoDePregunta = validarPregunta($itemsCadena[0], $preguntasComienzanDesde);

            if($validarRespuestas == false){
                print $chalk->bold->red("ERROR: en $columna$fila hay un error en el formato de las respuestas, probablemente no existan los parametros: {{F}} {{V}}. \n\n");
                die();
            }

            // Validaciones
            if($tipoDePregunta == false){
                print $chalk->bold->red("ERROR: en $columna$fila no pudimos detectar que tipo de pregunta es.  \n\n");
                die();
            }

            // Construir la pregunta
            for ($i=0; $i < $preguntasComienzanDesde; $i++) { 
                $preguntasConFormatoString .= $itemsCadena[0][$i];
            }

            // Tipos de preguntas
            if($tipoDePregunta == 'preguntaMultipleVariasRespuestas'){

                $arrayRespuestasPositivas = array();
                $arrayRespuestasNegativas = array();
                
                // Construir las respuestas
                for ($i = $preguntasComienzanDesde; $i <= count($itemsCadena[0])-1; $i++) {
                    if($itemsCadena[0][$i] !== ""){

                        // Sacar las respuestas positivas
                        $isPositiva = strpos($itemsCadena[0][$i], "{{V}}");
                        if($isPositiva == true){                         
                            $arrayRespuestasPositivas[] = $itemsCadena[0][$i];
                        }else{
                            $arrayRespuestasNegativas[] = $itemsCadena[0][$i];
                        }
                    } 
                }

                // Dividir 100 entre el numero dado
                $porcentajeRespuestasPositivas = round(100/count($arrayRespuestasPositivas), 5);
                $porcentajeRespuestasNegativas = -5;

                // Construir las respuestas
                for ($i = $preguntasComienzanDesde; $i <= count($itemsCadena[0])-1; $i++) {
                    if($itemsCadena[0][$i] !== ""){

                        // Sacar las respuestas positivas
                        $isPositiva = strpos($itemsCadena[0][$i], "{{V}}");
                        if($isPositiva == true){                         
                            $respuestasConFormatoString .= PHP_EOL . "~%" . $porcentajeRespuestasPositivas . "%" . $itemsCadena[0][$i];
                        }else{
                            $respuestasConFormatoString .= PHP_EOL . "~%" . $porcentajeRespuestasNegativas . "%" . $itemsCadena[0][$i];
                        }
                    } 
                }

                // Armar la pregunta con formato GIFT
                $stringGlobal = "$preguntasConFormatoString { $respuestasConFormatoString }" . PHP_EOL . PHP_EOL;

            }else if($tipoDePregunta == 'preguntaMultipleUnaSolaRespuesta'){
                // Construir las respuestas
                for ($i = $preguntasComienzanDesde; $i <= count($itemsCadena[0])-1; $i++) {
                    if($itemsCadena[0][$i] !== ""){
                        if(strpos($itemsCadena[0][$i], "{{V}}")){
                            $respuestasConFormatoString .= PHP_EOL . "=" . $itemsCadena[0][$i];
                        }else{
                            $respuestasConFormatoString .= PHP_EOL . "~" . $itemsCadena[0][$i];
                        }
                    }
                }

                

                // Armar la pregunta con formato GIFT
                $stringGlobal = "$preguntasConFormatoString { $respuestasConFormatoString }" . PHP_EOL . PHP_EOL;

            }else if($tipoDePregunta == 'preguntaVerdaderoOFalso'){

                $buscar = "Falso. {{V}}";

                $isVerdadoOFalso = array_filter($itemsCadena[0], function($var) use ($buscar) {
                    $s = str_replace(['a.', '.b', ' ', '.'], "", $buscar);
                    $a = str_replace(['a.', '.b', ' ', '.'], "", $var);
                    if(stristr($a,$s)){
                        return true;
                    }
                    
                });

                if($isVerdadoOFalso){
                    $respuestasConFormatoString .= "{F}";
                }else{
                    $respuestasConFormatoString .= "{T}";
                }

                // Armar la pregunta con formato GIFT
                $stringGlobal = "$preguntasConFormatoString $respuestasConFormatoString" . PHP_EOL . PHP_EOL;
            }

                
            
            $stringCadenaGlobal .= limpiarCadena($stringGlobal);
   
        
            print "En la celda $columna$fila tenemos el valor => \n\n" . limpiarCadena($stringGlobal) . "\n";
            print "Tipo de pregunta => $tipoDePregunta\n\n";
            print "------------------------------------------------ \n\n";
        
        }else{
            print $chalk->bold->red("ERROR: en $columna$fila no pudimos obtener un objeto, probablemente es por que el valor de la celda no tiene formato. \n\n");
            die();
        }

    }

    $indiceColumna = 2;
    $obtenerPregunta = false;

}


// Generar txt con las preguntas
if (file_exists("tmp/datos.txt")){
    $archivo = fopen("src/tmp/conocimiento " . $idBancoDePreguntas . ".txt", "a");
    fwrite($archivo, PHP_EOL ."$stringCadenaGlobal");
    fclose($archivo);

    print "\n\n------------------------------------------------\n";
    print $chalk->bold->green("Todas las preguntas fueron generadas con exito en un archivo .txt, en la siguiente ruta => tmp/conocimiento " . $idBancoDePreguntas . ".txt");
    print "\n------------------------------------------------ \n\n";
}
else{
    $archivo = fopen("src/tmp/conocimiento " . $idBancoDePreguntas . ".txt", "w");
    fwrite($archivo, PHP_EOL ."$stringCadenaGlobal");
    fclose($archivo);
    print "\n\n------------------------------------------------\n";
    print $chalk->bold->green("Todas las preguntas fueron generadas con exito en un archivo .txt, en la siguiente ruta => tmp/conocimiento " . $idBancoDePreguntas . ".txt");
    print "\n------------------------------------------------ \n\n";
}



function strpos_array($cadena, $array) {

    // Iteramos para saber desde que posicion comienzan las respuestas
    foreach($array as $item) {
        if(strpos($cadena, $item)){
            return true;
            break;
        }
    }
   
}

function validarPregunta($itemsCadena, $preguntasComienzanDesde){

    $preguntaMultiple = 0;
    $preguntaVerdaderoOFalso = 0;
    $preguntaDeRellenarEspacios = 0;

    // Iteramos cada respuesta para descubrir que tipo de pregunta es
    for ($i=$preguntasComienzanDesde; $i < count($itemsCadena); $i++) {

        if($itemsCadena[$i] !== ""){

            // Pregunta tipo verdadero o falso
            $isVerdadoFalso = strpos_array($itemsCadena[$i], ["Verdadero", "Falso", "VERDADERO", "FALSO", "VERDADERA", "FALSA", "Verdadera", "Falsa"]);
            if($isVerdadoFalso == true){
                $preguntaVerdaderoOFalso++;
                break;
            }

            // Pregunta tipo multiple
            $isMultiple = strpos($itemsCadena[$i], "{{V}}");
            if($isMultiple == true){
                $preguntaMultiple++;
            }

        }
            
    }

    if($preguntaMultiple > 1){
        return "preguntaMultipleVariasRespuestas";

    }else if($preguntaMultiple == 1){
        return "preguntaMultipleUnaSolaRespuesta";

    }else if($preguntaVerdaderoOFalso == 1){
        return "preguntaVerdaderoOFalso";
    }

}

function limpiarCadena($itemsCadena){
    $caracteresAborrar = array("a.", "b.", "c.", "d.", "e.", "f.", "g.", "h.", "i.", "j.", "k.", '“', '”', ":", "{{F}}","{{V}}");
    $cadena = str_replace($caracteresAborrar, "", $itemsCadena);
    $stringCadenaGlobal = preg_replace(["/=\s/", "/~\s/"], ["=", "~"], $cadena);
    return $stringCadenaGlobal;
}

/**
 * Esto no hace nada ;)
 */


// print "En $columna$fila tenemos el valor => " . $arrayCadena[1] . "\n\n <br><br>";
// print "<b>En $columna$fila tenemos el valor =></b>";
// print "<br><br>";
// print "<b>Pregunta obtenida desde el array: </b> $arrayCadena[0]";
// print "<br><br>";
// print "<b>Pregunta obtenida desde el toString: </b> $stringCadena";
// print "<br><br>";
// print "<b>Respuesta correcta obtenida desde el array: </b>";
// print "<br>";
// for ($i=1; $i < count($arrayCadena); $i++) { 
//     if($arrayCadena[$i] !== '"'){
//         print "$arrayCadena[$i]";
//         print "<br><br>";
//     }
// }