$(document).ready(function(){

    function update_online(){
        $.getJSON("http://rpi.ontime24.pl/?get=isonline", function(result){
            $(".rpi-online").removeClass("rpi-online");
            $(".rpi-offline").removeClass("rpi-offline");
            $.each(result, function( i, rpi ){ 
                  $("[sn="+rpi.sn+"]").addClass("rpi-"+rpi.online);
                });               
        });
    }

    setInterval( update_online, 3000 );
  
}); 