<?php
include_once('class/fl.php');
include_once('class/VT.php');


VT::table("carporate")->select(['title','description','id'])->whereRaw("title like '%?%' AND description=?",['a', 'bilgi']);
echo VT::$select;
?>