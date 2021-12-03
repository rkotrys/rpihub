$(document).ready(function(){

    function update_online(){
        $.getJSON("http://rpi.ontime24.pl/?get=isonline", function(result){
            $(".rpi-online").removeClass("rpi-online");
            $(".rpi-offline").removeClass("rpi-offline");
            $.each(result, function( i, rpi ){ 
                  $("[sn="+rpi.sn+"]").addClass("rpi-"+rpi.online);
                  $("[sn="+rpi.sn+"] .rpi-last .rpi-value").text(rpi.last);
                });               
        });
    }

    setInterval( update_online, 10000 );
  
}); 