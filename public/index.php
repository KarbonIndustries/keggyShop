<?php
require_once('..' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'init.php');

kProductManager::loadProductDatabase(JS_DIR . 'products.json','products');

$products;

try
{
	$products = kProductManager::getAllProducts();
}catch(Exception $e)
{
	echo $e->getMessage();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>Keggy Shop</title>
	<link rel="stylesheet" href="<?= CSS_DIR ?>styles.css" type="text/css" media="all" charset="utf-8">
</head>
<body>
	<div id="content">
		<?php
			foreach($products as $p)
			{
				#echo $p->name() . '<br />';
			}
		?>
	</div>
</body>
</html>