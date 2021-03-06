<?php
/**
 * Simple MySQLi Class 0.3
 *
 * @author      JReam
 * @license     GNU General Public License 3 (http://www.gnu.org/licenses/)
 *
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the
 * Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details:
 * http://www.gnu.org/licenses/
 *
 * @uses    ----------------------------------------
 *
        // A Config array should be setup from a config file with these parameters:
        $config = array();
        $config['host'] = 'localhost';
        $config['user'] = 'root';
        $config['pass'] = '';
        $config['table'] = 'table';

        // Then simply connect to your DB this way:
        $db = new DB($config);

        // Run a Query:
        $db->query('SELECT * FROM someplace');

        // Get an array of items:
        $result = $db->get();
        print_r($result);
        
        // Optional fetch modes (1 and 2)
        $db->setFetchMode(1);
        
        // Get a single item:
        $result = $db->get('field');
        print_r($result);

        What you do with displaying the array result is up to you!
        ----------------------------------------
 */

class DB
{

    /**
    * @var <str> The mode to return results, defualt is MYSQLI_BOTH, use setFetchMode() to change.
    */
   //private $rootDirectory;
      //$ADODB_FETCH_MODE = ADODB_FETCH_NUM;  

    /**
    * @desc     Creates the MySQLi object for usage.
    *
    * @param    <arr> $db Required connection params.
    */
    public function  __construct($db) {
	     $this->rootDirectory = dirname(__FILE__);
		require $this->rootDirectory . '/adodb5/adodb.inc.php';
        $this->dbd = ADONewConnection('pdo');
         $ADODB_CACHE_DIR = ABSPATH.'/cache/';
         $this->dbd->cacheSecs   = 6000;

	   if(!$this->dbd->Connect('mysql:host='.$db['host'].'',$db['user'],$db['pass'],$db['table']))
	     {
	  exit($this->dbd->ErrorMsg());
          }
              $this->dbd->Execute('SET NAMES utf8');
              $this->dbd->Execute('SET CHARACTER SET utf8');
              $this->dbd->Execute('SET COLLATION_CONNECTION="utf8_unicode_ci"');
              $this->dbd->debug = false;//false true Enable Debug Feature
    }
    
    /** 
    * @desc     Optionally set the return mode.
    *
    * @param    <int> $type The mode: 1 for MYSQLI_NUM, 2 for MYSQLI_ASSOC, default is MYSQLI_BOTH
    */
    public function setFetchMode($type)
    {
        switch($type)
        {           
            case 1:
            $this->dbd->setFetchMode(ADODB_FETCH_NUM);
            break;        
            case 2:
			$this->dbd->setFetchMode(ADODB_FETCH_ASSOC);
            break;
            default:
			$this->dbd->setFetchMode(ADODB_FETCH_BOTH);
            break;

        }

    }
	
   public function clean( $data )
     {
         $data = stripslashes( $data );
         $data = html_entity_decode( $data, ENT_QUOTES, 'UTF-8' );
         $data = nl2br( $data );
         $data = urldecode( $data );
         return $data;
     }
    /**
     * @desc    Simple preparation to clean the SQL/Setup result Object.
     *
     * @param   <str> SQL statement
     * @return  <bln|null>
     */
    public function query($SQL)
    {
        // $this->SQL = $SQL;
        $this->SQL = $this->dbd->qStr($SQL);
		//$this->SQL = str_replace("'", "",$this->SQL);
		  //$search  = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
          //$replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");
          //$this->SQL     = str_replace($search, $replace, $this->SQL);
         
         $this->result = $this->dbd->Execute($SQL);
		// $this->result = $this->dbd->CacheExecute(3600,$SQL);
        if ($this->result == true)
        {
            return true;
        }
        else
        {
            printf("<b>Problem with SQL:</b> %s\n", $this->SQL);
           // exit;
        }
    }

    /**
     * @desc    Get the results
     *
     * @param   <str|int> $field Select a single field, or leave blank to select all.
     * @return  <mixed>
     */
    public function get($field = NULL)
    {
        if ($field == NULL)
        {
            /** Grab all the data */
            $data = array();
			  
           $data = $this->result->getAll();
            				
           
        }
        else
        {
            /** Select the specific row */
			$data = array();
            $row = $this->result->getAll();
			if ($row)
        {
            $data = $row[0][$field];
		}
        }
       
	    //$this->result->free();

        /** Make sure to close the result Set */
        $this->result->close();

        return $data;

    }
    
    /**
    * @desc     Returns the automatically generated insert ID
    *           This MUST come after an insert Query.
    */
    public function id()
    {
        return $this->dbd->insert_Id;
    }

    /**
     * @desc    Automatically close the connection when finished with this object.
     */
    public function __destruct()
    {
	   // $this->dbd->free();
        $this->dbd->close();
    }

}