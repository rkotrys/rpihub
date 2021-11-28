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
  $query="CREATE TABLE IF NOT EXISTS rpi (sn varchar(20), 
                                          arch varchar(20), 
                                          chip varchar(20),
                                          hostname varchar(50), 
                                          ip varchar(16), 
                                          wip varchar(16), 
                                          puuid varchar(20), 
                                          emac varchar(20), 
                                          wmac varchar(20), 
                                          model varchar(50),
                                          memtotal varchar(20),
                                          `version` varchar(20),
                                          release varchar(20),
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
   $keys="";
   $values="";
   foreach( $d as $k=>$v){
      $keys.=" $k,";
      $values.=' "'.$v.'",';
   }
   $keys[strlen($keys)-1]=' ';
   $values[strlen($values)-1]=' ';
   $query="insert into rpi ( $keys ) values ( $values )";
   echo "INSERT: $query <br>\n";
   //$query="insert into rpi ( sn, arch, chip, hostname, ip, wip, puuid, emac, wmac ) values ('".$d['sn']."', '".$d['arch']."', '".$d['chip']."', '".$d['hostname']."', '".$d['ip']."', '".$d['wip']."', '".$d['puuid']."', '".$d['emac']."', '".$d['wmac']."')";
   try{ $r = $this->db->query($query); }
   catch(PDOException $e){ echo $e->getMessage().": ".$e->getCode()."\nQuery: $query"; exit; }
   return $r;   
}

public function update($d, $key='sn'){
   $values="";
   foreach( $d as $k=>$v){
      $values.=" $k=".'"'.$v.'"'.",";
   }
   $values[strlen($values)-1]=' ';
   $query="update rpi set $values where $key=".'"'.$d[$key].'",';
   echo "UPDATE: $query <br>\n";
   //$query="update rpi set arch='".$d['arch']."', chip='".$d['chip']."', hostname='".$d['hostname']."', ip='".$d['ip']."', wip='".$d['wip']."', puuid='".$d['puuid']."', emac='".$d['emac']."', wmac='".$d['wmac']."', last=datetime('now') where sn='".$d['sn']."' ";
   try{ $r = $this->db->query($query); }
   catch(PDOException $e){ echo $e->getMessage().": ".$e->getCode()."\nQuery: $query"; exit; }
   return $r;   
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

function totimedistance($sec){
   $buf='';
   $d=(int)($sec/86400);
   if( $d>0 ) { $sec=$sec-($d*86400); $buf.="$d days "; }
   $h=(int)($sec/3600);
   if( $h>0 ) { $sec=$sec-($h*3600); $buf.="$h H "; }
   $m=(int)($sec/60);
   if( $m>0 ) { $sec=$sec-($m*60); $buf.="$m min "; }
   $buf.="$sec s";
   return $buf;
}

function rpi_show($rpi){
   $tdiff = time() - strtotime($rpi['last']) - date_offset_get(new DateTime);
   if( $tdiff < 4 ){ $online='rpi-online'; } else { $online='rpi-offline'; }
   $buf="<div class='w3-card rpi $online' sn='" . $rpi["sn"] . "' >\n";
   $buf.="<div class='rpi-header'>".$rpi['model']."</div>\n";
   foreach( $rpi as $k=>$v){
      if( $k=='model') continue;
       $buf.="<div class='flex-container  rpi-$k'>\n<div class='rpikey'>$k :</div>\n<div class='rpivalue'>$v</div>\n</div>\n";
   }
   $buf.="<div class='rpi-status' >".($tdiff < 4)?"ON-Line":"OFF-Line: ".totimedistance($tdiff)."</div>\n";
   $buf.="</div>\n";
   return $buf;
}

function rpi_showall($r){
   $buf='';
   $buf.="<div class='rpi-list'>\n";
   foreach( $r as $k=>$rpi){
      $buf .= rpi_show($rpi);
   }
   $buf.="</div>";
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
         $buf = rpi_showall( array($r['sn']=>$r) );
         break;
      case 'insert':
      case 'post':         
         $df=json_decode(file_get_contents('php://input'), true);
         $d=array( 'sn'=>$df['serial'],
                   'arch'=>$df['machine'],
                   'chip'=>$df['chip'],
                   'hostname'=>$df['hostname'],
                   'ip'=>$df['ip'],
                   'wip'=>$df['wip'],
                   'puuid'=>$df['puuid'],
                   'emac'=>$df['emac'],
                   'release'=>$df['release'],
                   'version'=>$df['version'],
                   'memtotal'=>$df['memtotal'],
                   'model'=>$df['model'],
                   'wmac'=>$df['wmac']
         );
         if( is_array($db->get($d['sn']))){
            $r=$db->update($d);
         }else{
            $r=$db->insert($d);
         }
         exit;
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
?><!DOCTYPE HTML>
<html>
<head>
   <meta http-equiv='content-type' content='text/html; charset=utf-8'>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> 
   <link rel="stylesheet" href="css/rpi.css"> 
</head>
<body>
   <header class="w3-container w3-teal">
   <h1>RPI-hub</h1>
   </header>      
<?php if( isset($buf) ) print($buf); ?>
</body>
</html>
