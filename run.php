<?php
require_once '_DataHandler.php';
require_once '_Comlink.php';
set_time_limit(0);

//php -r "require 'full File Path'; function(arguments);"

function getPlayerData($allyCode, $host = "local", $port = 3000, $accessKey = null, $secretKey = null){
  echo "Preparing to get player data.\n";
  $fileHandler = new DataHandler($host, $port, $accessKey, $secretKey);
  $fileHandler->playerData($allyCode);
  echo "File created/updated successfully.";
}

function getGuildData($ids, $host="local", $port = 3000, $accessKey = null, $secretKey = null){
  if(is_array($ids)){
    echo "Preparing to retrieve guild data.\n";
    $fileHandler = new DataHandler($host,$port,$accessKey,$secretKey);
    $fileHandler->guildData($ids);
    echo "All files created successfully.";
  }else{
    echo "Argument must be an array. You can achieve this by using array(#,#,#) and sepearating each entry with a comma.";
  }
}

function getGameData($filtered =true, $allCollections = false, $host="local", $port = 3000, $accessKey = null, $secretKey = null){
  echo "Preparing to update all json files.\n";
  $fileHandler = new DataHandler($host, $port,$accessKey,$secretKey);
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

function getEvents($host="local", $port = 3000, $accessKey = null, $secretKey = null){
  echo "Preparing to update events file.\n";
  $fileHandler = new DataHandler($host, $port,$accessKey,$secretKey);
  $fileHandler->currentEvents();
  echo "All files created successfully.";
}

function getRecommendations($guildType = "gp", $recType = "standard", $count = 5,$minGear = 10, $lang="ENG_US", $host="local", $port = 3000, $accessKey = null, $secretKey = null){
  echo "This function is still in development.";
  return;
  /*echo "Preparing to create recommendation file(s).\n";
  $fileHandler = new DataHandler($host, $port,$accessKey,$secretKey);
  $fileHandler->buildRecommendations($guildType,$recType,$count,$minGear, $lang);
  echo "All files created successfully.";*/
}

function help(){
  $response = 'You can use the following functions:
  getPlayerData(allyCode, host, port, accessKey, secretKey)
     Retrieves the player profile belonging to the ally code provided.
     Parameters:
       allyCode (string/integer) - the ally code of the player
       OPTIONAL: host (string) - the web address SWGOH Comlink is running on. Default is localhost
       OPTIONAL: port (integer) - the port SWGOH Comlink is running on. Default is 3000
       OPTIONAL: accessKey (string) - the access key or accessKey needed by SWGOH Comlink if any
       OPTIONAL: secretKey (string) - the secret key or secretKey needed by SWGOH Comlink if any
  getGuildData(ids, host, port, accessKey, secretKey)
     Grabs guild data for up to 2 guilds and returns the members rosters.
     Parameters:
       ids (array) - the player id, ally code, or guild ids for the guilds to grab
       OPTIONAL: host (string) - the web address SWGOH Comlink is running on. Default is localhost
       OPTIONAL: port (integer) - the port SWGOH Comlink is running on. Default is 3000
       OPTIONAL: accessKey (string) - the access key or accessKey needed by SWGOH Comlink if any
       OPTIONAL: secretKey (string) - the secret key or secretKey needed by SWGOH Comlink if any
  getGameData(filtered, allCollections, host, port, accessKey, secretKey)
     Retrieves data collections related to different aspects of the game.
     Parameters:
       OPTIONAL: filtered (bool) - only returns the most useful data needed in each collection to reduce file sizes. The default is true.
       OPTIONAL: allCollections (bool) - returns all possible data collections the game uses. The default is false.
       OPTIONAL: host (string) - the web address SWGOH Comlink is running on. Default is localhost
       OPTIONAL: port (integer) - the port SWGOH Comlink is running on. Default is 3000
       OPTIONAL: accessKey (string) - the access key or accessKey needed by SWGOH Comlink if any
       OPTIONAL: secretKey (string) - the secret key or secretKey needed by SWGOH Comlink if any
  buildTerritoryBattles(lang)
    Creates Territory Battles file.
    Parameters:
      lang (string) - the language you want to use, not all data can be translated. Default is ENG_US
        OPTIONS: CHS_CN, CHT_CN, ENG_US, FRE_FR, GER_DE, IND_ID, ITA_IT,
               JPN_JP, KOR_KR, POR_BR, RUS_RU, SPA_XM, THA_TH, TUR_TR
  getEvents(host, port, accessKey, secretKey)
    Gets a list of all current and upcoming events that can appear in the Events screen in-game. Does not return guild events.
    Parameters:
    OPTIONAL: host (string) - the web address SWGOH Comlink is running on. Default is localhost
    OPTIONAL: port (integer) - the port SWGOH Comlink is running on. Default is 3000
    OPTIONAL: accessKey (string) - the access key or accessKey needed by SWGOH Comlink if any
    OPTIONAL: secretKey (string) - the secret key or secretKey needed by SWGOH Comlink if any
  getRecommendations(guildType, recType, count, minGear, lang, host, port, accessKey, secretKey)
    Grabs a list of guilds and then analyzes their rosters to give recommendations for mods, zetas, omicrons, and stats.
    Parameters:
      OPTIONAL: guildType (string) - the type of leaderboard to get. Default is gp
        OPTIONS: raids, pvp, gp
      OPTIONAL: recType (string) - the recommendation file to create. Default is standard
        OPTIONS: standard, mod_rolls, all
        standard does not include mod_rolls which is a special option for analyzing mod rolls
      OPTIONAL: count (integer) - the number of guilds to analyze. Default is 30, max is 200.
        Warning: Times to get over 30 guilds has not been tested and could result in hours of execution. Depending on your machine it takes about 4 minutes for every 500 player profiles.
      OPTIONAL: minGear (integer or string) - the minimum gear or relic level to include. Default is gear 10.
        OPTIONS: 1-13, R1-R9+
      OPTIONAL: lang (string) - the language to use for names and stats. Default is ENG_US.
      OPTIONAL: host (string) - the web address SWGOH Comlink is running on. Default is localhost
      OPTIONAL: port (integer) - the port SWGOH Comlink is running on. Default is 3000
      OPTIONAL: accessKey (string) - the access key or accessKey needed by SWGOH Comlink if any
      OPTIONAL: secretKey (string) - the secret key or secretKey needed by SWGOH Comlink if any
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