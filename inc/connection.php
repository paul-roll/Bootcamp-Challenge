<?php

//Schema: challengetwo
//
//  Table: users
//    user_id int PRIMARY_KEY
//    first varchar(35)
//    last varchar(35)
//    email varchar(254)
//    phone varchar(12) //12 instead of 11 to support country code if changed later
//      Rule: each set of {first, last, email, phone} must be unique
//    
//  Table: messages
//    message_id int PRIMARY_KEY
//    user_id int FOREIGN_KEY(users.user_id)
//    subject varchar(9)   //(9 characters is the longest string in the given drop-down box)
//    message text

try {
  $db = new PDO("mysql:host=localhost;dbname=challengetwo","root");
  $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
  echo "Unable to connect<br>";
  echo $e->getMessage();
  exit;
}
//Connection Successful!

?>