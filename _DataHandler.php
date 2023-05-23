<?php
require_once '_Comlink.php';
//require_once '_StatCalc.php';
ini_set('memory_limit', '6048M');

/**
 * DataHandler is a class for getting and converting needed game files for executing various methods and contains the following public methods:
 * 
 * - getFiles($for, $fileNames) - Used to get needed data files
 * - getLocalization($lang) - Gets localization specified
 * - splitLocalization($lang = "all") - Splits localization by language
 * - playerData($allyCode) - saves player data to drive
 * - guildData($allyCodes)- saves guild data to drive
 * - dataFiles($filtered, $allCollections) - saves collection files to drive
 * - filterData($data, $collection) - filters dataFiles
 * - buildGameData($rawData) - creates gameData.json file from code
*/
class DataHandler
{
  //PROPERTIES
    private $dataVersion;
    private $localVersion;
    private $comlink;
    private $unitInfo = array();
    //private $fullPath = realpath(dirname(__FILE__));
    private $filteredFiles = array(
      "ability" => true,
      "units" => true,
      "campaign" => true
    );
    private $defaultFiles = array(
      //Unit info
      "ability" => "ability",
      "category" => "category",
      "equipment" => "equipment",
      "material" => "material",
      "modRecommendation" => "modRecommendation",
      "recipe" => "recipe",
      "skill" => "skill",
      "units" => "units",
      "relicTierDefinition" => "relicTierDefinition",
      "statMod" => "statMod",
      "statModSet" => "statModSet",
      "statProgression" => "statProgression",
      "table" => "table",
      "xpTable" => "xpTable",
      //Game data
      "challenge" => "challenge",
      "challengeStyle" => "challengeStyle",
      "dailyActionCap" => "dailyActionCap",
      "playerTitle" => "playerTitle",
      "playerPortrait" => "playerPortrait",
      "campaign" => "campaign",
      "unitGuideDefinition" => "unitGuideDefinition",
      "scavengerConversionSet" => "scavengerConversionSet",
      "energyReward" => "energyReward",
      "cooldown" => "cooldown",
      "mysteryBox" => "mysteryBox",
      "mysteryStatMod" => "mysteryStatMod",
      "guildRaid" => "guildRaid",
      "calendarCategoryDefinition" => "calendarCategoryDefinition",
      "dailyLoginRewardDefinition" => "dailyLoginRewardDefinition",
      //Conquest info
      "artifactDefinition" => "artifactDefinition",
      "artifactTierDefinition" => "artifactTierDefinition",
      "conquestDefinition" => "conquestDefinition",
      "conquestMission" => "conquestMission",
      "consumableDefinition" => "consumableDefinition",
      "consumableTierDefinition" => "consumableTierDefinition",
      "consumableType" => "consumableType",
      //GAC Info
      "seasonDefinition" => "seasonDefinition",
      "seasonRewardTable" => "seasonRewardTable",
      "territoryTournamentDefinition" => "territoryTournamentDefinition",
      "territoryTournamentLeagueDefinition"  => "territoryTournamentLeagueDefinition",
      "territoryTournamentDivisionDefinition" => "territoryTournamentDivisionDefinition",
      "territoryTournamentDailyRewardTable" => "territoryTournamentDailyRewardTable",
      "territoryTournamentMatchmakingDescKey" => "territoryTournamentMatchmakingDescKey",
      //Territory Battle
      "territoryBattleDefinition" => "territoryBattleDefinition",
      //Datacrons
      "battleTargetingRule" => "battleTargetingRule",
      "datacronSet" => "datacronSet",
      "datacronTemplate" => "datacronTemplate",
      "datacronAffixTemplateSet" => "datacronAffixTemplateSet"
    );

  //CONSTRUCTOR
    /**
     * 
     */
    public function __construct($host="local",$port=3000,$username=null,$password=null){
      $this->comlink = new Comlink($host, $port,$username,$password);
      $metadata = json_decode($this->comlink->fetchMetadata(),true);
      $this->dataVersion = $metadata["latestGamedataVersion"];
      $this->localVersion = $metadata["latestLocalizationBundleVersion"];
    }
  //PUBLIC METHODS
    public function getFiles($for, $fileNames = array()){
      $dir = realpath(dirname(__FILE__))."/data/";
      switch($for){
        case "custom":
          $data = array();
          foreach($fileNames as $file){
            $rawData = file_get_contents($dir.$file.".json");
            $data[$file] = json_decode($rawData,true);
            $rawData = null;
          }
          return $data;
          break;
        case "conquest":
          $needFiles = array("artifactDefinition","conquestDefinition","conquestMission","consumableDefinition","consumableType");
          foreach($needFiles as $file){
            $rawData = file_get_contents($dir.$file.".json");
            $data[$file] = json_decode($rawData,true);
            $rawData = null;
          }
          return $data;
          break;
        case "gameData":
          $needFiles = array("equipment", "skill", "units","relicTierDefinition","statModSet","statProgression","table","xpTable");
          foreach($needFiles as $file){
            $rawData = file_get_contents($dir.$file.".json");
            $data[$file] = json_decode($rawData,true);
            $rawData = null;
          }
          return $data;
          break;
        case "territoryBattles":
          $needFiles = array("campaign","mysteryBox","equipment","material","table","territoryBattleDefinition","ability","units","category");
          foreach($needFiles as $file){
            $rawData = file_get_contents($dir.$file.".json");
            $data[$file] = json_decode($rawData,true);
            $rawData = null;
          }
          return $data;
          break;
        case "battles":
          $needFiles = array("material","equipment","units","campaign");
          foreach($needFiles as $file){
            $rawData = file_get_contents($dir.$file.".json");
            $data[$file] = json_decode($rawData,true);
            $rawData = null;
          }
          return $data;
          break;
        case "units":
          $needFiles = array("ability","category","equipment","recipe","skill","units","detailsShip","detailsChar");
          foreach($needFiles as $file){
            $rawData = file_get_contents($dir.$file.".json");
            $data[$file] = json_decode($rawData,true);
            $rawData = null;
          }
          return $data;
          break;
        case "unitPage":
          $needFiles = array("ability","skill","units");
          foreach($needFiles as $file){
            $rawData = file_get_contents($dir.$file.".json");
            $data[$file] = json_decode($rawData,true);
            $rawData = null;
          }
          return $data;
          break;
        case "stats":
          $needFiles = array("equipment","units");
          foreach($needFiles as $file){
            $rawData = file_get_contents($dir.$file.".json");
            $data[$file] = json_decode($rawData,true);
            $rawData = null;
          }
          return $data;
          break;
        case "details":
          $needFiles = array("category","units","unitDetails");
          foreach($needFiles as $file){
            $rawData = file_get_contents($dir.$file.".json");
            $data[$file] = json_decode($rawData,true);
            $rawData = null;
          }
          return $data;
          break;
        case "ability":
          $needFiles = array("ability","recipe","skill","units");
          foreach($needFiles as $file){
            $rawData = file_get_contents($dir.$file.".json");
            $data[$file] = json_decode($rawData,true);
            $rawData = null;
          }
          return $data;
          break;
        default:  
      }
    }


  public function getLocalization($lang){
    $fullPath = realpath(dirname(__FILE__));
    $localFile = file_get_contents($fullPath."/localization/".$lang.".txt");
    $split = explode("\n", $localFile);
        $localize = array();
        foreach($split as $id){
          $pos = strpos($id, "|");
          if($pos !== false){
            $key = substr($id, 0, $pos);
            $val = substr($id, $pos+1);
            $localize[$key] = $val;
          }
        }
        return $localize;
}

    /**
     * Takes the main datamined localization file and splits it into individual txt files on the server.
     * @param string $fileName The file name for the datamined game file. Default is 'localization_Comlink.json'
     * @param string $lang The language you want to use.
     */
    public function splitLocalization($lang = "all"){
      $fullPath = realpath(dirname(__FILE__));
      $languages = array();
      if($lang === "all"){
        $languages = array(
          "Loc_CHS_CN.txt",
          "Loc_CHT_CN.txt",
          "Loc_ENG_US.txt",
          "Loc_FRE_FR.txt",
          "Loc_GER_DE.txt",
          "Loc_IND_ID.txt",
          "Loc_ITA_IT.txt",
          "Loc_JPN_JP.txt",
          "Loc_KOR_KR.txt",
          "Loc_POR_BR.txt",
          "Loc_RUS_RU.txt",
          "Loc_SPA_XM.txt",
          "Loc_THA_TH.txt",
          "Loc_TUR_TR.txt"
        );
      }else{
        $languages = array("Loc_".strtoupper($lang).".txt");
      }
      $data = json_decode($this->comlink->fetchLocalization($this->localVersion),true);
      foreach($languages as $language => $set){
        $split = explode("\n", $data[$set]);
        $localize = array();
        foreach($split as $id){
          $pos = strpos($id, "|");
          if($pos !== false){
            $key = substr($id, 0, $pos);
            $val = substr($id, $pos+1);
            $localize[$key] = $val;
          }
        }
        $name = substr($set,4,6);
        $newFile = fopen($fullPath."/localization/".$name.".json", "w");
        $saveData = json_encode($localize);
        fwrite($newFile,$saveData);
        fclose($newFile);
      }
    }


    /**
     * Saves the Player response to the drive
     * @param array $allyCode List of player allycodes
     * @param string $useAlly Assigns the file name either allycode or playerid. Default is Allycode
     */
    public function playerData($allyCode, $useAlly = true){
      if( !is_array($allyCode) ){
        $allyCode = array($allyCode);
      }
      foreach($allyCode as $id){
        $data = json_decode($this->comlink->fetchPlayer($id),true);
        if($useAlly){
          $fileName = $data["allyCode"];
        }else{
          $fileName = $data["playerId"];
        }
        $fullPath = realpath(dirname(__FILE__));
        $newFile = fopen($fullPath."/player/".$fileName.".json", "w");
        $saveData = json_encode($data);
        fwrite($newFile,$saveData);
        fclose($newFile);  
      }
    }

    /**
     * Saves multiple Player responses to the drive
     * @param array $ids - The player ids, ally codes or guild ids to get guild data for
     */
    public function guildData($ids, $isPlayerID=false){
      $fullPath = realpath(dirname(__FILE__));
      //Get Guild Ids
      $guildIds = array();
      if(strlen(strval($ids[0])) < 12 || $isPlayerID){
        foreach($ids as $player){
          array_push($guildIds, json_decode($this->comlink->fetchPlayer($player),true)["guildId"]);
        }
      } else {
        $guildIds = $ids;
      }
      //Get Guild data
      $guildData = array();
      $memberData = array();
      foreach($guildIds as $guild){
        $guildData = json_decode($this->comlink->fetchGuild($guild),true);
        $fileName = $guildData["guild"]["profile"]["id"];
        $newFile = fopen($fullPath."/guild/".$fileName."_PROFILE.json", "w");
        $saveData = json_encode($guildData);
        fwrite($newFile,$saveData);
        fclose($newFile);
        echo "Guild profile created for ".$guildData["guild"]["profile"]["name"].".\n";
        $memCount = count($guildData["guild"]["member"]);
        $indx = 1;
        $counter = 0;
        foreach($guildData["guild"]["member"] as $member){
          array_push($memberData, json_decode($this->comlink->fetchPlayer($member["playerId"]),true));
          $counter++;
          if($counter === 10 || $counter === $memCount){
            $newFile = fopen($fullPath."/guild/".$fileName."_ROSTER".$indx.".json", "w");
            $saveData = json_encode($memberData);
            fwrite($newFile,$saveData);
            fclose($newFile);
            echo "Guild roster segment ".$indx." created.\n";
            //Reset counters and data
            $memberData = array();
            $indx++;
            $memCount = $memCount - 10;
            $counter = 0;
          }
        }
        while($indx < 6){
          $newFile = fopen($fullPath."/guild/".$fileName."_ROSTER".$indx.".json", "w");
          $saveData = json_encode(array());
          fwrite($newFile,$saveData);
          fclose($newFile);
          $indx++;
        }
      }
    }

    /**
     * Saves the Player response to the drive
     * @param string $allyCode The players ally code
     */
    public function dataFiles($filtered, $allCollections){
      $allData = json_decode($this->comlink->fetchData($this->dataVersion),true);

      $stdFiles = $this->defaultFiles;
      $fullPath = realpath(dirname(__FILE__));

      //Build gameData
      $this->buildGameData($allData);
      echo "gameData.json file created\n";

      //Build localization
      $this->splitLocalization();
      echo "Localization files created.\n";

      if(!$allCollections){
        foreach(array_keys($allData) as $fileID){
          if(array_key_exists($fileID, $stdFiles)){
            if($filtered && array_key_exists($fileID,$this->filteredFiles) ){
              $allData[$fileID] = $this->filterData($allData[$fileID], $fileID);
            }
            $newFile = fopen($fullPath."/data/".$fileID.".json", "w");
            $saveData = json_encode($allData[$fileID]);
            fwrite($newFile,$saveData);
            fclose($newFile);
          }
        }
      }else{
        foreach(array_keys($allData) as $fileID){
          if(array_key_exists($fileID, $stdFiles)){
            if($filtered && array_key_exists($fileID,$this->filteredFiles)){
              $allData[$fileID] = $this->filterData($allData[$fileID], $fileID);
            }
            $newFile = fopen($fullPath."/data/".$fileID.".json", "w");
            $saveData = json_encode($allData[$fileID]);
            fwrite($newFile,$saveData);
            fclose($newFile);
          }
        }
      }
      echo "All collection files were created successfully.\n";

      //Build Territory Battle collection

    }


    /**
     * Returns the filtered collection data
     * @return array
     */
    public function filterData($data, $collection){
      $tempData = array();
      switch($collection){
        case "units":
          foreach($data as $unit){
            if($unit["obtainable"] == true and $unit["obtainableTime"] == 0){
              array_push($tempData, array(
                "categoryId"  => $unit["categoryId"],
                "skillReference" => $unit["skillReference"],
                "unitTier" => $unit["unitTier"],
                "limitBreakRef" => (count($unit["limitBreakRef"]) > 0) ? array_map(function($ability){
                  return array(
                    "abilityId" => $ability["abilityId"],
                    "requiredTier" => $ability["requiredTier"],
                    "requiredRelicTier" => $ability["requiredRelicTier"],
                    "powerAdditiveTag" => $ability["powerAdditiveTag"],
                    "unlockRecipeId" => $ability["unlockRecipeId"]
                  );
                },$unit["limitBreakRef"]) : array(),
                "uniqueAbilityRef" =>  (count($unit["uniqueAbilityRef"]) > 0) ? array_map(function($ability){
                  return array(
                    "abilityId" => $ability["abilityId"],
                    "requiredTier" => $ability["requiredTier"],
                    "requiredRelicTier" => $ability["requiredRelicTier"],
                    "powerAdditiveTag" => $ability["powerAdditiveTag"],
                    "unlockRecipeId" => $ability["unlockRecipeId"]
                  );
                },$unit["uniqueAbilityRef"]) : array(),
                "crew" => $unit["crew"],
                "crewContributionTableId" => $unit["crewContributionTableId"],
                "modRecommendation" => $unit["modRecommendation"],
                "exampleSquad" => (count($unit["exampleSquad"]) > 0) ? array_map(function($squad){
                  return array("unitDefId" => $squad["unitDefId"]);
                }, $unit["exampleSquad"]) : array(),
                "obtainable" => $unit["obtainable"],
                "obtainableTime" => $unit["obtainableTime"],
                "id" => $unit["id"],
                "nameKey" => $unit["nameKey"],
                "rarity" => $unit["rarity"],
                "maxRarity" => $unit["maxRarity"],
                "forceAlignment" => $unit["forceAlignment"],
                "xpTableId"  => $unit["xpTableId"],
                "actionCountMax" => $unit["actionCountMax"],
                "combatType" => $unit["combatType"],
                "descKey" => $unit["descKey"],
                "baseId" => $unit["baseId"],
                "thumbnailName" => $unit["thumbnailName"],
                "maxLevelOverride" => $unit["maxLevelOverride"],
                "promotionRecipeReference" => $unit["promotionRecipeReference"],
                "statProgressionId" => $unit["statProgressionId"],
                "creationRecipeReference" => $unit["creationRecipeReference"],
                "basicAttackRef" =>  array(
                    "abilityId" => $unit["basicAttackRef"]["abilityId"],
                    "requiredTier" => $unit["basicAttackRef"]["requiredTier"],
                    "requiredRelicTier" => $unit["basicAttackRef"]["requiredRelicTier"],
                    "powerAdditiveTag" => $unit["basicAttackRef"]["powerAdditiveTag"],
                    "unlockRecipeId" => $unit["basicAttackRef"]["unlockRecipeId"]
                  ),
                "leaderAbilityRef" => ($unit["leaderAbilityRef"] !== null) ? array(
                    "abilityId" => $unit["leaderAbilityRef"]["abilityId"],
                    "requiredTier" => $unit["leaderAbilityRef"]["requiredTier"],
                    "requiredRelicTier" => $unit["leaderAbilityRef"]["requiredRelicTier"],
                    "powerAdditiveTag" => $unit["leaderAbilityRef"]["powerAdditiveTag"],
                    "unlockRecipeId" => $unit["leaderAbilityRef"]["unlockRecipeId"]
                  ) : null,
                "baseStat" => $unit["baseStat"],
                "legend" => $unit["legend"],
                "primaryUnitStat" => $unit["primaryUnitStat"],
                "relicDefinition" => ($unit["relicDefinition"] !== null) ?
                  $unit["relicDefinition"]["relicTierDefinitionId"] : null                
              ));
            }
          }
          break;
        case "ability":
          foreach($data as $ability){
            array_push($tempData, array(
              "tier" => $ability["tier"],
              "descriptiveTag"  => $ability["descriptiveTag"],
              "effectReference"  => $ability["effectReference"],
              "id" => $ability["id"],
              "nameKey" => $ability["nameKey"],
              "descKey" => $ability["descKey"],
              "cooldown" => $ability["cooldown"],
              "icon" => $ability["icon"],
              "abilityType" => $ability["abilityType"],
              "useAsReinforcementDesc" => $ability["useAsReinforcementDesc"],
              "ultimateChargeRequired" => $ability["ultimateChargeRequired"]
            ));
          }
          break;
        case "campaign":
          foreach($data as $content){
            if($content["id"] !== "TW_EVENTS" && $content["id"] !== "C00"){
              array_push($tempData, $content);
            }
          }
          break;
        default:        
      }
      return $tempData;
    }

    /**
     * Saves the Player response to the drive
     * @param array $events Optional: The list of events to include
     * @param string $startTime Optional: The start data of the event
     * @param string $endTime Optional: The end date of the event
     * @param boolean $enums Optional: To return enum values where available. Default is false
     */
    public function currentEvents($enums = false){
      $fullPath = realpath(dirname(__FILE__));
      $data = json_decode($this->comlink->fetchEvents($enums),true);
      $newFile = fopen($fullPath."/data/currentEvents.json", "w");
      $saveData = json_encode($data);
      fwrite($newFile,$saveData);
      fclose($newFile);
    }


    /** 
     * Builds the gameData file used with stat calculations
     * @param array $rawData Is the full game data object
    */
    private function buildGameData($rawData){
      $data = array();
      $dataType = "float";

    //Load gearData array
      foreach($rawData["equipment"] as $gear){
        $statList = array_key_exists("statList", $gear["equipmentStat"]) ? $gear["equipmentStat"]["statList"] : $gear["equipmentStat"]["stat"];
        if (count($statList) > 0){
          $data[$gear["id"]] = array("stats" => array());
          foreach($statList as $stat){
            settype($stat["unscaledDecimalValue"], "float");
            $data[$gear["id"]]["stats"][$stat["unitStatId"]] = $stat["unscaledDecimalValue"];
          }
        }
      }
      $this->gameData["gearData"] = $data;
      $data = array(); //Clear array for next data

    //Load modSetData array
      foreach($rawData["statModSet"] as $set){
        $id = $this->convertStat("integer",$set["completeBonus"]["stat"]["unitStatId"]); 
        settype($set["completeBonus"]["stat"]["unscaledDecimalValue"], $dataType);
        $data[$set["id"]] = array(
          "id" => $id,
          "count" => $set["setCount"],
          "value" => $set["completeBonus"]["stat"]["unscaledDecimalValue"]
        );
      }
      $this->gameData["modSetData"] = $data;
      $data = array(); //Clear array for next data

    //Load crTables and gpTables
      $data["cr"] = array();
      $data["gp"] = array();
      $rarityEnum = array(
        "ONE_STAR" => 1,
        "TWO_STAR" => 2,
        "THREE_STAR" => 3,
        "FOUR_STAR" => 4,
        "FIVE_STAR" => 5,
        "SIX_STAR" => 6,
        "SEVEN_STAR" => 7
      );
      $statEnum = $this->convertStat("int array");
      foreach($rawData["table"] as $table){
        switch($table["id"]){
          case "galactic_power_modifier_per_ship_crew_size_table":
            $data["gp"]["crewSizeFactor"] = array();
            foreach($table["row"] as $row){
              settype($row["value"], "float");
              $data["gp"]["crewSizeFactor"][$row["key"]] = $row["value"];
            }
            break;
          case "crew_rating_per_unit_rarity":
            $data["cr"]["crewRarityCR"] = array();
            foreach($table["row"] as $row) {
              settype($row["value"],$dataType);
              $data["cr"]["crewRarityCR"][ $rarityEnum[$row["key"]] ] = $row["value"];
            }
            $data["gp"]["unitRarityGP"] = $data["cr"]["crewRarityCR"]; // used for both CR and GP
            break;
          case "crew_rating_per_gear_piece_at_tier":
            $data["cr"]["gearPieceCR"] = array();
            foreach($table["row"] as $row) {
              settype($row["value"],$dataType);
              $data["cr"]["gearPieceCR"][intval(substr($row["key"],-2))] = $row["value"];
            }
            break;
          case "galactic_power_per_complete_gear_tier_table":
            $data["gp"]["gearLevelGP"] = array("1" => 0.0); // initialize with value of 0 for unit's at gear 1 (which have none 'complete')
            foreach($table["row"] as $row) {
              // 'complete gear tier' is one less than current gear level, so increment key by one
              settype($row["value"],$dataType);
              $data["gp"]["gearLevelGP"][ (intval(substr($row["key"],-2))+1) ] = $row["value"];
            }
            $data["cr"]["gearLevelCR"] = $data["gp"]["gearLevelGP"]; // used for both GP and CR
            break;
          case "galactic_power_per_tier_slot_table":
            $data["gp"]["gearPieceGP"] = array();
            foreach($table["row"] as $row) {
              $tierSlot = explode(":", $row["key"]);
              if(array_key_exists($tierSlot[0], $data["gp"]["gearPieceGP"]) == false){ //$data["gp"]["gearPieceGP"][$tierSlot[0]] == NULL){
                $data["gp"]["gearPieceGP"][ $tierSlot[0] ] = array(); 
              }
              settype($row["value"],$dataType);
              $data["gp"]["gearPieceGP"][$tierSlot[0]][ ($tierSlot[1]-1) ] = $row["value"]; // decrement slot by 1 as .help uses 0-based indexing for slot (game table is 1-based)
            }
            break;
          case "crew_contribution_multiplier_per_rarity":
            $data["cr"]["shipRarityFactor"] = array();
            foreach($table["row"] as $row) {
              settype($row["value"], "float");
              $data["cr"]["shipRarityFactor"][ $rarityEnum[$row["key"]] ] = $row["value"];
            }
            $data["gp"]["shipRarityFactor"] = $data["cr"]["shipRarityFactor"]; // used for both CR and GP
            break;
          case "galactic_power_per_tagged_ability_level_table":
            $data["gp"]["abilitySpecialGP"] = array();
            foreach($table["row"] as $row) {
              settype($row["value"], "float");
              $data["gp"]["abilitySpecialGP"][ $row["key"] ] = $row["value"];
              /*if ( $row["key"] == "zeta" ){ 
                $data["gp"]["abilitySpecialGP"][ $row["key"] ] = $row["value"];
              }else {
                $type, $level = preg_match('/^(\w+)_\w+?(\d)?$/',$row["key"]); //not sure what original designer was doing with this
                switch ($type) {
                  case "contract":
                    if(array_key_exists($type, $data["gp"]["abilitySpecialGP"]) == false){ 
                      $data["gp"]["abilitySpecialGP"][ $type ] = array(); // ensure 'contract' table exists
                    }
                    $data["gp"]["abilitySpecialGP"][ $row["key"] ][ $type ][ ($level +1) || 1 ] = $row["value"];
                    break;
                  case "reinforcement":
                    if(array_key_exists("hardware", $data["gp"]["abilitySpecialGP"]) == false){ 
                      $data["gp"]["abilitySpecialGP"][ "hardware" ] = array("1" => 0); // ensure 'hardware' table exists (and counts 0 xp for tier 1)
                    }
                    $data["gp"]["abilitySpecialGP"][ "hardware" ][ $level + 1 ] = $row["value"];
                    break;
                  default:
                     console.error('Unknown ability type $row["key"] found.');
                }
              }*/
            }
            break;
          case "crew_rating_per_mod_rarity_level_tier":
            $data["cr"]["modRarityLevelCR"] = array();
            $data["gp"]["modRarityLevelTierGP"] = array();
            foreach($table["row"] as $row) {
              if ( substr($row["key"], -1) == "0") { // only 'select' set 0, as set doesn't affect CR or GP
                $mod = explode(":", $row["key"]); //0=pip, 1=Lv, 2=Tier, 3=Set
                settype($row["value"], "float");
                if ( $mod[2] == 1) { // tier doesn't affect CR, so only save for tier 1
                  if(array_key_exists($mod[0], $data["cr"]["modRarityLevelCR"]) == false){ 
                    $data["cr"]["modRarityLevelCR"][ $mod[0] ] = array(); // ensure table exists for that rarity
                  }    
                  $data["cr"]["modRarityLevelCR"][ $mod[0] ][ $mod[1] ] = $row["value"];
                }
                if(array_key_exists($mod[0], $data["gp"]["modRarityLevelTierGP"]) == false){ 
                  $data["gp"]["modRarityLevelTierGP"][ $mod[0] ] = array(); // ensure table exists for that rarity
                }    
                if(array_key_exists($mod[1], $data["gp"]["modRarityLevelTierGP"][ $mod[0] ]) == false){ 
                  $data["gp"]["modRarityLevelTierGP"][ $mod[0] ][ $mod[1] ] = array(); // ensure table exists for that rarity
                }    
                $data["gp"]["modRarityLevelTierGP"][ $mod[0] ][ $mod[1] ][ $mod[2] ] = $row["value"];
              }
            }
            break;
          case "crew_rating_modifier_per_relic_tier":
            $data["cr"]["relicTierLevelFactor"] = array();
            foreach($table["row"] as $row) {
              settype($row["value"], "float");
              $data["cr"]["relicTierLevelFactor"][ ($row["key"] + 2) ] = $row["value"]; // relic tier enum is relic level + 2
            }
            break;
          case "crew_rating_per_relic_tier":
            $data["cr"]["relicTierCR"] = array();
            foreach($table["row"] as $row) {
              settype($row["value"], "float");
              $data["cr"]["relicTierCR"][ ($row["key"] + 2) ] = $row["value"];
            }
            break;
          case "galactic_power_modifier_per_relic_tier":
            $data["gp"]["relicTierLevelFactor"] = array();
            foreach($table["row"] as $row) {
              settype($row["value"], "float");
              $data["gp"]["relicTierLevelFactor"][ ($row["key"] + 2) ] = $row["value"]; // relic tier enum is relic level + 2
            }
            break;
          case "galactic_power_per_relic_tier":
            $data["gp"]["relicTierGP"] = array();
            foreach($table["row"] as $row) {
              settype($row["value"], "float");
              $data["gp"]["relicTierGP"][ ($row["key"] + 2) ] = $row["value"];
            }
            break;
          case "crew_rating_modifier_per_ability_crewless_ships":
            $data["cr"]["crewlessAbilityFactor"] = array();
            foreach($table["row"] as $row) {
              settype($row["value"], "float");
              $data["cr"]["crewlessAbilityFactor"][ $row["key"] ] = $row["value"];
            }
            break;
          case "galactic_power_modifier_per_ability_crewless_ships":
            $data["gp"]["crewlessAbilityFactor"] = array();
            foreach($table["row"] as $row) {
              settype($row["value"], "float");
              $data["gp"]["crewlessAbilityFactor"][ $row["key"] ] = $row["value"];
            }
            break;
          default:
        }
        if(preg_match('/_mastery$/',$table["id"])){ // id matches itself only if it ends in _mastery
            // These are not actually CR or GP tables, but were placed in the 'crTables' section of gameData when first implemented.
            // Still placed there for backwards compatibility
            $data["cr"][ $table["id"] ] = array();
            foreach($table["row"] as $row) {
              settype($row["value"], "float");
              $data["cr"][ $table["id"] ][ $statEnum[$row["key"]] ] = $row["value"];
            }
        }
      }

      foreach($rawData["xpTable"] as $table){
        $tempTable = array();
        if ( preg_match('/^crew_rating/', $table["id"]) || preg_match('/^galactic_power/',$table["id"]) ) {
          foreach($table["row"] as $row) {
            $tempTable[ $row["index"] +1 ] = $row["xp"];
          }
          switch ( $table["id"] ) {
            // 'CR' tables appear to be for both CR and GP on characters
            // 'GP' tables specify ships, but are currently idendical to the 'CR' tables.
            case "crew_rating_per_unit_level":
              $data["cr"]["unitLevelCR"] = $tempTable;
              $data["gp"]["unitLevelGP"] = $tempTable;
              break;
            case "crew_rating_per_ability_level":
              $data["cr"]["abilityLevelCR"] = $tempTable;
              $data["gp"]["abilityLevelGP"] = $tempTable;
              break;
            case "galactic_power_per_ship_level_table":
              $data["gp"]["shipLevelGP"] = $tempTable;
              break;
            case "galactic_power_per_ship_ability_level_table":
              $data["gp"]["shipAbilityLevelGP"] = $tempTable;
              break;
            default:
              return;
          }
        }
      }
      $this->gameData["crTables"] = $data["cr"];
      $this->gameData["gpTables"] = $data["gp"];
      $data = array(); //Clear array for next data

    //Load relicData
      $statTables = array();
      foreach($rawData["statProgression"] as $table){
        if(preg_match('/^stattable_/',$table["id"])){
          $tableData = array();
          $tableStat = array_key_exists("statList", $table["stat"]) ? $table["stat"]["statList"] : $table["stat"]["stat"];
          foreach($tableStat as $stat){
            settype($stat["unscaledDecimalValue"],"float");
            $tableData[$stat["unitStatId"]] = $stat["unscaledDecimalValue"];
          }
          $statTables[$table["id"]] = $tableData;
        }
      }
      foreach($rawData["relicTierDefinition"] as $relic){
        $data[ $relic["id"] ] = array( 
              "stats" => array(), 
              "gms" => $statTables[ $relic["relicStatTable"] ] 
            );
        $relicStat = array_key_exists("statList", $relic["stat"]) ? $relic["stat"]["statList"] : $relic["stat"]["stat"];
        foreach($relicStat as $stat) {
          settype($stat["unscaledDecimalValue"],"float");
          $data[ $relic["id"] ]["stats"][ $stat["unitStatId"] ] = $stat["unscaledDecimalValue"];
        }
      }
      $this->gameData["relicData"] = $data;
      $data = array(); //Clear array for next data

    //Load unitData
      $skills = array();
      foreach($rawData["skill"] as $skill){
        $s = array(
          "id" => $skill["id"],
          "maxTier" => array_key_exists("tierList", $skill) ? (count($skill["tierList"]) + 1) : (count($skill["tier"]) + 1),
          "powerOverrideTags" => array(),
          "isZeta" => $skill["isZeta"]
        );
        if($s["isZeta"]){
          $i = 2;
          $skillTier = array_key_exists("tierList", $skill) ? $skill["tierList"] : $skill["tier"];
          foreach($skillTier as $tier){
            if($tier["powerOverrideTag"]){
              $s["powerOverrideTags"][$i] = $tier["powerOverrideTag"];
            }
            $i = $i + 1;
          }
        }
        $skills[$skill["id"]] = $s;
      }
      $unitGMTables = array();
      foreach($rawData["units"] as $unit){
        if($unit["obtainable"] == true && $unit["obtainableTime"] == 0){
          if(array_key_exists($unit["baseId"], $unitGMTables) == false){
            $unitGMTables[$unit["baseId"]] = array();
          }
          $unitGMTables[ $unit["baseId"] ][ $unit["rarity"] ] = $statTables[ $unit["statProgressionId"] ];
          //build baseList
          if($unit["rarity"] == 1){
            if ( $unit["combatType"] == 1 ) { // character
              $tierData = array();
              $relicData = array();
              $skillData = array();
              $unitTier = array_key_exists("unitTierList", $unit) ? $unit["unitTierList"] : $unit["unitTier"];
              foreach($unitTier as $gearTier) {
                $tierData[ $gearTier["tier"] ] = array( 
                            "gear" => array_key_exists("equipmentSetList", $gearTier) ? $gearTier["equipmentSetList"] : $gearTier["equipmentSet"], 
                            "stats" => array()
                );
                $gearTierStat = array_key_exists("statList", $gearTier["baseStat"]) ? $gearTier["baseStat"]["statList"] : $gearTier["baseStat"]["stat"];
                foreach($gearTierStat as $stat) {
                  settype($stat["unscaledDecimalValue"],"float");
                  $tierData[ $gearTier["tier"] ]["stats"][ $stat["unitStatId"] ] = $stat["unscaledDecimalValue"];
                }
              }
              $unitRelicDefList = array_key_exists("relicTierDefinitionIdList", $unit["relicDefinition"]) ? $unit["relicDefinition"]["relicTierDefinitionIdList"] : $unit["relicDefinition"]["relicTierDefinitionId"]; 
              foreach($unitRelicDefList as $tier) {
                $relicData[ intval(substr($tier,-2)) +2 ] = $tier;
              }
              $unitSkillRefList = array_key_exists("skillReferenceList", $unit) ? $unit["skillReferenceList"] : $unit["skillReference"];
              foreach($unitSkillRefList as $skill){
                array_push($skillData, $skills[$skill["skillId"]]);
              }
              $unitCatId = array_key_exists("categoryIdList", $unit) ? $unit["categoryIdList"] : $unit["categoryId"];
              $data[$unit["baseId"]] = array( "combatType" => 1,
                                    "primaryStat" => $unit["primaryUnitStat"],
                                    "gearLvl" => $tierData,
                                    "growthModifiers" => array(),
                                    "skills" => $skillData,
                                    "relic" => $relicData,
                                    "masteryModifierID" => $this->getMasteryMultiplierName($unit["primaryUnitStat"], $unitCatId)
              );
            } else { // ship
              $stats = array();
              $skillData = array();
              $unitBaseStat = array_key_exists("statList",$unit["baseStat"]) ? $unit["baseStat"]["statList"] : $unit["baseStat"]["stat"];
              foreach($unitBaseStat as $stat) {
                settype($stat["unscaledDecimalValue"], "float");
                $stats[ $stat["unitStatId"] ] = $stat["unscaledDecimalValue"];
              }
              $unitSkillRefList = array_key_exists("skillReferenceList", $unit) ? $unit["skillReferenceList"] : $unit["skillReference"];
              foreach($unitSkillRefList as $skill){
                array_push($skillData, $skills[$skill["skillId"]]);
              }
              $ship = array( "combatType" => 2,
                           "primaryStat" => $unit["primaryUnitStat"],
                           "stats" => $stats,
                           "growthModifiers" => array(),
                           "skills" => $skillData,
                           "crewStats" => $statTables[ $unit["crewContributionTableId"] ],
                           "crew" => array()
              );
              $crewLst = array_key_exists("crewList", $unit) ? $unit["crewList"] : $unit["crew"];
              foreach($crewLst as $crew) {
                array_push($ship["crew"], $crew["unitId"] );
                $crewRefList = array_key_exists("skillReferenceList", $crew) ? $crew["skillReferenceList"] : $crew["skillReference"];
                foreach($crewRefList as $sk){
                  array_push($ship["skills"], $skills[ $sk["skillId"] ] ); 
                } 
              }
              $data[$unit["baseId"]] = $ship;
            }
          }
        }
      }
      foreach($data as $key => $unit){
        $data[$key]["growthModifiers"] = $unitGMTables[ strtoupper($key) ];
      }
      $this->gameData["unitData"] = $data;
      $data = array();

    //Update saved file
    $fullPath = realpath(dirname(__FILE__));
      /*$updateFile = true;
      if(file_exists($fullPath."/data/gameData.json")){
        $getFile = file_get_contents($fullPath."/data/gameData.json");
        $currentFile = json_decode($getFile,true);
        $getFile = NULL;
      }else{
        $currentFile = null;
        $updateFile = true;
      }

      if($currentFile !== null){
        $currentUnitCount = count($currentFile["unitData"]);
        $newUnitCount = count($this->gameData["unitData"]);
        if($newUnitCount > $currentUnitCount){
          $updateFile = true;
        }
      }
      //*/
      //if($updateFile){
        //if(file_exists("./JSON/gameData.json")){
          $newFile = fopen($fullPath."/data/gameData.json", "w");
          $saveData = json_encode($this->gameData);
          fwrite($newFile,$saveData);
          fclose($newFile);
        //}
      //}
    }


    /**
     * DATABUILDER ONLY - Private function called from within buildGameData
     * @param string $primaryStatID Is the identifier for the unit's primary stat
     * @param array $tags Is the list of tags a unit has such as Light Side
    */
    private function getMasteryMultiplierName($primaryStatID, $tags) {
      $primaryStats = array(
              "2" => "strength",
              "3" => "agility",
              "4" => "intelligence",
              "UNITSTATAGILITY" => "agility",
              "UNITSTATSTRENGTH" => "strength",
              "UNITSTATINTELLIGENCE" => "intelligence"
      );
      foreach($tags as $tag){
        if(preg_match('/^role_(?!leader)[^_]+/', $tag)){ // select 'role' tag that isn't role_leader
          $role = $tag;
        }
      }
      //$role = $tags.filter( tag => /^role_(?!leader)[^_]+/.test(tag)); // select 'role' tag that isn't role_leader
      return $primaryStats[ $primaryStatID ].'_'.$role.'_mastery';
    } 

    /**
     * Used to convert stat values between integers and strings
     * @param string $to Specify the value to return
     * - Pass 'integer' to return the integer that represents the stat. 
     * - Pass 'string' to return the string name for the stat number. 
     * - Pass 'int array' to return the array used to return integers. 
     * - Pass 'str array' to return the array used to return strings."
     * @param mixed $stat The stat string or integer being converted
     * @return mixed Return value is based on $to value
     */
    private function convertStat($to, $stat = null){
      if($to === "integer" && is_integer($stat)){
        return $stat;
      }
      $stringSearch = array(
        "MAX_HEALTH" => 1,
        "STRENGTH" => 2,
        "AGILITY" => 3,
        "INTELLIGENCE" => 4,
        "SPEED" => 5,
        "ATTACK_DAMAGE" => 6,
        "ABILITY_POWER" => 7,
        "ARMOR" => 8,
        "SUPPRESSION" => 9,
        "ARMOR_PENETRATION" => 10,
        "SUPPRESSION_PENETRATION" => 11,
        "DODGE_RATING" => 12,
        "DEFLECTION_RATING" => 13,
        "ATTACK_CRITICAL_RATING" => 14,
        "ABILITY_CRITICAL_RATING" => 15,
        "CRITICAL_DAMAGE" => 16,
        "ACCURACY" => 17,
        "RESISTANCE" => 18,
        "DODGE_PERCENT_ADDITIVE" => 19,
        "DEFLECTION_PERCENT_ADDITIVE" => 20,
        "ATTACK_CRITICAL_PERCENT_ADDITIVE" => 21,
        "ABILITY_CRITICAL_PERCENT_ADDITIVE" => 22,
        "ARMOR_PERCENT_ADDITIVE" => 23,
        "SUPPRESSION_PERCENT_ADDITIVE" => 24,
        "ARMOR_PENETRATION_PERCENT_ADDITIVE" => 25,
        "SUPPRESSION_PENETRATION_PERCENT_ADDITIVE" => 26,
        "HEALTH_STEAL" => 27,
        "MAX_SHIELD" => 28,
        "SHIELD_PENETRATION" => 29,
        "HEALTH_REGEN" => 30,
        "ATTACK_DAMAGE_PERCENT_ADDITIVE" => 31,
        "ABILITY_POWER_PERCENT_ADDITIVE" => 32,
        "DODGE_NEGATE_PERCENT_ADDITIVE" => 33,
        "DEFLECTION_NEGATE_PERCENT_ADDITIVE" => 34,
        "ATTACK_CRITICAL_NEGATE_PERCENT_ADDITIVE" => 35,
        "ABILITY_CRITICAL_NEGATE_PERCENT_ADDITIVE" => 36,
        "DODGE_NEGATE_RATING" => 37,
        "DEFLECTION_NEGATE_RATING" => 38,
        "ATTACK_CRITICAL_NEGATE_RATING" => 39,
        "ABILITY_CRITICAL_NEGATE_RATING" => 40,
        "OFFENSE" => 41,
        "DEFENSE" => 42,
        "DEFENSE_PENETRATION" => 43,
        "EVASION_RATING" => 44,
        "CRITICAL_RATING" => 45,
        "EVASION_NEGATE_RATING" => 46,
        "CRITICAL_NEGATE_RATING" => 47,
        "OFFENSE_PERCENT_ADDITIVE" => 48,
        "DEFENSE_PERCENT_ADDITIVE" => 49,
        "DEFENSE_PENETRATION_PERCENT_ADDITIVE" => 50,
        "EVASION_PERCENT_ADDITIVE" => 51,
        "EVASION_NEGATE_PERCENT_ADDITIVE" => 52,
        "CRITICAL_CHANCE_PERCENT_ADDITIVE" => 53,
        "CRITICAL_NEGATE_CHANCE_PERCENT_ADDITIVE" => 54,
        "MAX_HEALTH_PERCENT_ADDITIVE" => 55,
        "MAX_SHIELD_PERCENT_ADDITIVE" => 56,
        "SPEED_PERCENT_ADDITIVE" => 57,
        "COUNTER_ATTACK_RATING" => 58,
        "TAUNT" => 59,
        "UNITSTATMAXHEALTHPERCENTADDITIVE" => 55,
        "UNITSTATDEFENSEPERCENTADDITIVE" => 49,
        "UNITSTATCRITICALDAMAGE" => 16,
        "UNITSTATCRITICALCHANCEPERCENTADDITIVE" => 53,
        "UNITSTATRESISTANCE" => 18,
        "UNITSTATOFFENSEPERCENTADDITIVE" => 48,
        "UNITSTATACCURACY" => 17,
        "UNITSTATSPEEDPERCENTADDITIVE" => 57
      );
      $numberSearch = array(
        "0" => "None",
        "1" => "Health",
        "2" => "Strength",
        "3" => "Agility",
        "4" => "Tactics",
        "5" => "Speed",
        "6" => "Physical Damage",
        "7" => "Special Damage",
        "8" => "Armor",
        "9" => "Resistance",
        "10" => "Armor Penetration",
        "11" => "Resistance Penetration",
        "12" => "Dodge Chance",
        "13" => "Deflection Chance",
        "14" => "Physical Critical Chance",
        "15" => "Special Critical Chance",
        "16" => "Critical Damage",
        "17" => "Potency",
        "18" => "Tenacity",
        "19" => "Dodge",
        "20" => "Deflection",
        "21" => "Physical Critical Chance",
        "22" => "Special Critical Chance",
        "23" => "Armor",
        "24" => "Resistance",
        "25" => "Armor Penetration",
        "26" => "Resistance Penetration",
        "27" => "Health Steal",
        "28" => "Protection",
        "29" => "Protection Ignore",
        "30" => "Health Regeneration",
        "31" => "Physical Damage",
        "32" => "Special Damage",
        "33" => "Physical Accuracy",
        "34" => "Special Accuracy",
        "35" => "Physical Critical Avoidance",
        "36" => "Special Critical Avoidance",
        "37" => "Physical Accuracy",
        "38" => "Special Accuracy",
        "39" => "Physical Critical Avoidance",
        "40" => "Special Critical Avoidance",
        "41" => "Offense",
        "42" => "Defense",
        "43" => "Defense Penetration",
        "44" => "Evasion",
        "45" => "Critical Chance",
        "46" => "Accuracy",
        "47" => "Critical Avoidance",
        "48" => "Offense",
        "49" => "Defense",
        "50" => "Defense Penetration",
        "51" => "Evasion",
        "52" => "Accuracy",
        "53" => "Critical Chance",
        "54" => "Critical Avoidance",
        "55" => "Health",
        "56" => "Protection",
        "57" => "Speed",
        "58" => "Counter Attack",
        "59" => "UnitStat_Taunt",
        "60" => "unknown",
        "61" => "Mastery"
      );
      switch($to){
        case "integer":
          return $stringSearch[$stat];
          break;
        case "string":
          return $numberSearch[$stat];
          break;
        case "int array":
          return $stringSearch;
          break;
        case "str array":
          return $numberSearch;
          break;
        default:
          throw new error("Invalid argument. Pass 'integer' to return the integer that represents the stat. Pass 'string' to return the string name for the stat number. Pass 'int array' to return the array used to return integers. Pass 'str array' to return the array used to return strings.");
      }

    }

    /**
     * Creates JSON file containing Territory Battle Information. Some data must be added manually.
     * @param string $lang - The language for names and decsriptions. Default is 'ENG_US'
     */
    public function territoryBattles($lang = "ENG_US"){
      $fullPath = realpath(dirname(__FILE__));
      $files = json_decode($this->comlink->fetchData($this->dataVersion,0,true),true);
      $localize = $this->getLocalization($lang);
      $tbData = array();
      $campaignData = array();
      $categoryData = array();
      $npcData = array();
      $unitData = array();
      $rewardData = array();
      $prizeBoxMap = array();
      $waveData = array();
      $abilityMap = array();
      $platoonData = $this->getFiles("custom",array("platoons"))["platoons"];
      $platoonMap = array();

      //Create arrays from other sources for quick access
      $rewardData["TERRITORY_BATTLE_CURRENCY"] = array("name"=>"Mk I Guild Event Token","image"=>"tex.icon_territory_credit_0");
      $rewardData["TERRITORY_BATTLE_CURRENCY_02"] = array("name"=>"Mk II Guild Event Token", "image"=>"tex.icon_territory_geonosis_credit_0");
      $rewardData["PREMIUM"]	= array("name"=>"Crystals","image"=>"icon_premium"); //Main image is technically in standard_rgba_atlas.png but a separate image is located in territorywarui_territoryscoreview/assets/texture2D
      $rewardData["unitshard_HOTHLEIA"] = array("name" => "Shard: Rebel Officer Leia Organa", "image" => "tex.charui_leiahoth");
      $rewardData["unitshard_IMPERIALPROBEDROID"] = array("name" => "Shard: Imperial Probe Droid", "image" => "tex.charui_probedroid");
      $rewardData["unitshard_WATTAMBOR"] = array("name" => "Shard: Wat Tambor", "image" => "tex.charui_wattambor");
      $rewardData["unitshard_KIADIMUNDI"] = array("name" => "Shard: Ki-Adi-Mundi", "image" => "tex.charui_kiadimundi");
      foreach($files["equipment"] as $gear){
        $color = null;
        switch($gear["tier"]){
          case 12:
            $color = "Yellow";
            break;
          case 4:
            $color = "Blue";
            break;
          case 2:
            $color = "Green";
            break;
          case 1:
            $color = "Grey"; //Technicaly more of a light sky blue
            break;
          default:
            $color = "Purple";
        }
        $rewardData[ $gear["id"] ] = array(
          "name" => array_key_exists($gear["nameKey"], $localize) ? $localize[ $gear["nameKey"] ] : $gear["nameKey"],
          "color" => $color,
          "image" => $gear["iconKey"] //image is specifically for gear icon, border and backgound must be manually built
        );
      }
      foreach($files["material"] as $item){
        $rewardData[ $item["id"] ] = array(
          "name" => array_key_exists($item["nameKey"], $localize) ? $localize[ $item["nameKey"] ] : $item["nameKey"],
          "image" => $item["iconKey"], //image is specifically for item icon, border and backgound must be manually built
          "color" => null
        );
      }
      for($i=0; $i < count($files["mysteryBox"]); $i++){
        $chest = $files["mysteryBox"][$i];
        $prizeBoxMap[ $chest["id"] ] = $i;
        $chestLabel = array_key_exists($chest["iconTextKey"], $localize) ? $localize[ $chest["iconTextKey"] ] : $chest["iconTextKey"];
        $rewardData[ $chest["id"] ] = array("name" => "Prize Box ".$chestLabel, "image" => $chest["texture"]);
      }
      foreach($files["table"] as $table){
        $waveData[ $table["id"] ] = $table["row"];
      }
      foreach($files["category"] as $category){
        $categoryData[$category["id"]] = array_key_exists($category["descKey"], $localize) ? $localize[$category["descKey"]] : $category["descKey"];
        if($categoryData[$category["id"]] == "Placeholder"){
          $nStr = str_replace("affiliation_","",$category["id"]);
          $nStr = str_replace("_", " ", $nStr);
          $nStr = ucwords($nStr);
          $categoryData[ $category["id"] ] = $nStr;
        }
      }
      foreach($files["units"] as $unit){
        $npcData[$unit["id"]] = array_key_exists($unit["nameKey"], $localize) ? $localize[ $unit["nameKey"] ] : $unit["nameKey"];
        if($unit["obtainable"] == true && $unit["rarity"] == 7 && $unit["obtainableTime"] == 0){
          $unitData[$unit["baseId"]] = array(
            "name" => array_key_exists($unit["nameKey"], $localize) ? $localize[ $unit["nameKey"] ] : $unit["nameKey"],
            "image" => $unit["thumbnailName"]
          );
          /*$name = array_key_exists($unit["nameKey"], $localize) ? $localize[ $unit["nameKey"] ] : $unit["nameKey"];
          $unitList[$name] = array(
            "name" => $name,
            "baseId" => $unit["baseId"],
            "image" => $unit["thumbnailName"]
          );*/ //COde to use if using platoons_name.json
        }
      }
      $this->unitInfo = $unitData; //change to unitList
      for($p = 0; $p < count($platoonData); $p++){
        $platoonMap[$platoonData[$p]["zone_id"]] = $p;
      }
      for($a = 0; $a < count($files["ability"]); $a++){
        $abilityMap[ $files["ability"][$a]["id"] ] = $a;
      }

      foreach($files["campaign"] as $event){
        if($event["campaignType"] === 11){
          if(array_key_exists("campaignMapList",$event)){
            $suf = "List";
          }else{
            $suf = "";
          }
          $tbID = $event["id"];
          $campaignData[$tbID] = array();
          $campaignData[$tbID]["alignment"] = ($event["campaignMap".$suf][0]["campaignNodeDifficultyGroup".$suf][0]["campaignNode".$suf][0]["campaignNodeMission".$suf][0]["entryCategoryAllowed"]["categoryId"][0] == "alignment_light") ? "Light Side" : "Dark Side";

          foreach($event["campaignMap".$suf][0]["campaignNodeDifficultyGroup".$suf][0]["campaignNode".$suf] as $territory){
            $campaignData[$tbID][ $territory["id"] ] = array(); //PHASE_#_TERRITORY##
            $campaignData[$tbID][ $territory["id"] ]["combatType"] = $territory["campaignNodeMission".$suf][0]["combatType"]; //1 (Ship) or 2 (Char)
            $campaignData[$tbID][ $territory["id"] ]["missions"] = array();
            foreach($territory["campaignNodeMission".$suf] as $mission){
              //Localizations
              $name = $mission["nameKey"];
               $name = array_key_exists($name, $localize) ? $localize[$name] : $name;
              $desc = $mission["descKey"];
               $desc = array_key_exists($desc, $localize) ? $localize[$desc] : $desc;
               //Build campaign mission array
              $campaignData[$tbID][ $territory["id"] ]["missions"][$mission["id"]] = array();
              $campaignData[$tbID][ $territory["id"] ]["missions"][$mission["id"]]["id"] = $mission["id"];
              $campaignData[$tbID][ $territory["id"] ]["missions"][$mission["id"]]["nameKey"] = $name;
              $campaignData[$tbID][ $territory["id"] ]["missions"][$mission["id"]]["desc"] = $desc;
              $campaignData[$tbID][ $territory["id"] ]["missions"][$mission["id"]]["waveCount"] = count($mission["campaignNodeEncounter".$suf]);
              $campaignData[$tbID][ $territory["id"] ]["missions"][$mission["id"]]["requirements"] = array( //entryCategoryAllowed
                "categories" => array(), //categoryIdList
                "units" => array(), //mandatoryRosterUnitList
                "unitRarity" => $mission["entryCategoryAllowed"]["minimumUnitRarity"],
                "unitLevel" => $mission["entryCategoryAllowed"]["minimumUnitLevel"],
                "gearLevel" => $mission["entryCategoryAllowed"]["minimumUnitTier"],
                "relicLevel" => $mission["entryCategoryAllowed"]["minimumRelicTier"],
                "galacticPower" => $mission["entryCategoryAllowed"]["minimumGalacticPower"]
              ); 
              foreach($mission["entryCategoryAllowed"]["categoryId".$suf] as $cat){
                $cat = $categoryData[$cat];
                array_push($campaignData[$tbID][ $territory["id"] ]["missions"][$mission["id"]]["requirements"]["categories"], $cat);
              }
              foreach($mission["entryCategoryAllowed"]["mandatoryRosterUnit".$suf] as $unit){
                array_push($campaignData[$tbID][ $territory["id"] ]["missions"][$mission["id"]]["requirements"]["units"], array(
                  "name" => $unitData[$unit["id"]]["name"],
                  "baseId" => $unit["id"],
                  "image" => $unitData[$unit["id"]]["image"]
                ));
              }
              $campaignData[$tbID][ $territory["id"] ]["missions"][$mission["id"]]["enemies"] = array();
              foreach($mission["enemyUnitPreview".$suf] as $enemy){
                array_push($campaignData[$tbID][ $territory["id"] ]["missions"][$mission["id"]]["enemies"], array(
                  "id" => $enemy["baseEnemyItem"]["id"],
                  "name" => $npcData[ $enemy["baseEnemyItem"]["id"] ],
                  "level" => $enemy["enemyLevel"],
                  "gearTier" => $enemy["enemyTier"],
                  "relicTier" => $enemy["enemyRelicTier"],
                  "imageKey" => $enemy["thumbnailName"],
                  "threatLevel" => $enemy["threatLevel"],
                  "alignment" => $enemy["enemyForceAlignment"]
                ));
              }
            }
          }
        }
      }
      
      foreach($files["territoryBattleDefinition"] as $tb){
        if(array_key_exists("conflictZoneDefintionList",$tb)){
          $suf = "List";
        }else{
          $suf = "";
        }
        //Set variable adjustments
        $tbID = $tb["id"];
        $tb_nameKey = array_key_exists($tb["nameKey"], $localize) ? $localize[$tb["nameKey"]] : $tb["nameKey"];
        //Build general info
        $tbData[$tbID] = array();
        $tbData[$tbID]["id"] = $tbID;
        $tbData[$tbID]["nameKey"] = $tb_nameKey;
        $tbData[$tbID]["name"] =  explode(": ", $tb_nameKey)[1];
        $tbData[$tbID]["map"] = explode(": ", $tb_nameKey)[0];
        $tbData[$tbID]["phaseCount"] = $tb["roundCount"];
        $tbData[$tbID]["phaseDuration"] = ($tb["roundCount"] == 6) ? 24 : 36;
        $tbData[$tbID]["obtainableStars"] = count($tb["rankRewardPreview".$suf]);
        $tbData[$tbID]["requirements"] = array(
          "unitAlignment"=> $campaignData[$tbID]["alignment"],
          "playerLevel"=> 65,
          "guildGP"=> ($tbID == "t03D") ? 80000000 : (($tbID == "t04D") ? 100000000 : 0),
          "units" => array(),
          "categories" => array()          
        );

        //Build granted abilities (manual)
        $tempAbility = array();
        switch($tbID){
          case "t01D":
            $tempAbility = array(
              "0" => array(
                "applyTo" => array(
                  "category" => "Heroes",
                  "units" => array(
                    "0" => array("name" => "Commander Luke Skywalker", "baseId" => "COMMANDERLUKESKYWALKER", "image" => $unitData["COMMANDERLUKESKYWALKER"]["image"]),
                    "1" => array("name" => "Hoth Rebel Soldier", "baseId" => "HOTHREBELSOLDIER", "image" => $unitData["HOTHREBELSOLDIER"]["image"]),
                    "2" => array("name" => "Hoth Rebel Scout", "baseId" => "HOTHREBELSCOUT", "image" => $unitData["HOTHREBELSCOUT"]["image"]),
                    "3" => array("name" => "Rebel Officer Leia Organa", "baseId" => "HOTHLEIA", "image" => $unitData["HOTHLEIA"]["image"])
                  )
                ),
                "ability" => array(
                  "nameKey"=> "Last Stand",
                  "descKey"=> "The first time this unit is defeated, it is Revived with Offense Up, Advantage, and the [c][ffff33]Last Stand[-][/c] unique debuff for 2 turns.\\n[c][ffff33]Last Stand:[-][/c] Defeated in 2 turns unless an enemy is defeated or the encounter ends; can't be Dispelled.",
                  "image" => "tex.abilityui_passive_rebel"    
                )
              ),
              "1" => array(
                "applyTo" => array(
                  "category" => "Rebel",
                  "units" => array()
                ),
                "ability" => array(
                  "nameKey"=> "Focused Defense",
                  "descKey"=> "At the end of this unit's turn, it gains Protection Up (30%) for 2 turns if it used a Special ability and no enemies were defeated this turn.",
                  "image" => "tex.abilityui_passive_def"
                    )
              )
            );
            break;
          case "t02D":
            $tempAbility = array(
              "0" => array(
                "applyTo" => array(
                  "category" => "Heroes",
                  "units" => array(
                    "0" => array("name" => "Darth Vader", "baseId" => "VADER", "image" => $unitData["VADER"]["image"]),
                    "1" => array("name" => "Colonel Starck", "baseId" => "COLONELSTARCK", "image" => $unitData["COLONELSTARCK"]["image"]),
                    "2" => array("name" => "General Veers", "baseId" => "VEERS", "image" => $unitData["VEERS"]["image"]),
                    "3" => array("name" => "Snowtrooper", "baseId" => "SNOWTROOPER", "image" => $unitData["SNOWTROOPER"]["image"]),
                    "4" => array("name" => "Imperial Probe Droid", "baseId" => "IMPERIALPROBEDROID", "image" => $unitData["IMPERIALPROBEDROID"]["image"])      
                  )
                ),
                "ability" => array(
                  "nameKey"=> "Malice",
                  "descKey"=> "Hoth Hero characters gain: The first time this unit uses a special ability each encounter, dispel all buffs on selected target.",
                  "image" => "tex.abilityui_passive_uniqueability"
                )
              ),
              "1" => array(
                "applyTo" => array(
                  "category" => "Empire",
                  "units" => array()
                ),
                "ability" => array(
                  "nameKey"=> "Imperial Might",
                  "descKey"=> "Empire characters gain: +10% Critical Chance, +10% Max Health, and +10% Lifesteal.",
                  "image" => "tex.abilityui_passive_empire"
                )
              )
            );
            break;
          case "t03D":
            $tempAbility = array(
              "0" => array(
                "applyTo" => array(
                  "category" => "Heroes",
                  "units" => array(
                    "0" => array("name" => "General Grievous", "baseId" => "GRIEVOUS", "image" => $unitData["GRIEVOUS"]["image"]),
                    "1" => array("name" => "Nute Gunray", "baseId" => "NUTEGUNRAY", "image" => $unitData["NUTEGUNRAY"]["image"]),
                    "2" => array("name" => "Count Dooku", "baseId" => "COUNTDOOKU", "image" => $unitData["COUNTDOOKU"]["image"]),
                    "3" => array("name" => "Poggle the Lesser", "baseId" => "POGGLETHELESSER", "image" => $unitData["POGGLETHELESSER"]["image"]),
                    "4" => array("name" => "Wat Tambor", "baseId" => "WATTAMBOR", "image" => $unitData["WATTAMBOR"]["image"])
                  )
                ),
                "ability" => array(
                  "name"=> "Separatist Motives",
                  "desc"=> "[c][ffff33]Separatist Motives:[-][/c] Whenever this character defeats an enemy, all Separatist allies gain 1 stack of Separatist Affiliation for the rest of battle (max 10 stacks). Separatist Affiliation becomes Separatist Loyalty at max stacks.\\n\\n[c][ffff33]Separatist Affiliation:[-][/c] Deal 5% more damage per stack with attacks not based on Health\\n\\n[c][ffff33]Separatist Loyalty:[-][/c] Deal 50% more damage with attacks not based on Health, at the start of turn recover 10% Health and Protection, and when defeated all other allies with Separatist Loyalty recover 50% of their Max Health and Max Protection and they gain 100% of the defeated ally's Offense and 15% of their Max Health until the end of their next turn",
                  "image" => "tex.abilityui_passive_separatist_motives"
                )
              )
            );
            break;
          case "t04D":
            $tempAbility = array(
              "0" => array(
                "applyTo" => array(
                  "category" => "Heroes",
                  "units" => array(
                    "0" => array("name" => "Padmé Amidala", "baseId" => "PADMEAMIDALA", "image" => $unitData["PADMEAMIDALA"]["image"]),
                    "1" => array("name" => "R2-D2", "baseId" => "R2D2_LEGENDARY", "image" => $unitData["R2D2_LEGENDARY"]["image"]),
                    "2" => array("name" => "General Kenobi", "baseId" => "GENERALKENOBI", "image" => $unitData["GENERALKENOBI"]["image"]),
                    "3" => array("name" => "Grand Master Yoda", "baseId" => "GRANDMASTERYODA", "image" => $unitData["GRANDMASTERYODA"]["image"]),
                    "4" => array("name" => "Jedi Knight Anakin", "baseId" => "ANAKINKNIGHT", "image" => $unitData["ANAKINKNIGHT"]["image"]),
                    "5" => array("name" => "Ahsoka Tano", "baseId" => "AHSOKATANO", "image" => $unitData["AHSOKATANO"]["image"]),
                    "6" => array("name" => "Mace Windu", "baseId" => "MACEWINDU", "image" => $unitData["MACEWINDU"]["image"]),
                    "7" => array("name" => "Ki-Adi-Mundi", "baseId" => "KIADIMUNDI", "image" => $unitData["KIADIMUNDI"]["image"]),
                    "8" => array("name" => "General Skywalker", "baseId" => "GENERALSKYWALKER", "image" => $unitData["GENERALSKYWALKER"]["image"]),
                    "9" => array("name" => "Shaak Ti", "baseId" => "SHAAKTI", "image" => $unitData["SHAAKTI"]["image"]),
                    "10" => array("name" => "CT-7567 \"Rex\"", "baseId" => "CT7567", "image" => $unitData["CT7567"]["image"]),
                    "11" => array("name" => "CT-5555 \"Fives\"", "baseId" => "CT5555", "image" => $unitData["CT5555"]["image"]),
                    "12" => array("name" => "CT-21-0408 \"Echo\"", "baseId" => "CT210408", "image" => $unitData["CT210408"]["image"]),
                    "13" => array("name" => "ARC Trooper", "baseId" => "ARCTROOPER501ST", "image" => $unitData["ARCTROOPER501ST"]["image"]),
                    "14" => array("name" => "Echo", "baseId" => "BADBATCHECHO", "image" => $unitData["BADBATCHECHO"]["image"]),
                    "15" => array("name" => "Tech", "baseId" => "BADBATCHTECH", "image" => $unitData["BADBATCHTECH"]["image"]),
                    "16" => array("name" => "Wrecker", "baseId" => "BADBATCHWRECKER", "image" => $unitData["BADBATCHWRECKER"]["image"]),
                    "17" => array("name" => "Huner", "baseId" => "BADBATCHHUNTER", "image" => $unitData["BADBATCHHUNTER"]["image"]),
                    "18" => array("name" => "Omega", "baseId" => "BADBATCHOMEGA", "image" => $unitData["BADBATCHOMEGA"]["image"])
                  )
                ),
                "ability" => array(
                  "nameKey"=> "Bravery",
                  "descKey"=> "Whenever this character uses an ability, they deal bonus damage equal to 5% of their target's Max Health if the target does not have Droid Battalion active on them, which can't be evaded. Also, whenever this character uses an ability targeting an enemy with Droid Battalion during their turn, they attack again using their Basic ability (limited once per turn).",
                  "image" => "tex.abilityui_passive_bravery"
                )
              )
            );
            break;
          default:
        }
        $tbData[$tbID]["grantedAbilities"] = $tempAbility;

        //Build Zones
        $tbData[$tbID]["zones"] = array();
        //-->Create territories first
        foreach($tb["conflictZoneDefinition".$suf] as $territory){
          $zoneID = $territory["zoneDefinition"]["zoneId"];
          $zName = $territory["zoneDefinition"]["nameKey"];
          $zDesc = $territory["zoneDefinition"]["descriptionKey"];
          $tbData[$tbID]["zones"][ $zoneID ] = array(
            "territoryId" => $zoneID,
            "territoryName" => array_key_exists($zName, $localize) ? $localize[$zName] : $zName, 
            "territoryDesc" => array_key_exists($zDesc, $localize) ? $localize[$zDesc] : $zDesc, 
            "combatType" => $territory["combatType"], // 1=Squad, 2=Fleet //-> combatType
            "starPointsRequirements" => array(
                "1" => intval($territory["victoryPointRewards".$suf][0]["galacticScoreRequirement"]), 
                "2" => intval($territory["victoryPointRewards".$suf][1]["galacticScoreRequirement"]),
                "3" => intVal($territory["victoryPointRewards".$suf][2]["galacticScoreRequirement"])
            ),
            "unlockRequirements" => null,
            "combatMissions" => array(),
            "specialMissions" => array(),
            "platoons" => array()
          );
          if($territory["zoneDefinition"]["unlockRequirement"] !== null){
            $tbData[$tbID]["zones"][ $zoneID ]["unlockRequirements"] = array("territory_completed" => array_map(function($zone){
              return $zone["id"];
            },$territory["zoneDefinition"]["unlockRequirement"]["requirementItem".$suf]));
          }  
        }

        //--Add combat missions
        foreach($tb["strikeZoneDefinition".$suf] as $combat){
          $zoneID = $combat["zoneDefinition"]["linkedConflictId"];
          $missionID = $combat["zoneDefinition"]["zoneId"];
          $mName = $combat["zoneDefinition"]["nameKey"];
          $mDesc = $combat["zoneDefinition"]["descriptionKey"];
          $rewardID = $combat["encounterRewardTableId"]; //To get points from waves
          $campaignID = $combat["campaignElementIdentifier"]["campaignNodeId"];
          $camMissionID = $combat["campaignElementIdentifier"]["campaignMissionId"];
          $tbData[$tbID]["zones"][ $zoneID ]["combatMissions"][$missionID] = array(
              "missionID" => $missionID, //-> zoneId
              "missionName" => array_key_exists($mName, $localize) ? $localize[$mName] : $mName, 
              "missionDesc" => array_key_exists($mDesc, $localize) ? $localize[$mDesc] : $mDesc,
              "requirements" => $campaignData[$tbID][$campaignID]["missions"][$camMissionID]["requirements"],
              "waves" => count($waveData[$rewardID]) - 1,
              "waveRewards" => array_map(function($wave) { return 
                intval(str_replace("GALACTIC_SCORE:","",$wave["value"]));
              }, $waveData[$rewardID] )
          );
        }
        //--Add special missions
        foreach($tb["covertZoneDefinition".$suf] as $special){
          $zoneID = $special["zoneDefinition"]["linkedConflictId"];
          $missionID = $special["zoneDefinition"]["zoneId"];
          $mName = $special["zoneDefinition"]["nameKey"];
          $mDesc = $special["zoneDefinition"]["descriptionKey"];
          $campaignID = $special["campaignElementIdentifier"]["campaignNodeId"];
          $camMissionID = $special["campaignElementIdentifier"]["campaignMissionId"];
          $waves = $campaignData[$tbID][$campaignID]["missions"][$camMissionID]["waveCount"];
          if($tbID == "t03D" || $tbID == "t04D"){
            if($waves > 1){
              $waves = $waves / 2;
            }
          }
          $tbData[$tbID]["zones"][ $zoneID ]["specialMissions"][$missionID] = array(
              "missionID" => $missionID, //-> zoneId
              "missionName" => array_key_exists($mName, $localize) ? $localize[$mName] : $mName, 
              "missionDesc" => array_key_exists($mDesc, $localize) ? $localize[$mDesc] : $mDesc,
              "requirements" => $campaignData[$tbID][$campaignID]["missions"][$camMissionID]["requirements"],
              "waves" => $waves,
              "completionRewards" => array(
                "name" => $rewardData[ $special["victoryReward".$suf][0]["id"] ]["name"],
                "quantity" => $special["victoryReward".$suf][0]["maxQuantity"],
                "image" => $rewardData[ $special["victoryReward".$suf][0]["id"] ]["image"]
              )
          );
        }

        //Build Platoons
        foreach($tb["reconZoneDefinition".$suf] as $platoon){
          $zoneID = $platoon["zoneDefinition"]["linkedConflictId"];
          $pID = $platoonMap[$zoneID];
          $zonesToApply = array();
          $tempApply = "";
          foreach($platoon["strategicAbilities".$suf][0]["zonesToApplyTo".$suf] as $zone){
            $zID = substr($zone, 0, (strlen($zone) -9));
            if(strpos($tempApply, $zID) == false){
              $tempApply = $tempApply.",".$zID;
              array_push($zonesToApply, $zID);
            }
          }
          $tbData[$tbID]["zones"][ $zoneID ]["platoons"] = array(
            "unitRarity" => $platoon["unitRarity"],
            "abilityName" => $platoonData[$pID]["ability_name"],
            "abilityDesc" => "",
            "abilityTierDesc" => array(), //Descriptions pulled from code
            "zoneToApply" => $zonesToApply,
            "zoneToApplyImage" => $platoonData[$pID]["image"],
            "missions" => array()
          );
          //Set missions

          $counter = 1;
          foreach($platoon["platoonDefinition".$suf] as $pDef){
            $tbData[$tbID]["zones"][ $zoneID ]["platoons"]["missions"]["platoon".$counter] = array(
              "completionRewards" => intVal($pDef["reward"]["value"]),
              "requiredUnits" => array(
                "row1" => array_map(function($unit){
                  return array("baseId" => $unit, "name" => $this->unitInfo[$unit]["name"], "image" => $this->unitInfo[$unit]["image"]);
                },$platoonData[$pID]["platoon_".$counter."_row_1"]),
                "row2" => array_map(function($unit){
                  return array("baseId" => $unit, "name" => $this->unitInfo[$unit]["name"], "image" => $this->unitInfo[$unit]["image"]);
                },$platoonData[$pID]["platoon_".$counter."_row_2"]),
                "row3" => array_map(function($unit){
                  return array("baseId" => $unit, "name" => $this->unitInfo[$unit]["name"], "image" => $this->unitInfo[$unit]["image"]);
                },$platoonData[$pID]["platoon_".$counter."_row_3"])
              )
            );
            $counter = $counter + 1;
          }
          //Set AbilityTierDesc
          $counter = 0;
          foreach($platoon["strategicAbilities".$suf] as $ability){
            $aID = $abilityMap[$ability["abilityId"]];
            $abilityDesc = $files["ability"][$aID]["descKey"];
            $abilityDesc = array_key_exists($abilityDesc,$localize) ? $localize[$abilityDesc] : $abilityDesc;
            $abilityName = $files["ability"][ $aID ]["nameKey"];
            $abilityName = array_key_exists($abilityName,$localize) ? $localize[$abilityName] : $abilityName;
            $tbData[$tbID]["zones"][ $zoneID ]["platoons"]["abilityTierDesc"][$counter] = array(
                "name" => $abilityName,
                "desc" => $abilityDesc
            );
            $counter = $counter + 1;
          }
          foreach($tb["dynamicDescription".$suf] as $descriptions){
            $nameKey = array_key_exists($descriptions["abilityNameKey"], $localize) ? $localize[$descriptions["abilityNameKey"]] : $descriptions["abilityNameKey"]; 
            if(strpos($nameKey, $tbData[$tbID]["zones"][ $zoneID ]["platoons"]["abilityName"]) !== false){
              $tbData[$tbID]["zones"][ $zoneID ]["platoons"]["abilityDesc"] = array_key_exists($descriptions["abilityDescriptionKey"], $localize) ? $localize[$descriptions["abilityDescriptionKey"]] : $descriptions["abilityDescriptionKey"];
            }
          }
  
        }
        //Build Rewards
        $tbData[$tbID]["rankRewards"] = array();
        foreach($tb["rankRewardPreview".$suf] as $rank){
          $tbData[$tbID]["rankRewards"][$rank["rankStart"]] = array();
          $tbData[$tbID]["rankRewards"][$rank["rankStart"]]["rank"] = $rank["rankStart"];
          $tbData[$tbID]["rankRewards"][$rank["rankStart"]]["rewards"] = array();
          $tbData[$tbID]["rankRewards"][$rank["rankStart"]]["boxContents"] = array();
          $boxID = null;
          foreach($rank["detailedReward".$suf] as $reward){
            if(strpos($reward["id"], "mysterybox") !== false){
              $boxID = $reward["id"];
            }
            array_push($tbData[$tbID]["rankRewards"][$rank["rankStart"]]["rewards"], array("name" => $rewardData[$reward["id"]]["name"],"quantity" => $reward["minQuantity"],"image" => $rewardData[$reward["id"]]["image"]));
          }
          if($boxID !== null){
            $boxKey = $prizeBoxMap[$boxID];
            foreach($files["mysteryBox".$suf][$boxKey]["previewItem"] as $gear){
              array_push($tbData[$tbID]["rankRewards"][$rank["rankStart"]]["boxContents"], array(
                "name" => $rewardData[ $gear["id"] ]["name"],
                "quantity" => $gear["maxQuantity"],
                "image" => $rewardData[ $gear["id"] ]["image"],
                "color" => $rewardData[ $gear["id"] ]["color"]
              ));
            }  
          }
        }
      }

      //Save file to the server
      ksort($tbData);
      $newFile = fopen($fullPath."/data/territoryBattles.json", "w");
      $saveData = json_encode($tbData);
      fwrite($newFile,$saveData);
      fclose($newFile);
    }


    /**
     * Creates JSON file containing unit stat Information.
     * @param string $fileName The file name for the datamined game file. Default is 'all.json'
     * @param bool $onlyUsedFiles Flag to only create separate files specified in $stdFileNames. Default is true.
     */
    public function unitStatDefinition($lang = "ENG_US") {
      $fullPath = realpath(dirname(__FILE__));
      $file = json_decode(file_get_contents($fullPath."/data/unitStatDefinitions.json"),true);
      $statEnums = json_decode($this->comlink->fetchEnums(),true)["UnitStat"];
      $localize = $this->getLocalization($lang);
      $unitStats = array();
      $localMap = array();
      $newStats = array();

      $localize["UNIT_STAT_STAT_VIEW_MASTERY"] = "Mastery";
      $localize["UnitStat_Taunt"] = "Taunt";
      foreach($localize as $key => $val){
        if(strpos($key,"UnitStat") !== false || $key === "UNIT_STAT_STAT_VIEW_MASTERY" || $key === "Combat_Buffs_TASK_NAME_2"){
          $localMap[strtoupper($key)] = $key;
        }
      }

      foreach($statEnums as $key => $stat){
        if($key === "UnitStat_DEFAULT"){continue;}
        $newKey = str_replace("UNITSTAT","UNITSTAT_",$key);
        $descKey = str_replace("UNITSTAT","UNITSTATDESCRIPTION_",$key);
        switch($stat){
          case 1:
            $newKey = "UNITSTAT_HEALTH";
            $descKey = "UNITSTATDESCRIPTION_HEALTH_TU7";
            break;
          case 4:
            $newKey = strtoupper("UnitStat_Intelligence_TU7");
            break;
          case 12:
            $newKey = strtoupper("UnitStat_DodgeRating_TU5V");
            break;
          case 13:
            $newKey = strtoupper("UnitStat_DeflectionRating_TU5V");
            break;
          case 14:
            $newKey = strtoupper("UnitStat_AttackCriticalRating_TU5V");
            break;
          case 15:
            $newKey = strtoupper("UnitStat_AbilityCriticalRating_TU5V");
            break;
          case 59:
            $newKey = "COMBAT_BUFFS_TASK_NAME_2";
            break;
          case 61:
            $newKey = "UNIT_STAT_STAT_VIEW_MASTERY";
            break;
          default:
        }
        $unitStats[$stat] = array(
          "statId" => $stat,
          "nameKey" => (array_key_exists($newKey, $localMap)) ? $localMap[$newKey] : "",
          "name" => trim(preg_replace('/[\[{\(].*?[\]}\)]/' , "",$localize[$localMap[$newKey]])),
          "descKey" => (array_key_exists($descKey, $localMap)) ? $localMap[$descKey] : ""
        );
      }

      foreach($file as $stat => $val){
        $id = $val["statId"];
        $newStats[$id] = array(
          "statId" => $id,
          "nameKey" => $unitStats[$id]["nameKey"],
          "descKey" => $unitStats[$id]["descKey"],
          "isDecimal" => ($val["isDecimal"]) ? true : false,
          "name" => $unitStats[$id]["name"],
          "detailedName" => $val["detailedName"]
        );
      }

      $newFile = fopen($fullPath."/data/unitStatDefinitions.json", "w");
      $saveData = json_encode($newStats);
      fwrite($newFile,$saveData);
      fclose($newFile);
    }

    /**
     * Grabs guild data and analyzes member rosters for different recommendations
     * @param string $leaderboard - The leaderboard to use
     * @param string $recType - The recommendation files to create
     * @param integer $count - The number of guilds to analyze 
     * @param integer $minGear - The minimum gear of a unit to consider
     * @param string $lang - The language to use. Currently only supports unit names and skill names.
     */
    public function buildRecommendations($leaderboard, $recType, $count, $minGear=10, $lang = "ENG_US"){
      $localize = $this->getLocalization($lang);
      $data = $this->getFiles("ability");
      $data["gameData"] = $this->getFiles("custom",array("gameData"))["gameData"];
      $getStatEnums = array(
        "fullSet" => array( "Speed"=> 4,"Offense"=>4,"Critical Damage"=>4,"Health"=>2,"Defense"=>2,"Potency"=>2,"Tenacity"=>2,"Critical Chance"=>2),
        "setBonus" => array( "Health"=>10,"Defense"=>25,"Potency"=>15,"Tenacity"=>20,"Critical Chance"=>8,"Critical Damage"=>30,
                        "Speed"=>10,"Offense"=>15 ),
        "setNum" => array( "Health"=> 1, "Offense"=> 2, "Defense"=>3, "Speed"=>4, "Critical Chance"=> 5, "Critical Damage"=>6, "Potency"=>7, "Tenacity"=>8 
        ),
        "shapeSlot"=> array("Square"=>1,"Arrow"=>2,"Diamond"=>3,"Triangle"=>4,"Circle"=>5,"Plus"=>6),
        "setName" => array(
          1 =>"health",
          2=>"offense",
          3=>"defense",
          4=>"speed",
          5=>"crit_chance",
          6=>"crit_dmg",
          7=>"potency",
          8=>"tenacity"
        ),
        "statName" => array(
          1=>"health",5=>"speed",16=>"crit_dmg",
          17=>"potency",18=>"tenacity",28=>"protection",
          41=>"offense",42=>"defense",48=>"offense_pct",
          49=>"defense_pct",52=>"accuracy",53=>"crit_chance",
          54=>"crit_avoid",55=>"health_pct",56=>"protection_pct"
        ),
        "primaryName" => array(
          5=> "speed",
          16=> "crit_dmg",
          17=> "potency",
          18=> "tenacity",
          48=> "offense",
          49=> "defense",
          52=> "accuracy",
          53=> "crit_chance",
          54=> "crit_avoid",
          55=> "health",
          56=> "protection"
        ),
        "slotShape" => array(
          1=>"square",
          2=>"arrow",
          3=>"diamond",
          4=>"triangle",
          5=>"circle",
          6=>"plus"
        ),
        "statConv" => array(
          1=> "health",
          28=> "protection",
          5=> "speed",
          16=> "critical_damage",
          17=> "potency",
          18=> "tenacity",
          27=> "health_steal",	
          6=> "physical_dmg",
          14=> "physical_cc",	
          7=> "special_dmg",
          15=> "special_cc",
          37=> "accuracy",
          8=> "armor",
          9=> "resistance",
          39=> "crit_avoid"
        ),
        "convStat" => array(
          "accuracy" => "Accuracy",
          "crit_avoid" => "Critical Avoidance",
          "crit_chance" => "Critical Chance",
          "crit_dmg" => "Critical Damage",
          "defense" => "Defense",
          "defense_pct" => "Defense %",
          "health" => "Health",
          "health_pct" => "Health %",
          "offense" => "Offense",
          "offense_pct" => "Offense %",
          "potency" => "Potency",
          "protection" => "Protection",
          "protection_pct" => "Protection %",
          "speed" => "Speed",
          "tenacity" => "Tenacity"
        ),
        "convNumStat" => array(
          53=> "Critical Chance",
          42=> "Defense",
          49=> "Defense %",
          1=> "Health",
          55=> "Health %",
          41=> "Offense",
          48=> "Offense %",
          17=> "Potency",
          28=> "Protection",
          56=> "Protection %",
          5=> "Speed",
          18=> "Tenacity"
        )
      );
      $statConv = $getStatEnums["statConv"];
      $setName = $getStatEnums["setName"];
      $setNum = $getStatEnums["setNum"];
      $slotShape = $getStatEnums["slotShape"];
      $secondaryName = $getStatEnums["statName"];
      $primaryName = $getStatEnums["primaryName"];

      //Get unit list and set array for units
      $characterList = array();
      $characterData = array();
      $skillList = array();
      $abilityList = array();
      $abilityIds = array();
      foreach($data["ability"] as $ability){
        $abilityList[$ability["id"]] = array_key_exists($ability["nameKey"], $localize) ? $localize[ $ability["nameKey"] ] : $ability["nameKey"];
      }
      foreach($data["skill"] as $skill){
        $abilityIds[$skill["id"]] = $skill["abilityReference"];
      }
      foreach($data["units"] as $unit){
        if($unit["obtainable"] == true && $unit["rarity"] == 7 && $unit["obtainableTime"] == 0){
          array_push($characterList, array(
            "name" => array_key_exists($unit["nameKey"], $localize) ? $localize[ $unit["nameKey"] ] : $unit["nameKey"],
            "base_id" => $unit["baseId"],
            "skills" => $data["gameData"]["unitData"][$unit["baseId"]]["skills"]
          ));
          foreach($data["gameData"]["unitData"][$unit["baseId"]]["skills"] as $skill){
            if($skill["isZeta"] || $skill["maxTier"] === 9 || count($skill["powerOverrideTags"]) > 0 ){
              $zetaTier = null;
              $omiTier = null;
              $isOmi = false;
              foreach($skill["powerOverrideTags"] as $key => $val){
                if($val === "zeta") { $zetaTier = $key; }
                if($val === "omicron"){ $isOmi = true; $omiTier = $key; }
              }
              if($skill["maxTier"] === 9 ){
                $omiTier = 9;
                $isOmi = true;
              }
              $skillList[$skill["id"]] = array(
                "zeta" => $skill["isZeta"],
                "zetaTier" => $zetaTier,
                "omicron" => $isOmi,
                "omicronTier" => $omiTier
              );
            }
          }
        }
      }
      foreach($characterList as $unit){
        //Build ability data
        $omicronData = array();
        $zetaData = array();
        foreach($unit["skills"] as $skill){
          if($skill["isZeta"]){
            $zetaData[$skill["id"]] = array(
              "name" => $abilityList[ $abilityIds[ $skill["id"] ] ],
              "tier" => $skillList[ $skill["id"] ]["zetaTier"],
              "count" => 0
            );
          }
          if(array_key_exists($skill["id"], $skillList) && $skillList[ $skill["id"] ]["omicron"]){
            $omicronData[$skill["id"]] = array(
              "name" => $abilityList[ $abilityIds[ $skill["id"] ] ],
              "tier" => $skillList[ $skill["id"] ]["omicronTier"],
              "count" => 0
            );
          }
        }
        //Build full object
        $characterData[$unit["base_id"]] = array( 
          "base_id" => $unit["base_id"],
          "name" => $unit["name"],
          "count" => 0,
          "stats_max" => array(
            "health" => 0,
            "protection" => 0,
            "speed" => 0,
            "critical_damage" => 0,
            "potency" => 0,
            "tenacity" => 0,
            "health_steal" => 0,	
            "physical_dmg" => 0,
            "physical_cc" => 0,	
            "special_dmg" => 0,
            "special_cc" => 0,
            "accuracy" => 0,
            "armor" => 0,
            "resistance" => 0,
            "crit_avoid" => 0
          ),
          "stats_avg" => array(
            "health" => 0,
            "protection" => 0,
            "speed" => 0,
            "critical_damage" => 0,
            "potency" => 0,
            "tenacity" => 0,
            "health_steal" => 0,	
            "physical_dmg" => 0,
            "physical_cc" => 0,	
            "special_dmg" => 0,
            "special_cc" => 0,
            "accuracy" => 0,
            "armor" => 0,
            "resistance" => 0,
            "crit_avoid" => 0
          ),
          "mods_set" => array(
            "sets" => array(),
            "health" => 0,
            "defense" => 0,
            "potency" => 0,
            "tenacity" => 0,
            "crit_chance" => 0,
            "speed" => 0,
            "offense" => 0,
            "crit_dmg" => 0
          ),
          "mods_primary" => array(
            "arrow" => array(
              "speed" => 0,
              "accuracy" => 0,
              "crit_avoid" => 0,
              "health" => 0,
              "protection" => 0,
              "offense" => 0,
              "defense" => 0
            ),
            "triangle" => array(
              "crit_chance" => 0,
              "crit_dmg" => 0,
              "health" => 0,
              "protection" => 0,
              "offense" => 0,
              "defense" => 0
            ),
            "plus" => array(
              "potency" => 0,
              "tenacity" => 0,
              "health" => 0,
              "protection" => 0,
              "offense" => 0,
              "defense" => 0
            ),
            "circle" => array(
              "health" => 0,
              "protection" => 0
            )
          ),
          "mods_secondary" => array(
            "crit_chance" => 0,
            "defense" => 0,
            "defense_pct" => 0,
            "health" => 0,
            "health_pct" => 0,
            "offense" => 0,
            "offense_pct" => 0,
            "potency" => 0,
            "protection" => 0,
            "protection_pct" => 0,
            "speed" => 0,
            "tenacity" => 0
          ),
          "zetas" => $zetaData,
          "omicrons" => $omicronData
        );
      }

      //Get Leaderboard Data
      $event = null;
      switch($leaderboard){
        case "raid":
          $board = 0;
          break;
        case "pvp":
          $board = 5;
          $event = "TERRITORY_WAR_LEADERBOARD";
          break;
        default:
          $board = 3;
      }
      $boardData = json_decode($this->comlink->fetchGuildLeaderboard($board,$count, 1, $event),true);
      //Get Guild member ids
      $memberIds = array();
      $guildIds = array();
      foreach($boardData["leaderboard"][0]["guild"] as $guild){
        array_push($guildIds, $guild["id"]);
      }
      foreach($guildIds as $guild){
        $guildData = json_decode($this->comlink->fetchGuild($guild),true);
        foreach($guildData["guild"]["member"] as $member){
          array_push($memberIds, $member["playerId"]);
        }
      }
      $boardData = null;
      $guildIds = null;

      //Get player data and build out unit data
      $modCount = 0;
      $gearGoal = (strpos($minGear,"R") !==false) ? substr($minGear,1) : $minGear;
      $modRolls = array("1_pips" => array(),"2_pips" => array(),"3_pips" => array(),"4_pips" => array(),"5_pips" => array(), "6_pips" => array(), "7_pips" => array());
      $rawRollCounts = array("1_pips" => array(),"2_pips" => array(),"3_pips" => array(),"4_pips" => array(),"5_pips" => array(), "6_pips" => array(), "7_pips" => array());
      $test = array();
      $startTime = time();
      foreach($memberIds as $member){
        $memberData = json_decode($this->comlink->fetchPlayer($member),true);

        foreach($memberData["rosterUnit"] as $unit){
          $gear = (strpos($minGear,"R") !==false) ? $unit["relic"]["currentTier"] : $unit["currentTier"];
          if($unit["currentRarity"] === 7 && $gear >= $gearGoal){
          }
        }
        //Add data to objects
        array_push($test, $memberData["name"]);

        //Exit loop and use what you have if time exceeds limit.
        $runTime = time() - $startTime;
        if($runTime >= 4800){ break; }
      }
      $endTime = time();
      $speed = $endTime - $startTime;
      echo "Time taken: ".$speed."\n";

    }
}
?>
