<?php

if (!function_exists('getStackAsLines')) {
    function getStackAsLines($showLines=999, $ignore_count=1, $filter=null) {
        $lines = array_slice(debug_backtrace(), $ignore_count, $showLines);
        $count = $ignore_count;
        return array_reduce($lines,
            function($out, $l) use ($filter, &$count) {
                $count++;
                $fileLine = isset($l['file']) ? "$l[file]:$l[line]" : '';
                return $out . (($filter && !$filter($l, $count)) ? '' : "$fileLine\n");
            }, '');
    }
}

/** Debug Dump */
if (!function_exists('debug_dump')) {
    function debug_dump($dumpData='', $showStackLines=3, $ignoreCount=0, $stackLinesFilter=null) {
        if (php_sapi_name() !== 'cli') echo "<pre style='text-wrap:wrap'>";
        print_r($dumpData);
        echo "\n".getStackAsLines($showStackLines, $ignoreCount+1, $stackLinesFilter);
        if (php_sapi_name() !== 'cli') echo "</pre>\n";
    }
}

if (!function_exists('dd')) {
    function dd(...$vars) {
        debug_dump($vars, 10);
    }
}

/** Debug Var-Dump */
if (!function_exists('dvd')) {
    function dvd($param='', $showStackLines=1, $ignore_count=1) {
        if (php_sapi_name() !== 'cli') echo "<pre>";
        var_dump($param);
        echo "\n".getStackAsLines($showStackLines, $ignore_count);
        if (php_sapi_name() !== 'cli') echo "</pre>\n";
    }
}