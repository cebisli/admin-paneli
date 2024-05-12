<?php
class VT extends Upload{

    const Host = "localhost";
    const UserName = "root";
    const Password = "1234";
    const DataBase = "Test";
    
    protected static $db;

    public static $table = "";
    public static $select = "*";
    public static $orderBy = null;
    public static $limit = null;
    public static $join = "";

    
    public static $whereRawKey;
    public static $whereRawVal;

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
        self::$whereRawVal = null;
        self::$whereKey = null;
        self::$whereVal = [];
        self::$orderBy = null;
        self::$limit = null;
        self::$join = "";
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
        self::$whereRawVal = $whereRawVal;
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

    public static function orderBy($parameter)
    {
        $str = $parameter[0]." ";
        if (count($parameter) > 1)
            $str .= $parameter[1];
        else
            $str .= "ASC";
        self::$orderBy = $str;
        return new self;
    }

    public static function limit($start, $end = null)
    {
        self::$limit = $start. (($end != null) ? ",".$end : "");
        return new self;
    }

    public static function join($tableName, $thisColums, $joinColums)
    {
        self::$join .= "INNER JOIN ".$tableName." ON ".self::$table.'.'.$thisColums." = ".$tableName.'.'.$joinColums;
        return new self;
    }

    public static function Get()
    {
        $sql = "SELECT ".self::$select." FROM ".self::$table." ";
        $sql .= !empty(self::$join) ? self::$join : '';
        
        $where = null;
        if (!empty(self::$whereKey) && !empty(self::$whereRawKey))
        {
            $sql .= "where " .self::$whereKey. " AND ".self::$whereRawKey; 
            $where = array_merge(self::$whereVal, self::$whereRawVal);
        }
        else
        {
            if (!empty(self::$whereKey))
            {
                $sql .= "where " .self::$whereKey. " "; 
                $where = self::$whereVal;
            }
            
            if (!empty(self::$whereRawKey))
            {
                $sql .= "where " .self::$whereRawKey. " "; 
                $where = self::$whereRawVal;
            }
        }
        
        $sql .= !empty(self::$orderBy) ? "ORDER BY ".self::$orderBy." " : '';
        $sql .= !empty(self::$limit) ? "LIMIT ".self::$limit : '';
        
        if ($where != null)
        {
            $entity = self::$conn->prepare($sql);
            $sync = $entity->execute($where);
        }
        else
            $entity = self::$conn->query($sql);
        
        $result = $entity->fetchAll(PDO::FETCH_ASSOC);
        if ($result)
        {
            $data = [];
            foreach ($result as $item)            
                $data[] = (Object) $item;            
            return $data;
        }
        else
            return false;
    }
    
    public static function GetFirst()
    {
        $entity = self::Get();
        if ($entity)
            return $entity[0];
        else
            return false;
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
