<?php 
if(session_id() == '') { session_start(); } // START SESSIONS 
unset($_SESSION['Dspr']);
header('location: ../myaccount/');
?> 