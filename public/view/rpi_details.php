<?php
   $tdiff = time() - strtotime($rpi['last']) - date_offset_get(new DateTime);
   if( $tdiff < 4 ){ 
       $online=true;  
    } else { 
       $online=false; 
    }
    $ld =  json_decode( base64_decode( $rpi['theme'] ), true );
    $rpi['theme']=($ld['display']=='oled13')?'mono':$ld['localdata']['theme'];
?>
<div class='w3-card rpi ' sn='<?=$rpi["sn"]?>' >
<div class='rpi-header'><?=$rpi['model']?></div>
<div class='flex-container  rpi-sn'>
        <div class='rpikey'>sn :</div>
        <div class='rpivalue'><?=$rpi['sn']?></div>
</div>
<div class='flex-container rpi-display'>
        <div class='rpikey'>display :</div>
        <div class='rpivalue'><?=$ld['display']?></div>
</div>
<div class='flex-container rpi-coretemp'>
        <div class='rpikey'>core temp: :</div>
        <div class='rpivalue'><?=$ld['localdata']['coretemp']?></div>
</div>
<div class='flex-container  rpi-hostname'>
        <div class='rpikey'>hostname :</div>
        <div class='rpivalue'><?=$rpi['hostname']?></div>
</div>
<?php if( isset($ld['essid'] ) ){  ?>
<div class='flex-container  rpi-essid'>
        <div class='rpikey'>ESSID :</div>
        <div class='rpivalue'><?=$ld['essid']?></div>
</div>
<?php } ?>
<div class='flex-container  rpi-ip'>
        <div class='rpikey'>eth0 ip :</div>
        <div class='rpivalue'><?=$rpi['ip']?></div>
</div>
<div class='flex-container  rpi-wip'>
        <div class='rpikey'>wlan0 ip :</div>
        <div class='rpivalue'><?=$rpi['wip']?></div>
</div>
<?php foreach( $rpi as $k=>$v) { if( $k=='model' or $k=='sn'  or $k=='hostname' or $k=='ip' or $k=='wip' or $k=='cmd' ) continue; ?>
    <div class='flex-container  rpi-<?=$k?>'>
        <div class='rpikey'><?=$k?> :</div>
        <div class='rpivalue'><?=$v?></div>
    </div>
<?php  } ?>
    <div class='flex-container rpi-status' >
        <a status="configure" class="details_status w3-button w3-blue w3-round w3-small" href='?get=edit&sn=<?=$rpi['sn']?>'>configure</a>
        <a status="remove" class="details_status w3-btn w3-red w3-round w3-small" href='http://rpi.ontime24.pl/?get=delete&sn=<?=$rpi["sn"]?>' >remove</a>
    </div>
</div>