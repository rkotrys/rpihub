<?php
session_start();
date_default_timezone_set("Europe/Warsaw");

class Datatable 
{
protected $db;

public function __construct(){
// Tworzenie obiektu klasy PDO - baza danych SQLite
  try{ 
       $this->db = new PDO('sqlite:'.dirname(__FILE__).'./../db/db.sq3'); 
       $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     }
  catch(PDOException $e){ 
       echo $e->getMessage().": ".$e->getCode();  
       exit; 
     }
  $query="CREATE TABLE IF NOT EXISTS rpi (sn INTEGER PRIMARY KEY, 
                                          arch varchar(20), 
                                          chip varchar(20),
                                          hostname varchar(50), 
                                          ip varchar(16), 
                                          wip varchar(16), 
                                          puuid varchr(20), 
                                          emac varchar(20), 
                                          wmac varchar(20), 
                                          last datetime default CURRENT_TIMESTAMP  )";
  try{  $this->db->exec($query); }
  catch(PDOException $e){ 
      echo $e->getMessage().": ".$e->getCode(); 
      exit; 
      }
}
public function getall(){
   $query="select * from rpi order by sn";
   try{ $r = $this->db->query($query); }
   catch(PDOException $e){ echo $e->getMessage().": ".$e->getCode()."\nQuery: $query"; exit; }
   $result=array();
   while( $data = $r->fetch(\PDO::FETCH_ASSOC) ){
      $result[$data['sn']] = $data;
   }
   return $result;   
}

public function get($sn){
   $query="select * from rpi where sn='$sn' limit 1";
   try{ $r = $this->db->query($query); }
   catch(PDOException $e){ echo $e->getMessage().": ".$e->getCode()."\nQuery: $query"; exit; }
   $result=array();
   $data = $r->fetch(\PDO::FETCH_ASSOC);
   return $data;   
}

public function insert($d){
   $query="insert into rpi ( sn, arch, chip, hostname, ip, wip, puuid, emac, wmac ) values ('".$d['sn']."', '".$d['arch']."', '".$d['chip']."', '".$d['hostname']."', '".$d['ip']."', '".$d['wip']."', '".$d['puuid']."', '".$d['emac']."', '".$d['wmac']."')";
   echo $query;
   try{ $r = $this->db->query($query); }
   catch(PDOException $e){ echo $e->getMessage().": ".$e->getCode()."\nQuery: $query"; exit; }
   return $r;   
}

public function update($d){

}
public function set($d){

}


public function del($sn){
   $query="delete from rpi where sn='$sn' limit 1";
   try{ $r = $this->db->query($query); }
   catch(PDOException $e){ echo $e->getMessage().": ".$e->getCode()."\nQuery: $query"; exit; }
   return $r;
}


}

function rpi_show($rpi){
   $buf="<div class='rpi' sn='".$rpi["sn"]."'>\n";
   foreach( $rpi as $k=>$v){
       $buf.="<div class='rpi-$k'>\n<div class='rpikey'>$k</div>\n<div class='rpivalue'>$v</div>\n</div>\n";
   }
   $buf.="</div>\n";
   return $buf;
}

function rpi_showall($r){
   $buf='';
   foreach( $r as $k=>$rpi){
      $buf .= rpu_show($rpi);
   }
   return $buf;
}

$db = new Datatable();


if( isset($_GET['get']) and $_GET['get']!='' ){
   switch ( $_GET['get'] ){
      case 'datetime':
         echo date("Y-m-d H:i:s");
         exit;
         break;
      case 'getall':
         $r = $db->getall();
         $buf = rpi_showall($r);
         break;   
      case 'get':
         $r=$db->get($_GET['sn']);
         print_r($r);
         break;
      case 'insert':
         $d=array( 'sn'=>$_GET['sn'],
                   'arch'=>$_GET['arch'],
                   'chip'=>$_GET['chip'],
                   'hostname'=>$_GET['hostname'],
                   'ip'=>$_GET['ip'],
                   'wip'=>$_GET['wip'],
                   'puuid'=>$_GET['puuid'],
                   'emac'=>$_GET['emac'],
                   'wmac'=>$_GET['wmac']
         );
         $r=$db->insert($d);
         echo $r;
         break; 
      case 'delete':
         $db->del($_GET['sn']);
         break;      
      case 'show':
         $buf=
         $db->del($_GET['sn']);
         break;      
      case 'test':
         $buf="<p>Content</p>";
         break;

    default:
         $buf="<h1>RPI: bad token</h1>";

   }
}

echo "<!DOCTYPE HTML><html><head><meta http-equiv='content-type' content='text/html; charset=utf-8'></head><body>$buf</body></html>";
exit;
