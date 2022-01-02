$(document).ready(function(){
   $(".wlan_name").click( function(){
       alert($(this).attr("wlan_name"));
      $(".input[name='essid']").val( $(this).attr("wlan_name"));
   });

});
