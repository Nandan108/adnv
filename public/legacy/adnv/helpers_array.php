<?php

function array_arrGroupByKey($arrays, $keys, $asAssoc = true) {
    if (!is_iterable($arrays)) throw new \Error('Error: '.__FUNCTION__.'() arg #1 should be an iterable');
    if (!is_array($arrays)) $arrays = iterator_to_array($arrays);
    $keys = (array)$keys;
    $key = array_shift($keys);
    $out = [];
    if ($asAssoc) {
        foreach ($arrays as $k => $a) $out[$a[$key]][$k] = $a;
    } else {
        foreach ($arrays as $k => $a) $out[$a[$key]][] = $a;
    }
    if ($keys) foreach ($out as &$sub) $sub = array_arrGroupByKey($sub, $keys, $asAssoc);
    return $out;
}
function array_objGroupByKey($objects, $keys, $asAssoc = true) {
    if (!is_iterable($objects)) throw new \Error('Error: '.__FUNCTION__.'() arg #1 should be an iterable');
    if (!is_array($objects)) $objects = iterator_to_array($objects);
    $keys = (array)$keys;
    $key = array_shift($keys);
    $out = [];
    if ($asAssoc) {
        foreach ($objects as $k => $o) $out[$o->$key][$k] = $o;
    } else {
        foreach ($objects as $k => $o) $out[$o->$key][] = $o;
    }
    if ($keys) foreach ($out as &$sub) $sub = array_objGroupByKey($sub, $keys, $asAssoc);
    return $out;
}
function array_arrByKey($arrays, $key) {
    if (!is_iterable($arrays)) throw new \Error('Error: '.__FUNCTION__.'() arg #1 should be an iterable');
    if (!is_array($arrays)) $arrays = iterator_to_array($arrays);
    foreach ($arrays as $k => $a) $out[$a[$key]] = $a;
    return $out ?? [];
}
function array_objByKey($objects, $key) {
    if (!is_iterable($objects)) throw new \Error('Error: '.__FUNCTION__.'() arg #1 should be an iterable');
    if (!is_array($objects)) $objects = iterator_to_array($objects);
    foreach ($objects as $k => $o) $out[$o->$key] = $o;
    return $out ?? [];
}