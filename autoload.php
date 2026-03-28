<?php
date_default_timezone_set("Asia/Kolkata");


require_once('config.php'); // CONNECTION AND CRUD OPERATION FUNCTION MAIN FILE


class Action{
    public $db;
    public function __construct(){
        $this ->db = new Database;
    }
}
$action = new Action; // THIS SINGLE OBJECT VARIABLE USED FOR ALL SITE AND ADMIN QUERY
