<?php


//--------------
//Core Functions
//--------------

//all SQL queries pass through this function (for now)
function sql($sql) {
	include("connection.php");
    try {
      $results = $db->prepare($sql);
      $results->execute();
    } catch (Exception $e) {
      echo "bad query<br>";
      echo $e->getMessage();
      exit;
    }
  //detect if the query was an INSERT or SELECT for appropriate return value
	if (substr(ltrim($sql), 0, 6) == "SELECT") {
		return $results->fetchAll(PDO::FETCH_ASSOC);
	} else if (substr(ltrim($sql), 0, 6) == "INSERT") {
		return $db->lastInsertId();
	}
}

//get the users.user_id for a given set of inputs
function get_user_id($first, $last, $email, $phone) {
	$return = sql(" SELECT user_id
                  FROM users
                  WHERE first = '$first' AND last = '$last' AND email = '$email' AND phone = '$phone'");
	if (!empty($return)) {
		return $return[0]['user_id'];
	}
}

//insert the data from the contact form into the database
function insert($first, $last, $email, $phone, $subject, $message) {
  //to prevent duplicate rows in the users table (forbidden by DB rules) this will fetch the user_id for a given set of inputs
	$user_id = get_user_id($first, $last, $email, $phone);
  //not found on users table
	if (empty($user_id)) {
    //INSERT new row into the users table and get the new user_id back
		$user_id = sql("INSERT INTO users (first, last, email, phone)
                    VALUES ('$first', '$last', '$email', '$phone')");
  }
  //INSERT new row into the messages table using the user_id that was either retrieved or generated
	sql(" INSERT INTO messages (user_id, subject, message)
        VALUES ('$user_id', '$subject', '$message')");
}


//----------------
//ViewDB Functions
//----------------

//function to pull all the users and messages data from the database and turn it into a table - for use on the test ViewDB.php page
function viewDB() {
	$table = sql("SELECT users.user_id AS 'UID', first AS 'First name', last AS 'Last name', email AS 'Email address', phone AS 'Telephone number', message_id AS 'MID', subject AS 'Subject', message AS 'Message' 
                FROM users 
                JOIN messages ON messages.user_id = users.user_id 
                ORDER BY users.user_id DESC, message_id DESC");
	if (!empty($table)) {
		return array_to_table($table);
	}
}

//function to build a simple table out of a sql SELECT query
function array_to_table($array) {
	$table = "<table align=\"center\" border=1>";
	foreach($array[0] as $key=> $val){
		$table .= "<th>$key</th>";
	}
	foreach($array as $row) {
		$table .= "<tr>";
		foreach($row as $value) {
			$table .= "<td>$value</td>";
		}
		$table .= "</tr>";
	}
	$table .= "</table></div>";
	return $table;
}


//------------------------
//Unused/Testing Functions
//------------------------

//Test query to delete all data from the messages and users tables (in this order as rows in the messages table are linked to rows on the users table)
function delete_all_data() {
	sql("DELETE FROM messages");
	sql("DELETE FROM users");
}

?>