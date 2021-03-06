<?php
namespace lesson1;
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

    public function printStatus()
    {
        echo "У пользователя  $this->name сейчас на счету " . $this->getBalance() . "<br>";
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

$Vasya = new User("VASILII", 150);
$Petja = new User("Petja", 1500);

$Vasya->printStatus();
$Petja->printStatus();
echo $Vasya->giveMoney(100, $Petja);
$Vasya->printStatus();
$Petja->printStatus();
echo $Vasya->giveMoney(200, $Petja);
$Vasya->printStatus();
$Petja->printStatus();
?>