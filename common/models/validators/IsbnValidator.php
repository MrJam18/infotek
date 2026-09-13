<?php

declare(strict_types=1);

namespace common\models\validators;

use yii\validators\Validator;

/**
 * Validates ISBN-10 / ISBN-13 (hyphens and spaces are allowed), with checksum verification.
 */
class IsbnValidator extends Validator
{
    public bool $withChecksum = true;

    protected function validateValue($value): ?array
    {
        $isbn = strtoupper((string) $value);
        $isbn = (string) preg_replace('/[\s\-]/', '', $isbn);

        if (!preg_match('/^(?:\d{9}[\dX]|\d{13})$/', $isbn)) {
            return [$this->message ?: '{attribute} не является корректным ISBN.', []];
        }

        if (!$this->withChecksum) {
            return null;
        }

        if (strlen($isbn) === 10) {
            $sum = 0;
            for ($i = 0; $i < 9; $i++) {
                $sum += (int) $isbn[$i] * (10 - $i);
            }
            $check = (11 - ($sum % 11)) % 11;
            $checkChar = $check === 10 ? 'X' : (string) $check;

            return $isbn[9] === $checkChar
                ? null
                : [$this->message ?: '{attribute} не является корректным ISBN.', []];
        }

        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int) $isbn[$i] * (($i % 2 === 0) ? 1 : 3);
        }
        $check = (10 - ($sum % 10)) % 10;

        return (int) $isbn[12] === $check
            ? null
            : [$this->message ?: '{attribute} не является корректным ISBN.', []];
    }
}
