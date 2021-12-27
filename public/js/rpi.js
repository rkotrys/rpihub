$(document).ready(function(){
    function update_online(){
        $.getJSON("http://rpi.ontime24.pl/?get=isonline", function(result){
            $(".rpi-online").removeClass("rpi-online");
            $(".rpi-offline").removeClass("rpi-offline");
            let show_offline = ( $("#hide_offline").prop("checked") )?true:false;
            $.each(result, function( i, rpi ){ 
                  $("[sn="+rpi.sn+"]").addClass("rpi-"+rpi.online);

                  if( rpi.online=='online'){
                    $(".rpi[sn='"+rpi.sn+"'] a[status='configure']" ).show();
                    $(".rpi[sn='"+rpi.sn+"'] a[status='remove']" ).hide();
                  }else{
                    $(".rpi[sn='"+rpi.sn+"'] a[status='configure']" ).hide();
                    $(".rpi[sn='"+rpi.sn+"'] a[status='remove']" ).show();
                  }

                  if( show_offline | rpi.online=='online' )  
                    $(".rpi[sn="+rpi.sn+"]").show();
                  else 
                    $(".rpi[sn="+rpi.sn+"]").hide();

                  if( rpi.online=='online'){
                    $("[sn="+rpi.sn+"] .rpi-last .rpivalue").text(rpi.last);
                    $("[sn="+rpi.sn+"] .rpi-hostname .rpivalue").text(rpi.hostname);
                    $("[sn="+rpi.sn+"] .rpi-ip .rpivalue").text(rpi.ip);
                    $("[sn="+rpi.sn+"] .rpi-wip .rpivalue").text(rpi.wip);
                    if( rpi.ld!=null ){
                      let t = rpi.ld.coretemp.valueOf();
                      $("[sn="+rpi.sn+"] .rpi-coretemp .rpivalue").text( t.toPrecision(3) );
                      if( t > 50.0 ){
                        $("[sn="+rpi.sn+"] .rpi-coretemp").css("background-color", "red");
                      }
                    }
                  }
                });               
        });
    }
    
    //$(".rpi").hide();
    //$(".details_status").hide()
    
    setInterval( update_online, 1000 );
    //update_online();
}); 