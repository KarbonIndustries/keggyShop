<?php require_once('..' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . '_init.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>KEGGY Shop</title>
	<link rel="stylesheet" href="<?= CSS_DIR ?>styles.css" type="text/css" media="all" charset="utf-8">
</head>
<body>
	<?php
	try
	{
		kProductManager::loadProductDatabase(JS_DIR . 'products.json','products');
	}catch(Exception $e)
	{
		echo '<h3>' . $e->getMessage() . '</h3>';
	}

	$products;

	try
	{
		$belts = kProductManager::getProductsByType('belt');
		$rings = kProductManager::getProductsByType('ring');
	}catch(Exception $e)
	{
		echo $e->getMessage();
	}

	require_once(CONTENT_DIR . 'header.php');
	?>
	<div id="contentShell">
		<div id="slideshow">
			<img src="<?= IMG_DIR ?>slideshow/combo_01.jpg" alt="" />
			<?php
			$ssImgs = glob(IMG_DIR . 'slideshow' . DS . '*.jpg');
			foreach($ssImgs as $i)
			{?>
				<div style="display:none;background:url(<?= $i ?>);"></div>
			<?}?>
		</div>
		<div id="itemListShell">
			<?php
			if($belts)
			{
				$i = 1;
			?>
				<div class="vSpace14"></div>
				<div class="itemGroup">
					<a id="viewBelts"/>
					<h1 class="groupTitle">Belts</h1>
					<?foreach($belts as $b)
					{?>
						<a class="itemLink" href="product.php?itemId=<?= $b->id() ?>" title="<?= $b->name() ?>">
							<div class="itemShell<?= $i % 4 ? ' mr8' : '' ?>">
								<div class="imgShell">
									<img src="<?= IMG_DIR . strtolower($b->type()) . DS . IMG_SIZE_2_DIR . DS . $b->id() ?>_01.jpg" alt="" />
								</div>
								<div class="itemInfo">
									<p class="itemTitle"><?= $b->name() ?></p>
									<p>$<?= $b->price() ?></p>
								</div>
							</div>
						</a>
					<?
					++$i;
					}?>
					<div class="clearfix"></div>
				</div>
			<?}

			if($rings)
			{
				$i = 1;
			?>
				<div class="vSpace30"></div>
				<div class="itemGroup">
					<a id="viewRings"/>
					<h1 class="groupTitle">Rings</h1>
					<?foreach($rings as $r)
					{?>
						<a class="itemLink" href="product.php?itemId=<?= $r->id() ?>" title="<?= $r->name() ?>">
							<div class="itemShell<?= $i % 4 ? ' mr8' : '' ?>">
								<div class="imgShell">
									<img src="<?= IMG_DIR . strtolower($r->type()) . DS . IMG_SIZE_2_DIR . DS . $r->id() ?>_01.jpg" alt="" />
								</div>
								<div class="itemInfo">
									<p class="itemTitle"><?= $r->name() ?></p>
									<p>$<?= $r->price() ?></p>
								</div>
							</div>
						</a>
					<?
					++$i;
					}?>
					<div class="clearfix"></div>
				</div>
			<?}?>
		</div>
		<div class="vSpace30"></div>
	</div>
	<?php
	require_once(CONTENT_DIR . 'footer.php');
	$quotedJSArray;
	foreach($ssImgs as $i)
	{
		$quotedJSArray[] = "'" . $i . "'";
	}
	?>
	<script src="<?= JS_DIR ?>jquery_1.7.2.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
		var ssImgs = <?= '[' . implode(',',$quotedJSArray) . ']' ?>,
			ssIndex = 1;
		setInterval('swapHeaderImg()',3000);
		
		function swapHeaderImg()
		{
			$('div#contentShell').find('div#slideshow').find('img').attr('src',ssImgs[ssIndex]);
			ssIndex = (ssIndex === (ssImgs.length - 1)) ? 0 : ++ssIndex;
		}
	</script>
</body>
</html>
