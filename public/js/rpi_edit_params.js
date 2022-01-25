$(document).ready(function(){
   $(".wlan_name").click( function(){
       $("input[name='essid']").val( $(this).attr("wlan_name"));
   });
   $("#wlan_nets").click( function(){
       $("#wlan_nets_body").toggle() ;
   });
   $("#rpi").click( function(){
       $("#rpi_body").toggle() ;
   });
   $("#localdata").click( function(){
      $("#localdata_body").toggle() ;
   });
   $("#apconfig").click( function(){
    $("#apconfig_body").toggle() ;
 });

});
