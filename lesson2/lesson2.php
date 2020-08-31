<?php

namespace lesson2;
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

    private function setBalance($balance)
    {
        $this->balance = $balance;
    }

    public function __toString()
    {
        //return "У пользователя  $this->name сейчас на счету " . $this->getBalance() . "<br>";
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
}

abstract class Product
{
    private $name;
    private $price;
    private $owner;
    private static $products = array();

    public function __construct($name, $price, &$owner)
    {
        $this->name = $name;
        $this->price = $price;
        $this->owner = $owner;
    }

    /**
     * перехватывам обращение к классу как к строоке и возвращааем нужные нам поля
     * @return string
     */
    public function __toString()
    {
        return "$this->name - $this->price y.e; Покупатель $this->owner; <br>" . PHP_EOL;
    }

    /**
     * Если нет совпадений, метод добавляет новую запись о покупке товара
     * @param $object ссылка на новый екземпляр класса с товарами
     */
    public static function registerProduct(&$object)
    {
        if (!in_array($object, self::$products))
            self::$products[] = &$object;
    }

    /**
     * Метот выводит список совершенных покупок, которые храняться в статическом свойсвте $products
     */
    public static function getOrderList()
    {
        /**
         * создаем абстрактный класс, передаем у него массив с покупками, подключаем интерфейс итератора
         */
        return new class (self::$products) implements \Iterator {
            private $orderList = array();

            //переопределяем статическое свойсвто для удобства ипсользования
            public function __construct($products)
            {
                $this->orderList = $products;

            }
            //start обязательные методы класса Итератор

            /**
             * перемотка в начало
             */
            public function rewind()
            {
                reset($this->orderList);
            }

            /**
             * текущий елемент
             */
            public function current()
            {
                $orderList = current($this->orderList);
                return $orderList;
            }

            /**
             * возвращает ключ
             */
            public function key()
            {
                $var = key($this->var);
                return $var;
            }

            /**
             * следующий елемент
             */
            public function next()
            {

                $orderList = next($this->orderList);
                return $orderList;
            }

            /**
             *  проверка ключа
             */
            public function valid()
            {
                $key = key($this->orderList);
                $orderList = ($key !== NULL && $key !== FALSE);
                return $orderList;
            }
            //end обязательные методы класса Итератор

            /**
             * Выводим список заказов,из-за того что ранее описалы метоыд __toString() обращаемся к объектам в масиве
             * как к строкам, в коде не используем так как в ТЗ нужно использовать итератор.
             *
             */
            public function printOrderList()
            {
                foreach ($this->orderList as $product) {
                    echo $product;
                }
            }


        };
    }

}

class Processor extends Product
{
    private $frequency;

    public function __construct($name, $price, &$owner, $frequency)
    {
        parent::__construct($name, $price, $owner);
        $this->frequency = $frequency;
    }
}

class Ram extends Product
{
    private $type;
    private $memory;

    public function __construct($name, $price, &$owner, $type, $memory)
    {
        parent::__construct($name, $price, $owner);
        $this->type = $type;
        $this->memory = $memory;
    }
}

$Ivan = new User('Ivan', 300);
$Konan = new User("Konan", 9000);
$pokuka1 = new Ram("HyperX", "150", $Ivan, "DDR3", "2000byte");
$pokuka2 = new Ram("GoodRam", "300", $Konan, "DDR2", "4000byte");
$pokuka3 = new Ram("Kingston", "240", $Konan, "SODDR", "1000byte");
$pokuka11 = new Processor("Kingston", "200", $Konan, "1600mHz");
Product::registerProduct($pokuka1);
Product::registerProduct($pokuka2);
Product::registerProduct($pokuka3);
Product::registerProduct($pokuka11);
Product::registerProduct($pokuka2);
//Product::getOrderList()->printOrderList();
foreach (Product::getOrderList() as $product) {
    echo $product;
}


?>