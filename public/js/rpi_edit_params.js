$(document).ready(function(){
   $(".wlan_name").click( function(){
       $("input[name='essid']").val( $(this).attr("wlan_name"));
   });
   $("#rpi").click( function(){
       $("#rpi_body").toggleClass('w3_hide') ;
   });
   $("#localdata").click( function(){
      $("#localdata_body").toggleClass('w3_hide') ;
   });

});
