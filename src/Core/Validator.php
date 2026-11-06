<?php
namespace App\Core;

use App\Exceptions\ValidationException;

class Validator
{
    public static function validate(array $data, array $rules): void
    {
        $errors = [];

        foreach ($rules as $field => $ruleString) {
            $ruleList = explode('|', $ruleString);
            $value = $data[$field] ?? null;

            foreach ($ruleList as $rule) {
                if ($rule === 'required' && (is_null($value) || $value === '')) {
                    $errors[$field][] = "Field $field wajib diisi";
                }

                if ($rule === 'numeric' && !is_null($value) && $value !== '' && !is_numeric($value)) {
                    $errors[$field][] = "Field $field harus berupa angka";
                }

                if (str_starts_with($rule, 'min:')) {
                    $min = (int) explode(':', $rule)[1];
                    if (!is_null($value) && is_numeric($value) && (int)$value < $min) {
                        $errors[$field][] = "Field $field minimal bernilai $min";
                    }
                }
            }
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
    }
}