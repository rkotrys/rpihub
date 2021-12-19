<?php
        $ld =  json_decode( base64_decode( $rpi['theme'] ), true );
        $rpi['theme']=($ld['display']=='oled13')?'mono':$dl['localdata']['theme'];
?>
<div class='w3-container'>
    <header>
        <h2>Set parameters of RPi SN: <?=$rpi['sn']?></h2>
        <h4>HOST:  <?=$rpi['hostname']?>, PUUID: <?=$rpi['puuid']?>, Model: <?=$rpi['model']?>, display: <?=$ld['display']?></h4>
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
            <input name="hostname" id="hostname" type="text" value="<?=$rpi['hostname']?>">
            <input type="hidden" name="set" value="hostname" >
            <input type="hidden" name="sn" value="<?=$rpi['sn']?>" >
            <button type="submit" name="submit" value="hostname" >Submit</button>
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
            <?php foreach( $services as $service ){ ?>
            <a class="w3-button w3-green w3-round w3-small" style="color:red;margin-bottom:6px;" href='?set=update&service=<?=$service?>&sn=<?=$rpi['sn']?>' >Update service: <?=$service?></a>
            <?php } ?>
        </div>    
    </article>
    </section>    
    <footer>
        <p><a class="w3-button w3-blue w3-round w3-small" href='?get=getall'>Go back to RPi list</a></p>
    </footer>
</div>