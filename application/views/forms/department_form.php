<?php
   
   $name = "";
   $sorting="";
   $created_by="";
   $modified_by="";
   $created="";
   $modified="";

   
   if(isset($department)){
   		//var_dump($item[0]['id']);exit;

   		$name = $department[0]['name'];
   		$sorting = $department[0]['sorting'];
   		$created_by = $department[0]['created_by'];
   		$modified_by = $department[0]['modified_by'];
   		$created = $department[0]['created'];
   		$modified = $department[0]['modified'];

   }
   ?>
<style>
   .modal-dialog{width: 400px !important;height: 300px;}
</style>
