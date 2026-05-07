<?php
   $conn = new mysqli("localhost", "root", "", "realestate");
   if ($conn->connect_error) {
    die("connection failed:" . $conn->connect_error);
   }
?> 