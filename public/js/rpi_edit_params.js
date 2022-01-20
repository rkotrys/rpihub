$(document).ready(function(){
   $(".wlan_name").click( function(){
       $("input[name='essid']").val( $(this).attr("wlan_name"));
   });
   $("#rpi").click( function(){
       $("#rpi_body").toggle() ;
   });
   $("#localdata").click( function(){
      $("#localdata_body").toggle() ;
   });

});
