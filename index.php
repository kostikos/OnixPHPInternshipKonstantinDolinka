<?php

class User
{

    private $name;
    private $balance;

    public function __construct($name, $balance)
    {
        $this->name = $name;
        $this->balance = $balance;
    }

    public function printStatus()
    {
        echo "У пользователя  $this->name сейчас на счету  $this->balance<br>";
    }

    public function giveMoney($quantity, $payer)
    {
        if ($quantity <= $this->balance) {
            $this->balance -= $quantity;
            $payer->balance += $quantity;
            echo "Пользователь $this->name перечислил $quantity пользователю $payer->name<br>";
        } else {
            echo "У пользователя $this->name недостаточно денег на счету!<br>";
        }
    }


}

$Vasya = new User("VASILII", 200);
$Petja = new User("Petja", 6000);
$Vasya->printStatus();
$Petja->printStatus();
$Vasya->giveMoney(100, $Petja);
$Vasya->printStatus();
$Petja->printStatus();
$Vasya->giveMoney(200, $Petja);
$Vasya->printStatus();
$Petja->printStatus();
?>