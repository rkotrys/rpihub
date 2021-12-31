<?php
        $ld =  json_decode( base64_decode( $rpi['theme'] ), true );
        $rpi['theme']=($ld['display']=='oled13')?'mono':$ld['localdata']['theme'];
        $service = $ld['display'];
?>
<div class='w3-container'>
    <header>
        <h2>Set parameters of <?=$rpi['model']?> SN: <?=$rpi['sn']?></h2>
        <h4>HOST:  <strong><?=$rpi['hostname']?></strong>, PUUID: <strong><?=$rpi['puuid']?></strong>, msdid: <strong><?=$ld['localdata']['msdid']?></strong>, display: <strong><?=$ld['display']?></strong></h4>
    </header>
    <section  class="rpi-list" id="rpi-details" >
    <?php if( $rpi['theme']!='mono' ) { ?>    
    <article class="w3-card">
        <header>
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
        <header>
            <h3>Set new hostname</h3>
        </header>
        <form method="get" action="" >
            <input class="w3-input" name="hostname" id="hostname" type="text" value="<?=$rpi['hostname']?>">
            <input type="hidden" name="set" value="hostname" >
            <input type="hidden" name="sn" value="<?=$rpi['sn']?>" >
            <button  class="w3-button w3-red w3-round w3-small" style="color:red;margin-top:6px;" type="submit" name="submit" value="hostname" >Submit</button>
        </form>
            </article>
    <article  class="w3-card">
        <header>
            <h3>State manager</h3>
        </header>
        <div class="w3-center" >
            <a class="w3-button w3-red w3-round w3-small" style="color:red;margin-bottom:6px;" href='?set=poweroff&sn=<?=$rpi['sn']?>' >Power OFF</a>
            <a class="w3-button w3-red w3-round w3-small" style="color:red;margin-bottom:6px;" href='?set=reboot&sn=<?=$rpi['sn']?>' >Reboot</a>
            </div>
    </article>
    <article  class="w3-card">
        <header>
            <h3>Update manager</h3>
        </header>
        <div class="w3-center" >
            <a class="w3-button w3-green w3-round w3-small" style="color:red;margin-bottom:6px;" href='?set=update&service=<?=$service?>&sn=<?=$rpi['sn']?>' >Update service: <?=$service?></a>
        </div>    
    </article>
    <article  class="w3-card">
        <header>
            <h3>WLAN client manager</h3>
        </header>
        <div class="w3-panel w3-left-align">
        <table class="w3-table-all w3-hoverable w3-tiny">
        <thead class="w3-light-grey">
        <tr><th colspan="3">Aviable wlans:</th></tr>        
        <tr><th>Name</th><th class="w3-center">Chanell</th><th>Level</th></tr>   
        </thead>
        <?php  
        $wlans=[];
        foreach( $ld['localdata']['scan'] as $k=>$v )  { $wlans[$k] = $v['level']; }
        asort($wlans);
        $wlans=array_reverse($wlans);
        foreach(  $wlans as $k=>$v){  ?>
        <tr><td style="font-weight:bold;">"<?=$k?>"</td><td class="w3-center"><?=$ld['localdata']['scan'][$k]['channel']?></td><td><?=$ld['localdata']['scan'][$k]['level']?>dB</td></tr>
        <?php } ?>
        </table>        
        </div>
        <div class="w3-center" >
            <form id="wlan_client" method="get" action="">
            <input class="w3-input" name="essid" id="essid" type="text" value="<?=$ld['localdata']['essid'] ?>" placeholder="WLAN name (ESSID)" >
            <input class="w3-input" name="wpa_key" id="wpa_key" type="text" value="" placeholder="wpa-key ( >= 8 char)">
            <input type="hidden" name="set" value="wlan_client" >
            <input type="hidden" name="sn" value="<?=$rpi['sn']?>" >
            <button class="w3-button w3-red w3-round w3-small" style="color:red;margin-top:6px;" type="submit" name="submit" value="hostname" >Submit</button>
            </form>
        </div>    
    </article>
    </section>    
    <footer>
        <p><a class="w3-button w3-blue w3-round w3-small" href='?get=getall'>Go back to RPi list</a></p>
    </footer>
</div>