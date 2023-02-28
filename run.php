<?php
require_once '_DataHandler.php';
require_once '_Comlink.php';
set_time_limit(0);

//php -r "require 'full File Path'; function(arguments);"

function getPlayerData($allyCode, $host = "local", $port = 3000, $username = null, $password = null){
  echo "Preparing to get player data.\n";
  $fileHandler = new DataHandler($host, $port, $username, $password);
  $fileHandler->playerData($allyCode);
  echo "File created/updated successfully.";
}

function getGuildData($allyCodes, $host="local", $port = 3000, $username = null, $password = null){
  if(is_array($allyCodes)){
    echo "Preparing to retrieve guild data.\n";
    $fileHandler = new DataHandler($host,$port,$username,$password);
    $fileHandler->guildData($allyCodes);
    echo "All files created successfully.";
  }else{
    echo "Argument must be an array. You can achieve this by using array(#,#,#) and sepearating each entry with a comma.";
  }
}

function getGameData($filtered =true, $allCollections = false, $host="local", $port = 3000, $username = null, $password = null){
  echo "Preparing to update all json files.\n";
  $fileHandler = new DataHandler($host, $port,$username,$password);
  $fileHandler->dataFiles($filtered, $allCollections);
  echo "All files created successfully.";
}

function buildTerritoryBattles($lang = "ENG_US"){
  $fileHandler = new DataHandler();
  $fileHandler->territoryBattles($lang);
  echo "File created successfully";
}

function buildUnitStatDefinition($lang = "ENG_US"){
  $fileHandler = new DataHandler();
  $fileHandler->unitStatDefinition($lang);
  echo "File created successfully";
}

function help(){
  $response = 'You can use the following functions:
  getPlayerData(allyCode, host, port, username, password)
     Retrieves the player profile belonging to the ally code provided.
     Parameters:
       allyCode (string/integer) - the ally code of the player
       OPTIONAL: host (string) - the web address SWGOH Comlink is running on. Default is localhost
       OPTIONAL: port (integer) - the port SWGOH Comlink is running on. Default is 3000
       OPTIONAL: username (string) - the access key or username needed by SWGOH Comlink if any
       OPTIONAL: password (string) - the secret key or password needed by SWGOH Comlink if any
  getGuildData(allyCodes, host, port, username, password)
     Retrieves multiple player profiles belonging to each specified ally code provided.
     Parameters:
       allyCodes (array) - the ally codes of each player in a guild(s)
       OPTIONAL: host (string) - the web address SWGOH Comlink is running on. Default is localhost
       OPTIONAL: port (integer) - the port SWGOH Comlink is running on. Default is 3000
       OPTIONAL: username (string) - the access key or username needed by SWGOH Comlink if any
       OPTIONAL: password (string) - the secret key or password needed by SWGOH Comlink if any
  getGameData(filtered, allCollections, host, port, username, password)
     Retrieves data collections related to different aspects of the game.
     Parameters:
       OPTIONAL: filtered (bool) - only returns the most useful data needed in each collection to reduce file sizes. The default is true.
       OPTIONAL: allCollections (bool) - returns all possible data collections the game uses. The default is false.
       OPTIONAL: host (string) - the web address SWGOH Comlink is running on. Default is localhost
       OPTIONAL: port (integer) - the port SWGOH Comlink is running on. Default is 3000
       OPTIONAL: username (string) - the access key or username needed by SWGOH Comlink if any
       OPTIONAL: password (string) - the secret key or password needed by SWGOH Comlink if any
  buildTerritoryBattles(lang)
    Creates Territory Battles file.
    Parameters:
      lang (string) - the language you want to use, not all data can be translated. Default is ENG_US
      OPTIONS: CHS_CN, CHT_CN, ENG_US, FRE_FR, GER_DE, IND_ID, ITA_IT,
               JPN_JP, KOR_KR, POR_BR, RUS_RU, SPA_XM, THA_TH, TUR_TR
  ';
  echo $response;
}

function about(){
  $response = "SWGOH Comlink for GitHub is designed to get data from the Star Wars Galaxy of Heroes public API 
via SWGOH Comlink and then save it to your hard drive and/or GitHub repo.
  SWGOH Comlink is created by Munelear
  SWGOH Comlink for GitHub is created by Kidori";

  echo $response;
}

?>