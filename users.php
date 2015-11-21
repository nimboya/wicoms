<?php require_once('../Connections/wistream.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {

  session_start();

}
// ** Logout the current user. **

$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";

if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){

  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);

}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){

  //to fully log out a visitor we need to clear the session varialbles

  $_SESSION['MM_Username'] = NULL;

  $_SESSION['MM_UserGroup'] = NULL;

  $_SESSION['PrevUrl'] = NULL;

  unset($_SESSION['MM_Username']);

  unset($_SESSION['MM_UserGroup']);

  unset($_SESSION['PrevUrl']);

	
  $logoutGoTo = "index.php?status=logout";

  if ($logoutGoTo) {

    header("Location: $logoutGoTo");

    exit;

  }

}

?>

<?php

if (!isset($_SESSION)) {

  session_start();

}

$MM_authorizedUsers = "";

$MM_donotCheckaccess = "true";



// *** Restrict Access To Page: Grant or deny access to this page

function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 

  // For security, start by assuming the visitor is NOT authorized. 

  $isValid = False; 



  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 

  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 

  if (!empty($UserName)) { 

    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 

    // Parse the strings into arrays. 

    $arrUsers = Explode(",", $strUsers); 

    $arrGroups = Explode(",", $strGroups); 

    if (in_array($UserName, $arrUsers)) { 

      $isValid = true; 

    } 

    // Or, you may restrict access to only certain users based on their username. 

    if (in_array($UserGroup, $arrGroups)) { 

      $isValid = true; 

    } 

    if (($strUsers == "") && true) { 

      $isValid = true; 

    } 

  } 

  return $isValid; 

}



$MM_restrictGoTo = "index.php";

if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   

  $MM_qsChar = "?";

  $MM_referrer = $_SERVER['PHP_SELF'];

  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";

  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 

  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];

  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);

  header("Location: ". $MM_restrictGoTo); 

  exit;

}

?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "createuser")) {
  $insertSQL = sprintf("INSERT INTO accts (uname, pwd) VALUES (%s, %s)",
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['password'], "text"));

  mysql_select_db($database_wistream, $wistream);
  $Result1 = mysql_query($insertSQL, $wistream) or die(mysql_error());

  $insertGoTo = "users.php?action=created";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_wistream, $wistream);
$query_rsUsers = "SELECT * FROM accts";
$rsUsers = mysql_query($query_rsUsers, $wistream) or die(mysql_error());
$row_rsUsers = mysql_fetch_assoc($rsUsers);
$totalRows_rsUsers = mysql_num_rows($rsUsers);
?>
<?php
if(isset($_REQUEST['action']) && $_REQUEST['action'] == "del") {
// Delete Item
$delsql = mysql_query("DELETE FROM accts WHERE id = '" . $_GET['id'] . "'") or die("ERROR: Try again later");
header("Location: users.php?action=deleted");

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Wistream CMS</title>
<style type="text/css">
body {
	margin-top: 0px;
}

</style>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
</head>



<body>

<table width="98%" align="center" cellpadding="0" cellspacing="0">

  <tr>

    <td align="center" bgcolor="#FFFFFF"><img src="images/wicee.jpg" width="127" height="109" align="middle" /></td>

  </tr>

</table>

<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">

  <tr>

    <td width="54%" height="26" bgcolor="#FFCC33"><strong><a href="cpanel.php	">Control Panel</a></strong></td>

    <td width="23%" align="right" bgcolor="#FFCC33">&nbsp;</td>
    <td width="23%" align="right" bgcolor="#FFCC33"><a href="<?php echo $logoutAction ?>">Log Out</a></td>

  </tr>

  <tr>

    <td height="314" colspan="3" valign="top"><table width="100%" height="245" border="1" align="center" cellpadding="0" cellspacing="0">

        <tr>

          <td align="center"><h2>Users
          </h2>
            <table width="99%" cellpadding="0" cellspacing="0">
            <tr>
              <td><a href="?action=create">Create User</a>&nbsp;|&nbsp;<a href="?action=view">View Users</a></td>
              </tr>
            <tr>
              <td height="331" colspan="2" valign="top">
              <?php if(isset($_GET['action']) && $_GET['action'] == "create") { ?>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" id="createuser">
                <tr>
                  <td height="52"><form action="<?php echo $editFormAction; ?>" id="createuser" name="createuser" method="POST">
                   <?php if($_GET['action'] == "created") { // Created User  ?> <table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td align="center" bgcolor="#00FF00">User Created</td>
                      </tr>
                    </table><?php } // Created User ?>
                    <table width="387" height="153" border="1" align="center" cellpadding="1" cellspacing="0">
                      <tr>
                        <td colspan="2" align="center">Create User</td>
                      </tr>
                      <tr>
                        <td width="85">Username</td>
                        <td width="292"><span id="sprytextfield1">
                          <label for="username"></label>
                          <input type="text" name="username" id="username" />
                          <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                      </tr>
                      <tr>
                        <td>Password</td>
                        <td><span id="sprytextfield2">
                          <input type="password" name="password" id="password" />
                          <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center"><input type="submit" name="button" id="button" value="Create User" /></td>
                      </tr>
                    </table>
                    <input type="hidden" name="MM_insert" value="createuser" />
                  </form></td>
                </tr>
              </table>
              <?php } ?>
              <?php if(isset($_GET['action']) && $_GET['action'] == "view") { ?>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" id="deleteuser">
                <tr>
                    <td><table width="100%" border="1" cellpadding="1" cellspacing="0">
                      <tr>
                        <td><strong>Username</strong></td>
                        <td><strong>Usertype</strong></td>
                        <td><strong>Action</strong></td>
                      </tr>
                      <?php do { ?>
                      <tr>
                          <td><?php echo $row_rsUsers['uname']; ?></td>
                          <td><?php echo $row_rsUsers['type']; ?></td>
                          <td><a href="?action=del&amp;id=<?php echo $row_rsUsers['id']; ?>">Delete</a></td>
                          </tr>
                          <?php } while ($row_rsUsers = mysql_fetch_assoc($rsUsers)); ?>
                    </table></td>
                  </tr>
          </table>
                <?php } ?></td>
              </tr>
          </table></td>

        </tr>

    </table></td>

  </tr>

  <tr>

    <td height="24" colspan="3" align="center" bgcolor="#FFD9B3">powered by Phincorp</td>

  </tr>

</table>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
</script>
</body>

</html>
<?php
mysql_free_result($rsUsers);
?>