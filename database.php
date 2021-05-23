<?php

require_once "./CONFIG.php";
require_once "./MysqliDb.php";

if(!defined("TABLE_NAME")) define("TABLE_NAME",     "disburses");

class Database{
    protected $mysqlConn = null;
    public function __construct(){
        if(DATABASE_TYPE == 'mysql'){
            $this->mysqlConn = new MysqliDb(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
        }
    }

    public function saveOrUpdate($data){
        if(DATABASE_TYPE == "file") return $this->saveOrUpdateFile($data);
        return $this->saveOrUpdateMysql($data);
    }

    public function getAll(){
        if(DATABASE_TYPE == "file") return $this->getAllFile();
        return $this->getAllMysql();
    }

    public function getAllPending(){
        if(DATABASE_TYPE == "file") return $this->getAllPendingFile();
        return $this->getAllPendingMysql();
    }

    public function get($id){
        if(DATABASE_TYPE == "file") return $this->getFile($id);
        return $this->getMysql($id);
    }

    // for file
    protected function saveOrUpdateFile($data){
        if(!isset($data['id'])) die("No ID provided.");
        $id = $data['id'];
        file_put_contents(DB_FILE_PATH.$id.".data", json_encode($data));
    }

    protected function getAllFile(){
        $files = scandir(DB_FILE_PATH);
        $datas = [];
        foreach($files as $file) {
            if(substr($file, -5) != ".data") continue;

            $content = file_get_contents(DB_FILE_PATH.$file);
            if(!!($x = json_decode($content, 1))) $datas[] = $x;
        }
        return $datas;
    }

    protected function getAllPendingFile(){
        $all = $this->getAll();
        $pending = [];
        foreach($all as $d){
            if($d['status'] == "PENDING") $pending[] = $d;
        }
        return $pending;
    }

    protected function getFile($id){
        $data = file_get_contents(DB_FILE_PATH.$id.".data");
        return @json_encode($data);
    }

    // for mysql
    protected function saveOrUpdateMysql($data){
        if(!isset($data['id'])) die("No ID provided.");
        $id = $data['id'];
        $exists = $this->mysqlConn->where("id", $id)->getOne(TABLE_NAME, "id");
        if(!$exists) $this->mysqlConn->insert(TABLE_NAME, $data);
        else{
            $data = [
                'status' => $data['status'],
                'receipt'   => $data['receipt'],
                'time_served'   => $data['time_served']
            ];
            $this->mysqlConn->where("id", $id)->update(TABLE_NAME, $data);
        }

    }

    protected function getAllMysql(){
        return $this->mysqlConn->arrayBuilder()->get(TABLE_NAME);
    }

    protected function getAllPendingMysql(){
        return $this->mysqlConn->arrayBuilder()->where("status", "PENDING")->get(TABLE_NAME);
    }

    protected function getMysql($id){
        return $this->mysqlConn->where("id", $id)->getOne();
    }
    

}