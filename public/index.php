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
     catch(PDOException $e){ error_log( $e->getMessage().": ".$e->getCode()."\nQuery: $query", 3, "/srv/www/rpi/error.log" ); exit; }
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
                                          theme varchar(20),
                                          cmd text,
                                          last datetime default CURRENT_TIMESTAMP  )";
  try{  $this->db->exec($query); }
  catch(PDOException $e){ error_log( $e->getMessage().": ".$e->getCode()."\nQuery: $query", 3, "/srv/www/rpi/error.log" ); exit; }
}
public function getall(){
   $query="select * from rpi order by sn";
   try{ $r = $this->db->query($query); }
   catch(PDOException $e){ error_log( $e->getMessage().": ".$e->getCode()."\nQuery: $query", 3, "/srv/www/rpi/error.log" ); exit; }
   $result=array();
   while( $data = $r->fetch(\PDO::FETCH_ASSOC) ){
      $result[$data['sn']] = $data;
   }
   return $result;   
}

public function get($sn){
   $query="select * from rpi where sn='$sn' limit 1";
   try{ $r = $this->db->query($query); }
   catch(PDOException $e){ error_log( $e->getMessage().": ".$e->getCode()."\nQuery: $query", 3, "/srv/www/rpi/error.log" ); exit; }
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
   try{ $r = $this->db->query($query); }
   catch(PDOException $e){ error_log( $e->getMessage().": ".$e->getCode()."\nQuery: $query", 3, "/srv/www/rpi/error.log" ); exit; }
   return $r;   
}

public function update($d, $key='sn'){
   $values="";
   foreach( $d as $k=>$v){
      $values.=" $k=".'"'.$v.'"'.",";
   }
   $values[strlen($values)-1]=' ';
   $query="update rpi set $values, last=datetime('now') where $key=".'"'.$d[$key].'"';
   //error_log( "Query: $query", 3, "/srv/www/rpi/error.log" );
   try{ $r = $this->db->query($query); }
   catch(PDOException $e){ error_log( $e->getMessage().": ".$e->getCode()."\nQuery: $query", 3, "/srv/www/rpi/error.log" ); exit; }
   return $r;   
}

public function set($d){

}

public function del($sn){
   $query="delete from rpi where sn='$sn' limit 1";
   try{ $r = $this->db->query($query); }
   catch(PDOException $e){ error_log( $e->getMessage().": ".$e->getCode()."\nQuery: $query", 3, "/srv/www/rpi/error.log" ); exit; }
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
   $buf.="<div class='rpi-status' >".(($online=='rpi-online')?"ON-Line":"OFF-Line: ".totimedistance($tdiff)."\n<br><a href='http://rpi.ontime24.pl/?get=delete&sn=".$rpi["sn"]." '>remove</a>")."</div>\n";

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
      case 'post':         
         $df=json_decode(file_get_contents('php://input'), true);
         foreach( $df as $k=>$v) $df[$k]=str_replace('"','',$v);
         $df['model']=str_replace("Raspberry Pi","RPi",$df['model']);
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
                   'wmac'=>$df['wmac'],
                   'theme'=>$df['theme']
         );
         $rpi = $db->get($d['sn']);
         if( is_array($rpi) and count($rpi)>0 ){
            $r=$db->update($d);
         }else{
            $d['cmd']=json_encode( array( 'name'=>'none' ) );
            $r=$db->insert($d);
         }
         $cmd = json_decode( $rpi['cmd'] );
         $x = array( 'status'=>'OK', 'time'=>date("Y-m-d H:i:s"), 'cmd'=>$cmd['cmd'] );
         $buf = base64_encode(json_encode( $x ));
         echo $buf;
         if( $cmd['name']!='none' ){
            $db->update( array( 'sn'=>$rpi['sn'], 'cmd'=>json_encode( array( 'name'=>'none' ) ) ) );
         }
         exit;
         break; 
      case 'delete':
         $db->del($_GET['sn']);
         header("Location: /?get=getall");
         exit;
         break;      
      case 'theme':   // set 'theme' cmd
         if( is_array($rpi=$db->get($_GET['sn'])) and count($rpi)>0 and $_GET['face']!='' ){
            $cmd=json_encode( array( 'name'=>'theme', 'value'=>$_GET['face'] ) );
            $db->update( array( 'sn'=>$rpi['sn'], 'cmd'=>$cmd ) );
         }
         header("Location: /?get=getall");
         exit;
         break;      
      case 'hostname':   // set 'hostname' cmd
            if( is_array($rpi=$db->get($_GET['sn'])) and count($rpi)>0 and $_POST['hostname']!='' ){
               $cmd=json_encode( array( 'name'=>'hostname', 'value'=>$_POST['hostname'] ) );
               $db->update( array( 'sn'=>$rpi['sn'], 'cmd'=>$cmd ) );
            }
            header("Location: /?get=getall");
            exit;
            break;      
      case 'edit':  // show 'edit' form
            if( is_array($rpi=$db->get($_GET['sn'])) and count($rpi)>0 ){
               $buf="<p></p>";
            }
            break;
      case 'test':
         $buf="<ol>\n";
         foreach( array('blue','gold','red','green','purple','silver') as $face ){
            $buf.="<ul><a href='?get=theme&face=$face' >$face</a></ul>\n";
         }
         $buf.="</ol>\n";
         $buf.="<p><a href='?get=getall'>Go back to RPi list</a></p>\n";
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
