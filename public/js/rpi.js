$(document).ready(function(){

    function update_online(){
        $.getJSON("http://rpi.ontime24.pl/?get=isonline", function(result){
            $.each(result, function( i, rpi ){ 
                  $(".rpi-online").removeClass("rpi-online");
                  $(".rpi-offline").removeClass("rpi-offline");
                  $("[sn="+rpi.sn+"]").addClass("rpi-"+rpi.online);
                });               
        });
    }

    setInterval( update_online, 3000 );
  
}); 