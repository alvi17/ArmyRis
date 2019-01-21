<?php

/**
 * ScratchCard related functions
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date November 22, 2016 02:44
 */


class ScratchCard{
    
    const CARD_AVAILABLE_CONDITION = 5;
    const CARD_USED_CONDITION = 6;
    const CARD_DELETED_CONDITION = 7;
    
    private $_db = null;
    public function __construct() {
        $this->_db = DB::getInstance();
    }
    
    public function isAvailable($card_no){
        $table = 'scratch_cards';
        $conditions = [
            ['code', '=', $card_no],
            ['status_id', '=', self::CARD_AVAILABLE_CONDITION],
        ];
        $check = $this->_db->get($table, $conditions);
        return $check->count() ? true : false;
    }
    
    public static function isUsed($card_no){
        
    }
    
    public static function isDeleted($card_no){
        
    }
    
    public static function find($card_no){
        $sql = "";
    }
}