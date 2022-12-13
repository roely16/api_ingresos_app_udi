<?php  

    include "saprfc.php";

    class Connection extends SapRFC{
        
        protected $connection;
        protected $host = '172.23.50.45';
        protected $sysnr = "00";
        protected $client = "500";
        protected $user = "SIS_CAT_WF";
        protected $pass = "interfacecatastrosap";
        protected $show_errors = true;
        public $debug = true;

        public function connect(){

            $this->connection = new SapRFC( array(
                                            "logindata"=>array(
                                                "ASHOST"  => $this->host	
                                                ,"SYSNR"  => $this->sysnr			   
                                                ,"CLIENT" => $this->client			   
                                                ,"USER"   => $this->user	   
                                                ,"PASSWD" => $this->pass		
                                            )
                                            ,"show_errors"=> $this->show_errors			
                                            ,"debug"=> $this->debug
                                            )
                                        ) ;  
            
            return $this->connection;

        }

    }
    

?>