<?php

class Database
{

    private static $instance;
    private $conid;
    public $numRows;

    private function __construct()
    {
        $this->openConnection();
        $this->numRows = 0;
    }

    public function __destruct()
    {
        if (isset(self::$instance))
        {
            mysql_close($this->conid);
            unset($this->conid);
            self::$instance = null;
        }
    }

    //Singleton: check for an instance od this class
    public static function getInstance()
    {
        if (!isset(self::$instance))
        {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }

//-----------------------------------------------------------------------------------------------------------     

    public function openConnection()
    {

        $this->conid = mysql_connect(DB_HOST, DB_USER, DB_PASS);

        if (!$this->conid)
        {
            die("Datenbankverbindung konnte nicht hergestellt werden: " . mysql_error());
        }
        else
        {
            mysql_query("SET CHARACTER SET utf8");
            mysql_query("SET NAMES utf8");

            if (!mysql_select_db(DB_NAME, $this->conid))
            {
                die("Datenbankauswahl scheiterte: " . mysql_error());
            }
        }
    }

//----------------------------------------------------------------------------------------------------------     
    public function closeConnection()
    {

        if (isset($this->connection))
        {
            mysql_close($this->connection);
            unset($this->connection);
        }
    }

//-----------------------------------------------------------------------------------------------------------
    public function select($_query, $_fetchMode = "mysql_fetch_assoc")
    {
        $result = array();
        $i = 0;
        try
        {
            
            $query = mysql_query($_query);

            if (!$query)
            {
                throw new Exception(DB_ERR_SELECT . mysql_error());
                return false;
            }
            else
            {
                $this->numRows = mysql_num_rows($query);

                if ($this->numRows)
                {
                    while ($record = $_fetchMode($query))
                    {
                        $result[$i++] = $record;
                    }
                    return $result;
                }
                else
                {
                    return $result;
                }
            }
        }
        catch (Exception $e)
        {
            $this->writeExceptionToDB($_SERVER['REQUEST_URI'], $e->getMessage(), #
                    $e->getCode(), $e->getFile(), $e->getLine(), date('Y-m-d H:i:s'), $e->getTraceAsString());
            return false;
        }
    }

//----------------------------------------------------------------------------------------------------------
    /**
     * insert
     * @param string $table A name of table to insert into
     * @param string $data An associative array
     */
    public function insert($_table, $_dataArray)
    {
        ksort($_dataArray);

        $fieldNames = implode(', ', array_keys($_dataArray));
        $fieldValues = implode("', '", array_values($_dataArray));

        $query = "INSERT INTO $_table ($fieldNames) VALUES ('$fieldValues')";

        try
        {
            $res = mysql_query($query, $this->conid);

            if (!$res)
            {
                throw new Exception(DB_ERR_INSERT . mysql_error());
                return false;
            }
            else
            {
                return true;
            }
        }
        catch (Exception $e)
        {
            $this->writeExceptionToDB($_SERVER['REQUEST_URI'], $e->getMessage(), $e->getCode(), $e->getFile(), 
                                     $e->getLine(), date('Y-m-d H:i:s'), $e->getTraceAsString());
  
        }
    }

//-----------------------------------------------------------------------------------------------------------
    /**
     * update
     * @param string $table A name of table to insert into
     * @param string $data An associative array
     * @param string $where the WHERE query part
     */
    public function update($_table, $_dataArray, $_where)
    {
        ksort($_dataArray);

        $fieldDetails = NULL;

        foreach ($_dataArray as $key => $value)
        {
            $fieldDetails .= "$key = '$value',";
        }
        
        $fieldDetails = rtrim($fieldDetails, ',');

        $query = "UPDATE $_table SET $fieldDetails WHERE $_where";

        try
        {
            $res = mysql_query($query, $this->conid);

            if (!$res)
            {
                throw new Exception(DB_ERR_UPT . mysql_error());
                return false;
            }
            else
            {
                return true;
            }
        }
        catch (Exception $e)
        {
            $this->writeExceptionToDB($_SERVER['REQUEST_URI'], $e->getMessage(), $e->getCode(), $e->getFile(), 
                                     $e->getLine(), date('Y-m-d H:i:s'), $e->getTraceAsString());
  
        }

        
    }
//----------------------------------------------------------------------------------------------------------
    /**
     * delete
     * 
     * @param string $table
     * @param string $where
     * @param integer $limit
     * @return integer Affected Rows
     */
    public function delete($_table, $_where, $_limit = 1)
    {
        $query = "DELETE FROM $_table WHERE $_where LIMIT $_limit";
        try
        {
            $res = mysql_query($query, $this->conid);

            if (!$res)
            {
                throw new Exception(DB_ERR_DELETE . mysql_error());
                return false;
            }
            else
            {
                return true;
            }
        }
        catch (Exception $e)
        {
            $this->writeExceptionToDB($_SERVER['REQUEST_URI'], $e->getMessage(), $e->getCode(), $e->getFile(), 
                                     $e->getLine(), date('Y-m-d H:i:s'), $e->getTraceAsString());
  
        }        
    } 
//-----------------------------------------------------------------------------------------------------------

    /*
      // called in the catch-block of catchMysqlQuery()
     */
    public function writeExceptionToDB($_url, $_error, $_errorCode, $_file, $_line, $_date, $_trace)
    {
        $replace = array("'", "\"");

        $_error = str_replace($replace, "", $_error);
        $_url = str_replace($replace, "", $_url);
        $_file = str_replace($replace, "", $_file);

        $Query = "INSERT INTO log_exception (url, fehlermeldung, fehlercode, datei, zeile, zeitpunkt) 
                             VALUES ('$_url', '$_error', $_errorCode, '$_file', $_line, '$_date')";
        mysql_query($Query);


        
        //generate the traceroute of the exception
        $traceArray = explode('#', $_trace);
        foreach ($traceArray as $value)
        {
            $trace = $trace . $value . "<br>";
        }

        
        //Debugmode, shows the Error on the page
        if (DEBUG_MODE)
        {
            echo "<div class=\"status_error\">" . $_error . "<br>source file: " .
            $_file . "<br>interrupt at line: " . $_line . "<br><br>---Traceroute(LIFO)---" . $trace . "</div>";
            return false;
        }
        else
        {
            Notice::getGlobalErr();  
            return false;
        }
    }

//-----------------------------------------------------------------------------------------------------------
}