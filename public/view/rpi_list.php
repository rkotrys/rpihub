<div class='w3-container'>
    <header class="w3-display-container">
        <h2 class="w3-left">List of RPi devices</h2>
        <div class="w3-right w3-card" ><div style="padding:5px;margin-top: 5px;"><input type="checkbox" id="hide_offline" name="hide_offline" value="hide_offline"> show all</div></div>
    </header>
    <div class='rpi-list'>
    <?php 
       foreach( $rpi_list as $sn=>$rpi ){
          echo view( 'rpi_details', array( 'rpi'=>$rpi ) );
       }       
    ?>   
    </div>
</div>  
