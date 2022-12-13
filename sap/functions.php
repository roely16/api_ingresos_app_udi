<?php 

    include "connection.php";

    class SAP_Function
    {
        public $sap_conn;
        public $login_data;

		public function __construct(){

			// parent::__construct();

			$conn = new Connection();
            $this->sap_conn = $conn->connect();

        }

        public function test_connection(){

            $conn = $this->sap_conn;

            return $conn;

        }

        public function obtenerIngresos($fecha_inicio, $fecha_fin, $psobtyp){

            $result = $this->sap_conn->callFunction("ZPSCD_FM_CI_025",
                        array(
                            array("IMPORT","P_PSOBTYP", $psobtyp),
                            array("IMPORT","P_FECHAINI", $fecha_inicio),
                            array("IMPORT","P_FECHAFIN", $fecha_fin), 
                            array("EXPORT", "T_IUSI_MONTO"),
                            array("EXPORT", "T_MULTA_MONTO"),
                            array("EXPORT", "T_CONVENIO_MONTO")									   
                        ));

            return $result;

        }
    }
    
?>