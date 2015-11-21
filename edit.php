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



if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "addnew")) {

  $updateSQL = sprintf("UPDATE content SET title=%s, content=%s WHERE id=%s",

                       GetSQLValueString($_POST['title'], "text"),

                       GetSQLValueString($_POST['content'], "text"),

                       GetSQLValueString($_POST['id'], "int"));



  mysql_select_db($database_wistream, $wistream);

  $Result1 = mysql_query($updateSQL, $wistream) or die(mysql_error());



  $updateGoTo = "cats.php?status=newpost";

  if (isset($_SERVER['QUERY_STRING'])) {

    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";

    $updateGoTo .= $_SERVER['QUERY_STRING'];

  }

  header(sprintf("Location: %s", $updateGoTo));

}



$colname_rsNews = "-1";

if (isset($_GET['id'])) {

  $colname_rsNews = $_GET['id'];

}

mysql_select_db($database_wistream, $wistream);

$query_rsNews = sprintf("SELECT * FROM content WHERE id = %s", GetSQLValueString($colname_rsNews, "int"));

$rsNews = mysql_query($query_rsNews, $wistream) or die(mysql_error());

$row_rsNews = mysql_fetch_assoc($rsNews);

$totalRows_rsNews = mysql_num_rows($rsNews);



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
<script src="ckeditor/ckeditor.js"></script>
<link rel="stylesheet" href="ckeditor/samples/sample.css">
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>

<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>

<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />

<link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />

</head>



<body>

<table width="98%" align="center" cellpadding="0" cellspacing="0">

  <tr>

    <td align="center" bgcolor="#FFFFFF"><img src="images/wicee.jpg" alt="" width="127" height="109" align="middle" /></td>

  </tr>

</table>

<table width="71%" align="center" cellpadding="0" cellspacing="0">

  <tr>

    <td width="54%" height="26" bgcolor="#FFCC33"><strong>Wistream CMS</strong></td>

    <td width="46%" align="right" bgcolor="#FFCC33"><a href="<?php echo $logoutAction ?>">Log Out</a></td>

  </tr>

  <tr>

    <td height="26" colspan="2" align="center" bgcolor="#FFFFCC"><b><font size="+1">Edit Post</font></b></td>

  </tr>

  <tr>

    <td height="436" colspan="2" align="center" valign="top"><table width="100%" cellspacing="0" cellpadding="0">

      <tr>

        <td bgcolor="#FFFF00"><a href="cpanel.php">Categories</a> &gt; <a href="cats.php?c=<?php echo $_GET['c']; ?>">Return</a> &gt;<strong> <?php echo $row_rsNews['title']; ?></strong></td>

      </tr>

    </table>

      <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="addnew" id="addnew">

        <table width="100%" align="center" cellpadding="0" cellspacing="0">

          <tr>

            <td width="7%" height="71" align="center">Title: 

              <label for="title"></label></td>

            <td width="93%"><span id="sprytextfield1">

              <label for="title"></label>

              <input name="title" type="text" id="title" style="font-size:18px;font-family:Arial;" value="<?php echo $row_rsNews['title']; ?>" size="95" />

            <span class="textfieldRequiredMsg"><br />

            Enter Title</span></span></td>

          </tr>
          <tr>

            <td height="348" colspan="2" align="center"><label for="content"></label>

              <span id="sprytextarea1">

              <label for="content"></label>

              <textarea name="content" class="ckeditor" id="content" style="font-family:Arial;" cols="132" rows="20"><?php echo $row_rsNews['content']; ?></textarea>

            <span class="textareaRequiredMsg"><br />

            Enter Body</span></span></td>

          </tr>

          <tr>

            <td height="39" colspan="2" align="center"><label for="fileField"></label>

            Attach Image: 

            <input type="file" name="fileField" id="fileField" /></td>

          </tr>

        </table>

        <input name="id" type="hidden" id="id" value="<?php echo $row_rsNews['id']; ?>" />

        <input type="submit" style="font-size:18px;" name="btncreate" id="btncreate" value="Update" />

        <input type="hidden" name="MM_update" value="addnew" />

      </form></td>

  </tr>

  <tr>

    <td height="24" colspan="2" align="center" bgcolor="#FFD9B3">powered by <a href="http://www.facebook.com/wicee" target="_blank">Wicee</a></td>

  </tr>

</table>

<script type="text/javascript">

var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");

var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");

</script>

</body>

</html>

<?php

mysql_free_result($rsNews);

?>

