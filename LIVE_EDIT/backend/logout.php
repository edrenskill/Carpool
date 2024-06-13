<?php 
if(session_id() == '') { session_start(); } // START SESSIONS 
unset($_SESSION['ADM']);
header('location: ../myaccount/');
?> 