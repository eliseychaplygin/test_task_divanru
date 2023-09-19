<?php

declare(strict_types = 1);

class ExchangeRates
{
    private $exchangeRates = [
        'eur' => ['usd' => 1, 'rub' => 80],
        'usd' => ['eur' => 1, 'rub' => 70],
        'rub' => ['eur' => 0.0125, 'usd' => 0.0143]
    ];

    public function getRate(string $fromCurrency, string $toCurrency): float
    {
        if (!isset($this->exchangeRates[$fromCurrency]) || !isset($this->exchangeRates[$toCurrency])) {
            throw new InvalidArgumentException("Недопустимая валюта");
        }
        return $this->exchangeRates[$fromCurrency][$toCurrency];
    }

    public function setRate(string $fromCurrency, string $toCurrency, float $rate): void
    {
        if ($rate <= 0) {
            throw new InvalidArgumentException("Недопустимое значение курса");
        }
        if (!isset($this->exchangeRates[$fromCurrency]) || !isset($this->exchangeRates[$toCurrency])) {
            throw new InvalidArgumentException("Недопустимая валюта");
        }
        $this->exchangeRates[$fromCurrency][$toCurrency] = round($rate, 4);
        $this->exchangeRates[$toCurrency][$fromCurrency] = round(1 / $rate, 4);
    }
}