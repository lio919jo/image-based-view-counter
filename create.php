<?php
require_once('config.php');
$link = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS);
if(!$link)
	die('There was a database error during setup. Please try again later.'.mysql_error());
mysql_select_db(MYSQL_DABA);

$input = $_POST['tag'];
$input = mysql_escape_string($input);

$result = mysql_query("SELECT COUNT(*) FROM `tags` WHERE `name`='{$input}'");
$count = mysql_result($result, 0);

if($count)
	die('That tag already exists. Go back and pick another.');

$result = mysql_query("INSERT INTO `tags` (`name`, `count`) VALUES ('{$input}', '0')");
if($result)
	header("Location: http://jacobshreve.com/counter/created/{$input}/");
else
	die('There was an error creating your tag. Please try again later.');