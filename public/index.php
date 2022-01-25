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
                                          theme text,
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
   $query="select * from rpi where sn='$sn';";
   //error_log( "TEST: get() query: $query\n", 3, "/srv/www/rpi/error.log" );
   try{ $r = $this->db->query($query); }
   catch(PDOException $e){ error_log( $e->getMessage().": ".$e->getCode()."\nQuery: $query", 3, "/srv/www/rpi/error.log" ); exit; }
   $data = $r->fetch(\PDO::FETCH_ASSOC);
   //error_log( "TEST: get() fetch:".print_r($data, true)."\n", 3, "/srv/www/rpi/error.log" );
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

}  #enf of 'Datatable' class


function totimedistance($sec){
   $buf='';
   $d=(int)($sec/86400);
   if( $d>0 ) { $sec=$sec-($d*86400); $buf.="$d days "; }
   $h=(int)($sec/3600);
   if( $h>0 ) { $sec=$sec-($h*3600); $buf.="$h h "; }
   $m=(int)($sec/60);
   if( $m>0 ) { $sec=$sec-($m*60); $buf.="$m min "; }
   $buf.="$sec s";
   return $buf;
}

function view($view,$data=NULL,$to_str=false){
   if($data) extract($data);
   if( $to_str ) ob_start();
   include("view/$view.php");
   if( $to_str ) {
      $out = ob_get_contents();
      ob_end_clean();
      return $out;
   }   
}

$db = new Datatable();
$do_reload = false;

if( isset($_GET['get']) and $_GET['get']!='' ){
   switch ( $_GET['get'] ){
      case 'datetime':
         echo date("Y-m-d H:i:s");
         exit;
         break;
      case 'getall':
         $r = $db->getall();
         $buf = view('rpi_list', array( 'rpi_list'=>$r ), true );
         $do_reload = true;
         break;   
      case 'get':
         $r=$db->get($_GET['sn']);
         //error_log( "TEST: get: ".print_r($r,true)."\n", 3, "/srv/www/rpi/error.log" );
         $buf = view( 'rpi_details', array('rpi'=>$r), true );
         break;
      case 'post':         
         //error_log( "TEST: post start\n", 3, "/srv/www/rpi/error.log" );
         $df=json_decode(file_get_contents('php://input'), true);
         //error_log( "TEST: post json:\n".print_r($df,True), 3, "/srv/www/rpi/error.log" );
         $localdata = json_decode( base64_decode($df['theme']) );
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
         //error_log( "TEST: post rpi1: ".$df['theme']." \n", 3, "/srv/www/rpi/error.log" );
         //error_log( "TEST: post rpi2: ".print_r(base64_decode($df['theme']),true)." \n", 3, "/srv/www/rpi/error.log" );
         $rpi = $db->get($d['sn']);
         //error_log( "TEST: post rpi: ".print_r($rpi,true)." \n", 3, "/srv/www/rpi/error.log" );
         if( is_array($rpi) and count($rpi)>0 ){
            $r=$db->update($d);
            //error_log( "TEST: update\n", 3, "/srv/www/rpi/error.log" );
         }else{
            $d['cmd']=base64_encode(json_encode( array( 'name'=>'none' ) ));
            $r=$db->insert($d);
            $rpi=$d;
            //error_log( "TEST: insert\n", 3, "/srv/www/rpi/error.log" );
         }
         $cmd=json_decode(base64_decode($rpi['cmd']),true);
         //error_log( "TEST: cmd: ".print_r($cmd,true)." \n", 3, "/srv/www/rpi/error.log" );
         $x = array( 'status'=>'OK', 'time'=>date("Y-m-d H:i:s"), 'cmd'=>$cmd );
         $buf = base64_encode(json_encode( $x ));
         echo $buf;
         if( $cmd['name']!='none' ){
            $db->update( array( 'sn'=>$rpi['sn'], 'cmd'=>base64_encode(json_encode( array( 'name'=>'none' ) ) ) ) );
         }

         exit;
         break; 
      case 'delete':
         $db->del($_GET['sn']);
         header("Location: /?get=getall");
         exit;
         break;      
      case 'theme':   // set 'theme' cmd
         $rpi=$db->get($_GET['sn']);
         if( is_array($rpi) and count($rpi)>0 and $_GET['face']!='' ){
            $cmd=base64_encode( json_encode( array( 'name'=>'theme', 'value'=>$_GET['face'], 'sn'=>$_GET['sn'] ) ) );
            $db->update( array( 'sn'=>$rpi['sn'], 'cmd'=>$cmd ) );
            //error_log( "TEST: theme: $cmd\n", 3, "/srv/www/rpi/error.log" );
         }
         header("Location: /?get=getall");
         exit;
         break;      
      case 'edit':  // show 'edit' form
         $rpi=$db->get($_GET['sn']);
         if( is_array($rpi) and count($rpi)>0 ){
            $buf= view( 'edit_params', 
                  array( 'rpi'=>$rpi, 
                     'faces'=>array('blue','gold','red','green','purple','black'),
                     'services'=>array('lcd144')
                  ),
                  true
            );
         }
         break;

      case 'isonline':
         $r = $db->getall();
         foreach( $r as $sn=>$rpi ){
            $tdiff = time() - strtotime($rpi['last']) - date_offset_get(new DateTime);
            if( $tdiff < 4){
               $rpi['online']='online';
               $rpi['last']='ON-Line';
            }else{
               $rpi['online']='offline';
               $rpi['last']=totimedistance($tdiff);
            }
            unset( $rpi['cmd'] );
            $ld =  json_decode( base64_decode( $rpi['theme'] ), true );
            $rpi['theme']=($ld['display']=='oled13')?'mono':$ld['localdata']['theme'];
            $rpi['ld']=$ld['localdata'];
            $rpi['display']=$ld['display'];
            $r[$sn]=$rpi;
         }   
         header("Content-Type: application/json; charset=UTF-8");
         echo ( json_encode( $r ) );
         exit;
         break;
   
      case 'test':
         $buf="<p>Test</p>";
         break;

    default:
         $buf="<h1>RPI: bad token</h1>";

   }
}

if( isset($_GET['set'] ) and $_GET['set']!='' ){
   switch ( $_GET['set'] ){
      case 'hostname':
         $rpi=$db->get($_GET['sn']);
         if( is_array($rpi) and count($rpi)>0 ){   
            $cmd=base64_encode( json_encode( array( 'name'=>'hostname', 'value'=>$_GET['hostname'], 'sn'=>$rpi['sn'] ) ) );
            $db->update( array( 'sn'=>$rpi['sn'], 'cmd'=>$cmd ) );
         }
      header("Location: /?get=getall");
      exit;
      break;      
      case 'rootaccesskey':
         $rpi=$db->get($_GET['sn']);
         if( is_array($rpi) and count($rpi)>0 ){   
            $cmd=base64_encode( json_encode( array( 'name'=>'rootaccesskey', 'value'=>$_GET['rootaccesskey'], 'sn'=>$rpi['sn'] ) ) );
            $db->update( array( 'sn'=>$rpi['sn'], 'cmd'=>$cmd ) );
         }
      header("Location: /?get=getall");
      exit;
      break;      
      case 'piaccesskey':
         $rpi=$db->get($_GET['sn']);
         if( is_array($rpi) and count($rpi)>0 ){   
            $cmd=base64_encode( json_encode( array( 'name'=>'piaccesskey', 'value'=>$_GET['piaccesskey'], 'sn'=>$rpi['sn'] ) ) );
            $db->update( array( 'sn'=>$rpi['sn'], 'cmd'=>$cmd ) );
         }
      header("Location: /?get=getall");
      exit;
      break;      
      case 'pipass':
         $rpi=$db->get($_GET['sn']);
         if( is_array($rpi) and count($rpi)>0 ){   
            $cmd=base64_encode( json_encode( array( 'name'=>'pipass', 'value'=>$_GET['pipass'], 'sn'=>$rpi['sn'] ) ) );
            $db->update( array( 'sn'=>$rpi['sn'], 'cmd'=>$cmd ) );
         }
      header("Location: /?get=getall");
      exit;
      break;      
      case 'update':
         $rpi=$db->get($_GET['sn']);
         if( is_array($rpi) and count($rpi)>0 ){   
            $cmd=base64_encode( json_encode( array( 'name'=>'update', 'service'=>$_GET['service'], 'sn'=>$rpi['sn'] ) ) );
            $db->update( array( 'sn'=>$rpi['sn'], 'cmd'=>$cmd ) );
         }
      header("Location: /?get=getall");         
      exit;
      break;

      case 'poweroff':
         $rpi=$db->get($_GET['sn']);
         if( is_array($rpi) and count($rpi)>0 ){   
            $cmd=base64_encode( json_encode( array( 'name'=>'poweroff', 'sn'=>$rpi['sn'] ) ) );
            $db->update( array( 'sn'=>$rpi['sn'], 'cmd'=>$cmd ) );
         }
      header("Location: /?get=getall");         
      exit;
      break;

      case 'reboot':
         $rpi=$db->get($_GET['sn']);
         if( is_array($rpi) and count($rpi)>0 ){   
            $cmd=base64_encode( json_encode( array( 'name'=>'reboot', 'sn'=>$rpi['sn'] ) ) );
            $db->update( array( 'sn'=>$rpi['sn'], 'cmd'=>$cmd ) );
         }
      header("Location: /?get=getall");         
      exit;
      break;

      case 'towlanAP':
         $rpi=$db->get($_GET['sn']);
         if( is_array($rpi) and count($rpi)>0 ){   
            $cmd=base64_encode( json_encode( array( 'name'=>'towlanAP', 'sn'=>$rpi['sn'] ) ) );
            $db->update( array( 'sn'=>$rpi['sn'], 'cmd'=>$cmd ) );
         }
      header("Location: /?get=getall");         
      exit;
      break;

      case 'towlanClient':
         $rpi=$db->get($_GET['sn']);
         if( is_array($rpi) and count($rpi)>0 ){   
            $cmd=base64_encode( json_encode( array( 'name'=>'towlanClient', 'sn'=>$rpi['sn'] ) ) );
            $db->update( array( 'sn'=>$rpi['sn'], 'cmd'=>$cmd ) );
         }
      header("Location: /?get=getall");         
      exit;
      break; 

      case 'wlan_client':
         $rpi=$db->get($_GET['sn']);
         if( is_array($rpi) and count($rpi)>0 ){   
            $essid=$_GET['essid'];
            $wpa_key=$_GET['wpa_key'];
            $cmd=base64_encode( json_encode( array( 'name'=>'wlan_client', 'sn'=>$rpi['sn'], 'essid'=>$essid, 'wpa_key'=>$wpa_key ) ) );
            $db->update( array( 'sn'=>$rpi['sn'], 'cmd'=>$cmd ) );
         }
      header("Location: /?get=getall");         
      exit;
      break;

      
      default:
         $buf="<h1>RPI: bad token</h1>";
   }   
}

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
view('main', array('content'=>$buf, 'do_reload'=>$do_reload ) );