<?php
/****c* Keggy/kProduct
* NAME
* 	kProduct: Keggy Product
* SYNOPSIS
*	$belt1 = new kProduct()
* FUNCTION
*	creates a product
****
*/

class kProduct
{
	const REQUIRED_COLOR_KEYS  = 'name,id';
	const REQUIRED_OPTION_KEYS = 'name,value';

	private static $REQUIRED_COLOR_KEYS;
	private static $REQUIRED_OPTION_KEYS;
	private static $DEFAULT_PRICE        = 0;
	private static $DEFAULT_PRODUCT_TYPE = 'Generic';

	private $name,
			$type,
			$id,
			$price,
			$color,
			$options;
	
	function __construct(&$name = null,&$type = null,&$id = null,&$price = null,Array &$color = null,Array &$options = null)
	{
		$this->name     = $name;
		$this->type     = is_string($type) && $type ? $type : self::$DEFAULT_PRODUCT_TYPE;
		$this->id       = self::isValidId($id) ? $id : null;
		fMoney::setDefaultCurrency(USD);
		$this->price                = new fMoney(self::isValidCurrency($price) ? $price : self::$DEFAULT_PRICE);
		self::$REQUIRED_COLOR_KEYS  = explode(',',self::REQUIRED_COLOR_KEYS);
		self::$REQUIRED_OPTION_KEYS = explode(',',self::REQUIRED_OPTION_KEYS);
		$this->color                = is_array($color) && self::isValidColorSet($color) ? $color : array();
		$this->options              = is_array($options) && self::isValidOptionSet($options) ? $options : array();

		if(is_array($options) && !empty($options))
		{
			foreach($options as $k => $v)
			{
				if(self::isValidKeyValuePair($k,$v))
				{
					$this->options[$k] = $v;
				}
			}
		}else
		{
			$this->options = array();
		}
	}

	public function name(&$v = null)
	{
		if(is_null($v))
		{
			return $this->{__FUNCTION__};
		}

		if(is_string($v) and !empty($v))
		{
			$this->{__FUNCTION__} = $v;
			return $this;
		}else
		{
			throw new Exception('Name must be a valid string');
		}
	}

	public function type(&$v = null)
	{
		if(is_null($v))
		{
			return $this->{__FUNCTION__};
		}

		if(is_string($v) && $v)
		{
			$this->{__FUNCTION__} = $v;
			return $this;
		}else
		{
			throw new Exception('Type must be a valid alphanumeric value');
		}
	}

	public function id(&$v = null)
	{
		if(is_null($v))
		{
			return $this->{__FUNCTION__};
		}

		if(self::isValidId($v))
		{
			$this->{__FUNCTION__} = $v;
			return $this;
		}else
		{
			throw new Exception('Id must be a valid alphanumeric value');
		}
	}

	public function price(&$v = null)
	{
		if(is_null($v))
		{
			return $this->{__FUNCTION__}->getAmount();
		}

		if(self::isValidCurrency($v))
		{
			$this->{__FUNCTION__} = new fMoney($v);
			return $this;
		}else
		{
			throw new Exception('Price must be a valid integer or string');
		}
	}

	public function color(Array &$v = null)
	{
		if(is_null($v))
		{
			return $this->{__FUNCTION__};
		}

		if(self::isValidColorSet($v))
		{
			$this->{__FUNCTION__} += $v;
			return $this;
		}else
		{
			throw new Exception('Color must be a valid array');
		}
		
	}

	public function options(&$k = null,&$v = null)
	{
		if(is_string($k) && !empty($k) && is_null($v))
		{
			if(array_key_exists($k,$this->{__FUNCTION__}))
			{
				return $this->{__FUNCTION__}[$k];
			}else
			{
				throw new Exception('Option "' . $k . '" does not exist');
			}
		}

		if(self::isValidKeyValuePair($k,$v))
		{
			$this->{__FUNCTION__}[$k] = $v;
			return $this;
		}else
		{
			throw new Exception('Option name must be a string and value must be an integer or string');
		}
	}

	public static function isValidCurrency(&$v)
	{
		return ((is_int($v) && $v > 0) || preg_match('/^\$?\d+\.\d{1,2}$/',$v));
	}

	public static function isValidId(&$v)
	{
		return preg_match('/^[A-z0-9_]{3,}$/',$v);
	}

	public static function isValidKeyValuePair(&$k,&$v)
	{
		return (is_string($k) && !empty($k)) && (is_int($v) || (is_string($v)  && !empty($v)));
	}

	public static function isValidColorSet(Array &$a)
	{
		if(!empty($a))
		{
			foreach($a as $v)
			{
				if(empty($v) || array_keys($v) !== self::$REQUIRED_COLOR_KEYS)
				{
					return false;
				}
			}
			return true;
		}
		return false;
	}

	public static function isValidOptionSet(Array &$a)
	{
		if(!empty($a))
		{
			foreach($a as $v)
			{
				if(empty($v) || array_keys($v) !== self::$REQUIRED_OPTION_KEYS)
				{
					return false;
				}
			}
			return true;
		}
		return false;
	}

	function __toString()
	{
		return $this->name;
	}
}

?>