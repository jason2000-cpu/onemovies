<?php
session_start();
if(!is_dir(__DIR__.'./db'))
    mkdir(__DIR__.'./db');
if(!defined('db_file')) define('db_file',__DIR__.'./db/plan_db.db');
function my_udf_md5($string) {
    return md5($string);
}

Class DBConnection extends SQLite3{
    protected $db;
    function __construct(){
        $this->open(db_file);
        $this->createFunction('md5', 'my_udf_md5');
        $this->exec("PRAGMA foreign_keys = ON;");

        $this->exec("CREATE TABLE IF NOT EXISTS `admin_list` (
            `admin_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `fullname` INTEGER NOT NULL,
            `username` TEXT NOT NULL,
            `password` TEXT NOT NULL,
            `status` INTEGER NOT NULL Default 1,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"); 

        $this->exec("CREATE TABLE IF NOT EXISTS `system_info` (
            `info_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `meta_field` TEXT NOT NULL,
            `meta_value` TEXT NOT NULL,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        $this->exec("CREATE TABLE IF NOT EXISTS `plan_list` (
            `plan_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `title` TEXT NOT NULL,
            `description` TEXT NOT NULL,
            `current_price` REAL NOT NULL DEFAULT 0,
            `before_price` REAL NOT NULL DEFAULT 0,
            `subscription_type` TEXT NOT NULL,
            `status` INTEGER NOT NULL DEFAULT 1,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        $this->exec("CREATE TABLE IF NOT EXISTS `application_list` (
            `application_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `plan_id` INTEGER NOT NULL,
            `application_code` TEXT NOT NULL,
            `fullname` TEXT NOT NULL,
            `status` INTEGER NOT NULL DEFAULT 0,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`plan_id`) REFERENCES `plan_list`(`plan_id`) ON DELETE CASCADE
        )");
        $this->exec("CREATE TABLE IF NOT EXISTS `application_meta` (
            application_id INTEGER NOT NULL,
            meta_field text NOT NULL,
            meta_value text NOT NULL,
            FOREIGN KEY (`application_id`) REFERENCES `application_list`(`application_id`) ON DELETE CASCADE
        )");

        $this->exec("CREATE TABLE IF NOT EXISTS `featured_list` (
            `plan_id` INTEGER NOT NULL,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`plan_id`) REFERENCES `plan_list`(`plan_id`) ON DELETE CASCADE
        )");

        $this->exec("INSERT or IGNORE INTO `admin_list` VALUES (1,'Administrator','admin',md5('admin123'),1, CURRENT_TIMESTAMP)");
        $has_settings = $this->query("SELECT count(info_id) as `count` FROM `system_info` ")->fetchArray()['count'];
        if($has_settings <= 0){
            $this->exec("INSERT or IGNORE INTO `system_info` VALUES (1,'company_name','Sample Subscription Company', CURRENT_TIMESTAMP),
            (2,'company_address','Here St.,There City, Nowhere Province', CURRENT_TIMESTAMP),
            (3,'company_contact','091233554466 / 4567-885-8899', CURRENT_TIMESTAMP)
            ");
        }
        $settings = $this->query("SELECT * FROM `system_info` ");
        while($row=$settings->fetchArray()){
            $_SESSION['system_info'][$row['meta_field']] = $row['meta_value'];
        }


    }
    function __destruct(){
         $this->close();
    }
}

$conn = new DBConnection();