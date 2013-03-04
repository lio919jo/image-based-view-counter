<?php
require_once('config.php');
$link = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS);
if(!$link)
	die('There was a database error during setup. Please try again later.'.mysql_error());
mysql_select_db(MYSQL_DABA);
$request = array_filter(explode('/', $_SERVER['REQUEST_URI']));
array_shift($request);
if($request[0])
	$action = $request[0];
else
	header('Location: http://jacobshreve.com/counter/create/');

if($action == 'create')
{
?>
<h1>Create Your Tag</h1>
<p>this is the identifier for your tag</p>

<form method="post" action="/counter/create.php">
<input type="text" name="tag" id="tag"/>
<input type="submit" value="create"/>
</form>
<?php
}
if($action == 'created')
{
$tag = $request[1];
?>
<h1>Here's your Tag</h1>
<p>to implement your visit counter, include this code in your page, posting, etc.</p>
<pre>
	<?php echo htmlspecialchars("<img height=\"0\" width=\"0\" src='http://jacobshreve.com/counter/tag/{$tag}/'/>"); ?>
</pre>
<p>to show a view counter, similar to the one below, include this code as well.</p>
<pre>
	<?php echo htmlspecialchars("<img src='http://jacobshreve.com/counter/image/{$tag}/'/>"); ?>
</pre>
<?php 
$font = 4;
$string = "Visits: 50";
$height = ImageFontHeight($font) * 1.4;
$width = ImageFontWidth($font) * strlen($string) * 1.1 + 10;
$im = @imagecreate($width, $height) or die("Cannot Initialize new GD image stream");
$background_color = imagecolorallocate($im, 230, 230, 230);
$text_color = imagecolorallocate($im, 20,20,20);
imagestring($im, 4, 10, 3,  $string , $text_color);
ob_start();
imagepng($im);
$png = ob_get_contents();
ob_end_clean();
?>
<img src="data:img/png;base64,<?php echo base64_encode($png); ?>"/>
<?php
}
if($action == 'tag')
{
$tag = $request[1];
$update_tag = mysql_query("UPDATE `tags` SET `count`=`count`+1 WHERE `name`='{$tag}'");
echo mysql_error();
header('Content-Type: image/png');
if($update_tag)
{
	$id = mysql_result(mysql_query("SELECT `id` FROM `tags` WHERE `name`='{$tag}'"), 0);
	$ip = ip2long($_SERVER['REMOTE_ADDR']);
	$url = mysql_real_escape_string($_SERVER['HTTP_REFERER']);
	$created = time();
	$insert_visit = mysql_query("INSERT INTO `visits` (`tag_id`, `url`, `ip`, `time`) VALUES ('{$id}', '{$url}', '{$ip}', '{$created}')");
	$image = @imagecreate(1,1);
	imagepng($image);
}
}
