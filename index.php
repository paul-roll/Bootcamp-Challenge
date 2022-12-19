<?php


include("inc/functions.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
  //Sanitize, Trim, Lowercase, Capital first
  $first = ucfirst(strtolower(trim(filter_input(INPUT_POST,"first",FILTER_SANITIZE_STRING))));
  if (empty($first)) {
    $error["first"] = "*required*";
  //Must use only letters and hyphens
  } else if (!preg_match("/^[a-zA-Z-]*$/", $first)) {
    $error["first"] = "*invalid characters*";
  //Must be at most 35 characters (DB limit)
  } else if (strlen($first) > 35) {
    $error["first"] = "*too long*";
  //Must be at least 2 characters
  } else if (strlen($first) < 2) {
    $error["first"] = "*too short*";
  //Must start and end with a letter, Must not contain two hyphens in a row
  } else if (!preg_match("/^(?!.*--)[a-zA-Z]{1}[a-zA-Z-]*[a-zA-Z]{1}$/", $first)) {
    $error["first"] = "*invalid*";
  }

  //Sanitize, Trim, Lowercase, Capital first
  $last = ucfirst(strtolower(trim(filter_input(INPUT_POST,"last",FILTER_SANITIZE_STRING))));
  if (empty($last)) {
    $error["last"] = "*required*";
  //Only letters and hyphens
  } else if (!preg_match("/^[a-zA-Z-]*$/", $last)) {
    $error["last"] = "*invalid characters*";
  //At most 35 characters (DB limit)
  } else if (strlen($last) > 35) {
    $error["last"] = "*too long*";
  //At least 2 characters
  } else if (strlen($last) < 2) {
    $error["last"] = "*too short*";
  //Start and end with a letter, Not contain two hyphens in a row
  } else if (!preg_match("/^(?!.*--)[a-zA-Z]{1}[a-zA-Z-]*[a-zA-Z]{1}$/", $last)) {
    $error["last"] = "*invalid*";
  }

  //Sanitize, Trim, Lowercase
  $email = strtolower(trim(filter_input(INPUT_POST,"email",FILTER_SANITIZE_EMAIL)));
  if (empty($email)) {
    $error["email"] = "*required*";
  //Keeping "a-zA-Z" instead of "a-z" in-case strtolower() is removed
  //Only alphanumeric, hyphen, period and AT characters
  } else if (!preg_match("/^[a-zA-Z0-9-.@]*$/", $email)) {
    $error["email"] = "*invalid characters*";
  //At most 254 characters (DB limit)
  } else if (strlen($email) > 254) {
    $error["email"] = "*invalid*";
  //Far from perfect, catches the general format of emails but does not prevent weird input such as "-@..ab"
  } else if (!preg_match("/^[a-zA-Z0-9-.]+@[a-zA-Z0-9-.]+\.[a-zA-Z]{2,4}$/", $email)) {
  //} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {  //Instructed to use REGEX instead
    $error["email"] = "*invalid*";
  }

  //Sanitize, Trim, Remove spaces/hyphens
  $phone = str_replace([" ", "-"], "", trim(filter_input(INPUT_POST,"phone",FILTER_SANITIZE_STRING)));
  if (empty($phone)) {
    $error["phone"] = "*required*";
  //Only numbers
  } else if (!preg_match("/^[0-9]*$/", $phone)) {
    $error["phone"] = "*invalid characters*";
  //At most 11 characters (change to 12 to support country code) (12 = DB limit)
  } else if (strlen($phone) > 11) {
    $error["phone"] = "*too long*";
  //At least 11 characters
  } else if (strlen($phone) < 11) {
    $error["phone"] = "*too short*";
  }

  //Sanitize, Trim
  $subject = trim(filter_input(INPUT_POST,"subject",FILTER_SANITIZE_STRING));
  if (empty($subject)) {
    $error["subject"] = "*required*";
  //not sure if its possible for users to bypass the dropdown list, but check it for validity anyway
  //Only letters and spaces, At most 9 characters (DB limit)
  } else if (!preg_match("/^[a-zA-Z\s]{1,9}$/", $subject)) {
    $error["subject"] = "*invalid*";
  }

  //Sanitize, Trim
  $message = trim(filter_input(INPUT_POST,"message",FILTER_SANITIZE_SPECIAL_CHARS));
  if (empty($message)) {
    $error["message"] = "*required*";
  }
  
  //If the $error array is empty then there were no problems with inputs
  if (empty($error)) {
    //set success message, push data to the database, clear all values
    $error["result"] = "Your message has been successfully submitted";
    insert($first, $last, $email, $phone, $subject, $message);
    $first = "";
    $last = "";
    $email = "";
    $phone = "";
    $subject = "";
    $message = "";
  } else {
    //set error message
    $error["result"] = "Invalid fields have been highlighted";
  }

//Before POST has been made; initialize empty variables
} else {
  $first = "";
  $last = "";
  $email = "";
  $phone = "";
  $subject = "";
  $message = "";
}

$pageTitle = "Contact";
include("inc/header.php");


?>

<h1>Contact</h1>
<form action="" method="post">
  <table>
    <?php if(empty($error["first"])) {
      echo "<tr>";
      echo    "<th><label for=\"first\">First name:</label></th>";
      echo    "<td><input type=\"text\" name=\"first\" id=\"first\" value=\"$first\"></td>";
    } else {
      echo "<tr class=\"error\">";
      echo    "<th><label for=\"first\">First name:<div class=\"error\">{$error["first"]}</div></label></th>";
      echo    "<td><input type=\"text\" name=\"first\" id=\"first\" value=\"$first\" class=\"error\"></td>";
    } echo "</tr>";?>
    
    <?php if(empty($error["last"])) {
      echo "<tr>";
      echo    "<th><label for=\"last\">Last name:</label></th>";
      echo    "<td><input type=\"text\" name=\"last\" id=\"last\" value=\"$last\"></td>";
    } else {
      echo "<tr class=\"error\">";
      echo    "<th><label for=\"last\">Last name:<div class=\"error\">{$error["last"]}</div></label></th>";
      echo    "<td><input type=\"text\" name=\"last\" id=\"last\" value=\"$last\" class=\"error\"></td>";
    } echo "</tr>";?>
    
    <?php if(empty($error["email"])) {
      echo "<tr>";
      echo    "<th><label for=\"email\">Email address:</label></th>";
      echo    "<td><input type=\"text\" name=\"email\" id=\"email\" value=\"$email\"></td>";
    } else {
      echo "<tr class=\"error\">";
      echo    "<th><label for=\"email\">Email address:<div class=\"error\">{$error["email"]}</div></label></th>";
      echo    "<td><input type=\"text\" name=\"email\" id=\"email\" value=\"$email\" class=\"error\"></td>";
    } echo "</tr>";?>

    <?php if(empty($error["phone"])) {
      echo "<tr>";
      echo    "<th><label for=\"phone\">Telephone number:</label></th>";
      echo    "<td><input type=\"text\" name=\"phone\" id=\"phone\" value=\"$phone\"></td>";
    } else {
      echo "<tr class=\"error\">";
      echo    "<th><label for=\"phone\">Telephone number:<div class=\"error\">{$error["phone"]}</div></label></th>";
      echo    "<td><input type=\"text\" name=\"phone\" id=\"phone\" value=\"$phone\" class=\"error\"></td>";
    } echo "</tr>";?>
    
    <?php if(empty($error["subject"])) {
      echo "<tr>";
      echo    "<th><label for=\"subject\">Subject:</label></th>";
      echo    "<td><select name=\"subject\" id=\"subject\">";
    } else {
      echo "<tr class=\"error\">";
      echo    "<th><label for=\"subject\">Subject:<div class=\"error\">{$error["subject"]}</div></label></th>";
      echo    "<td><select name=\"subject\" id=\"subject\" class=\"error\">";
    } 
    echo        "<option value=\"\">--- Select One ---</option>";
    echo        "<option value=\"Enquiry\"";    if ($subject == "Enquiry")    {echo " selected";} echo ">Enquiry</option>";
    echo        "<option value=\"Call Back\"";  if ($subject == "Call Back")  {echo " selected";} echo ">Call Back</option>";
    echo        "<option value=\"Complaint\"";  if ($subject == "Complaint")  {echo " selected";} echo ">Complaint</option>";
    echo      "</select></td>";
    echo   "</tr>";?>
    
    <?php if(empty($error["message"])) {
      echo "<tr>";
      echo    "<th><label for=\"message\">Message:</label></th>";
      echo    "<td><textarea rows=5 cols=40 name=\"message\" id=\"message\">$message</textarea></td>";
    } else {
      echo "<tr class=\"error\">";
      echo    "<th><label for=\"message\">Message:<div class=\"error\">{$error["message"]}</div></label></th>";
      echo    "<td><textarea rows=5 cols=40 name=\"message\" id=\"message\" class=\"error\">$message</textarea></td>";
    } echo "</tr>";?>
    
  </table>

  <?php if (!empty($error["result"])) {
    echo "<p><div class=\"result\">{$error["result"]}</div></p>";
  }?>

  <input type="submit" value="Submit">
</form>

<?php include("inc/footer.php"); ?>