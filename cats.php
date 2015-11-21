<?php require_once('../Connections/wistream.php'); ?>
<?php

//initialize the session

session_start();


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



$currentPage = $_SERVER["PHP_SELF"];



$maxRows_rsNews = 40;

$pageNum_rsNews = 0;

if (isset($_GET['pageNum_rsNews'])) {

  $pageNum_rsNews = $_GET['pageNum_rsNews'];

}

$startRow_rsNews = $pageNum_rsNews * $maxRows_rsNews;

$colname_rsNews = "-1";

if (isset($_GET['c'])) {

  $colname_rsNews = $_GET['c'];

}

mysql_select_db($database_wistream, $wistream);

$query_rsNews = sprintf("SELECT * FROM content WHERE category = %s ORDER BY id DESC", GetSQLValueString($colname_rsNews, "text"));

$query_limit_rsNews = sprintf("%s LIMIT %d, %d", $query_rsNews, $startRow_rsNews, $maxRows_rsNews);

$rsNews = mysql_query($query_limit_rsNews, $wistream) or die(mysql_error());

$row_rsNews = mysql_fetch_assoc($rsNews);



if (isset($_GET['totalRows_rsNews'])) {

  $totalRows_rsNews = $_GET['totalRows_rsNews'];

} else {

  $all_rsNews = mysql_query($query_rsNews);

  $totalRows_rsNews = mysql_num_rows($all_rsNews);

}

$totalPages_rsNews = ceil($totalRows_rsNews/$maxRows_rsNews)-1;



$queryString_rsNews = "";

if (!empty($_SERVER['QUERY_STRING'])) {

  $params = explode("&", $_SERVER['QUERY_STRING']);

  $newParams = array();

  foreach ($params as $param) {

    if (stristr($param, "pageNum_rsNews") == false && 

        stristr($param, "totalRows_rsNews") == false) {

      array_push($newParams, $param);

    }

  }

  if (count($newParams) != 0) {

    $queryString_rsNews = "&" . htmlentities(implode("&", $newParams));

  }

}

$queryString_rsNews = sprintf("&totalRows_rsNews=%d%s", $totalRows_rsNews, $queryString_rsNews);

?>

<?php



if(isset($_GET['action']) && $_GET['action'] == "delete") {

// Delete News Content

$delsql = mysql_query("DELETE FROM content WHERE id = '" . $_GET['delid'] . "'") or die("ERROR: Try again later");

header("Location: cats.php?c=" . $_GET['c'] . "&status=deleted");

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

</head>



<body>

<table width="98%" align="center" cellpadding="0" cellspacing="0">

  <tr>

    <td align="center" bgcolor="#FFFFFF"><img src="images/wicee.jpg" alt="" width="127" height="109" align="middle" /></td>

  </tr>

</table>

<table style="border-style:dotted;" width="57%" border="1" align="center" cellpadding="0" cellspacing="0">

  <tr>

    <td width="54%" height="26" bgcolor="#FFCC33"><strong>Wistream CMS</strong></td>

    <td width="46%" align="right" bgcolor="#FFCC33"><a href="<?php echo $logoutAction ?>">Log Out</a></td>

  </tr>

  <tr>

    <td height="314" colspan="2" valign="top"><table width="100%" align="center" cellpadding="0" cellspacing="0">

        <tr>

          <td bgcolor="#FFFF00"><a href="cpanel.php">Categories</a> &gt; <strong><?php echo ucwords($_GET['c']); ?></strong></td>

        </tr>

    </table>

      <?php if(isset($_GET['status']) && $_GET['status'] == "newpost") { // Show if posted ?>

      <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">

        <tr>

          <td height="27" align="center" bgcolor="#0000FF"><font color="#FFFFFF"><strong>Content Created Sucessfully</strong></font></td>

        </tr>

      </table>

	  <?php } // Show when deleted ?>

       <?php if(isset($_GET['status']) &&  $_GET['status'] == "deleted") { // Show if posted ?>

      <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">

        <tr>

          <td height="27" align="center" bgcolor="#FF0000"><strong><font color="#FFFFFF">Deleted Successfully</font></strong></td>

        </tr>

      </table>

	  <?php } // Show when deleted ?>

      <font size="+1" face="Arial"><b><a href="post.php?c=<?php echo $_GET['c']; ?>">Create New</a></b></font>

      <?php if ($totalRows_rsNews > 0) { // Show if recordset not empty ?>

        <?php do { ?>

          <table width="100%" border="0" cellpadding="0" cellspacing="0">

            <tr>

              <td width="88%" height="30" bgcolor="#F8F8F8" style="padding-left:12px;"><font face="Arial" size="3"><?php echo $row_rsNews['title']; ?></font></td>

              <br />

              <td width="12%" align="center" bgcolor="#F8F8F8"><a href="edit.php?c=<?php echo $_GET['c']; ?>&amp;id=<?php echo $row_rsNews['id']; ?>">edit</a> | <a href="?action=delete&amp;delid=<?php echo $row_rsNews['id']; ?>&amp;c=<?php echo $_GET['c']; ?>">delete</a></td>

            </tr>

          </table>

          <?php } while ($row_rsNews = mysql_fetch_assoc($rsNews)); ?>

        <?php } // Show if recordset not empty ?>

      <br />

      <?php if ($totalRows_rsNews > 0) { // Show if recordset not empty ?>

        <table width="77%" align="center">

          <tr>

            <td align="center"><a href="<?php printf("%s?pageNum_rsNews=%d%s", $currentPage, max(0, $pageNum_rsNews - 1), $queryString_rsNews); ?>">Older</a>&nbsp;&diams;&nbsp;<a href="<?php printf("%s?pageNum_rsNews=%d%s", $currentPage, min($totalPages_rsNews, $pageNum_rsNews + 1), $queryString_rsNews); ?>">Newer</a></td>

          </tr>

        </table>

        <?php } // Show if recordset not empty ?>

      <?php if ($totalRows_rsNews == 0) { // Show if recordset empty ?>

        <center>No News Content Here</center>

  <?php } // Show if recordset empty ?></td>

  </tr>

  <tr>

    <td height="24" colspan="2" align="center" bgcolor="#FFD9B3">powered by <a href="http://wiceeweb.com/" target="_blank">Wicee</a></td>

  </tr>

</table>

</body>

</html>

<?php

mysql_free_result($rsNews);

?>

