<?
	
	// saprfc-class-library	
	require_once("saprfc.php");
	
	// Create saprfc-instance
	/*	$sap = new saprfc(array(
							"logindata"=>array(
							//	"ASHOST"  =>"172.23.50.43
								"ASHOST"  =>"172.23.50.45"	// ip de la direccion donde esta la bd de sap
								,"SYSNR"  =>"00"			// system number
								,"CLIENT" =>"500"			// mandante
								,"USER"   =>"SIS_CAT_WF"			// user
								,"PASSWD" =>"interfacecatastrosap"		// password
								//	,"USER"   =>"CRODRIGUEZ" ,"PASSWD" =>"gloriafernanda09"		// password
								)
							,"show_errors"=>true			// let class printout errors
							,"debug"=>false)) ; 				// detailed debugging information */

   /* MANDANTE 200 */
  /* $sap = new saprfc( array(
						"logindata"=>array(
							"ASHOST"  =>"172.23.50.44"	
							,"SYSNR"  =>"00"			   
							,"CLIENT" =>"200"			   
							,"USER"   =>"JLGUTIERREZ"	   
							,"PASSWD" =>"lestoniv2010"		
							)
						,"show_errors"=>true			
						,"debug"=>false)
					) ; 			
					
  */  									
  /* MANDANTE 500 LOCAL*/				
 	/*$sap = new saprfc( array(
							"logindata"=>array(
								"ASHOST"  =>"172.23.50.7"	
								,"SYSNR"  =>"03"			   
								,"CLIENT" =>"500"			   
								,"USER"   =>"SIS_CAT_WF"	   
								,"PASSWD" =>"interfacecatastrosap"		
								)
							,"show_errors"=>true			
							,"debug"=>false)
						) ; */
						
/* MANDANTE 500 GBM */				
 	$sap = new saprfc( array(
							"logindata"=>array(
								"ASHOST"  =>"172.23.50.45"	
								,"SYSNR"  =>"00"			   
								,"CLIENT" =>"500"			   
								,"USER"   =>"SIS_CAT_WF"	   
								,"PASSWD" =>"interfacecatastrosap"		
								)
							,"show_errors"=>true			
							,"debug"=>false)
						) ;  		

  
?>