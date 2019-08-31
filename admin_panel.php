<html>
	<title>Activity Submission</title>
	<h3>Admin Panel</h3>
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
	if (isset ($_SESSION['admin'])){
		$admin = ucfirst ($_SESSION['admin']);
?>
	<header>
		Welcome, <b><?php echo $admin; ?></b> | Options : <a href="admin_panel.php">Home</a>, <a href="admin_panel.php?a=changepw">Change Password</a>, <a href="logout.php">Logout</a> 
	</header>
<?php
		if ($a == 'changepw'){
			if (isset ($_GET['continue'])){
				$oldpw = md5 (mysql_real_escape_string ($_POST['oldpw']));
				$newpw1 = md5 (mysql_real_escape_string ($_POST['newpw1']));
				$newpw2 = md5 (mysql_real_escape_string ($_POST['newpw2']));
				$check = mysql_num_rows (mysql_query ("SELECT id FROM admins WHERE username = '$admin' AND password = '$oldpw'"));
				if ($check == 1){
					if ($newpw1 == $newpw2){
						mysql_query ("UPDATE admins SET password = '$newpw1' WHERE username = '$admin'");
						exit (header ('location: admin_panel.php?a=changepw&msg=3'));
					}
					else{
						//Error: New passwords don't match.
						exit (header ('location: admin_panel.php?a=changepw&msg=2'));
					}
				}
				else{
					//Error: Incorrect password.
					exit (header ('location: admin_panel.php?a=changepw&msg=1'));
				}
			}
			else{
?>
	<h3>Change Password</h3>
<?php
		$msg = $_GET['msg'];
		if ($msg == 1){
?>
	<font color="red"><b>Error: Incorrect password.</b></font></br></br>
<?php
		}
		elseif ($msg == 2){
?>
	<font color="red"><b>Error: New passwords don't match.</b></font></br></br>
<?php
		}
		elseif ($msg == 3){
?>
	<font color="green"><b>Your password has been successfully changed.</b></font></br></br>
<?php
		}
?>
	<form method="POST" action="admin_panel.php?a=changepw&continue">
		<table>
			<tr>
				<td>Old Password:</td>
				<td><input type="password" name="oldpw" /></td>
			</tr>
			<tr>
				<td>New Password¹:</td>
				<td><input type="password" name="newpw1" /></td>
			</tr>
			<tr>
				<td>New Password²:</td>
				<td><input type="password" name="newpw2" /></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="Change Password"/></td>
			</tr>
		</table>
	</form>
<?php
			}
		}
		elseif ($a == 'pending_students'){
			if (isset ($_GET['id'])){
				$id = mysql_real_escape_string ($_GET['id']);
				if (isset ($_GET['approve'])){
					$info = mysql_fetch_array (mysql_query ("SELECT * FROM pending_students WHERE id = '$id'"));
					$username = $info['username'];
					$password = $info['password'];
					$student_id = $info['student_id'];
					$first_name = $info['first_name'];
					$last_name = $info['last_name'];
					$class = $info['class'];
					mysql_query ("INSERT INTO pending_students (username, password, student_id, first_name, last_name, class) VALUES ('$username','$password','$student_id','$first_name','$last_name','$class')");
					
				}
				elseif (isset ($_GET['delete'])){
					mysql_query ("DELETE FROM pending_students WHERE id = '$id'");
				}
			}
?>
	<h3>Pending Students</h3>
	<table border="1px" cellpadding="2px" cellspacing="0px" width="40%">
		<tr>
			<td><b>Username</b></td>
			<td><b>Student ID</b></td>
			<td><b>First Name</b></td>
			<td><b>Last Name</b></td>
			<td><b>Class</b></td>
			<td><b>Options</b></td>
		</tr>
<?php
	$q = mysql_query ("SELECT * FROM pending_students");
	if (mysql_num_rows ($q) != 0){
		while ($row = mysql_fetch_array ($q)){
?>
		<tr>
			<td><?php echo $row['username']; ?></td>
			<td><center><?php echo $row['student_id']; ?></center></td>
			<td><?php echo $row['first_name']; ?></td>
			<td><?php echo $row['last_name']; ?></td>
			<td><center><?php echo $row['class']; ?></center></td>
			<td><a href="admin_panel.php?a=pending_students&approve&id=<?php echo $row['id']; ?>">Approve</a>, <a href="admin_panel.php?a=pending_students&delete&id=<?php echo $row['id']; ?>">Delete</a></td>
		</tr>
<?php
		}
	}
	else{
?>
		<tr>
			<td colspan="6">There are currently no pending students.</td>
		</tr>
<?php
	}
?>
	</table>
<?php
		}
		else{
?>
	<h3>Home</h3>
	<ul>
		<li><a href="admin_panel.php?a=pending_students">Pending Students</a></li>
		<li>Manage Classes</li>
	</ul>
<?php
		}
	}
	elseif ($a == 'login'){
		$username = mysql_real_escape_string ($_POST['username']);
		$password = md5(mysql_real_escape_string ($_POST['password']));
		$check = mysql_num_rows (mysql_query ("SELECT * FROM admins WHERE username = '$username' AND password = '$password'"));
		if ($check == 1){
			$_SESSION['admin'] = $username;
			exit (header ('location: admin_panel.php'));
		}
		else{
		//Error: Incorrect username or password.
		exit (header ('location: admin_panel.php?msg=1'));
		}
	}
	else{
		$msg = $_GET['msg'];
		if ($msg == 1){
?>
	<font color="red"><b>Error: Incorrect username or password.</b></font></br></br>
<?php
		}
?>
	<form method="POST" action="admin_panel.php?a=login">
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
				<td colspan="2">Click <a href="index.php">here</a> to return home.</td>
			</tr>
		</table>
	</form>
<?php
	}
?>
</html>