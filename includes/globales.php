<?php
date_default_timezone_set( 'UTC' );

include 'includes/Query.inc.php';
$Arabic = new Arabic_Query;
function getData($rowno,$rowperpage,$method,$search="") {
global $db,$Arabic;
switch($method){
	case '1':
$aKeyword = explode(" ", $search);
$method_sq =  "simple like '%".$aKeyword[0]."%'";
for($i = 1; $i < count($aKeyword); $i++) {
if(!empty($aKeyword[$i])) {
 $method_sq .= " OR simple like '%". $aKeyword[$i] ."%'";
		}
       }
		break;
		case '2':
$aKeyword = explode(" ", $search);
$method_sq =  "simple like '%".$aKeyword[0]."%'";
for($i = 1; $i < count($aKeyword); $i++) {
if(!empty($aKeyword[$i])) {
 $method_sq .= " AND simple like '%". $aKeyword[$i] ."%'";
		}
       }
		break;
    case '3':
	$Arabic->setQueryStrFields('simple');
    $Arabic->setQueryMode(2);
    $strCondition = $Arabic->arQueryWhereCondition($search);
    $strOrderBy = $Arabic->arQueryOrderBy($search);     
    $method_sq =  "$strCondition ORDER BY $strOrderBy";
        break;
    case '4':
	$Arabic->setQueryStrFields('simple');
    $Arabic->setQueryMode(1);
    $strCondition = $Arabic->arQueryWhereCondition($search);
    $strOrderBy = $Arabic->arQueryOrderBy($search);     
    $method_sq =  "$strCondition ORDER BY $strOrderBy";
        break;
  case '5':
  $aKeyword = explode(" ", $search);
  $method_sq =  "simple RLIKE '".lex($aKeyword[0])."'";
  for($i = 1; $i < count($aKeyword); $i++) {
  if(!empty($aKeyword[$i])) {
  $method_sq .= " OR simple RLIKE '". lex($aKeyword[$i]) ."'";
		}
       }
		break;
  case '6':
 $aKeyword = explode(" ", $search);
 $method_sq =  "simple RLIKE '[[:<:]]". $aKeyword[0] . "[[:>:]]'";
 for($i = 1; $i < count($aKeyword); $i++) {
 if(!empty($aKeyword[$i])) {
 $method_sq .=  "OR simple RLIKE '[[:<:]]". $aKeyword[$i] . "[[:>:]]'";
		}
       }
		break;		
	default:
$method_sq =  "simple '%". $search ."%'";
        break;		
 }	
$sql ="SELECT * FROM quran_ayat WHERE " . $method_sq . "";
$sql.=" limit ".$rowno.",".$rowperpage.""; 
//echo $sql;
$db->setFetchMode(2);
$db->query($sql);
return $db->get();
	}
function getrecordCount($method,$search = '') {
global $db,$Arabic;
switch($method){
	case '1':
$aKeyword = explode(" ", $search);
$method_sq =  "simple like '%".$aKeyword[0]."%'";
for($i = 1; $i < count($aKeyword); $i++) {
if(!empty($aKeyword[$i])) {
 $method_sq .= " OR simple like '%". $aKeyword[$i] ."%'";
		}
       }
		break;
		case '2':
$aKeyword = explode(" ", $search);
$method_sq =  "simple like '%".$aKeyword[0]."%'";
for($i = 1; $i < count($aKeyword); $i++) {
if(!empty($aKeyword[$i])) {
 $method_sq .= " AND simple like '%". $aKeyword[$i] ."%'";
		}
       }
		break;
    case '3':
	$Arabic->setQueryStrFields('simple');
    $Arabic->setQueryMode(2);
    $strCondition = $Arabic->arQueryWhereCondition($search);
    $strOrderBy = $Arabic->arQueryOrderBy($search);     
    $method_sq =  "$strCondition ORDER BY $strOrderBy";
        break;
    case '4':
	$Arabic->setQueryStrFields('simple');
    $Arabic->setQueryMode(1);
    $strCondition = $Arabic->arQueryWhereCondition($search);
    $strOrderBy = $Arabic->arQueryOrderBy($search);     
    $method_sq =  "$strCondition ORDER BY $strOrderBy";
        break;
  case '5':
  $aKeyword = explode(" ", $search);
  $method_sq =  "simple RLIKE '".lex($aKeyword[0])."'";
  for($i = 1; $i < count($aKeyword); $i++) {
  if(!empty($aKeyword[$i])) {
  $method_sq .= " OR simple RLIKE '". lex($aKeyword[$i]) ."'";
		}
       }
		break;
  case '6':
 $aKeyword = explode(" ", $search);
 $method_sq =  "simple RLIKE '[[:<:]]". $aKeyword[0] . "[[:>:]]'";
 for($i = 1; $i < count($aKeyword); $i++) {
 if(!empty($aKeyword[$i])) {
 $method_sq .=  "OR simple RLIKE '[[:<:]]". $aKeyword[$i] . "[[:>:]]'";
		}
       }
		break;		
	default:
  $method_sq =  "simple '%". $search ."%'";
        break;		
  }	
$sql ="SELECT count(*) AS total FROM quran_ayat WHERE " . $method_sq. "";
//echo $sql;
$db->setFetchMode(2);
$db->query($sql);
return $db->get('total');
    }
function sorahname($sura_id){
	global $db;
$request_suras = "SELECT name from quran_surah_names WHERE id ='".$sura_id."'";
$db->query($request_suras);
$db->setFetchMode(2);
$result = $db->get('name');
return $result;
}	
function sorah($sura_id){
	global $db;
$request_suras = "SELECT * from quran_surah_names WHERE id ='".$sura_id."'";
$db->query($request_suras);
$db->setFetchMode(2);
$result = $db->get();
return $result;
}
function al_aya($aya_id,$sura_id){
	global $db;
$request_aya = "SELECT * from quran WHERE aya='".$aya_id."' and sura ='".$sura_id."'";
$db->query($request_aya);
$db->setFetchMode(2);
$result = $db->get();
return $result;
}
function ayats($aya_id,$sura_id){
	global $db;
$request_aya = "SELECT * from quran_ayat WHERE aya='".$aya_id."' and sura ='".$sura_id."'";
$db->query($request_aya);
$db->setFetchMode(2);
$result = $db->get();
return $result;
}
function ayats_sura($sura_id){
	global $db;
$request_aya = "SELECT * from quran WHERE sura ='".$sura_id."'";
$db->query($request_aya);
$db->setFetchMode(2);
$result = $db->get();
return $result;
}
function altafsire($aya_id,$sura_id,$table){
	global $db;
$request_aya = "SELECT * from ".$table." WHERE aya='".$aya_id."' and sura ='".$sura_id."'";
$db->query($request_aya);
$db->setFetchMode(2);
$result = $db->get('text');
return $result;
}
function lex($arg) {
        $patterns = array();
        $replacements = array();

        // Prefix's
        array_push($patterns, '/^????/');
        array_push($replacements, '(????)?');

        // Singular
        array_push($patterns, '/(\S{3,})??????$/');
        array_push($replacements, '\\1(??????|??)?');
        array_push($patterns, '/(\S{3,})????$/');
        array_push($replacements, '\\1(????)?');
        array_push($patterns, '/(\S{3,})????$/');
        array_push($replacements, '\\1(????)?');
        array_push($patterns, '/(\S{3,})????$/');
        array_push($replacements, '\\1(????)?');
        array_push($patterns, '/(\S{3,})????$/');
        array_push($replacements, '\\1(????)?');
        array_push($patterns, '/(\S{3,})??$/');
        array_push($replacements, '\\1(??)?');
        array_push($patterns, '/(\S{3,})(??|????)$/');
        array_push($replacements, '\\1(??|????)?');

        // Postfix's
        array_push($patterns, '/(\S{3,})??????$/');
        array_push($replacements, '\\1(??????)?');
        array_push($patterns, '/(\S{3,})??????$/');
        array_push($replacements, '\\1(??????)?');
        array_push($patterns, '/(\S{3,})????$/');
        array_push($replacements, '\\1(????)?');
        array_push($patterns, '/(\S{3,})????$/');
        array_push($replacements, '\\1(????)?');
        array_push($patterns, '/(\S{3,})????$/');
        array_push($replacements, '\\1(????)?');
        array_push($patterns, '/(\S{3,})????$/');
        array_push($replacements, '\\1(????)?');
        array_push($patterns, '/(\S{3,})????$/');
        array_push($replacements, '\\1(????)?');
        array_push($patterns, '/(\S{3,})????$/');
        array_push($replacements, '\\1(????)?');
        array_push($patterns, '/(\S{3,})????$/');
        array_push($replacements, '\\1(????)?');
        array_push($patterns, '/(\S{3,})????$/');
        array_push($replacements, '\\1(????)?');
        array_push($patterns, '/(\S{3,})????$/');
        array_push($replacements, '\\1(????)?');
        array_push($patterns, '/(\S{3,})????$/');
        array_push($replacements, '\\1(????)?');
        array_push($patterns, '/(\S{3,})????$/');
        array_push($replacements, '\\1(??|????)?');
        array_push($patterns, '/(\S{3,})??$/');
        array_push($replacements, '\\1(??)?');
          //
        array_push($patterns, '/(??|??)$/');
        array_push($replacements, '(??|??)');
        //array_push($patterns, '/(??|??)$/');
        //array_push($replacements, '(??|??)');
        // Writing errors
        array_push($patterns, '/(??|??)$/');
        array_push($replacements, '(??|??)');
        array_push($patterns, '/(??|??)$/');
        array_push($replacements, '(??|??)');
        array_push($patterns, '/(??|??)$/');
        array_push($replacements, '(??|??)');
        array_push($patterns, '/(??|??)$/');
        array_push($replacements, '(??|??)');
        array_push($patterns, '/(??|????|??|????|??)/');
        array_push($replacements, '(??|????|??|????|??)');

        // Normalization
        array_push($patterns, '/??|??|??|??|??|??|??|??/');
        array_push($replacements, '(??|??|??|??|??|??|??|??)?');
        array_push($patterns, '/??|??|??|??/');
        array_push($replacements, '(??|??|??|??)');

        $arg = preg_replace($patterns, $replacements, $arg);

        return $arg;
    }
class MicroTimer {

	private $startTime, $stopTime;

	// creates and starts a timer
	function __construct()
	{
		$this->startTime = microtime(true);
	}

	// stops a timer
	public function stop()
	{
		$this->stopTime = microtime(true);
	}

	// returns the number of seconds from the timer's creation, or elapsed
	// between creation and call to ->stop()
	public function elapsed()
	{
		if ($this->stopTime)
			return round($this->stopTime - $this->startTime, 4);

		return round(microtime(true) - $this->startTime, 4);
	}

	// called when using a MicroTimer object as a string
	public function __toString()
	{
		return (string) $this->elapsed();
	}

}
?>