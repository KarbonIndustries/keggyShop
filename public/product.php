<?php
require_once('..' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'init.php');

$loadErrMsg = null;
$itemErrMsg = null;
try
{
	kProductManager::loadProductDatabase(JS_DIR . 'products.json','products');
}catch(Exception $e)
{
	$errMsg = $e->getMessage();
}

if(!$loadErrMsg)
{
	$G =& $_GET;

	if(isset($G['itemId']))
	{
		$pid = $G['itemId'];

		try
		{
			$p = kProductManager::getProductById($pid);
		}catch(Exception $e)
		{
			$itemErrMsg = $e->getMessage();
		}
	}else
	{
		echo 'item does not exist';
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>KEGGY Shop :: <?= $p->name() ?></title>
	<link rel="stylesheet" href="<?= CSS_DIR ?>styles.css" type="text/css" media="all" charset="utf-8">
</head>
<body>
	<?php
	require_once(CONTENT_DIR . 'header.php');
	?>
	<div id="contentShell">
		<h1 id="productItemTitle"><?= $p->name() ?></h1>
		<div id="productItemImageShell">
			<div id="productLrgImgShell"><img src="<?= IMG_DIR . strtolower($p->type()) . DS . IMG_SIZE_3_DIR . DS . $p->id() . '_01.jpg' ?>" alt="" /></div>
			<div id="productItemThumbnails">
				<?php
				$thumbs = glob(IMG_DIR . strtolower($p->type()) . DS . IMG_SIZE_1_DIR . DS . $p->id() . '_*.jpg');
				foreach($thumbs as $t)
				{?>
					<a href="#"><img src="<?= $t ?>" alt="" /></a>
				<?}
				?>
			</div>
		</div>
		<div id="productItemInfoShell">
			<h2><?= $p->name() ?></h2>
			<?php
			if($p->optionExists('strap_color') && ($p->options('strap_color') === 'all'))
			{
				$jsonFile = JS_DIR . 'products.json';
				if(is_file($jsonFile) && $loadedFile = new fFile($jsonFile))
				{
					$rawData = json_decode($loadedFile->read(),true);
					if(array_key_exists('strap_colors',$rawData))
					{
						$strapColors = $rawData['strap_colors'];
					}else
					{
						die('No strap colors available');
					}?>
					<select name="strapColor" id="strapColor" size="1">
				
					<?foreach($strapColors as $c)
					{?>
						<option value="<?= $c['color_name'] ?>"><?= $c['color_name'] ?></option>
					<?}?>
					</select>
				<?}	
			}
			?>
			<p id="productItemQuantityShell"><input id="productItemQuantity" type="text" value="1"/></p>
			<p id="productItemTotalShell">$<?= $p->price() ?></p>
			<p id="productShippingShell">Free Shipping</p>
			<div id="productAddToCartShell">
			<?php
			$addToCartURLTemplate = 'https://www.paypal.com/cgi-bin/webscr?cmd=_cart&business=GXXR4AW8MBFBL&lc=US&item_name=' . rawurlencode($p->name()) . '&item_number=' . rawurlencode($p->id()) . '&amount=' . rawurlencode($p->price()) . '&currency_code=USD&button_subtype=products&no_note=1&no_shipping=2&rm=1&return=http%3a%2f%2fkeggy%2ecom%2fshop%2f&add=1&bn=PP%2dShopCartBF%3akeggy_shop%3aNonHosted&on0=' . rawurlencode('Strap Color') . '&os0={{strap_color}}&quantity={{item_quantity}}';
			$addToCartURL         = preg_replace('/\{\{strap_color\}\}/i',rawurlencode($strapColors[0]['color_name']),$addToCartURLTemplate,1);
			$addToCartURL         = preg_replace('/\{\{item_quantity\}\}/i','1',$addToCartURL,1);
			?>
				<a href="<?= $addToCartURL ?>">Add to cart</a>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="vSpace30"></div>
	</div>
	<?php
	require_once(CONTENT_DIR . 'footer.php');
	?>
	<script src="<?= JS_DIR ?>jquery_1.7.2.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?= JS_DIR ?>accounting.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
	var imgShell = $('div#contentShell').find('div#productItemImageShell').find('div#productLrgImgShell'),
		thumbs = $('div#contentShell').find('div#productItemImageShell').find('div#productItemThumbnails').find('a').find('img')
		thumbSizeMarker      = '<?= DS . IMG_SIZE_1_DIR . DS ?>',
		largeSizeMarker      = '<?= DS . IMG_SIZE_3_DIR . DS ?>',
		curThumbURL          = '',
		productItemInfoShell = $('div#contentShell').find('div#productItemInfoShell'),
		strapColor           = productItemInfoShell.find('select#strapColor'),
		addToCartLink        = productItemInfoShell.find('div#productAddToCartShell').find('a'),
		addToCartURLTemplate = '<?= $addToCartURLTemplate ?>',
		initialItemQuantity  = 1,
		itemPrice            = parseFloat(<?= $p->price() ?>),
		itemTotalShell       = productItemInfoShell.find('p#productItemTotalShell'),
		itemQuantity         = productItemInfoShell.find('p#productItemQuantityShell').find('input#productItemQuantity');

	thumbs.bind('mouseover click',function(e)
	{
		curThumbURL = $(this).attr('src');
		imgShell.find('img').attr('src',curThumbURL.replace(thumbSizeMarker,largeSizeMarker));
	});

	strapColor.change(function(e)
	{
		updateAddToCartURL();
	});

	itemQuantity.bind('focus blur keyup',function()
	{
		updateTotal();
	});

	function updateTotal()
	{
		var quantityVal = parseInt(itemQuantity.val(),10),
			quantity = typeof quantityVal === 'number' && quantityVal > 0 ? quantityVal : 1,
			total = itemPrice * quantity;
		itemTotalShell.text(accounting.formatMoney(total.toFixed(2)));
		updateAddToCartURL();
	}

	function updateAddToCartURL()
	{
		var newURL = addToCartURLTemplate.replace('{{strap_color}}',encodeURIComponent(strapColor.val())),
			newURL = newURL.replace('{{item_quantity}}',encodeURIComponent(parseInt(itemQuantity.val())));
		addToCartLink.attr('href',newURL);
	}
	</script>
</body>
</html>
