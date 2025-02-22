<?php
namespace App\Services;

class FileNameService
{
    private static function normalize(string $str)
    {
        $noWs      = preg_replace("/\s+/", '_', $str);
        $noSpecial = preg_replace("/\W/", '_', $noWs);
        return $noSpecial;
    }
    public function createName(array $portions, string $ext): string
    {
        $name = '';

        foreach ($portions as $portion) {
            if ($name === '') {
                $name = self::normalize($portion);
            } else {
                $name .= '-' . self::normalize($portion);
            }
        }

        return $name . '.' . $ext;
    }
}
