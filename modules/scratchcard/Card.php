<?php

/**
 * Description of Scratchcard
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date January 12, 2017 03:16 am
 */

class Card {
    private $length = null;
    private $serial_number_start_point = 150;
    private $tmp_scratch_card_table = 'tmp_scratch_cards';
    
    public function __construct() {}
    
    public function listCards($code, $is_total = false, $page=1, $limit = LIMIT_PER_PAGE){
        $cond_str = '';
        $cond_params = [];
        
        if(!empty($code)){
            $cond_str .= "WHERE c.`code` LIKE '%".$code."%' OR c.`serial_no` LIKE '%".$code."%'";
        }
        
        if($is_total){
            $fields = "COUNT(1) AS TOTAL";
            $order_limit_str = "";
        } else{
            $fields = "
                  c.`code`
				, c.`serial_no`
                , c.`amount`
                , CASE c.`status_id` 
                    WHEN 5 THEN 'Available' 
                    WHEN 6 THEN 'Used' 
                    ELSE '?' 
                  END AS `status`
                , s.`username` AS used_by
                , p.`created_at` AS `used_at`";
            
            $order_limit_str = " ORDER BY c.`status_id` DESC, c.`code` ASC
            LIMIT ".($page-1)*$limit.", {$limit}";
        }
        
        $sql = "SELECT ".$fields."
            FROM `scratch_cards` c
            LEFT JOIN `payments` p ON p.`id_payment_key` = c.`ref_id` AND p.`type` = 'Scratch Card'
            LEFT JOIN `subscribers` s ON s.`id_subscriber_key` = p.`subscriber_id`
            ".$cond_str.$order_limit_str;
        
        return $data = DB::getInstance()->query($sql, $cond_params)->results();
    }
    
    public function getMaxCardSerialNumber($prefix){
        $sql = "SELECT MAX(s.`serial_no`) AS sl
                FROM `scratch_cards` s
                WHERE s.`serial_no` LIKE '{$prefix}%'";
        $tmp = DB::getInstance()->query($sql)->first();
        $tmp = str_replace('O', '0', str_replace($prefix, '', $tmp['sl']));
        $tmp = (int) $tmp;
        return empty($tmp) ? $this->serial_number_start_point : $tmp;
    }
    
    
    public function getRandomNumbersInRange($total, $length, $index=0){
        $this->length = $length;
        
        $randomNumbers = array_map(function() {
            $cryptoStrong = true;
            $bytes = openssl_random_pseudo_bytes($this->length, $cryptoStrong);
            $str = strtoupper(bin2hex($bytes));
            // Removes if first character is 0
            //if( $str{0} == "0" || $str{0} == "O" ) {$str = substr($str, 1);}
            return $randomString = substr($str, $this->length);
        }, range(1, $total));

        // Re-index from a key position
        return array_combine(range(($index+1), count($randomNumbers) + $index), $randomNumbers);
    }
    
    private function importCardsInTempTable($amount, $quantity, $card_length, $card_prefix, $serial_length, $serial_prefix){
        
        $serial_length = $serial_length - strlen($serial_prefix);
        
        $sql = "DROP TABLE IF EXISTS `{$this->tmp_scratch_card_table}`";
        DB::getInstance()->exec($sql);
        /*
        $sql = "CREATE TABLE `{$this->tmp_scratch_card_table}` (
                `code` VARCHAR(20) COLLATE utf8_unicode_ci NOT NULL,
                `serial_no` VARBINARY(20) NOT NULL,
                `amount` int(5) DEFAULT NULL,
                `is_active` tinyint(1) DEFAULT '1',
                UNIQUE KEY `unq_code` (`code`, `amount`),
                UNIQUE KEY `serial_no` (`serial_no`)
              ) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		*/
		$sql = "CREATE TABLE `{$this->tmp_scratch_card_table}` (
                `code` VARCHAR(20) COLLATE utf8_unicode_ci NOT NULL,
                `serial_no` VARBINARY(20) NOT NULL,
                `amount` int(5) DEFAULT NULL,
                `is_active` tinyint(1) DEFAULT '1',
                UNIQUE KEY `serial_no` (`serial_no`)
              ) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        DB::getInstance()->exec($sql);
        
        $index = $this->getMaxCardSerialNumber($serial_prefix);
        $cards = $this->getRandomNumbersInRange($quantity+100, $card_length - strlen($card_prefix), $index);
		
        $sql = "INSERT INTO `{$this->tmp_scratch_card_table}` (`code`, `serial_no`, `amount`) VALUES ";
        foreach($cards as $key=>$val){
            $sql .= "('".$card_prefix.$val."', '".$this->formatSerialNumber($key, $serial_length, $serial_prefix)."', $amount), ";
        }
        $sql = rtrim($sql, ', ');
        DB::getInstance()->exec($sql);
    }
    
    private function formatSerialNumber($number, $width, $prefix){
        return $prefix.str_pad((string)$number, $width, "0", STR_PAD_LEFT); 
    }
    
    public function generateCards($amount, $quantity, $card_length, $card_prefix, $serial_length, $serial_prefix){
        
        $now = date('Y-m-d H:i:s');
        $uid = Session::get('uid');
        
        $lot_data = [
            'amount' => $amount,
            'qty' => $quantity,
            'status_id' => 1,
            'created_at' => $now,
            'created_by' => $uid,
        ];
        $lot_id = DB::getInstance()->insert('scratch_card_lots', $lot_data, true);
        
        $this->importCardsInTempTable($amount, $quantity, $card_length, $card_prefix, $serial_length, $serial_prefix);
        
        // Discard existing card numbers
        $sql = "UPDATE `tmp_scratch_cards` t
                INNER JOIN `scratch_cards` s ON t.`code` = s.`code` AND t.`amount` = s.`amount`
                SET t.is_active = 0";
        DB::getInstance()->exec($sql);
        
        // Insert new card numbers
        $sql = "INSERT INTO `scratch_cards` (`code`, `serial_no`, `amount`, `lot_id`, `status_id`, `created_at`, `created_by`)
                SELECT `code`, `serial_no`, `amount`, {$lot_id}, 5, '{$now}', {$uid}
                FROM `{$this->tmp_scratch_card_table}` t
                WHERE t.is_active = 1
				GROUP BY `code`
                LIMIT {$quantity}";
        DB::getInstance()->exec($sql);
    }
}
