<?php


/**
 * produces a date string according to given parameters
 *
 * @param string $format See ICU doc: https://unicode-org.github.io/icu/userguide/format_parse/datetime/#datetime-format-syntax
 * @param int|string|DateTime|null $time A timestamp or a string that strtotime() understands. Defaults to current time.
 * @param string $locale Defaults to Swiss-French locale
 * @return boolean|string
 */
function fmtDate($format = 'd MMM yyyy', int|string|DateTime|null $time = null, $locale = 'fr_CH'): bool|string {
    $time ??= time();
    if (!is_numeric($time)) {
        if (is_string($time)) $time = strtotime($time);
        elseif($time instanceof DateTime) $time = $time->getTimestamp();
    }
    $tz = new DateTimeZone(date_default_timezone_get());
    $dateTime = (new DateTime("@$time"))->setTimeZone($tz);
    $intlCal = \IntlCalendar::fromDateTime($dateTime);
    return IntlDateFormatter::formatObject($intlCal, $format, $locale);
}

/**
 * Add $days days to $date and return it as a new DateTime();
 *
 * @param [type] $date
 * @param integer $days
 * @return Datetime
 */
function dateAddDays(string $date, int $diffDays): DateTime {
    $str = ($diffDays >= 0 ? '+' : '')."$diffDays day";
    $newDate = (new DateTime($date))->modify($str);
    return $newDate;
}

function formatDate($date, $nbsp = true) {
    $date = fmtDate('dd MMM yyyy', $date);
    return $nbsp ? str_replace(' ', '&nbsp;', $date) : $date;
    // les lignes ci-dessous produisent un format de date comme l'ancienne version,
    // c'est à dire par exemple: "19 Avr 2023" et "20 Mar 2024" au lieu de "19 avr. 2023" et "20 mars 2024"
    // $date = explode(' ', fmtDate('dd MMM yyyy', $date));
    // $date[1] = ucfirst(substr($date[1], 0, 3));
    // return implode($nbsp ? '&nbsp;' : ' ', $date);
};

/**
 * Given an array of objects, returns a string of HTML "<option value='...'>...</option>..."
 * @param array $source list of objects to be outputed as &lt;option&gt;s
 * @param string|Closure $valueSource name of field where to get option's value or Closure($obj) { return $value; }
 * @param mixed $selectedVal
 * @param string|Closure $displaySource name of field where to get option's text or Closure($obj) { return $text; }
 * @param array|Closure $attrSource array of option's attributes (class, data-xxx, etc..) or Closure($obj) returns array { ... }
 * @return string
 */
function printSelectOptions($source, $valueSource=null, $selectedVal=null, $displaySource=null, $attrSource=null) {
    $displaySource ??= $valueSource;
    $options = [];
    foreach ($source as $opt) {
        $value = is_callable($valueSource)
            ? call_user_func($valueSource, $opt)
            : (!$valueSource ? $opt : (
                is_object($opt) ? $opt->$valueSource : (
                is_array($opt) ? $opt[$valueSource] : $opt
            )));
        //$selected = $selectedVal === $value ? ' selected' : '';
        $attributes = array_merge(['value' => $value],
            is_callable($attrSource)
                ? call_user_func($attrSource, $opt)
                : (array)($attrSource ?? [])
        );
        if (isset($selectedVal) && $selectedVal == $value ||
            (is_callable($selectedVal) && call_user_func($selectedVal, $opt))) {
            $attributes['selected'] = true;
        }
        $attr = implode('', array_map(
            fn($k, $v) => $v === true ? ' '.$k :
                (($v ?? false) === false ? '' : " $k=\"$v\""),
            array_keys($attributes),
            $attributes
        ));
        $text = is_callable($displaySource)
            ? call_user_func($displaySource, $opt)
            : (!$displaySource ? $opt : (
                is_object($opt) ? $opt->$displaySource : (
                is_array($opt) ? $opt[$displaySource] : $opt
            )));
        $options[] = "<option$attr>$text</option>";
    }
    return implode("\n", $options ?? []);
}