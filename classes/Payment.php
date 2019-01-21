<?php
/**
 * Description of Payment
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date December 02, 2016 10:07
 */
class Payment {
    //private $_db = null;
    public static $payment_types = ['Scratch Card', 'Complementary', 'Enable Internet', 'Invoice', NULL];
    
    public function __construct() {
        //$this->_db = DB::getInstance();
    }
    
    public static function rechargAcountParams($subscriber_id){
        $sql = "SELECT
                  s.`category`
                , s.`complementary_amount`
                , p.`price` AS `package_price`
                , (p.`price` - s.`complementary_amount`) AS `payment_amount`
                , s.`payment_balance`
                , s.`category_version`
                , s.`payment_version`
                FROM `subscribers` s 
                INNER JOIN packages p ON p.id = s.`package_id`
                WHERE s.id_subscriber_key = ?";
        return DB::getInstance()->query($sql, [$subscriber_id])->first();
    }
}
