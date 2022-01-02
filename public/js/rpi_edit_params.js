$(document).ready(function(){
   $(".wlan_name").click( function(){
       $("input[name='essid']").val( $(this).attr("wlan_name"));
   });

});
