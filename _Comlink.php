<?php
//***Instructions***
//Change './tokenlocation'

class Comlink
{
    private $user;
    private $signin;
    private $data;
    private $player;
    private $guild;
    private $metadata;
    private $localization;

    /** 
     * @param string $host - The web address the api is running on
     * @param integer $port - Only used when running on localhost
     * @param string $username - The username or access key if one is required for the api
     * @param string $password - The password or secret key if one is required for the api
    */

    public function __construct($host = "local",$port = 3000,$username=null,$password=null)
    {
        if($host === "local"){
            $host = "http://localhost:".$port;
        }
        //$this->user = "username=".$username;
        //$this->user .= "&password=".$password;
        //$this->user .= "&grant_type=password&client_id=abc&client_secret=123";
        $this->metadata = $host."/metadata";
        $this->localization = $host."/localization";
        $this->player = $host."/player";
        $this->data = $host."/data";
    }

    public function login()
    {
        try {
            $opts = array(
                'http'=>array(
                    'method'=>"POST",
                    'header'=>"Content-Type: application/x-www-form-urlencoded",
                    'content'=>$this->user
                )
            );
            $context = stream_context_create($opts);
            $auth = file_get_contents($this->signin, false, $context);
            $obj = json_decode($auth);

            if (!isset($obj->access_token)) {
                throw new Exception('Cannot login with these credentials!');
            }

            file_put_contents('./tokenlocation.txt', $obj->access_token);
            return $obj->access_token;
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function jwt_request($token, $post, $fetchUrl) {

             $ch = curl_init($fetchUrl); // INITIALISE CURL

             //header('Content-Type: application/json');
             //$authorization = "Authorization: Bearer ".$token; // **Prepare Authorization Token**
             //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // **Inject Token into Header**
             curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' )); // **Inject Token into Header**
             curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
             curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
             curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
             $result = curl_exec($ch);
             curl_close($ch);
             return $result;
    }
          
    public function fetchAPI( $fetchUrl, $payload ) {
        try {
            //$logintoken = file_get_contents('./tokenlocation.txt');
            //if( !$logintoken ) { $logintoken = $this->login(); }

            //$authorization = "Authorization: Bearer ".$logintoken;
            //$request = $this->jwt_request($logintoken, $payload, $fetchUrl);
            $request = $this->jwt_request(null, $payload, $fetchUrl);

            $temp = json_decode($request);
            if (isset($temp->code) && $temp->code > 200){
              //$logintoken = $this->login();
              //$authorization = "Authorization: Bearer ".$logintoken;
              $request = $this->jwt_request(null, $payload, $fetchUrl);
            }

            return $request;

        } catch(Exception $e) {
            throw $e;
        }
    }

    /*See https://gitlab.com/swgoh-tools/swgoh-comlink/-/wikis/Getting-Started for more help and information.
      Example that returns all units
       
        $comlink = new Comlink($port, $username, $password);

        $units = $scomlink->fetchData($version, $segment, $includePve, $enums );
    */
    public function fetchMetadata($enums = false)
    {
        try {
          $myObj = new stdClass();
          $myObj->payload = new stdClass();
          $myObj->enums = $enums;
          return $this->fetchAPI($this->metadata, json_encode($myObj, JSON_NUMERIC_CHECK));
        } catch (Exception $e) {
            throw $e;
        }
    }
    //This function can take several allycodes separated by commas.
    public function fetchPlayer($allycode, $enums = false)
    {
        try {
          $myObj = new stdClass();
          $myObj->payload = new stdClass();
          $myObj->payload->allyCode = strval($allycode);
          $myObj->enums = $enums;
          return $this->fetchAPI($this->player, json_encode($myObj));
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function fetchData($version, $segment = 0, $includePveUnits = false, $enums = false)
    {
        try {
          $myObj = new stdClass();
          $myObj->payload = new stdClass();
          $myObj->payload->version = $version;
          $myObj->payload->includePveUnits = $includePveUnits;
          $myObj->payload->requestSegment = $segment;
          $myObj->enums = $enums;
          return $this->fetchAPI($this->data, json_encode($myObj, JSON_NUMERIC_CHECK));
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function fetchLocalization($version, $unzip = true)
    {
        try {
          $myObj = new stdClass();
          $myObj->payload = new stdClass();
          $myObj->payload->id = $version;
          $myObj->unzip = $unzip;
          return $this->fetchAPI($this->localization, json_encode($myObj, JSON_NUMERIC_CHECK));
        } catch (Exception $e) {
            throw $e;
        }
    }
}
?>