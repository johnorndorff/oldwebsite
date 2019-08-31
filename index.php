<html>
	<title>Activity Submission</title>
	<h3>Activity Submission</h3>
	<style type="text/css">
		body
		{
			padding-left: 10px;
		}
		header
		{
			padding-left: 15px;
		}
	</style>
<?php
	require ('config.php');
	session_start ();
	$a = $_GET['a'];
	if (isset ($_SESSION['logged_in'])){
	
	}
	elseif ($a == 'login'){
	
	}
	elseif ($a == 'register'){
		if (isset ($_GET['continue'])){
			$username = mysql_real_escape_string ($_POST['username']);
			$password1 = mysql_real_escape_string ($_POST['password1']);
			$password2 = mysql_real_escape_string ($_POST['password2']);
			$student_id = mysql_real_escape_string ($_POST['student_id']);
			$class = mysql_real_escape_string ($_POST['class']);
			$first_name = mysql_real_escape_string ($_POST['first_name']);
			$last_name = mysql_real_escape_string ($_POST['last_name']);
			if (($username != '') and ($password1 != '') and ($password2 != '') and ($student_id != '') and ($class != '') and ($first_name != '') and ($last_name != '')){
				//Check passwords.
				if ($password1 == $password2){
					//Check if username or student ID exists.
					$check1 = mysql_num_rows (mysql_query ("SELECT id FROM students WHERE username = '$username' OR student_id = '$student_id'"));
					$check2 = mysql_num_rows (mysql_query ("SELECT id FROM pending_students WHERE username = '$username' OR student_id = '$student_id'"));
					if (($check1 == 0) and ($check2 == 0)){
						//Add registration to 'pending_students' table.
						$password = md5 ($password1);
						mysql_query ("INSERT INTO pending_students (username, password, student_id, first_name, last_name, class) VALUES ('$username','$password','$student_id','$first_name','$last_name','$class')");
						exit (header ('location: index.php?a=register&msg=4'));
					}
					else{
						//Error: Username or Student ID already in use.
						exit (header ('location: index.php?a=register&msg=3'));
					}
				}
				else{
					//Error: Passwords don't match.
					exit (header ('location: index.php?a=register&msg=2'));
				}
			}
			else{
				//Error: All fields must be filled in.
				exit (header ('location: index.php?a=register&msg=1'));
			}
		}
		else{
			$msg = $_GET['msg'];
			if ($msg == 1){
?>
	<font color="red"><b>Error: All fields must be filled in.</b></font></br></br>
<?php
			}
			elseif ($msg == 2){
?>
	<font color="red"><b>Error: Passwords don't match.</b></font></br></br>
<?php
			}
			elseif ($msg == 3){
?>
	<font color="red"><b>Error: Username or Student ID already in use.</b></font></br></br>
<?php
			}
			elseif ($msg == 4){
?>
	<font color="green"><b>Notice: Your registration is now pending, you'll be able to login once it's approved.</b></font></br></br>
<?php
			}
?>
	<form method="POST" action="index.php?a=register&continue">
		<table>
			<tr>
				<td>Username:</td>
				<td><input name="username"/></td>
			</tr>
			<tr>
				<td>Password¹:</td>
				<td><input type="password" name="password1"/></td>
			</tr>
			<tr>
				<td>Password²:</td>
				<td><input type="password" name="password2"/></td>
			</tr>
			<tr>
				<td>Student ID:</td>
				<td><input name="student_id"/></td>
				<td>
					<select name="class">
						<option value="A1">A1</option>
						<option value="A2">A2</option>
						<option value="A3">A3</option>
						<option value="B1">B1</option>
						<option value="B2">B2</option>
						<option value="B3">B3</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>First Name:</td>
				<td><input name="first_name"/></td>
			</tr>
			<tr>
				<td>Last Name:</td>
				<td><input name="last_name"/></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="Register"/></td>
			</tr>
		</table>
	</form>
<?php
		}
	}
	else{
?>
	<form method="POST" action="index.php?a=login">
		<table>
			<tr>
				<td>Username:</td>
				<td><input name="username"/></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><input type="password" name="password"/></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="Continue"/></td>
			</tr>
			<tr>
				<td colspan="2">Don't have an account? Click <a href="index.php?a=register">here</a>.</td>
			</tr>
			<tr>
				<td colspan="2">To access the admin panel, click <a href="admin_panel.php">here</a>.</td>
			</tr>
		</table>
	</form>
<?php
	}
?>
</html>