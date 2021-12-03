$(document).ready(function(){

    function update_online(){
        $.getJSON("http://rpi.ontime24.pl/?get=isonline", function(result){
            $.each(result, function( i, rpi ){ 
                   $("[sn="+rpi.sn+"]").addClass(rpi.online);
                });               
        });
    }

    setInterval( update_online, 3000 );
  
}); 