<div class='w3-container'>
    <header>
        <h2>Set parameters of RPi SN: <?=$rpi['sn']?></h2>
    </header>
    <article>
        <header>
            <h3>Color of the clock face</h3>
        </header>
        <ol>
        <?php foreach( $faces as $face ) { ?>    
            <?php if( $face==$rpi['theme'] ){ ?>
                <li><span style="background-color: <?=$face?>;color:<?=$face?>;border:2px solid black;"> ***** </span> &nbsp;&nbsp;&nbsp;<?=$face?></li>
            <?php } else { ?>
                <li><span style="background-color: <?=$face?>;color:<?=$face?>;box-shadow:2px 2px 5px #222;"> ***** </span>  &nbsp;&nbsp;&nbsp;<a style="text-decoration:none;" href='?get=theme&face=<?=$face?>&sn=<?=$rpi['sn']?>' ><?=$face?></a> </li>
            <?php } ?>    
        <?php } ?>    
        </ol>
    </article>
    <article>
        <header>
            <h3>Set new hostname</h3>
        </header>
        <form method="get" action="/?set=hostname&sn=<?=$rpi['sn']?>">
            <input name="hostname" id="hostname" type="text" value="<?=$rpi['hostname']?>">
            <button type="submit" name="submit" value="hostname" >Submit</button>
        </form>
    <article>
    <footer>
        <p><a href='?get=getall'>Go back to RPi list</a></p>
    </footer>
</div>