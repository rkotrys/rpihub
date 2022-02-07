<?php
        $ld =  json_decode( base64_decode( $rpi['theme'] ), true );
        $rpi['theme']=($ld['display']=='oled13')?'mono':$ld['localdata']['theme'];
        $service = $ld['display'];

function setparam( $header, $name, $sn, $value="",$placeholder="" ){
$buf = "
<form class=\"subform\" method=\"get\" action=\"\" >
<header class=\"w3-left-align w3-border-bottom\">$header</header>
<div class=\"w3-row\">
<div class=\"w3-col m8 l8\">
<input class=\"w3-input\" name=\"$name\" id=\"$name\" type=\"text\" value=\"$value\" placeholder=\"$placeholder\">
</div>
<div class=\"w3-col m4 l4\">
<button  class=\"w3-button w3-red w3-round w3-small\" style=\"color:red;margin-top:6px;\" type=\"submit\" name=\"submit\" value=\"$name\" >Submit</button>
</div>
</div>
<input type=\"hidden\" name=\"set\" value=\"$name\" >
<input type=\"hidden\" name=\"sn\" value=\"$sn\" >
</form>
";
END;
echo $buf;
}
function setparams( $header, $sn, $cmd_name, $items ){
    $buf = "<form id=\"$cmd_name\" class=\"subform\" method=\"get\" action=\"\" >\n<header class=\"w3-left-align w3-border-bottom\">$header</header>\n";
    foreach($items as $k=>$v){
       $name=$k;
       $value=$v;
       $buf.="<div class=\"w3-row\"><div class=\"w3-col m12 l12 w3-left-align\"><label>$name</label></div>\n";
       $buf.="<div class=\"w3-col m12 l12\"><input class=\"w3-input\" name=\"$name\" id=\"$name\" type=\"text\" value=\"$value\" ></div></div>\n";
    }
    $buf.="<button  class=\"w3-button w3-red w3-round w3-small\" style=\"color:red;margin-top:6px;\" type=\"submit\" name=\"submit\" value=\"$sn\" >Submit</button>
<input type=\"hidden\" name=\"set\" value=\"$cmd_name\" >
<input type=\"hidden\" name=\"sn\" value=\"$sn\" >
</form>
";
END;
echo $buf;
}
?>
<div class='w3-container'>
    <header>
        <h2>Set parameters of <?=$rpi['model']?> SN: <?=$rpi['sn']?></h2>
        <h4>HOST:  <strong><?=$rpi['hostname']?></strong>, PUUID: <strong><?=$rpi['puuid']?></strong>, msdid: <strong><?=$ld['localdata']['msdid']?></strong>, display: <strong><?=$ld['display']?></strong></h4>
    </header>
    <section  class="rpi-list" id="rpi-details" >
    <?php if( $rpi['theme']!='mono' and $rpi['theme']!='headless' ) { ?>    
    <article class="w3-card">
        <header class="w3-panel">
            <h3>Color of the clock face</h3>
        </header>
        <ol>
        <?php foreach( $faces as $face ) { ?>    
            <?php if( $face==$rpi['theme'] ){ ?>
                <li><span style="background-color: <?=$face?>;color:<?=$face?>;border:2px solid black;"> ***** </span> &nbsp;&nbsp;&nbsp;<?=$face?></li>
            <?php } else { ?>
                <li><a style="text-decoration:none;" href='?get=theme&face=<?=$face?>&sn=<?=$rpi['sn']?>' ><span style="background-color: <?=$face?>;color:<?=$face?>;box-shadow:2px 2px 5px #222;"> ***** </span>  &nbsp;&nbsp;&nbsp;<?=$face?></a> </li>
            <?php } ?>    
        <?php } ?>    
        </ol>
    </article>
    <?php } //ecd of theme card ?>
    <article  class="w3-card">
        <header >
            <h3>Host manager</h3>
        </header>
        <?=setparam("Set new hostname","hostname",$rpi['sn'],$rpi['hostname']);?>
        <?=setparam("Set 'root' access key","rootaccesskey",$rpi['sn'],"","ssh public key");?>
        <?=setparam("Set 'pi' access key","piaccesskey",$rpi['sn'],"","ssh public key");?>
        <?=setparam("Set password for 'pi'","pipass",$rpi['sn'],"","password for pi");?>

        <div class="w3-center" >
            <a class="w3-button w3-red w3-round w3-small" style="color:red;margin-bottom:6px;" href='?set=poweroff&sn=<?=$rpi['sn']?>' >Power OFF</a>
            <a class="w3-button w3-red w3-round w3-small" style="color:red;margin-bottom:6px;" href='?set=reboot&sn=<?=$rpi['sn']?>' >Reboot</a>
            <a class="w3-button w3-green w3-round w3-small" style="color:red;margin-bottom:6px;" href='?set=update&service=<?=$service?>&sn=<?=$rpi['sn']?>' >Update service: <?=$service?></a>
        </div>
    </article>
    <article  class="w3-card">
        <header>
            <h3>AP manager</h3>
        </header>
        <?php if( !is_array( $ld['localdata']['AP'])  ) { ?>
        <div class="w3-center" >
            <a class="w3-button w3-red w3-round w3-small" style="color:red;margin-bottom:6px;" href='?set=towlanAP&sn=<?=$rpi['sn']?>' >Swich to:<br><strong>Routed Access Point</strong></a>            
        </div>
        <div class="w3-center" >
            <a class="w3-button w3-red w3-round w3-small" style="color:red;margin-bottom:6px;" href='?set=towlanBridgeAP&sn=<?=$rpi['sn']?>' >Swich to:<br><strong>Bridged Access Point</strong></a>      
        </div>
        <?php }else{ ?>
        <table class="w3-table-all w3-hoverable w3-tiny">
        <thead>
        <tr class="w3-indigo" id="apconfig"><th colspan="2">AP config:</th><tr>
        </thead>
        <tbody id="apconfig_body">
        <?php foreach( $ld['localdata']['AP'] as $k=>$v){ if( $k=='stations'){ ?>
            <tr><td><?=$k?>:</td><td>
            <?php foreach( $v as $stk=>$stv ){ ?>
            <?=$stv['hostname']?>: <?=$stv['ip']?><br><?=$stv['mac']?>
            <?php } ?>    
            </td></tr>      
        <?php }else{ ?>
            <tr><td><?=$k?>:</td><td><?=$v?></td></tr>    
        <?php } } ?>    
        </tbody>    
        </table>
        <?php
        $items=array(
            'ssid'=>$ld['localdata']['AP']['ssid'],
            'wpa_passphrase'=>$ld['localdata']['AP']['wpa_passphrase'],
            'hw_mode'=>$ld['localdata']['AP']['hw_mode'],
            'channel'=>$ld['localdata']['AP']['channel'],
            'ignore_broadcast_ssid'=>$ld['localdata']['AP']['ignore_broadcast_ssid']
            );
        setparams( "Set AP params", $rpi['sn'], 'apsetparams', $items );
        ?>
          <?php if($ld['localdata']['AP']['bridge']!=1) { ?>
             <?=setparam("Update wlan0 IP","updatewlan0ip",$rpi['sn'],$rpi['wip'],'1.1.1.1/24');?>
          <?php } ?>
        <?php } ?>    
    </article>
    <article  class="w3-card wlan_client_manager">
        <header>
            <h3>WLAN client manager</h3>
        </header>
        <!--<div class="w3-panel w3-left-align">-->
        <?php if( !is_array( $ld['localdata']['AP'])  ) { ?>
        <table class="w3-table-all w3-hoverable w3-tiny">
        <thead class="w3-light-grey">
        <tr class="w3-indigo" id="wlan_nets"><th colspan="3">Aviable wlans:</th></tr>        
        <tr><th>Name</th><th class="w3-center">Chanell</th><th>Level</th></tr>   
        </thead>
        <tbody id="wlan_nets_body"> 
        <?php  
        $wlans=[];
        foreach( $ld['localdata']['scan'] as $k=>$v )  { $wlans[$k] = $v['level']; }
        asort($wlans);
        $wlans=array_reverse($wlans);
        $wno=1;
        foreach(  $wlans as $k=>$v){  
           if( $ld['localdata']['scan'][$k]['level'] < -85 ) break;
           if( $wno > 10 ) {break;} 
           else $wno+=1;
           $onflag = ($ld['localdata']['wlan_id']==$ld['localdata']['scan'][$k]['address'])?True:False;
        ?>
        <tr class="<?=($onflag)?'w3-khaki':''?>"><td class="wlan_name" wlan_name="<?=$k?>">"<?=$k?>"<br><?=$ld['localdata']['scan'][$k]['address']?></td><td class="w3-center"><?=$ld['localdata']['scan'][$k]['channel']?></td><td><?=$ld['localdata']['scan'][$k]['level']?>dB</td></tr>
        <?php $wlan_no+=1; } ?>
        </tbody>
        </table> 
        <!--</div>-->
        <div class="w3-center" >
            <form id="wlan_client" method="get" action="">
            <label>Connect to WLAN</label>   
            <input class="w3-input" name="essid" id="essid" type="text" value="<?=$ld['localdata']['essid'] ?>" placeholder="WLAN name (ESSID)" >
            <input class="w3-input" name="wpa_key" id="wpa_key" type="text" value="" placeholder="wpa-key ( >= 8 char)">
            <input type="hidden" name="set" value="wlan_client" >
            <input type="hidden" name="sn" value="<?=$rpi['sn']?>" >
            <button class="w3-button w3-red w3-round w3-small" style="color:red;margin-top:6px;" type="submit" name="submit" value="hostname" >Submit</button>
            </form>
        </div> 
        <?php }else{ ?>
        <div class="w3-center" >
            <a class="w3-button w3-red w3-round w3-small" style="color:red;margin-bottom:6px;" href='?set=towlanClient&sn=<?=$rpi['sn']?>' >Swich to<br>WLAN Client</a>            
        </div>
        <?php } ?>
    </article>

    <article  class="w3-card" id="bluetooth_manager">
        <header>
            <h3>Bluetooth manager</h3>
        </header>
        <table class="w3-table-all w3-hoverable w3-tiny">
        <thead class="w3-light-grey">
        <tr class="w3-indigo" id="bluetooth"><th colspan="2" >Bluetooth info:</th></tr>        
        <tr><th>name</th><th>value</th></tr>   
        </thead>
        <tbody id="bluetooth_body">
        <?php 
        if( count($ld['localdata']['bluetooth'])>0 ){
            foreach( $ld['localdata']['bluetooth'] as $k=>$v ){ 
        ?>
            <tr><td><?=$k?>:</td><td>
            <?php 
            print_r($v);
            if( is_array($v) ){
                foreach( $v as $k1=>$v2 ) { 
                    echo "1) $k1: $v2<br>"; 
                }
            }else{
                echo $v2;
            } 
            ?> 
            <?=$v?>
            </td></tr>
        <?php }}else{ ?>
            <tr><td conspan="2">No bluetooth data <?=$ld['localdata']['bluetooth']['Controler']?></td></tr>
        <?php } ?>    
        </tbody>
        </table>
    </article>


    <article  class="w3-card" id="debug_info">
        <header>
            <h3>Debag info</h3>
        </header>
        <table class="w3-table-all w3-hoverable w3-tiny">
        <thead class="w3-light-grey">
        <tr class="w3-indigo" id="rpi"><th colspan="2" >rpi:</th></tr>        
        <tr><th>name</th><th>value</th></tr>   
        </thead>
        <tbody id="rpi_body">
        <?php foreach( $rpi as $k=>$v ){ ?>
            <tr><td><?=$k?>:</td><td><?=(is_array($v))?'array':$v?></td></tr>
        <?php } ?>
        </tbody>
        <tr class="w3-indigo"><th colspan="2" id="localdata">localdata:</th></tr>        
        <tr><th>name</th><th>value</th></tr>   
        </thead>
        <tbody id="localdata_body">
        <?php foreach( $ld['localdata'] as $k=>$v ){ ?>
            <tr><td><?=$k?>:</td><td><?=(is_array($v))?'array '.count($v).' items':$v?></td></tr>
        <?php } ?>
        </tbody>
        </table>
    </article>

    </section>    
    <footer>
        <p><a class="w3-button w3-blue w3-round w3-small" href='?get=getall'>Go back to RPi list</a></p>
    </footer>
</div>
<script src="js/rpi_edit_params.js"></script>   