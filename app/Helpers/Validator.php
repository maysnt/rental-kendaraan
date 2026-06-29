<?php

namespace App\Helpers;

/**
 * Validator
 *
 * Validasi form sederhana berbasis rule string, mirip gaya Laravel tapi minimalis.
 * Contoh pemakaian:
 *   Validator::make(['nama' => $nama], ['nama' => 'required|min:3']);
 *
 * Semua method bersifat static karena class ini tidak menyimpan state apa pun.
 */
class Validator
{
    public static function make(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $ruleString) {
            $value     = $data[$field] ?? null;
            $rulesArr  = explode('|', $ruleString);

            foreach ($rulesArr as $rule) {
                $param = null;
                if (str_contains($rule, ':')) {
                    [$rule, $param] = explode(':', $rule, 2);
                }

                $gagal = match ($rule) {
                    'required' => self::required($value),
                    'email'    => self::email($value),
                    'numeric'  => self::numeric($value),
                    'min'      => self::min($value, (int) $param),
                    'max'      => self::max($value, (int) $param),
                    default    => false,
                };

                if ($gagal) {
                    $errors[$field] = self::pesan($field, $rule, $param);
                    break; // satu pesan error sudah cukup per field
                }
            }
        }

        return $errors;
    }

    private static function required(mixed $value): bool
    {
        return $value === null || trim((string) $value) === '';
    }

    private static function email(mixed $value): bool
    {
        if ($value === null || trim((string) $value) === '') {
            return false; // email opsional, dicek 'required' secara terpisah jika wajib
        }
        return filter_var($value, FILTER_VALIDATE_EMAIL) === false;
    }

    private static function numeric(mixed $value): bool
    {
        return !is_numeric($value);
    }

    private static function min(mixed $value, int $param): bool
    {
        return mb_strlen((string) $value) < $param;
    }

    private static function max(mixed $value, int $param): bool
    {
        return mb_strlen((string) $value) > $param;
    }

    private static function pesan(string $field, string $rule, ?string $param): string
    {
        $label = ucfirst(str_replace('_', ' ', $field));

        return match ($rule) {
            'required' => "{$label} wajib diisi.",
            'email'    => "{$label} harus berupa alamat email yang valid.",
            'numeric'  => "{$label} harus berupa angka.",
            'min'      => "{$label} minimal {$param} karakter.",
            'max'      => "{$label} maksimal {$param} karakter.",
            default    => "{$label} tidak valid.",
        };
    }
}
