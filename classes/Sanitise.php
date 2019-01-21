<?php

/**
 * Description of Sanitise
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date December 02, 2016 10:07
 */
class Sanitise {
    public static function escape($string){
        return htmlentities($string, ENT_QUOTES, 'UTF-8');
    }
}
