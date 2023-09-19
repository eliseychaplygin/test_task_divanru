<?php

declare(strict_types=1);

require_once 'ExchangeRates.php';

use PHPUnit\Framework\TestCase;

class ExchangeRatesTest extends TestCase
{
    public function testGetRateValid(): void
    {
        $exchangeRates = new ExchangeRates();

        $eurUsdRate = $exchangeRates->getRate('eur', 'usd');
        $this->assertSame(1.0, $eurUsdRate);

        $usdRubRate = $exchangeRates->getRate('usd', 'rub');
        $this->assertSame(70.0, $usdRubRate);
    }

    public function testGetRateInvalid(): void
    {
        $exchangeRates = new ExchangeRates();

        $this->expectException(InvalidArgumentException::class);
        $exchangeRates->getRate('eur', 'INVALID_CURRENCY');
    }

    public function testSetRateValid(): void
    {
        $exchangeRates = new ExchangeRates();

        $newEurUsdRate = 1.2;
        $exchangeRates->setRate('eur', 'usd', $newEurUsdRate);
        $retrievedEurUsdRate = $exchangeRates->getRate('eur', 'usd');
        $this->assertSame($newEurUsdRate, $retrievedEurUsdRate);

        $retrievedUsdEurRate = $exchangeRates->getRate('usd', 'eur');
        $expectedUsdEurRate = round(1 / $newEurUsdRate, 4); // Rounded as per initial exchange rates
        $this->assertSame($expectedUsdEurRate, $retrievedUsdEurRate);
    }

    public function testSetRateInvalid(): void
    {
        $exchangeRates = new ExchangeRates();

        $this->expectException(InvalidArgumentException::class);
        $exchangeRates->setRate('eur', 'INVALID_CURRENCY', 2.0);
    }

    public function testSetRateInvalidRate(): void
    {
        $exchangeRates = new ExchangeRates();

        $this->expectException(InvalidArgumentException::class);
        $exchangeRates->setRate('eur', 'usd', 0);
    }
}