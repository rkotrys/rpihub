$(document).ready(function(){

    function update_online(){
        $.getJSON("http://rpi.ontime24.pl/?get=isonline", function(result){
            $(".rpi-online").removeClass("rpi-online");
            $(".rpi-offline").removeClass("rpi-offline");
            let show_offline = ( $("#hide_offline").prop("checked") )?true:false;
            $.each(result, function( i, rpi ){ 
                  $("[sn="+rpi.sn+"]").addClass("rpi-"+rpi.online);
                  if( show_offline )  
                    $("[sn="+rpi.sn+"]").show();
                  else 
                    $(".rpi-offline[sn="+rpi.sn+"]").hide();
                  $("[sn="+rpi.sn+"] .rpi-last .rpivalue").text(rpi.last);
                  $("[sn="+rpi.sn+"] .rpi-hostname .rpivalue").text(rpi.hostname);
                  $("[sn="+rpi.sn+"] .rpi-ip .rpivalue").text(rpi.ip);
                  $("[sn="+rpi.sn+"] .rpi-wip .rpivalue").text(rpi.wip);
                });               
        });
    }

    setInterval( update_online, 1000 );
  
}); 