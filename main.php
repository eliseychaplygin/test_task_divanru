<?php

declare(strict_types=1);

require_once 'Currency.php';
require_once 'ExchangeRates.php';
require_once 'BankAccount.php';


$rub = new Currency('rub', 1);
$usd = new Currency('usd', 70);
$eur = new Currency('eur', 80);
$rates = new ExchangeRates();

$account = new BankAccount($rub, $rates);
$account->addCurrency($usd);
$account->addCurrency($eur);
print_r($account->getListCurrencies());
$account->deposit($rub, 1000);
$account->deposit($eur, 50);
$account->deposit($usd, 50);
echo $account->getBalance() . ' rub'. PHP_EOL;
echo $account->getBalance($usd) . ' usd' . PHP_EOL;
echo $account->getBalance($eur) . ' eur' . PHP_EOL;
$account->deposit($rub, 1000);
$account->deposit($eur, 50);
$account->withdraw($usd, 10);
echo $account->getBalance() . ' rub' . PHP_EOL;
echo $account->getBalance($usd) . ' usd' . PHP_EOL;
echo $account->getBalance($eur) . ' eur' . PHP_EOL;
$account->setRate('eur', 'rub', 150);
$account->setRate('usd', 'rub', 100);
echo $account->getBalance() . ' rub' . PHP_EOL;
$account->setMainCurrency($eur);
echo $account->getBalance($rub) . ' rub' . PHP_EOL;
echo $account->getBalance($usd) . ' usd' . PHP_EOL;
echo $account->getBalance($eur) . ' eur' . PHP_EOL;
echo $account->getBalance() . ' eur' . PHP_EOL;
$money = $account->withdraw($rub, 1000);
$account->deposit($eur, $money, $rub);
echo $account->getBalance() . ' eur' . PHP_EOL;
$account->setRate('eur', 'rub', 120);
echo $account->getBalance() . ' eur' . PHP_EOL;
$account->setMainCurrency($rub);
$account->removeCurrency($eur);
$account->removeCurrency($usd);
print_r($account->getListCurrencies());
echo $account->getBalance() . ' rub' . PHP_EOL;
