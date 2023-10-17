<?php
include_once('class/fl.php');
include_once('class/VT.php');

$abc = VT::table("carporate")->join('catogories', 'kategori_id', 'id');

//$abc = VT::table("carporate")->where("id",'<>',3);

$a = VT::table("carporate")->select(['carporate.id','carporate.title','carporate.description'])
                            ->join("catogories","kategori_id","id")
                            ->orderBy(['carporate.id','desc'])
                            ->limit(0,5)
                            ->Get();
var_dump($a);                            
?>