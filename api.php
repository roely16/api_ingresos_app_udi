<?php 

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Allow: GET, POST, OPTIONS, PUT, DELETE");

    include $_SERVER['DOCUMENT_ROOT'] . '/apps/api_ingresos/sap/functions.php';

    class Api extends Rest {
        
        public $dbConn;

		public function __construct(){

			parent::__construct();

			$db = new Db();
			$this->dbConn = $db->connect();

        }

        public function test(){
        }
        
        public function consultar_ingresos(){

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL,"http://172.23.25.36/apps/api_ingresos_app/");
            curl_setopt($ch, CURLOPT_POST, 1);

            $data = array(
                "name" => "app_data",
                "param" => array()
            );

            $payload = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = json_decode(curl_exec($ch), true);

            // Detalle 
            setlocale(LC_TIME, 'es_ES');
            $month = date('n');
            $mes_letras = ucfirst(strftime("%B"));
            $year = date('Y');

            $detalle = array();

            $detalle_actual = array();
            
            // Meta del mes
            $query = "  SELECT ROUND(sum(to_number(proyeccion_solvente))/1000000,2) AS proyeccion_solvente,
                        ROUND(sum(to_number(proyeccion_mora))/1000000,2) AS proyeccion_mora                     
                        FROM tbl_proyeccion_pagos
                        WHERE TO_NUMBER(TO_CHAR(fecha,'MM'  ))  = $month
                        AND TO_NUMBER(TO_CHAR(fecha,'YYYY')) = $year";

            $stid = oci_parse($this->dbConn, $query);
            oci_execute($stid);

            $proyeccion = oci_fetch_array($stid, OCI_ASSOC);

            $query = "ALTER SESSION SET nls_date_format = 'dd/mm/yyyy'";
            $stid = oci_parse($this->dbConn, $query);
            oci_execute($stid);

            // Mora mes actual 
            $query = "  SELECT sum(to_number(impuesto))/1000000 AS impuesto,
                        sum(to_number(multas))/1000000 AS multas,
                        sum(to_number(convenios))/1000000 AS convenios
                        FROM tbl_actual_pagos
                        WHERE TO_DATE(fecha, 'DD/MM/YYYY') BETWEEN TO_DATE('01/08/2019', 'DD/MM/YYYY')
                        AND TO_DATE('22/08/2019', 'DD/MM/YYYY')";

            $stid = oci_parse($this->dbConn, $query);
            oci_execute($stid);

            $mora_mes_actual = oci_fetch_array($stid, OCI_ASSOC);

            $multas_mora_actual    = doubleval($mora_mes_actual['MULTAS']);
            $convenios_mora_actual = doubleval($mora_mes_actual['CONVENIOS']);

            $iusi_mora_actual     = round((($multas_mora_actual * 100 / 20) + $convenios_mora_actual + $multas_mora_actual),2);

            // Mora Acumulado
            $query = "  SELECT sum(to_number(impuesto))/1000000 AS impuesto,
                        sum(to_number(multas))/1000000 AS multas,
                        sum(to_number(convenios))/1000000 AS convenios
                        FROM tbl_actual_pagos
                        WHERE TO_DATE(fecha, 'DD/MM/YYYY') BETWEEN TO_DATE('01/01/2019', 'DD/MM/YYYY')
                        AND TO_DATE('22/08/2019', 'DD/MM/YYYY')";

            $stid = oci_parse($this->dbConn, $query);
            oci_execute($stid);

            $mora_acumulada = oci_fetch_array($stid, OCI_ASSOC);

            $multas_acumulada    = doubleval($mora_acumulada['MULTAS']);
            $convenios_acumulada = doubleval($mora_acumulada['CONVENIOS']);

            $iusi_mora_acumulada     = round((($multas_acumulada * 100 / 20) + $convenios_acumulada + $multas_acumulada),2);

            $meta_mes = doubleval($proyeccion["PROYECCION_SOLVENTE"]) + doubleval($proyeccion["PROYECCION_MORA"]);

            // Calculos 
            $mora_real = ((doubleval($server_output["TOTAL_ACUMULADO"]["T_MULTA_MONTO"])) * 100) / 20;

            $mora_real_mes = ((doubleval($server_output["TOTAL_MES"]["T_MULTA_MONTO"])) * 100) / 20;

            $fields_actual = array(
                array(
                    "key" => "name",
                    "label" => $mes_letras . " " . $year
                ),
                array(
                    "key" => "value",
                    "label" => "META " .$meta_mes. "M"
                )
            );

            // $iusi_mes = doubleval($server_output["TOTAL_MES"]["T_IUSI_MONTO"]) + doubleval($server_output["TOTAL_MES"]["T_CONVENIO_MONTO"]);

            $iusi_mes = round(((doubleval($server_output["TOTAL_MES"]["T_IUSI_MONTO"]) - $mora_real_mes) / 1000000), 2);

            $iusi_mora_actual = round((doubleval($server_output["TOTAL_MES"]["T_CONVENIO_MONTO"] + doubleval($server_output["TOTAL_MES"]["T_MULTA_MONTO"] + $mora_real_mes)) / 1000000), 2);

            $items_actual = array(
                array(
                    "name" => "CUENTAS SOLVENTES",
                    "value" => $iusi_mes . "M"
                ),
                array(
                    "name" => "CUENTAS MOROSAS",
                    "value" => $iusi_mora_actual . "M"
                ),
                array(
                    "name" => "TOTAL",
                    "value" => $iusi_mora_actual + $iusi_mes . "M",
                    "_rowVariant" => 'success'
                )
            );

            $detalle_acumulado = array();

            $fields_acumulado = array(
                array(
                    "key" => "name",
                    "label" => "Año " . $year
                ),
                array(
                    "key" => "value",
                    "label" => "META 525M"
                )
            );

            // $iusi_acumulado = doubleval($server_output["TOTAL_ACUMULADO"]["T_IUSI_MONTO"]) + doubleval($server_output["TOTAL_ACUMULADO"]["T_CONVENIO_MONTO"]);

            $iusi_acumulado = round(((doubleval($server_output["TOTAL_ACUMULADO"]["T_IUSI_MONTO"]) - $mora_real) / 1000000), 2);

            $mora_acumulada = round((doubleval($server_output["TOTAL_ACUMULADO"]["T_CONVENIO_MONTO"] + doubleval($server_output["TOTAL_ACUMULADO"]["T_MULTA_MONTO"] + $mora_real)) / 1000000), 2);

            $items_acumulado = array(
                array(
                    "name" => "IUSI RECAUDADO",
                    "value" => $iusi_acumulado . "M"
                ),
                array(
                    "name" => "MORA RECAUDADA",
                    "value" => $mora_acumulada . "M"
                ),
                array(
                    "name" => "TOTAL",
                    "value" => $iusi_acumulado + $mora_acumulada . "M",
                    "_rowVariant" => 'success', 
                )
            );

            // Detalle actual
            $detalle_actual["FIELDS"] = $fields_actual;
            $detalle_actual["ITEMS"] = $items_actual;
            $detalle["ACTUAL"] = $detalle_actual;

            // Detalle acumulado
            $detalle_acumulado["FIELDS"] = $fields_acumulado;
            $detalle_acumulado["ITEMS"] = $items_acumulado;
            $detalle["ACUMULADO"] = $detalle_acumulado;

            $server_output["DETALLE"] = $detalle;

            $server_output["IUSI_REAL"] = $mora_real_mes;

            $this->returnResponse(SUCCESS_RESPONSE, $server_output);

        }

        public function detalle(){

            $detalle = array();

            $detalle_actual = array();
            
            $fields_actual = array(
                array(
                    "key" => "name",
                    "label" => "Agosto 2019"
                ),
                array(
                    "key" => "value",
                    "label" => "META 15M"
                )
            );

            $items_actual = array(
                array(
                    "name" => "IUISI + CONVENIOS",
                    "value" => 5
                ),
                array(
                    "name" => "MORA",
                    "value" => 8
                )
            );

            $detalle_acumulado = array();

            $fields_acumulado = array(
                array(
                    "key" => "name",
                    "label" => "Año 2019"
                ),
                array(
                    "key" => "value",
                    "label" => "META 500M"
                )
            );

            $items_acumulado = array(
                array(
                    "name" => "IUISI + CONVENIOS",
                    "value" => 300
                ),
                array(
                    "name" => "MORA",
                    "value" => 50
                )
            );

            // Detalle actual
            $detalle_actual["FIELDS"] = $fields_actual;
            $detalle_actual["ITEMS"] = $items_actual;
            $detalle["ACTUAL"] = $detalle_actual;

            // Detalle acumulado
            $detalle_acumulado["FIELDS"] = $fields_acumulado;
            $detalle_acumulado["ITEMS"] = $items_acumulado;
            $detalle["ACUMULADO"] = $detalle_acumulado;

            $this->returnResponse(SUCCESS_RESPONSE, $detalle);

        }

    }
    

?>