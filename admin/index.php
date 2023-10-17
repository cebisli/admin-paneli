<?php
include_once('class/fl.php');
include_once('class/VT.php');

$abc = VT::table("carporate")->where(['title'=>'ikram','description'=>'aciklama','id'=>5]);

$abc = VT::table("carporate")->where("id",'<>',3);

var_dump(VT::$whereKey);
?>