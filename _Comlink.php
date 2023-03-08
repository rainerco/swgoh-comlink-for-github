<?php
/*
Instructions
See https://github.com/swgoh-utils/swgoh-comlink/wiki for more help and information.
Example that returns all units
    $comlink = new Comlink($host, $port, $accessKey, $secretKey);
    $units = $comlink->fetchData($version, $segment, $includePve, $enums );
*/
/**
 * SWGoH Comlink API wrapper used for making api calls to Star Wars Galaxy of Heroes to return data from the game.
 * Available Methods:
 * - .fetchMetaData()
 * - .fetchEnums()
 * - .fetchPlayer()
 * - .fetchPlayerArena()
 * - .fetchGuild()
 * - .fetchData()
 * - .fetchLocalization()
 * - .fetchEvents()
 * - .fetchGuildsByName()
 * - .fetchGuildsByCriteria()
 * - .fetchGuildLeaderboard
 * - .fetchGACBrackets()
 * - .fetchGACLeaderboards()
 */
class Comlink
{
    private $data;
    private $player;
    private $enums;
    private $guild;
    private $metadata;
    private $localization;

    /** 
     * @param string $host - The web address the api is running on. Default is localhost.
     * - "local" = localhost
     * - any valid url
     * @param integer $port - Only used when running on localhost
     * @param string $accessKey - The access key if one is required for the api
     * @param string $secretKey - The secret key if one is required for the api
    */
    public function __construct($host = "local",$port = 3000,$accessKey=null,$secretKey=null)
    {
        //Security settings
        if($accessKey !== null && $secretKey !== null){
            $this->hmac = true;
        }else {
            $this->hmac = false;
        }
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
        //Endpoints
        if($host === "local"){
            $host = "http://localhost:".$port;
        }
        $this->metadata = $host."/metadata";
        $this->localization = $host."/localization";
        $this->player = $host."/player";
        $this->playerArena = $host."/playerArena";
        $this->guild = $host."/guild";
        $this->getGuilds = $host."/getGuilds";
        $this->getEvents = $host."/getEvents";
        $this->getLeaderboard = $host."/getLeaderboard";
        $this->getGuildLeaderboard = $host."/getGuildLeaderboard";
        $this->data = $host."/data";
        $this->enums = $host."/enums";
    }

    /**
     * Builds signature for secure connections using HMAC
     * @param string $post The payload request information
     * @param string $fetchUrl The url address for comlink
     * @param string $time The current unix epoch time
     * @return string Returns the authorization header
     */
    private function getAuthorization($post, $fetchUrl,$time)
    {
        $endpoint = substr($fetchUrl, strrpos($fetchUrl,"/") );
        $msg = $time."POST".$endpoint;
        $body = hash("md5",$post);
        $msg .= $body;
        $secret = hash_hmac("sha256",$msg, $this->secretKey);
        return "Authorization: HMAC-SHA256 Credential=".$this->accessKey.",Signature=".$secret;
    }

    /**
     * The curl used to get the data from comlink
     * @param string $post The payload request information
     * @param string $fetchUrl The url address for comlink
     * @param string $type The request method; POST or GET
     * @return object Returns the response from comlink
     */
    private function jwt_request($post, $fetchUrl, $type) {

             $ch = curl_init($fetchUrl); // INITIALISE CURL

            if($this->hmac && $type === "POST"){
                $time = strval(time() * 1000);
                $authorization = $this->getAuthorization($post,$fetchUrl,$time);
                $timeHeader = "X-Date:".$time;
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization, $timeHeader ));
            }else{
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' ));
            }
             curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
             curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
             curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
             $result = curl_exec($ch);
             $code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
             // Look for http errors
             if($code !==200){
                throw new Error("Error: HTTP Response Code = ".$code." ".$this->getResponseCode($code));
             };
             curl_close($ch);
             return $result;   
    }

    /**
     * Retrieves the requested data from comlink and returns the json format of it
     * @param string $post The payload request information
     * @param string $fetchUrl The url address for comlink
     * @param string $type The request method; Default is POST
     * @return object Returns the response from comlink
     */
    public function fetchAPI( $fetchUrl, $payload, $type = "POST" ) {
        try {
            $request = $this->jwt_request($payload, $fetchUrl, $type);

            // Look for Comlink errors
            $temp = json_decode($request);
            if(isset($temp->data[0]->keyword) && $temp->data[0]->keyword === "errorMessage"){
                throw new Exception($temp->message);
            }
            return $request;

        } catch(Exception $e) {
            throw $e;
        }
    }

    /**
     * Returns the HTTP Response code description.
     * @param integer $code The http response code
     * @return string The description for the http response code
     */
    private function getResponseCode($code){
        switch(strVal($code)){
            case "100": return "Continue";
            case "101": return "Switching Protocols";
            case "200": return "OK";
            case "201": return "Created";
            case "202": return "Accepted";
            case "203": return "Non-Authoritative Information";
            case "204": return "No Content";
            case "205": return "Reset Content";
            case "206": return "Partial Content";
            case "300": return "Multiple Choices";
            case "301": return "Moved Permanently";
            case "302": return "Found";
            case "303": return "See Other";
            case "304": return "Not Modified";
            case "305": return "Use Proxy";
            case "306": return "(Unused)";
            case "307": return "Temporary Redirect";
            case "400": return "Bad Request";
            case "401": return "Unauthorized";
            case "402": return "Payment Required";
            case "403": return "Forbidden";
            case "404": return "Not Found";
            case "405": return "Method Not Allowed";
            case "406": return "Not Acceptable";
            case "407": return "Proxy Authentication Required";
            case "408": return "Request Timeout";
            case "409": return "Conflict";
            case "410": return "Gone";
            case "411": return "Length Required";
            case "412": return "Precondition Failed";
            case "413": return "Request Entity Too Large";
            case "414": return "Request-URI Too Long";
            case "415": return "Unsupported Media Type";
            case "416": return "Requested Range Not Satisfiable";
            case "417": return "Expectation Failed";
            case "500": return "Internal Server Error";
            case "501": return "Not Implemented";
            case "502": return "Bad Gateway";
            case "503": return "Service Unavailable";
            case "504": return "Gateway Timeout";
            case "505": return "HTTP Version Not Supported";
            default: return "An unknown error has occurred.";
        }

    }

    /**
     * Returns the metadata for the game including version data
     * @param boolean $enums Optional: Indicates using enums for values where applicable. Default is false.
     * @return object The metadata
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

    /**
     * Returns a list of the enums available
     */
    public function fetchEnums()
    {
        try {
          $myObj = new stdClass();
          $myObj->payload = new stdClass();
          return $this->fetchAPI($this->enums, json_encode($myObj), "GET");
        } catch (Exception $e) {
            throw $e;
        }
    }

    /** 
     * Grabs a player profile from the game data.
     * @param string $allycode The player id or ally code. Also accepts integer values.
     * @param boolean $enums Optional: Indicates using enums for values where applicable. Default is false.
     * @return object The requested player profile
     */
    public function fetchPlayer($allycode, $enums = false)
    {
        try {
          $allycode = strVal($allycode);
          $myObj = new stdClass();
          $myObj->payload = new stdClass();
          if (strlen($allycode) > 11){
            $myObj->payload->playerId = $allycode;            
          } else {
            if(strlen($allycode) > 9){
                $allycode = preg_replace('/-/',"",$allycode);
            }
            $myObj->payload->allyCode = $allycode;
          }
          $myObj->enums = $enums;
          return $this->fetchAPI($this->player, json_encode($myObj));
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    /** 
     * Retrieves only the arena data from a player's profile.
     * @param string $allycode The player id or ally code. Also accepts integer values.
     * @param boolean $enums Optional: Indicates using enums for values where applicable. Default is false.
     * @return object The requested player arena profile
     */
    public function fetchPlayerArena($allycode, $enums = false)
    {
        try {
          $allycode = strVal($allycode);
          $myObj = new stdClass();
          $myObj->payload = new stdClass();
          if (strlen($allycode) > 11){
            $myObj->payload->playerId = $allycode;            
          } else {
            $myObj->payload->allyCode = $allycode;
          }
          $myObj->enums = $enums;
          return $this->fetchAPI($this->playerArena, json_encode($myObj));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Retrieves non-player game data separated into collections.
     * @param string $version The current game version id found within metadata
     * @param integer $segment Optional: Indicates returning the whole collection or specific chunks of it. Default is all.
     * - 0 = All
     * - 1,2,3,4 = See wiki Game Data for details
     * @param boolean $includePveUnits Optional: Indicates including npcs in data. Default is false.
     * @param boolean $enums Optional: Indicates using enums for values where applicable. Default is false.
     * @return object The requested data
     */
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

    /**
     * Retrieves the localization files
     * @param string $version The localization bundle version found within metadata
     * @param boolean $unzip Optional: Indicates returning the data as a zip file to reduce bandwidth. Default is true.
     * @return object The localization data separated by 
     */
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

    /**
     * Retrieves the guild profile specified.
     * @param string $guildId The guild id
     * @param boolean $enums Optional: Indicates using enums for values where applicable. Default is false.
     * @return object The guild profile
     */
    public function fetchGuild($guildId, $enums = false)
    {
        try {
            $myObj = new stdClass();
            $myObj->payload = new stdClass();
            $myObj->payload->guildId = strval($guildId);
            $myObj->payload->includeRecentGuildActivityInfo = true;
            $myObj->enums = $enums;
            return $this->fetchAPI($this->guild, json_encode($myObj));  
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Retrieve a list of current and upcoming events. Does not include guild events.
     * @param boolean $enums Optional: Indicates using enums for values where applicable. Default is false.
     * @return object The event data
     */
    public function fetchEvents($enums = false)
    {
        try {
            $myObj = new stdClass();
            $myObj->enums = $enums;
            return $this->fetchAPI($this->getEvents, json_encode($myObj));  
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Searches for guilds by name and returns a small portion of the guild profile. Returns all guilds that contain the specified search value in their name.
     * @param string $name The name or portion of the name to search for.
     * @param integer $startIndex Optional: Indicates which character to start at within the names. Default is 0.
     * @param integer $count Optional: Indicates up-to how many guild results to return. Default is 100, max is 10,000.
     * @param boolean $enums Optional: Indicates using enums for values where applicable. Default is false.
     * @return object The list of guilds
     */
    public function fetchGuildsByName($name, $startIndex = 0, $count = 100, $enums = false)
    {
        try {
            $myObj = new stdClass();
            $myObj->payload = new stdClass();
            $myObj->payload->filterType = 4;
            $myObj->payload->startIndex = $startIndex;
            $myObj->payload->name = $name;
            $myObj->payload->count = $count;
            $myObj->enums = $enums;
            return $this->fetchAPI($this->getGuilds, json_encode($myObj));  
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Searches for guilds by criteria and returns a small portion of the guild profile. Results may not always return all or the same data.
     * @param integer $minMembers The minimum number guild members in the guild. Default is 1
     * @param integer $maxMembers The maximum number guild members in the guild. Default is 50
     * @param integer $minGuildGP The minimum guild Galactic Power. Default is 1
     * @param integer $maxGuildGP The axnimum guild Galactic Power. Default is 999999999
     * @param integer $count Optional: Indicates up-to how many guild results to return. Default is 100, max is 10,000.
     * @param array $tbParticipation Optional: Indicates returning only guilds that have results for participating in specified Territory Battles. See the wiki for more details. Default is ignoring this option.
     * @param boolean $includeInviteOnly Optional: Indicates including guilds that are invite only. Default is true.
     * @param boolean $enums Optional: Indicates using enums for values where applicable. Default is false.
     * @return object THe list of guilds that match the criteria
     */
    public function fetchGuildsByCriteria($minMembers = 1, $maxMembers = 50, $minGuildGP = 1, $maxGuildGP = 999999999, $count = 100, $tbParticipation = null, $includeInviteOnly = true, $enums = false)
    {
        try {
            $myObj = new stdClass();
            $myObj->payload = new stdClass();
            $myObj->payload->filterType = 5;
            $myObj->payload->count = $count;
            $myObj->payload->searchCriteria = new stdClass();
            $myObj->payload->searchCriteria->minMemberCount = $minMembers;
            $myObj->payload->searchCriteria->maxMemberCount = $maxMembers;
            $myObj->payload->searchCriteria->minGuildGalacitcPower = $minGuildGP;
            $myObj->payload->searchCriteria->maxGuildGalacticPower = $maxGuildGP;
            $myObj->payload->searchCriteria->includeInviteOnly = $includeInviteOnly;
            $myObj->enums = $enums;
            if($tbParticipation !== null){
                $myObj->payload->searchCriteria->recentTbParticipatedIn = $tbParticipation;
            }
            return $this->fetchAPI($this->getGuilds, json_encode($myObj));  
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Retrieves the specified guild leaderboards and returns the results.
     * @param integer $type Indicates the specified leaderboard to return.
     * - 0 = Total Raid Points
     * - 2 = Specified Raid Points Requires $eventId
     * - 3 = Galactic Power
     * - 4 = Territory Battle Requires $eventId
     * - 5 = Territory Wars Requires $eventId
     * @param integer $count Indicates where you want the leaderboard to stop. Default and max is 200.
     * @param integer $month Indicates returning this month or the previous month results. Default is 0.
     * - 0 = Current month
     * - 1 = Previous month
     * @param string $eventId The id for the event
     * - sith_raid = Raid Sith Triumverate
     * - rancor = Raid The Pit
     * - aat = Raid Tank Takedown
     * - rancor_challenge = Raid The Pit Challenge Tier
     * - t01D = TB Rebel Assault
     * - to2D = TB Imperial Retaliation
     * - t03D = TB Separatist Might
     * - t04D = TB Republic Offensive
     * - TERRITORY_WAR_LEADERBOARD = Territory War
     * @param boolean $enums Optional: Indicates using enums for values where applicable. Default is false.
     * @return object The list of guilds
     */
    public function fetchGuildLeaderboard($type, $count = 200, $month = 0, $eventId = null, $enums = false)
    {
        try {
            $myObj = new stdClass();
            $myObj->payload = new stdClass();
            $myObj->payload->leaderboardId = array(new stdClass());
            $myObj->payload->leaderboardId[0]->leaderboardType = $type;
            $myObj->payload->leaderboardId[0]->monthOffset = $month;
            $myObj->payload->count = $count;
            $myObj->enums = $enums;
            if($eventId !== null){
                $myObj->payload->leaderboardId[0]->defId = $eventId;
            }
            return $this->fetchAPI($this->getGuildLeaderboard, json_encode($myObj));  
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Returns the specified GAC bracket leaderboard. Can only be retrieved during the event and only for that event.
     * @param string $instanceId The event id and instance id found within fetchEvents separated by a :
     * - E.G. CHAMPIONSHIPS_GRAND_ARENA_GA2_EVENT_SEASON_36:O1676412000000
     * @param string $groupId The event id, instance id, league name, and bracket number all separated by :
     * - E.G. CHAMPIONSHIPS_GRAND_ARENA_GA2_EVENT_SEASON_36:O1676412000000:KYBER:100
     * @param boolean $enums Optional: Indicates using enums for values where applicable. Default is false.
     */
    public function fetchGACBrackets($instanceId, $groupId, $enums = false)
    {
        try {
            $myObj = new stdClass();
            $myObj->payload = new stdClass();
            $myObj->payload->leaderboardType = 4;
            $myObj->payload->eventInstanceId = $instanceId;
            $myObj->payload->groupId = $groupId;
            $myObj->enums = $enums;
            return $this->fetchAPI($this->getLeaderboard, json_encode($myObj));  
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Returns the global League and division leaderboard specified.
     * @param integer $league The league id
     * - 20 = Carbonite
     * - 40 = Bronzium
     * - 60 = Chromium
     * - 80 = Aurodium
     * - 100 = Kyber
     * @param integer $div The division id
     * - 5 = Division 5
     * - 10 = Division 4
     * - 15 = Division 3
     * - 20 = Division 2
     * - 25 = Division 1
     * @param boolean $enums Optional: Indicates using enums for values where applicable. Default is false.
     */
    public function fetchGACLeaderboards($league, $div, $enums = false)
    {
        try {
            $myObj = new stdClass();
            $myObj->payload = new stdClass();
            $myObj->payload->leaderboardType = 6;
            $myObj->payload->league = $league;
            $myObj->payload->division = $div;
            $myObj->enums = $enums;
            return $this->fetchAPI($this->getLeaderboard, json_encode($myObj));  
        } catch (Exception $e) {
            throw $e;
        }
    }
}
?>