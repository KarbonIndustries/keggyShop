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
}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>KEGGY Shop :: <?= $p->name() ?></title>
	<link rel="stylesheet" href="<?= CSS_DIR ?>styles.css" type="text/css" media="all" charset="utf-8" />
</head>
<body>
	<?php
	require_once(CONTENT_DIR . 'header.php');
	?>
	<div id="contentShell">
		<h1 id="productItemTitle"><a href="./">Products</a> &raquo; <?= $p->name() ?></h1>
		<div id="productItemImageShell">
			<div id="productLrgImgShell"><img src="<?= IMG_DIR . strtolower($p->type()) . DS . IMG_SIZE_3_DIR . DS . $p->id() . '_01.jpg' ?>" alt="" /></div>
			<div id="productItemThumbnails">
				<?php
				$thumbs = glob(IMG_DIR . strtolower($p->type()) . DS . IMG_SIZE_1_DIR . DS . $p->id() . '_*.jpg');
				foreach($thumbs as $t)
				{?>
					<a href="#"><img src="<?= $t ?>" alt="" /></a>
				<?}?>
				<div class="clearfix"></div>
			</div>
		</div>
		<div id="productItemInfoShell">
			<h2 class="productInfoTitle"><?= $p->name() ?></h2>
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

					<h3 class="productSwatchesTitle">Strap Colors</h3>
					<ul id="swatchesShell">
						<?php
						function toCSSName($v)
						{
							return preg_match('/\s/',$v) ? lcfirst(preg_replace('/\s/','',ucwords($v))) : lcfirst($v);
						}

						foreach($strapColors as $c)
						{?>
							<li class="swatchShell">
								<span class="swatch <?= toCSSName($c['color_name']) ?>">
									<span class="<?= toCSSName($c['color_name']) ?> largeSwatch"></span>
								</span>
								<span class="swatchLabel"><?= $c['color_name'] ?></span>
							</li>
						<?}?>
						<div class="clearfix"></div>
					</ul>

					<div id="productItemStrapColorShell">
						<h3 class="selectStrapColor">Choose a strap color</h3>
						<select name="strapColor" id="strapColor" size="1">
				
						<?php
						foreach($strapColors as $c)
						{?>
							<option value="<?= $c['color_name'] ?>"><?= $c['color_name'] ?></option>
						<?}?>
						</select>
					</div>
				<?}
			}?>
			<?php
			if($p->optionExists('size') && $beltSizes = $p->options('size'))
			{?>
				<div id="productItemBeltSizeShell">
					<h3 class="selectBeltSize">Choose a size</h3>
					<select name="beltSize" id="beltSize" size="1">
				
					<?php
					foreach($beltSizes as $s)
					{?>
						<option value="<?= $s['label'] ?>"><?= $s['value'] ?></option>
					<?}?>

					</select>
				</div>
			<?}

			if(strtolower($p->type()) === 'ring' && $p->optionExists('ring_size') && $ringSizes = $p->options('ring_size'))
			{?>
				<div id="productItemRingSizeShell">
					<h3 class="selectStrapColor">Choose a ring size</h3>
					<select name="ringSize" id="ringSize" size="1">
						<?php
						foreach($ringSizes as $s)
						{?>
							<option value="<?= $s['label'] ?>"><?= $s['label'] ?></option>
						<?}?>
					</select>
				</div>
			<?}?>
			<div id="productItemQuantityShell">
				<h3>Quantity</h3>
				<input id="productItemQuantity" type="text" value="1" maxlength="2"/>
			</div>
			<div id="productShippingShell">
				<p class="shippingText">Free Shipping</p>
			</div>
			<div class="clearfix"></div>
			<div id="productItemTotalShell">
				<div id="productItemPriceSection">
					<h4 class="fl productItemPriceLabel">Price</h4>
					<span class="productItemPriceValue">$<?= $p->price() ?></span>
					<div class="clearfix"></div>
				</div>
				<div class="clearfix"></div>
				<div id="productItemTotalSection">
					<h3 class="fl productItemTotalLabel">Total</h3>
					<span class="productItemTotalValue">$<?= $p->price() ?><span>
					<div class="clearfix"></div>
				</div>
			</div>
			<div id="productAddToCartShell">
				<?php
				$addToCartURLTemplate = 'https://www.paypal.com/cgi-bin/webscr?cmd=_cart&business=GXXR4AW8MBFBL&lc=US&item_name=' . rawurlencode($p->name()) . '&item_number=' . rawurlencode($p->id()) . '&amount=' . rawurlencode($p->price()) . '&currency_code=USD&button_subtype=products&no_note=1&no_shipping=2&rm=1&return=http%3a%2f%2fkeggy%2ecom%2fshop%2f&add=1&bn=PP%2dShopCartBF%3akeggy_shop%3aNonHosted';
				if(strtolower($p->type()) === 'belt')
				{
					$addToCartURLTemplate .= '&on0=' . rawurlencode('Strap Color') . '&os0={{strap_color}}';
					$addToCartURLTemplate .= '&on1=' . rawurlencode('Size') . '&os1={{belt_size}}';
				}elseif(strtolower($p->type()) === 'ring')
				{
					$addToCartURLTemplate .= '&on0=' . rawurlencode('Ring Size') . '&os0={{ring_size}}';
				}
				$addToCartURLTemplate .= '&quantity={{item_quantity}}';
				$addToCartURL = $addToCartURLTemplate;
				if(strtolower($p->type()) === 'belt')
				{
					$addToCartURL = preg_replace('/\{\{strap_color\}\}/i',rawurlencode($strapColors[0]['color_name']),$addToCartURL,1);
					$addToCartURL = preg_replace('/\{\{belt_size\}\}/i',rawurlencode($beltSizes[0]['value']),$addToCartURL,1);
				}elseif(strtolower($p->type()) === 'ring')
				{
					$addToCartURL = preg_replace('/\{\{ring_size\}\}/i',rawurlencode($ringSizes[0]['label']),$addToCartURL,1);
				}
				$addToCartURL = preg_replace('/\{\{item_quantity\}\}/i','1',$addToCartURL,1);
				?>
				<div class="clearfix"></div>
				<a id="productAddToCartLink" href="<?= $addToCartURL ?>">Add to cart</a>
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
	var productType          = '<?= strtolower($p->type()) ?>',
		imgShell             = $('div#contentShell').find('div#productItemImageShell').find('div#productLrgImgShell'),
		thumbs               = $('div#contentShell').find('div#productItemImageShell').find('div#productItemThumbnails').find('a').find('img')
		thumbSizeMarker      = '<?= DS . IMG_SIZE_1_DIR . DS ?>',
		largeSizeMarker      = '<?= DS . IMG_SIZE_3_DIR . DS ?>',
		curThumbURL          = '',
		productItemInfoShell = $('div#contentShell').find('div#productItemInfoShell'),
		<?php
		if(strtolower($p->type()) === 'belt')
		{?>
		strapColor           = productItemInfoShell.find('select#strapColor'),
		beltSize             = productItemInfoShell.find('select#beltSize'),
		<?}elseif(strtolower($p->type()) === 'ring')
		{?>
		ringSize             = productItemInfoShell.find('select#ringSize'),
		<?}?>
		addToCartLink        = productItemInfoShell.find('div#productAddToCartShell').find('a'),
		addToCartURLTemplate = '<?= $addToCartURLTemplate ?>',
		initialItemQuantity  = 1,
		itemPrice            = parseFloat(<?= $p->price() ?>),
		itemTotalShell       = productItemInfoShell.find('div#productItemTotalShell'),
		itemTotal            = itemTotalShell.find('span.productItemTotalValue'),
		itemQuantity         = productItemInfoShell.find('div#productItemQuantityShell').find('input#productItemQuantity'),
		selects              = $([]);

	thumbs.bind('mouseover click',function(e)
	{
		curThumbURL = $(this).attr('src');
		imgShell.find('img').attr('src',curThumbURL.replace(thumbSizeMarker,largeSizeMarker));
	});

	<?php
	if(strtolower($p->type()) === 'belt')
	{?>
		selects = selects.add(strapColor);
		selects = selects.add(beltSize);
	<?}elseif(strtolower($p->type()) === 'ring')
	{?>
		selects = selects.add(ringSize);
	<?}?>

	selects.change(function(e)
	{
		console.log('change');
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
		itemTotal.text(accounting.formatMoney(total.toFixed(2)));
		updateAddToCartURL();
	}

	function updateAddToCartURL()
	{
		var newURL = addToCartURLTemplate,
			<?php
			if(strtolower($p->type()) === 'belt')
			{?>
				newURL = newURL.replace('{{strap_color}}',encodeURIComponent(strapColor.val())),
				newURL = newURL.replace('{{belt_size}}',encodeURIComponent(beltSize.val())),
			<?}elseif(strtolower($p->type()) === 'ring')
			{?>
				newURL = newURL.replace('{{ring_size}}',encodeURIComponent(ringSize.val())),
			<?}?>
			newURL = newURL.replace('{{item_quantity}}',encodeURIComponent(parseInt(itemQuantity.val())));
		addToCartLink.attr('href',newURL);
	}
	</script>
</body>
</html>