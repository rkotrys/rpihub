<?php
   $tdiff = time() - strtotime($rpi['last']) - date_offset_get(new DateTime);
   if( $tdiff < 4 ){ 
       $online=true;  
    } else { 
       $online=false; 
    }
?>
<div class='w3-card rpi ' sn='<?=$rpi["sn"]?>' >

<div class='rpi-header'><?=$rpi['model']?></div>
<div class='flex-container  rpi-sn'>
        <div class='rpikey'>sn :</div>
        <div class='rpivalue'><?=$rpi['sn']?></div>
</div>
<div class='flex-container  rpi-hostname'>
        <div class='rpikey'>hostname :</div>
        <div class='rpivalue'><?=$rpi['hostname']?></div>
</div>
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
    <div class='rpi-status' >
    <?php if($online){ ?>
        <a href='?get=edit&sn=<?=$rpi['sn']?>'>configure</a>
    <?php }else{ ?>
        <a href='http://rpi.ontime24.pl/?get=delete&sn=<?=$rpi["sn"]?>' >remove</a>
    <?php } ?>   
    </div>
</div>