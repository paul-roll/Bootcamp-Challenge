<?php

	include("inc/functions.php");

  $pageTitle = "ViewDB";
  include("inc/header.php");

//
?>

<h1>ViewDB();</h1>
<p align="center">SELECT users.user_id AS 'UID', first AS 'First name', last AS 'Last name', email AS 'Email address', phone AS 'Telephone number', message_id AS 'MID', subject AS 'Subject', message AS 'Message'<br>
FROM users JOIN messages ON messages.user_id = users.user_id ORDER BY users.user_id DESC, message_id DESC</p>
<?php echo viewDB(); ?>


<?php	include("inc/footer.php"); ?>