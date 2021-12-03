<?php
   $tdiff = time() - strtotime($rpi['last']) - date_offset_get(new DateTime);
   if( $tdiff < 4 ){ 
       $online=true;  
    } else { 
       $online=false; 
    }
?>
<!--  <?=($online)?'rpi-online':'rpi-offline';?> -->
<div class='w3-card rpi ' sn='<?=$rpi["sn"]?>' >
<div class='rpi-header'><?=$rpi['model']?></div>
<?php foreach( $rpi as $k=>$v) { if( $k=='model') continue; ?>
    <div class='flex-container  rpi-<?=$k?>'>
        <div class='rpikey'><?=$k?> :</div>
        <div class='rpivalue'><?=($k!='cmd')?$v:'***';?></div>
    </div>
<?php  } ?>
    <div class='rpi-status' >
    <?php if($online){ ?>
        ON-Line
    <?php }else { ?>    
        OFF-Line: <?=totimedistance($tdiff)?>
    <?php } ?>    
    </div>     
    <div class='rpi-status' >
    <?php if($online){ ?>
        <a href='?get=edit&sn=<?=$rpi['sn']?>'>configure</a>
    <?php }else{ ?>
        <a href='http://rpi.ontime24.pl/?get=delete&sn=<?=$rpi["sn"]?>' >remove</a>
    <?php } ?>   
    </div>
</div>