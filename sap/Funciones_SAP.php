<?			
	include "conexion_SAP.php";
	
	// VARIABLE QUE INDICA QUE FUNCION EJECUTAR
	// $tipo_operacion_sap = 2;

	//FUNCIONES DE SAP		
	if ($tipo_operacion_sap == 7 ) //Claves de identificacion
	{
		$result=$sap->callFunction("ZPSCD_FM_CI_016",
						array(	array("TABLE","RETURN",array()),										   		
								array("IMPORT","PARTNEREXTERNAL",$PARTNEREXTERNAL),
								array("IMPORT","IDCATEGORY",$IDENTIFICATIONCATEGORY),
								array("IMPORT","IDNUMBER",$IDENTIFICATIONNUMBER),								
	  							array("IMPORT","IDVALIDFROM",$IDVALIDFROM),
								array("IMPORT","IDVALIDTO",$IDVALIDTO),
								array("IMPORT","TESTRUN",$TESTRUN),											
								array("EXPORT","PARTNER")																			
							 )
							     );	
					/*foreach ($result["RETURN"] as $error)  
					{						
					    $mensaje = "<br> TIPO: ".$error["TYPE"]. " " .$error["MESSAGE"]. "<br>";
					}*/

	} elseif($tipo_operacion_sap == 100){
	
		$result=$sap->callFunction("ZPSCD_FM_CI_021",
						array(	array("TABLE","RETURN",array()),
						       	array("TABLE","ID", $arrayclaves ),									   		
								array("IMPORT","TESTRUN",$TESTRUN),											
								array("IMPORT","FACTURADOR",$FACTURADOR)																																						
							 )
							     );	
								 

	} elseif ($tipo_operacion_sap == 8 ) //Sujeto de derecho 
	{
		$result=$sap->callFunction("ZPSCD_FM_CI_019",
						array(	array("TABLE","RETURN",array()),										   		
								array("IMPORT","BPEXT",$BPEXT),
								array("IMPORT","LEGALORG",$LEGALORG)								
							 )
							     );		

 	
 					/*foreach ($result["RETURN"] as $error)  
					{						
					    $mensaje = "<br> TIPO: ".$error["TYPE"]. " " .$error["MESSAGE"]. "<br>";
					}*/


	}elseif ($tipo_operacion_sap == 99 ) //Pagador Alternativo
	{
		$result=$sap->callFunction("ZPSCD_FM_CI_015",
						array(	array("TABLE","RETURN",array()),
				  			    array("EXPORT","PARTNER_IN"),																
				  			    array("EXPORT","PARTNER_PR"),																
						  	    array("IMPORT","PAGADOR",  
									 array("PSOBTYP" 		=> $PSOBTYP  			   
											,"BPEXT_IN" 	=> $BPEXT_IN  			   				
											,"PARTNER_IN" 	=> $PARTNER_IN  			   
											,"BPEXT_PR" 	=> $BPEXT_PR  			   
											,"PARTNER_PR" 	=> $PARTNER_PR  			   
			 							)									 
									 )
								   )
							    );		
					$retorno = $result['PARTNER_IN'];
					echo "<br> RETORNO: ".$retorno."<br>";
							foreach ($result["RETURN"] as $error)  
					{	
					echo "<br> ERRORES EN MANTENIMIENTO PAGADOR ALTERNATIVO <br>";					
					echo "<br> TIPO: ".$error["TYPE"]."<br>";
					echo "<br> MENSAJE: ".$error["MESSAGE"]."<br>";
					}
	  

	}elseif ($tipo_operacion_sap == 5 ) //Función que agrega el numero de autorizacion de visanet luego de haber efectuado el pago 
	{
		/************************************************************************************************/
		//Parametro que recibe la funcion que agrega en # de autorizacion luego del pago
		//$OPBEL = Nº documento de la cuenta corriente contractual
		//$AUNUM = Numero de autorizacion proporcionado por VISANET
		
	$result=$sap->callFunction("ZPSCD_FM_CAJA_026",
								array(	array("EXPORT","RETURN"),
										array("IMPORT","OPBEL",$OPBEL),
										array("IMPORT","AUNUM",$AUNUM)		
 									  )
									);
	
	}elseif ($tipo_operacion_sap == 10 ) //Mantenimieto Pagador Alternativo
	{
						$result=$sap->callFunction("ZPSCD_FM_CI_015",
						array(	array("TABLE","RETURN",array()),
				  			    array("EXPORT","PARTNER_IN"),																
				  			    array("EXPORT","PARTNER_PR"),																
						  	    array("IMPORT","PAGADOR",  
									 array("PSOBTYP" 		=> $PSOBTYP  			   
											,"BPEXT_IN" 	=> $BPEXT_IN  			   				
											,"PARTNER_IN" 	=> $PARTNER_IN  			   
											,"BPEXT_PR" 	=> $BPEXT_PR  			   
											,"PARTNER_PR" 	=> $PARTNER_PR  			   
			 							)									 
									 )
								   )
							    );		
					$retorno = $result['PARTNER_IN'];
					echo "<br> RETORNO: ".$retorno."<br>";
							foreach ($result["RETURN"] as $error)  
					{	
					echo "<br> ERRORES EN MANTENIMIENTO PAGADOR ALTERNATIVO <br>";					
					echo "<br> TIPO: ".$error["TYPE"]."<br>";
					echo "<br> MENSAJE: ".$error["MESSAGE"]."<br>";
					}

	}elseif ($tipo_operacion_sap == 9 ) //Mantenimieto Facturador
	{
				$result=$sap->callFunction("ZPSCD_FM_FACT_021",
						array(	array("TABLE","TI_RETURN",array()),
				  			    array("EXPORT","ZZFACT"),																
						  	    array("IMPORT","TI_I_REG_1",  
									 array("TYPEREG" 			=> $TYPEREG  			   
											,"PSOBTYP" 			=> $PSOBTYP  			   				
											,"ZZRECALCULO" 		=> $ZZRECALCULO  			   
											,"BPEXT" 			=> $BPEXT  			   
											,"GPART" 			=> $GPART  			   
											,"BLDAT" 			=> $BLDAT  			   				
											,"FAEDN" 			=> $FAEDN  			   				
											,"ZZFECHAFIN" 		=> $ZZFECHAFIN  			   				
											,"BETRW" 			=> $BETRW  			   				
											,"BETRW_OLD" 		=> $BETRW_OLD  			   				
											,"BETRW2" 			=> $BETRW2  			   				
											,"BETRW3" 			=> $BETRW3  			   				
											,"OPTXT" 			=> $OPTXT  			   				
											,"INDEX" 			=> $INDEX  			   				
			 							)									 
									 )
								   )
							    );		
					$retorno = $result['ZZFACT'];
					echo "<br> RETORNO: ".$retorno."<br>";
					foreach ($result["TI_RETURN"] as $error)  
					{	
					echo "<br> ** ERROR EN FACTURADOR ** <br>";					
					echo "<br> TYPE: ".$error["TYPE"]."<br>";
					echo "<br> MESSAGE: ".$error["MESSAGE"]."<br>";
					}

	}
	
	

	//Verifica el estatus que devolvio la funcion 
	if ($sap->getStatus() == SAPRFC_OK) 
	{
		//echo "SAPRFC OK <br>";
		$error_sap = 0;	 //funcion ejecutada satisfactoriamente	
						
	}else 
	{ 	
		//$sap->printStatus();		
		$error_sap = 1;	//error en la funcion

		echo "<pre>"; 
        echo print_r ($mensaje);  
        echo "</pre>";

				
	}		
	
	//echo str_pad ( $ZZNCOBRO, 10 , "0" , STR_PAD_LEFT ); 
	//echo($error_sap);
						
	$sap->logoff();
	
		
	
?>