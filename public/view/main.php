<!DOCTYPE HTML>
<html>
<head>
   <meta http-equiv='content-type' content='text/html; charset=utf-8'>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> 
   <script src="js/jquery-3.6.0.min.js"></script>
   <link rel="stylesheet" href="css/rpi.css"> 
   <script src="js/rpi.js"></script>
   <script>
   //    function do_onload(){
   //       let myVar = setTimeout( function () { let timer = window.location.reload(true); }, 5000);
   //    }
   </script>    
</head>
<body <?php if($do_reload) print( 'onload="do_onload()"' ); ?> >
   <header class="w3-container w3-teal">
   <h1>RPI-hub</h1>
   </header>      
<?php if( isset($content) ) print($content); ?>
</body>
</html>