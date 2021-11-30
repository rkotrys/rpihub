<div class='w3-container'>
    <header>
        <h2>List of RPi devices</h2>
    </header>
    <div class='rpi-list'>
    <?php foreach( $rpi_list as $sn=>$rpi ){
       echo view( 'rpi_details', array( 'rpi'=>$rpi ) );
    ?>   
    </div>
</div>  
  