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
            <li><a href='?get=theme&face=<?=$face?>&sn=<?=$rpi['sn']?>' ><?=$face?></a></li>
        <?php } ?>    
        </ol>
    </article>
    <article>
        <header>
            <h3>Set new hostname</h3>
        </header>
        <form method="get" action="/?set=hostname&sn=<?=$rpi['sn']?>">
            <input name="hostname" id="hostname" type="text" >
            <button type="submit" name="submit" value="submit" >
        </form>
    <article>
    <footer>
        <p><a href='?get=getall'>Go back to RPi list</a></p>
    </footer>
</div>