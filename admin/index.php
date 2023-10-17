<?php
include_once('class/fl.php');
include_once('class/VT.php');

$abc = VT::table("carporate")->join('catogories', 'kategori_id', 'id');

//$abc = VT::table("carporate")->where("id",'<>',3);

echo VT::$join;
?>