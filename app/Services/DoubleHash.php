<?php

namespace App\Services;

class DoubleHash
{
    protected string $pepper;
    protected int $hashLength;

    public function __construct()
    {
        $this->pepper = env ('DOUBLE_HASH_PEPPER', 'valor_defecto_xing');

        $this->hashLength = 32;
    }

    public function make(string $data): string
    {
        $primerHash = hash('sha256', $data, true);

        $doubleHash = \sodium_crypto_generichash(
            $primerHash . $this->pepper,
            '',
            $this->hashLength
        );

        return bin2hex($doubleHash);
    }

    public function check(string $data, string $hexHash): bool
    {
        $hashBin = hex2bin($hexHash);
        $primerHash = hash('sha256', $data, true);

        $doubleHash = \sodium_crypto_generichash(
            $primerHash . $this->pepper,
            '',
            $this->hashLength
        );

        return hash_equals($doubleHash, $hashBin);
    }
}
