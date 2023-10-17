<?php
class VT extends Upload{

    const Host = "localhost";
    const UserName = "root";
    const Password = "1234";
    const DataBase = "Test";
    
    protected static $db;

    public static $table = "";
    public static $select = "*";
    
    public static $whereRawKey;
    public static $whereRawKeyVal;

    public static $whereKey;
    public static $whereVal = array();


    function __construct()
    {
        self::__connect();
    }

    public static function __connect()
    {
        try {
            self::$db = new PDO("mysql:host=".self::Host.";dbname=".self::DataBase.";charset=utf8",self::UserName,self::Password);
        } catch (PDOException $error) {
            $data = (Object) ["type"=>"501", "title"=>"Conection Error", "mesaj"=>"Veritabanına Bağlantı Yapılamadı.", "code"=>$error->getMessage()];

            return self::view("connection", $data);
            exit();
        }
    }

    public static function table($tableName)
    {
        self::$table = $tableName;
        self::$select = "*";
        self::$whereRawKey = null;
        self::$whereRawKeyVal = null;
        self::$whereKey = null;
        self::$whereVal = [];
        return new self;
    }

    public static function select($coloms)
    {
        self::$select = (is_array($coloms)) ? implode(',', $coloms) : $coloms;        
        return new self;
    }

    public static function whereRaw($whereRaw, $whereRawVal)
    {
        self::$whereRawKey = "(". $whereRaw .")";
        self::$whereRawKeyVal = $whereRawVal;
        return new self;
    }

    public static function where($coloms1, $coloms2 = null, $coloms3 = null)
    {
        if (is_array($coloms1))
        {
            $keyList = [];
            foreach ($coloms1 as $key=>$item)
            {
                self::$whereVal[] = $item;
                $keyList[] = $key;
            }
            self::$whereKey = implode("=? AND ", $keyList)."=?";
        }
        else if ($coloms2 != null && $coloms3 == null)
        {
            self::$whereKey = $coloms1 .'=?';
            self::$whereVal[] = $coloms2;
        }
        else if ($coloms2 != null && $coloms3 != null)
        {
            self::$whereKey = $coloms1.$coloms2.'?';
            self::$whereVal[] = $coloms3;
        }

        return new self;
    }

    public static function view($pageName, $error)
    {
        $fileHref = "errors/".$pageName.".php";

        if (file_exists($fileHref))
            include_once($fileHref);
        else if (file_exists("../".$fileHref))
            include_once("../".$fileHref);

    }
}

?>