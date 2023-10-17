<?php
include_once('class/fl.php');
include_once('class/VT.php');

VT::table("carporate")->select('title,description,id');
echo VT::$select;
VT::table("carporate")->select(['title','description','id']);
echo VT::$select;
?>