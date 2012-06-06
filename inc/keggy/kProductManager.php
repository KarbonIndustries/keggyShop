<?php
class kProductManager
{
	const REQUIRED_PRODUCT_KEYS = 'name,type,id,price,options';

	private static $_requiredProductKeys;
	private static $_dbLoaded;
	private static $_rawDb;
	private static $_rawProducts;
	private static $_db;
	private static $_file;

	public static function loadProductDatabase($file,$key)
	{
		if(is_file($file) && is_string($key) && $key)
		{
			self::$_file  = new fFile($file);	
			self::$_rawDb = json_decode(self::$_file->read(),true);

			if(array_key_exists($key,self::$_rawDb))
			{
				self::$_rawProducts = self::$_rawDb[$key];
				if(is_array(self::$_rawProducts) && !empty(self::$_rawProducts))
				{
					self::$_requiredProductKeys = explode(',',self::REQUIRED_PRODUCT_KEYS);
					self::$_db = array();
					foreach(self::$_rawProducts as $p)
					{
						if(self::keysExist(self::$_requiredProductKeys,$p))
						{
							reset(self::$_requiredProductKeys);
							$product = new kProduct();
							while($k = current(self::$_requiredProductKeys))
							{
								$product->{$k}($p[$k]);
								next(self::$_requiredProductKeys);
							}
							#self::$_db[] = new kProduct($p['name'],$p['type'],$p['id'],$p['price'],$p['options']);
						}else
						{
							throw new Exception('Product(s) missing required keys');
						}
						reset(self::$_requiredProductKeys);
					}
					self::$_dbLoaded    = true;
				}else
				{
					throw new Exception('Invalid database');
				}
			}else
			{
				self::$_file = self::$_db = null;
				throw new Exception('Product key "' . $key . '" doesn\'t exist');
			}
		}
	}

	public static function getProductsByType($t)
	{
		if(self::databaseLoaded())
		{
			if(is_string($t) && $t)
			{
				$result = array();
				foreach(self::$_db as $p)
				{
					if(strtolower($p->type()) === strtolower($t))
					{
						$result[] = $t;
					}
				}
			}else
			{
				throw new Exception('Invalid product type');
			}

			return $result;
		}else
		{
			throw new Exception('No products in database');
		}
	}

	public static function getProductCountByType($t)
	{
		if(self::databaseLoaded())
		{
			if(is_string($t) && $t)
			{
				$count = 0;
				foreach(self::$_db as $p)
				{
					if(strtolower($p->type()) === strtolower($t))
					{
						++$count;
					}
				}
			}else
			{
				throw new Exception('Invalid product type');
			}

			return $count;
		}else
		{
			throw new Exception('No products in database');
		}
	}

	public static function getAllProducts()
	{
		if(self::databaseLoaded())
		{
			return self::$_db;
		}else
		{
			throw new Exception('No products in database');
		}
	}

	public static function databaseLoaded()
	{
		return !!self::$_dbLoaded;
	}

	private static function keysExist(Array $keys,Array &$array)
	{
		foreach($keys as $k)
		{
			if(!array_key_exists($k,$array))
			{
				return false;
			}
		}

		return true;
	}
}


?>