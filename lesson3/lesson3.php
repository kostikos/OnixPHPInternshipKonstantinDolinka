<?php

namespace lesson3;

class User
{

    private $name;
    private $balance;

    public function __construct($name, $balance)
    {
        $this->name = $name;
        $this->balance = $balance;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function getName()
    {
        return $this->name;
    }

    private function setBalance($balance)
    {
        $this->balance = $balance;
    }

    public function __toString()
    {

        return $this->name;
    }

    public function giveMoney($quantity, $recipient)
    {
        if ($quantity <= $this->getBalance()) {
            $this->setBalance($this->getBalance() - $quantity);
            $recipient->setBalance($recipient->getBalance() + $quantity);
            return "Пользователь $this->name перечислил $quantity пользователю $recipient->name<br>";
        } else {
            return "У пользователя $this->name недостаточно денег на счету!<br>";
        }
    }

    public function listProducts()
    {
        return Product::getProducts($this->getName());
    }

    public function sellProduct(User $buyer, Product $product)
    {
        $hasProduct = false;
        foreach ($this->listProducts() as $item) {
            if ($item->getName() == $product->getName()) {
                $hasProduct = true;
                break;
            }
        }

        if ($hasProduct) {
            if ($product->getOwner()->getName() != $this->getName()) {
                echo 'Пользователь ' . $this->getName() . ' не может продать продукт ' . $product->getName() . ' (' . $product->getPrice() . ')  так как он принадлежит ' . $product->getName();
            } else {
                if ($buyer->getBalance() < $product->getPrice()) {
                    echo "Пользователь {$buyer->getName()} не может перечислить {$product->getPrice()} пользователю {$this->getName()} так как имеет только {$buyer->getBalance()}";
                } else {

                    $product->setOwner($buyer);
                    $buyer->giveMoney($product->getPrice(), $this);
                    echo "Пользователь " . $this->getName() . " продал продукт " . $product->getName() . " (" . $product->getPrice() . " y.e. ) пользователю " . $buyer->getName() . "<br>";
                }
            }
        } else {
            echo "Зарегистрируйте продукт!<br>";
        }

    }
}

abstract class Product
{
    private $name;
    private $price;
    private $owner;
    private static $products = array();

    public function __construct($name, $price, $owner)
    {
        $this->name = $name;
        $this->price = $price;
        $this->owner = $owner;
    }

    public function __toString()
    {
        return "$this->name; $this->price y.e; Владелец $this->owner; <br>" . PHP_EOL;
    }

    private static function registerProduct($object)
    {
        if (!in_array($object, self::$products))
            self::$products[] = $object;
    }

    public static function getProducts($userName)
    {
        $result = array();
        foreach (self::$products as $product) {
            if ($userName == $product->owner->getName()) {
                $result[] = $product;
            }
        }

        return $result;
    }

    public static function createRandomProduct($user)
    {

        self::$products[] = Processor::createProcessor($user);
        self::$products[] = Ram::createRam($user);


    }

    public static function getOrderList()
    {
        return new class (self::$products) implements \Iterator {
            private $orderList = array();

            public function __construct($products)
            {
                $this->orderList = $products;
            }

            public function rewind()
            {
                reset($this->orderList);
            }

            public function current()
            {
                $orderList = current($this->orderList);
                return $orderList;
            }

            public function key()
            {
                $var = key($this->var);
                return $var;
            }

            public function next()
            {
                $orderList = next($this->orderList);
                return $orderList;
            }

            public function valid()
            {
                $key = key($this->orderList);
                $orderList = ($key !== NULL && $key !== FALSE);
                return $orderList;
            }
        };

    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setOwner(User $user)
    {
        $this->owner = $user;
    }

    public function getName()
    {
        return $this->name;
    }
}

class Processor extends Product
{
    private $frequency;

    public function __construct($name, $price, $owner, $frequency)
    {
        parent::__construct($name, $price, $owner);
        $this->frequency = $frequency;
    }

    public static function createProcessor($user)
    {
        return new Processor('name' . uniqid(), rand(5, 444), $user, 'frequency' . uniqid());
    }

}

class Ram extends Product
{
    private $type;
    private $memory;
    public $owner2;

    public function __construct($name, $price, $owner, $type, $memory)
    {
        parent::__construct($name, $price, $owner);
        $this->type = $type;
        $this->memory = $memory;
    }

    public static function createRam($user)
    {
        return new Ram('name' . uniqid(), rand(10, 555), $user, 'type' . uniqid(), 'memory' . uniqid());
    }

}


$Ivan = new User('Ivan', 300);
$Konan = new User("Konan", 5000);
echo "Пользователь {$Ivan->getName()},  баланс =  {$Ivan->getBalance()}<br>";
echo "Пользователь {$Konan->getName()},  баланс =  {$Konan->getBalance()}<br>";

$makeProduct1 = new Ram("HyperX", "150", $Ivan, "DDR3", "2000byte");
Product::createRandomProduct($Ivan);
Product::createRandomProduct($Konan);
echo "Ivan's products:<br>";
foreach ($Ivan->listProducts($Ivan) as $product) {
    echo $product;
}

$Ivan->sellProduct($Konan, $makeProduct1);
$Ivan->sellProduct($Konan, Product::getProducts($Ivan->getName())[0]);

foreach (Product::getOrderList() as $product) {
    echo $product;
}
echo "Пользователь {$Ivan->getName()} его баланс =  {$Ivan->getBalance()}<br>";
echo "Пользователь {$Konan->getName()} его баланс =  {$Konan->getBalance()}<br>";
