<?php /* Property of Wicee Technologies */ ?>
<?php require_once('../Connections/wistream.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

session_start();
if (!function_exists("GetSQLValueString")) {

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 

{

  if (PHP_VERSION < 6) {

    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  }



  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);



  switch ($theType) {

    case "text":

      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";

      break;    

    case "long":

    case "int":

      $theValue = ($theValue != "") ? intval($theValue) : "NULL";

      break;

    case "double":

      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";

      break;

    case "date":

      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";

      break;

    case "defined":

      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;

      break;

  }

  return $theValue;

}

}

?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['user'])) {
  $loginUsername=$_POST['user'];
  $password=$_POST['pwd'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "cpanel.php";
  $MM_redirectLoginFailed = "?status=failed";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_wistream, $wistream);
  
  $LoginRS__query=sprintf("SELECT uname, pwd FROM accts WHERE uname=%s AND pwd=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $wistream) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Welcome to Wistream CMS</title>

</head>



<body>

<form id="loginfrm" name="loginfrm" method="POST" action="<?php echo $loginFormAction; ?>">

  <table width="98%" align="center" cellpadding="0" cellspacing="0">

    <tr>

      <td align="center" bgcolor="#FFFFFF"><img src="images/wicee.jpg" alt="" width="127" height="109" align="middle" /></td>

    </tr>

  </table>

  <table width="58%" height="232" align="center" cellpadding="0" cellspacing="0">

    <tr>

      <td align="center" bgcolor="#FFC68C"><h2>Welcome to Wistream Cpanel</h2></td>

    </tr>

    <tr>

      <td height="79" align="center">

        <?php if(isset($_GET['status']) && $_GET['status'] == "logout") {  // Show if incorrect login ?>

        <table width="80%" border="0" cellspacing="0" cellpadding="0">

          <tr>

            <td align="center" bgcolor="#66CC99"><font color="#FFFFFF">Logged out Successfully</font></td>

          </tr>

        </table>

        <?php } // Show if incorrect login ?>

<?php if(isset($_GET['status']) && $_GET['status'] == "failed") {  // Show if incorrect login ?><table width="80%" border="0" cellspacing="0" cellpadding="0">

        <tr>

          <td align="center" bgcolor="#FF0000"><font color="#FFFFFF">Wrong Username/Password</font></td>

        </tr>

      </table><?php } // Show if incorrect login ?>

        <br />

        Username:

<label for="user"></label>

        <input type="text" name="user" id="user" /></td>

    </tr>

    <tr>

      <td height="95" align="center">Password: 

        <label for="pwd"></label>

      <input type="password" name="pwd" id="pwd" /></td>

    </tr>

    <tr>

      <td height="95" align="center"><input type="submit" name="btnLogin" id="btnLogin" style="font-size:18px;" value="Log In" /></td>

    </tr>

    <tr>

      <td height="28" align="center" bgcolor="#FFD9B3">powered by <a href="http://www.facebook.com/wicee" target="_blank">Wicee</a></td>

    </tr>

  </table>

</form>

</body>

</html>