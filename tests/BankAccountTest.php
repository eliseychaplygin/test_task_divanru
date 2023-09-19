<?php

declare(strict_types=1);

require_once 'Currency.php';
require_once 'ExchangeRates.php';
require_once 'BankAccount.php';

use PHPUnit\Framework\TestCase;

class BankAccountTest extends TestCase
{
    private $bankAccount;
    private $exchangeRates;

    protected function setUp(): void
    {
        $this->exchangeRates = new ExchangeRates();
        $usd = new Currency('usd');
        $this->bankAccount = new BankAccount($usd, $this->exchangeRates);
    }

    public function testDepositToMainCurrency(): void
    {
        $usd = new Currency('usd');
        $this->bankAccount->deposit($usd, 100);
        $balance = $this->bankAccount->getBalance($usd);
        $this->assertEquals(100, $balance);
    }

    public function testWithdrawFromMainCurrency(): void
    {
        $usd = new Currency('usd');
        $this->bankAccount->deposit($usd, 100);
        $withdrawAmount = $this->bankAccount->withdraw($usd, 50);
        $balance = $this->bankAccount->getBalance($usd);
        $this->assertEquals(50, $withdrawAmount);
        $this->assertEquals(50, $balance);
    }

    public function testSetMainCurrency(): void
    {
        $eur = new Currency('eur');
        $this->bankAccount->addCurrency($eur);
        $usd = $this->bankAccount->getMainCurrency();
        $this->assertEquals('usd', $usd->getCurrencyCode());

        $this->bankAccount->setMainCurrency($eur);
        $eur = $this->bankAccount->getMainCurrency();
        $this->assertEquals('eur', $eur->getCurrencyCode());
    }

    public function testAddCurrency(): void
    {
        $eur = new Currency('eur');
        $this->bankAccount->addCurrency($eur);
        $currencies = $this->bankAccount->getListCurrencies();

        $eurFound = false;
        foreach ($currencies as $currency) {
            if ($currency->getCurrencyCode() === 'eur') {
                $eurFound = true;
                break;
            }
        }
        $this->assertTrue($eurFound);
    }

    public function testRemoveCurrency(): void
    {
        $eur = new Currency('eur');
        $this->bankAccount->addCurrency($eur);

        $eurFound = false;
        foreach ($this->bankAccount->getListCurrencies() as $currency) {
            if ($currency->getCurrencyCode() === 'eur') {
                $eurFound = true;
                break;
            }
        }
        $this->assertTrue($eurFound);

        $this->bankAccount->removeCurrency($eur);
        $eurFound = false;
        foreach ($this->bankAccount->getListCurrencies() as $currency) {
            if ($currency->getCurrencyCode() === 'eur') {
                $eurFound = true;
                break;
            }
        }
        $this->assertFalse($eurFound);
    }
}