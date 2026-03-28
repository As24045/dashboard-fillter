<?php
require_once(__DIR__ . "/autoload.php");

$json = array();


$action->db->sql("UPDATE tbl_client SET membership_status = 0, expiryDate = NULL, purchase_date = NULL WHERE membership_status = 1 AND expiryDate <= CURDATE()");



echo json_encode($json);

?>