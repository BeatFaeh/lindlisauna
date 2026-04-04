<?php

/**
 * Decodiert HTML-Entities mehrfach, falls z. B. &amp;ouml; vorliegt.
 * Wandelt <br> in Zeilenumbrüche und entfernt HTML-Tags.
 */
function normalize_csv_cell($value)
{
    if ($value === null) {
        return '';
    }

    if (!is_string($value)) {
        return $value;
    }

    // <br> -> Zeilenumbruch
    $value = preg_replace('/<\s*br\s*\/?>/i', "\n", $value);

    // Mehrfach-Decoding bis stabil (max. 3 Durchläufe)
    $prev = null;
    $i = 0;
    while ($prev !== $value && $i < 3) {
        $prev = $value;
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $i++;
    }

    // HTML-Tags entfernen
    $value = strip_tags($value);

    // Whitespace bereinigen
    $value = trim($value);

    // Valides UTF-8 sicherstellen
    $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');

    // [at] durch @ ersetzen
    $value = str_replace('[at]', '@', $value);

    return $value;
}