$(document).ready(function(){
    function update_online(){
        $.getJSON("http://rpi.ontime24.pl/?get=isonline", function(result){
            $(".rpi-online").removeClass("rpi-online");
            $(".rpi-offline").removeClass("rpi-offline");
            let show_offline = ( $("#hide_offline").prop("checked") )?true:false;
            $.each(result, function( i, rpi ){ 
                  $("[sn="+rpi.sn+"]").addClass("rpi-"+rpi.online);
                  if( show_offline | rpi.online=='online' )  
                    $(".rpi[sn="+rpi.sn+"]").show();
                  else 
                    $(".rpi[sn="+rpi.sn+"]").hide();
                  /*
                    if( rpi.online=='online' ){
                    $("#conf_"+rpi.sn).show();
                    $("#remove_"+rpi.sn).hide();
                  }else{
                    $("#conf_"+rpi.sn).hide();
                    $("#remove_"+rpi.sn).show();
                  }
                  */
                  $("[sn="+rpi.sn+"] .rpi-last .rpivalue").text(rpi.last);
                  $("[sn="+rpi.sn+"] .rpi-hostname .rpivalue").text(rpi.hostname);
                  $("[sn="+rpi.sn+"] .rpi-ip .rpivalue").text(rpi.ip);
                  $("[sn="+rpi.sn+"] .rpi-wip .rpivalue").text(rpi.wip);
                });               
        });
    }
    
    //$(".rpi").hide();
    //$(".details_status").hide()
    
    setInterval( update_online, 1000 );
    //update_online();
}); 