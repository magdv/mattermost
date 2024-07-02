<?php

declare(strict_types=1);

namespace App;

final class JsonHelper
{
    // Метод для форматирования массива в json, даже при ошибке он вернет строку с текстом с ошибкой. Полагаться на нго можно, если целостность данных не важна
    public static function jsonEncode(array|object $value, bool $convertEncoding = false): string
    {
        try {
            if (is_object($value)) {
                $value = (array)$value;
            }

            if ($convertEncoding) {
                $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
            }

            $result = json_encode($value, JSON_THROW_ON_ERROR | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
        } catch (\JsonException $e) {
            $result = '{"Не смогли декодировать данные в json": "' . $e->getMessage() . '"}';
        }

        return $result ?: '{"error": "jsonEncode Не смогли декодировать в json"}';
    }
}
