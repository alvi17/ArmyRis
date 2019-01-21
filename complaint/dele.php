<?php

/* 
 * Hard delete of complaint. 
 * No relevant record will be exists after delete operation is run. 
 * Authenticated user must be needed to perform delte operation.
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date June 02, 2018 10:34 am
 */

require "../core/config.php";
require "../core/init.php";
// require "../modules/acl/Roles.php";
// require "../modules/complaint/Complaint.php";

$is_complain_del_allowed = in_array(Session::get('uid'), $uid_conplain_del_allowed) ? true : false;
$id = (int) $_POST['id'];

if($is_complain_del_allowed ){
	DB::getInstance()->delete('complains', 'id', '=', $id);
	DB::getInstance()->delete('complains_audit', 'complain_id', '=', $id);
	echo 'Complaint deleted successfuly!';
} else{
    echo 'You do not have permission to delete this complaint!';
}