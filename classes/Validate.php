<?php

/**
 * Description of Validate
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */

class Validate {
    private $_passed = false,
            $_errors = [],
            $_db = null;
    
    public function __construct() {
        $this->_db = DB::getInstance();
    }
    
    public function check($source, $items=[]){
        
        foreach($items as $field => $set){
            foreach($set['rules'] as $rule => $rule_value){
//                echo "'{$set['label']}' {$rule} must be {$rule_value}<br>";
                $value = $set['value'];
                if($rule == 'required' && $rule_value && empty($value)){
                    $this->_addError($field, "{$set['label']} is required.");
                } 
                elseif(!empty($source_value) && $rule == 'matches'){
                    $source_value = trim($source[$rule_value]);                            
                    if($value != $source_value){
                        $this->_addError($field, "{$set['label']} must match with {$items[$rule_value]['label']}.");
                    }
                }
//                elseif($rule=='other_input_match'){
//                    
//                }
                elseif(!empty($value)) {
                    switch($rule){
                        case 'min':
                            if(strlen($value) < $rule_value){
                                $this->_addError($field, "{$set['label']} must be minimum of {$rule_value} characters.");
                            }
                            break;
                        
                        case 'max':
                            if(strlen($value) > $rule_value){
                                $this->_addError($field, "{$set['label']} must be maximum of {$rule_value} characters.");
                            }
                            break;
                        
                        case 'exact':
                            if(strlen($value) != $rule_value){
                                $this->_addError($field, "{$set['label']} must be {$rule_value} characters.");
                            }
                            break;
                            
                        /*case 'matches':
                            $source_value = trim($source[$rule_value]);                            
                            if($value != $source_value){
                                $this->_addError($field, "{$set['label']} must match with {$items[$rule_value]['label']}.");
                            }
                            break;*/
                        
                        case 'unique':
                            $tmp = explode('|', $rule_value);
                            $table = $tmp[0];
                            $column = isset($tmp[1]) ? $tmp[1] : $field;
                            $conditions = [[$column,'=', $value]];
                            if(isset($tmp[2]) && isset($tmp[3])){
                                $conditions[] = [$tmp[2],'<>', $tmp[3]];
                            }
                            
                            $check = $this->_db->get($table, $conditions);
                            if($check->count()){
                                $this->_addError($field, "{$set['label']} already exists.");
                            }
                            break;
                        
                        case 'email':
                            if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
                                $this->_addError($field, "Invalid {$set['label']}.");
                            }
                            break;
                        
                        case 'digit':
                            if(!self::digit($value)){
                                $this->_addError($field, "{$set['label']} should contain digit only.");
                            }
                            break;
                        
                        case 'no_digit':
                            if(self::has_number($value)){
                                $this->_addError($field, "{$set['label']} should not contain digit.");
                            }
                            break;
                        
                        case 'valid_ip':
                            if(!self::validIp($value)){
                                $this->_addError($field, "Invalid {$set['label']}.");
                            }
                            break;
                            
                    }
                }
            }
        }
        
        if(empty($this->errors())){
            $this->_passed = true;
        }
        
        return $this;
    }
    
    private function _addError($field, $message){
        $this->_errors[$field] = $message;
    }
    
    public function errors(){
        return $this->_errors;
    }
    
    public function passed(){
        return $this->_passed;
    }
    
    public static function validIp($ip){
        return !filter_var($ip, FILTER_VALIDATE_IP) === false ? true : false;
    }
    
    public static function blank($str)
    {
        return strlen($str)<1 ? true : false;
    }
    
    public static function digit($str)
    {
        $str = (string)$str;
        return ctype_digit($str) ? true : false;
    }
    
    public static function has_number($str)
    {
        return (1 === preg_match('~[0-9]~', $str)) ? true : false;
    }
}
