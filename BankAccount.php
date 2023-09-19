<?php
declare(strict_types = 1);
class BankAccount
{
    private array $currencies;
    private Currency $mainCurrency;
    private array $balances;

    private ExchangeRates $rates;

    public function __construct(Currency $mainCurrency, ExchangeRates $rates)
    {
        $this->mainCurrency = $mainCurrency;
        $this->currencies[] = $this->mainCurrency;
        $this->balances[$this->mainCurrency->getCurrencyCode()] = 0;
        $this->rates = $rates;
    }

    public function addCurrency(Currency $currency): void
    {
        if (!in_array($currency, $this->currencies)) {
            $this->currencies[] = $currency;
            $this->balances[$currency->getCurrencyCode()] = 0.0;
        }
    }

    public function removeCurrency(Currency $currency): void
    {
        if ($currency->getCurrencyCode() === $this->mainCurrency->getCurrencyCode()) {
            throw new Exception('Удалить основную валюту счета нельзя!');
        }
        foreach ($this->currencies as $key => $value) {
            if ($value === $currency) {
                $money = $this->withdraw($currency, $this->balances[$currency->getCurrencyCode()]);
                $this->deposit($this->mainCurrency, $money, $currency);
                unset($this->currencies[$key]);
                unset($this->balances[$currency->getCurrencyCode()]);
            }
        }
    }

    public function getListCurrencies(): array
    {
        return $this->currencies;
    }

    public function getMainCurrency(): Currency
    {
        return $this->mainCurrency;
    }
    public function setMainCurrency(Currency $currency): void
    {
        foreach ($this->currencies as $item) {
            if ($item === $currency) {
                $this->mainCurrency = $currency;
                return;
            }
        }
        throw new Exception('Валюта не найдена');
    }

    public function deposit(Currency $currency, float $amount, ?Currency $convertCurrency = null): void
    {
        if (!in_array($currency, $this->currencies)) {
            throw new Exception("Валюта не найдена.");
        }
        if ($convertCurrency !== null) {
            $rate = $this->rates->getRate($convertCurrency->getCurrencyCode(), $currency->getCurrencyCode());
            $this->balances[$currency->getCurrencyCode()] += $amount * $rate;
            return;
        }
        $this->balances[$currency->getCurrencyCode()] += $amount;
    }

    public function withdraw(Currency $currency, float $amount): float
    {
        if (!in_array($currency, $this->currencies)) {
            throw new Exception("Валюта не найдена");
        }
        if ($this->balances[$currency->getCurrencyCode()] < $amount) {
            throw new Exception("Недостаточно средств");
        }
        $this->balances[$currency->getCurrencyCode()] -= $amount;
        return $amount;
    }

    public function getBalance(?Currency $currency = null): float
    {
        if ($currency === null) {
            $totalBalance = 0;
            foreach ($this->balances as $currencyCode => $balance) {
                if ($currencyCode !== $this->mainCurrency->getCurrencyCode()) {
                    $balance *= $this->rates->getRate($currencyCode, $this->mainCurrency->getCurrencyCode());
                }
                $totalBalance += $balance;
            }
            return round($totalBalance, 2);
        }

        $currencyCode = $currency->getCurrencyCode();
        if (!array_key_exists($currencyCode, $this->balances)) {
            throw new Exception("Валюта не найдена");
        }

        return round($this->balances[$currencyCode], 2);
    }

    public function setRate(string $fromCurrency, string $toCurrency, float $rate): void
    {
        if ($rate <= 0) {
            throw new InvalidArgumentException("Недопустимое значение курса");
        }
        $this->rates->setRate($fromCurrency, $toCurrency, $rate);
    }
}