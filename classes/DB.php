<?php

/**
 * DataBase Connection and Basic DB Operatoins
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */
class DB {
    private static $_instance = null;
    private $_pdo,
            $_query,
            $_error = false,
            $_results,
            $_count = 0;
    private $_operators   = ['=', '>', '<', '>=', '<=', '!=', '<>'];
    private function __construct()
    {
        $this->_pdo = self::connectDb(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    }
    
    public function startTransaction()
    {
        $this->_pdo->beginTransaction();
    }
    
    public function commitTransaction()
    {
        $this->_pdo->commit();
    }
    
    public function rollbackTransaction()
    {
        $this->_pdo->rollBack();
    }
    
    public static function getInstance()
    {
        if(!isset(self::$_instance)){
            self::$_instance = new DB();
        }
        return self::$_instance;
    }
    
    public static function connectDb($host=DB_HOSTNAME, $username=DB_USERNAME, $password=DB_PASSWORD, $dbname=DB_DATABASE)
    {
        $dbcon = null;
        try{
            $dbcon = new PDO("mysql:host={$host};dbname={$dbname}", $username, $password);
            $dbcon->exec("SET time_zone='Asia/Dhaka';");
            $dbcon->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            if(ENVIRONMENT == 'development'){
                // set the PDO error mode to exception
                $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            
        } catch (Exception $e) {
             echo "Database Connection Failed: " . $e->getMessage();
        }
        return $dbcon;
    }
    
    public function exec($sql, $params = [])
    {
        $query = false;
        if($query = $this->_pdo->prepare($sql)){
            if(count($params)){
                $x = 1;
                foreach($params as $param){
                    $query->bindValue($x, $param);
                    $x++;
                }
            }
            $query->execute();
        }
    }
    
    public function query($sql, $params = [])
    {
        $this->_error = false;
        if($this->_query = $this->_pdo->prepare($sql)){
            if(count($params)){
                $x = 1;
                foreach($params as $param){
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            
            if($this->_query->execute()){
                $this->_results = $this->_query->fetchAll();
                $this->_count = $this->_query->rowCount();
            } else{
                $this->_error = true;
            }
        }
        
        return $this;
    }
    
    /*
     * example 1: $where = [['username', '=', 'rafiq']]
     * WHERE (username = 'rafiq')
     * 
     * example 2: $where = [['username', '=', 'rafiq'], ['id', '<>', 1]]
     * WHERE (username = 'rafiq') AND (id <> 1)
     */
    private function _action($action, $table, $where = [])
    {
        $whereCond = ''; $values = [];
        foreach($where as $wh){
            if(count($wh) == 3){
                $field      = $wh[0];
                $operator   = $wh[1];
                $values[]   = $wh[2];
                if(in_array($operator, $this->_operators)){
                    if(!empty($whereCond)){ $whereCond .= ' AND ';}
                    $whereCond .= "(`{$field}` {$operator} ?)";
                }
            }
        }
        if(!empty($whereCond)){ $whereCond = "WHERE ".$whereCond;}
        $sql = "{$action} FROM `{$table}` {$whereCond}";
        
        if(!$this->query($sql, $values)->error()){
            return $this;
        }
        return false;
    }
    
    public function get($table, $where = [])
    {
        return $this->_action("SELECT *", $table, $where);
    }
    
    /**
     * INSERT function with LastInsertId return Option.
     * @param type $table
     * @param type $fields
     * @param type $returnLastInsertId. If true returns lastInsertId
     * @return boolean or ID
     */
    public function insert($table, $fields = [], $returnLastInsertId = false)
    {
        $this->_error = false;
        if(count($fields)){
            $keys = array_keys($fields);
            $values = '';
            foreach($fields as $field){
                $values .=  '?, ';
            }
            $sql = "INSERT INTO {$table} (`".  implode('`,`', $keys)."`) VALUES(". rtrim($values, ', ') .")";
        }
        
        if($this->_query = $this->_pdo->prepare($sql)){
            if(count($fields)){
                $x = 1;
                foreach($fields as $field){
                    $this->_query->bindValue($x, $field);
                    $x++;
                }
            }
            if($this->_query->execute()){
                return $returnLastInsertId ? $this->_pdo->lastInsertId() : true;
            } else{
                $this->_error = true;
            }
        }
        
        return false;
    }
    
    
    public function bulkInsert($table, $fields = [], $returnLastInsertId = false)
    {
        $this->_error = false;
        if(count($fields)){
            $keys = array_keys($fields);
            $values = '';
            foreach($fields as $field){
                $values .=  '?, ';
            }
            $sql = "INSERT INTO {$table} (`".  implode('`,`', $keys)."`) VALUES(". rtrim($values, ', ') .")";
        }
        
        if($this->_query = $this->_pdo->prepare($sql)){
            if(count($fields)){
                $x = 1;
                foreach($fields as $field){
                    $this->_query->bindValue($x, $field);
                    $x++;
                }
            }
            if($this->_query->execute()){
                return $returnLastInsertId ? $this->_pdo->lastInsertId() : true;
            } else{
                $this->_error = true;
            }
        }
        
        return false;
    }
    
    public static function updateSql($table, $fields, $column, $value=''){
        $sql = ''; $set = '';
        if(count($fields)){
            foreach($fields as $key=>$val){
                $set .=  "`{$key}` = ?, ";
            }
            $sql = "UPDATE `{$table}` SET ". rtrim($set, ', ') ." WHERE `$column` = ?";
        }
        
        return $sql;
    }


    public function update($table, $fields, $column, $value)
    {
        $this->_error = false;
        $sql = ''; $set = '';
        
        if(count($fields)){
            $values = '';
            foreach($fields as $key=>$val){
                $set .=  $key." = ?, ";
            }
            $sql = "UPDATE {$table} SET ". rtrim($set, ', ') ." WHERE $column = ?";
            
            if($this->_query = $this->_pdo->prepare($sql)){
                if(count($fields)){
                    $x = 1;
                    foreach($fields as $field){
                        //echo '<br>bindValue['.$x.'] : '. $field;
                        $this->_query->bindValue($x, $field);
                        $x++;
                    }
                    //echo '<br>bindValue['.$x.'] : '. $value;
                    $this->_query->bindValue($x, $value);
                }
                if($this->_query->execute()){
                    return true;
                } else{
                    $this->_error = true;
                }
            }
        }
        
        return false;
    }
    
    public function delete($table, $column, $operator, $value)
    {
        if(in_array($operator, $this->_operators)){
            $sql = "DELETE FROM {$table} WHERE {$column} {$operator} ?";
            if($this->_query = $this->_pdo->prepare($sql)){
                $this->_query->bindValue(1, $value);
                $this->_query->execute();
                return $this->_query->rowCount()>0 ? true : false;
            }
        }
        return false;
    }

    public function results()
    {
        return $this->_results;
    }
    
    public function first()
    {
        return isset($this->_results[0]) ? $this->_results[0] : [];
        //return $this->results()[0];
    }

    public function count()
    {
        return $this->_count;
    }
    
    public function error()
    {
        return $this->_error;
    }
}
