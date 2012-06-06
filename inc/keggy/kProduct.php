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
	private static $DEFAULT_QUANTITY     = 0;
	private static $DEFAULT_PRICE        = 0;
	private static $DEFAULT_PRODUCT_TYPE = 'Generic';

	private $name,
			$type,
			$id,
			$quantity,
			$price,
			$options;
	
	function __construct($name = null,$type = null,$id = null,$price = null,Array $options = null)
	{
		$this->name     = $name;
		$this->type     = is_string($type) && $type ? $type : self::$DEFAULT_PRODUCT_TYPE;
		$this->id       = self::isValidId($id) ? $id : null;
		fMoney::setDefaultCurrency(USD);
		$this->price    = new fMoney(self::isValidCurrency($price) ? $price : self::$DEFAULT_PRICE);

		if(!empty($options))
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

	public function name($v = null)
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

	public function type($v = null)
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

	public function id($v = null)
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

	public function price($v = null)
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

	public function options($k = null,$v = null)
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
		}elseif(is_array($k) && !empty($k) && is_array($v) && !empty($v))
		{
			foreach($k as $key => $val)
			{
				echo '<pre>';
				var_dump($key,$val);
				echo '</pre>';
				#$this->{__FUNCTION__}($key,$val);
			}
			return $this;
		}elseif(is_array($k) && !empty($k) && is_null($v))
		{

		}else
		{
			throw new Exception('Option name must be a string and value must be an integer or string');
		}
	}

	public static function isValidCurrency($v)
	{
		return ((is_int($v) && $v > 0) || preg_match('/^\$?\d+\.\d{1,2}$/',$v));
	}

	public static function isValidId($v)
	{
		return preg_match('/^[A-z0-9_]{3,}$/',$v);
	}

	public static function isValidKeyValuePair($k,$v)
	{
		return (is_string($k) && !empty($k)) && (is_int($v) || ((is_string($v) || is_array($v))  && !empty($v)));
	}

	function __toString()
	{
		return $this->name;
	}
}

?>