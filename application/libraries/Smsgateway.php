<?php
    class Smsgateway
    {
        public function send($config = [])
        {            
            switch (strtolower($config['apiProvider'])) {
                case 'nexmo':
                        return $this->nexmo(@$config);
                    break; 
                 case 'clickatell':
                        return $this->send_clickatell_message($config);
                    break;
                                       
                default:
                        return json_encode(['exception' => 'No api found']);
                    break;
            }
        } 


        #--------------------------------------
        # For nexmo provider
        public function nexmo($config = [])
        {

             @$url = "https://rest.nexmo.com/sms/json?from=".urlencode($config['from'])."&text=".urlencode($config['message'])."&to=".urlencode($config['to'])."&api_key=".urlencode($config['username'])."&api_secret=".urlencode($config['password'])."";
            $data = file_get_contents(@$url);
            return $data; 
        }

        #--------------------------------------------       
        public function send_clickatell_message($config = [])
        {
            echo "comment out"; 
        }


        private function _do_api_call($url)
        {
            echo "comment out";
        }
        #---------------------------------------

        private $operator = array('11','12','13','14','15','16','17','18','19'); 

        public function validMobile($mobile = null)
        {    
                        echo "comment out";
        }


        protected function checkValidMobileOperator($mobile = null)
        {
            echo "comment out";         
        } 


        public function template($config = null)
        {
            echo "comment out";  
        }

    } 
