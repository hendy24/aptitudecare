<?php

abstract class CMS_Cart_Session extends CMS_Table {
	public static $table = "cart_session";
	public static $inAdmin = false;
	protected static $enableCreateStructure = true;
	protected static $metadata = array();

	public static function initCart() {

		// first, see if the user has a cookie on his machine. if he does,
		// that cookie merely contains the pubid of a cart_session record.
		$cart_id = $_COOKIE[APP_NAME . "_cart_session"];
		$cls = get_called_class();

		if ($cart_id != '') {
			// cookie was found. load it up.
			$obj = new $cls($cart_id);
		} else {
			// no cookie was found. make a new one.
			$obj = new $cls;
			$obj->pubid = generate_pubid();
			$obj->datetime = datetime();
			$obj->data = array(
				"cart" => array()
			);
			
			// try to save... won't actually write to DB if cart is empty, but that is okay.
			$obj->save();
		}
		
		// return the cart object.
		return $obj;
	}

	abstract public function processPayment($cardNumber, $cardExpiration, $cardCVV);

	public static function storeToCookie($obj) {
		setcookie(APP_NAME . "_cart_session", $obj->pubid, time()+60*60*24*30, '/', COOKIE_DOMAIN);
	}

	public function save() {
		// before we write to the DB, put the data aside.
		$data = $this->data;

		// now serialize the data in preparation for writing
		$this->data = serialize($this->data);

		// write the timestamp
		$this->datetime = datetime();
		
		// write to the DB if we have actually utilized the cart
		if ($data["cart"] !== false && is_array($data["cart"]) && count($data["cart"]) > 0) {
			parent::save();
		}
		
		// put the unserialized data back so we can use it in the app
		$this->data = $data;


		static::storeToCookie($this);
	}


	public function load($id) {
		// load from the DB
		parent::load($id);

		// unserialize the data so we can use it in the app
		$this->data = unserialize($this->data);

	}
	
	public function itemCount() {
		return count($this->getCart());
	}

	public function getCart() {
		$cart = $this->data["cart"];
		if ($cart === false) {
			return array();
		}
		return $cart;
	}

	public function count() {
		return count($this->data["cart"]);
	}
	
	public function getData() {
		return $this->data;
	}

	// data and other can actually be anything.
	public function addToCart($data, $unit_price = 0, $quantity = 1, $other = false) {
		// generate a uniqud ID for this item
		$itemId = generate_pubid();

		// prepare contents
		$contents = array(
			"data" => $data,
			"unit_price" => $unit_price,
			"quantity" => $quantity,
			"other" => $other
		);
		
		$_data = $this->data;
	
		// initialize cart if this is the first item
		if ($_data === false) {
			$_data["cart"] = array();
		}
		
		// place in the cart
		$_data["cart"][$itemId] = $contents;

		// put the data back in the obj
		$this->data = $_data;
		$this->save();

	}

	public function setQuantity($itemId, $quantity) {
		if ($quantity == 0) {
			$this->removeFromCart($itemId);
			return true;
		}
		$_data = $this->data;
		if (is_array($_data["cart"][$itemId])) {
			$_data["cart"][$itemId]["quantity"] = $quantity;
		}
		$this->data = $_data;
		$this->save();
	}

	public function removeFromCart($itemId) {
		$_data = $this->data;

		if (isset($_data["cart"][$itemId])) {
			unset($_data["cart"][$itemId]);
		}

		$this->data = $_data;
		$this->save();
	}

	public function getItem($itemId) {
		return $this->data["cart"][$itemId];	
	}
	
	public function setItem($itemId, $bundle) {
		$_data = $this->data;
		$_data["cart"][$itemId] = $bundle;
		$this->data = $_data;
		$this->save();
	}
	
	public function getItemCost($itemId) {
		return $this->data["cart"][$itemId]["unit_price"] * $this->data["cart"][$itemId]["quantity"];
	}

	public function getAllItemsCost() {
		$cost = 0;
		foreach ($this->data["cart"] as $itemId => $contents) {
			$cost += $this->getItemCost($itemId);
		}
		return $cost;
	}

	public function getTotal() {
		$tax = cart()->getTax($this->getCheckoutVar("billing_state"), $this->getAllItemsCost());
		$shipping = cart()->getShipping($this->getCheckoutVar("shipping_state"));
		return $tax + $shipping + $this->getAllItemsCost();
	}

	public function setCheckoutVar($key, $val) {
		$_data = $this->data;
		$_data["checkout"][$key] = $val;
		$this->data = $_data;
		$this->save();
	}

	public function getCheckoutVar($key) {
		$_data = $this->data;
		return $_data["checkout"][$key];
	}
	
	public function clearCart() {
		// clear data
		$this->data = array();
		
		// drop cookie
		setcookie(APP_NAME . "_cart_session", $this->pubid, time() - 3600, '/', COOKIE_DOMAIN);
		
		// delete the cart from the table
		static::delete($this);
	}

}