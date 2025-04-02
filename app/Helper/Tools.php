<?php

namespace App\Helper;

use App\Classes\Constant;
use App\Data\FileType;
use Exception;
use Transliterator;

abstract class Tools
{
    public static function checkInternet(string $domain = 'www.google.com', int $port = 80): bool
    {
        $result = false;
        try {
            $connected = @fsockopen($domain, $port); //website, port  (try 80 or 443)
            if ($connected) {
                fclose($connected);
                $result = true;
            }

            return $result;
        } catch (Exception $ex) {
            echo 'Error '.$ex->getMessage();

            return $result;
        }
    }

    public static function buildFileName(string $fileType, string $fileExtension = Constant::PNG): string
    {
        return FileType::getLabel($fileType).'_'.uniqid().'.'.$fileExtension;
    }

    public static function nextPosition(string $subCh, string $ch): int
    {
        return (int) substr($ch, strpos($ch, $subCh) + strlen($subCh)) + 1;
    }

    public static function makeAcronym(string $phrase): string
    {
        $removalWords = ['et', "'", 'à', 'après', 'les', 'au', 'aux', 'du', 'des', 'chez', 'dans', 'de', 'entre', 'jusque', 'hors', 'par', 'pour', 'sans', 'vers', 'de', 'la', 'des', "l'", "d'", 'en', "(", ")"];

        $_acronym = '';

        if (empty($phrase)) {
            return $_acronym;
        }
        $retrievedWords = explode(' ', $phrase);

        foreach ($retrievedWords as $word) {
            if (! in_array($word, $removalWords)) {
                if ($_word = Transliterator::create('NFD; [:Nonspacing Mark:] Remove; NFC')
                    ->transliterate($word)
                ) {
                    if (str_contains($_word, "'")) {
                        $_word = explode("'", $_word)[1];
                    }

                    if (! empty($_word)) {
                        $_acronym .= $_word[0];
                    }
                }
            }
        }

        return strtoupper($_acronym);
    }
}
