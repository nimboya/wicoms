<?php require_once('Connections/wistream.php'); ?>
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

if(isset($_POST['createpage']) && $_POST['createpage'] == "yes")
{
	// New Page Creation
	$replace = array(" ","@", ",", "?", "!", "+", "=", "#", "$", "&", "*", "(", ")", "/", "'", "~", "%", "[", "]", ":", ";", "<", ">", ".");
	$pageurl = str_replace($replace ,"-", strtolower($_POST['pagename']));
	//mysql_select_db($database_wistream, $wistream);
	$insqry = mysqli_query($wistream, "INSERT INTO $database_wistream.pages (pagename, pageurl, pagetype) VALUES ('$_POST[pagename]', '$pageurl', '$_POST[type]')") or die(mysql_error());
}
if(isset($_GET['act']) && $_GET['act'] == "del")
{
	// Delete Page
	//mysql_select_db($database_wistream, $wistream);
	$delopt = mysqli_query($wistream,"DELETE FROM $database_wistream.pages WHERE id='$_GET[id]'");
	header("Location: cpanel.php");
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

mysqli_select_db($wistream, $database_wistream);
$query_rsPages = "SELECT * FROM $database_wistream.pages";
$rsPages = mysqli_query($wistream,$query_rsPages) or die(mysqli_error($wistream));
$row_rsPages = mysqli_fetch_assoc($rsPages);
$totalRows_rsPages = mysqli_num_rows($rsPages);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Telemobi</title>

<style type="text/css">

body {

	margin-top: 0px;

}

</style>

</head>



<body>

<table width="100%" align="center" cellpadding="0" cellspacing="0">

  <tr>

    <td align="center" bgcolor="#000"><img src="images/logo.png" align="middle" /></td>

  </tr>

</table>

<table width="57%" border="0" align="center" cellpadding="0" cellspacing="0">

  <tr>

    <td width="40%" height="26" bgcolor="#FFCC33"><strong>Wistream CMS</strong></td>
    <td width="25%" align="right" bgcolor="#FFCC33"><a href="users.php">Users</a></td>

    <td width="35%" align="right" bgcolor="#FFCC33"><a href="<?php echo $logoutAction ?>">Log Out</a></td>

  </tr>

  <tr>

    <td height="245" colspan="3" valign="top"><table width="100%" height="39" border="1" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td height="37" align="center"><form id="form1" name="form1" method="post" action="">
          Create New Page
        : 
              <label for="pagename"></label>
        <input type="text" name="pagename" id="pagename" />
        <label for="textfield"></label>
        <br />
        Type:
        <select name="type" id="type">
          <option value="0">basic</option>
          <option value="1">list/detail</option>
        </select>
        <input type="submit" name="btnCreate" id="btnCreate" value="Create" />
        <input name="createpage" type="hidden" id="createpage" value="yes" />
        </form></td>
      </tr>
    </table>
      <?php if ($totalRows_rsPages > 0) { // Show if recordset not empty ?>
        Pages
  <?php do { ?>
    <table width="100%" height="39" border="1" align="center" cellpadding="0" cellspacing="0">
      
      <tr>
        <td width="90%" height="37" align="center"><h3><a href="cats.php?c=<?php echo $row_rsPages['pageurl']; ?>"><?php echo $row_rsPages['pagename']; ?></a></h3></td>
        <td width="20%" align="center">[<a href="?act=del&amp;id=<?php echo $row_rsPages['id']; ?>">delete</a>]</td>
        </tr>
      
    </table>
    <?php } while ($row_rsPages = mysqli_fetch_assoc($rsPages)); ?>
  <?php } // Show if recordset not empty ?></td>

  </tr>
  <tr>
    <td height="24" colspan="3" align="center" bgcolor="#FFD9B3"></td>

  </tr>

</table>

</body>

</html>
<?php
mysqli_free_result($rsPages);
?>
