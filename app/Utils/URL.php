<?php
namespace App\Utils;

class URL
{
    private static $currentURL;
    private $url;

    function __construct($url = null)
    {
        if ($url !== null) {
            if ($url instanceof URL) {
                $this->url = $url->url;
            } else {
                $this->url          = parse_url($url);
                $this->url['query'] = empty($this->url['query'])
                    ? []
                    : self::cgi_parse_str($this->url['query']);
            }
        } else {
            if (!self::$currentURL) {
                if (php_sapi_name() === 'cli') return;
                preg_match('/^(.*?)(?:\?(.*?)(?:#(.*))?)?$/', $q = $_SERVER['REQUEST_URI'], $matches);
                list(, $path, $query, $fragment) = array_merge($matches, array_fill(0, 4, null));

                self::$currentURL = array(
                    'scheme'   => 'http' . (empty($_SERVER['HTTPS']) ? '' : 's'),
                    'host'     => $_SERVER['HTTP_HOST'],
                    'port'     => $_SERVER['SERVER_PORT'] == 80 ? null : $_SERVER['SERVER_PORT'],
                    'path'     => $path,
                    'query'    => self::cgi_parse_str($query),
                    'fragment' => $fragment,
                );
            }
            $this->url = self::$currentURL;
        }
    }

    private function part($p, $name, $s = '')
    {
        return !empty($this->url[$name]) ? $p . $this->url[$name] . $s : '';
    }

    function __toString()
    {
        $path = $this->url['path'] ?? '';
        return ($base = $this->getBase())
            . ($path ? ($path[0] == '/' || !$base ? '' : '/') . $path : '')
            . (empty($this->url['query']) ? '' : '?' . http_build_query($this->url['query']))
            . $this->part('#', 'fragment');
    }

    function getBase()
    {
        return $compactUrl =
            $this->part('', 'scheme', ':')
            . $this->part('//', 'host')
            . $this->part(':', 'port');
    }

    public static function get($url = null): URL
    {
        return new URL($url);
    }
    public static function getRelative($url = null): URL
    {
        return (new URL($url))->setRelative();
    }

    public function rmParam(): URL
    {
        foreach (func_get_args() as $k)
            unset($this->url['query'][$k]);
        return $this;
    }

    public function setParam($k, $v): URL
    {
        if ($v === null)
            $this->rmParam($k);
        else
            $this->url['query'][$k] = $v;
        return $this;
    }

    public function setParams(array $params): URL
    {
        foreach ($params as $k => $v) {
            $this->setParam($k, $v);
        }
        return $this;
    }

    public function filterParams($callback): URL
    {
        $this->url['query'] = array_filter($this->url['query'], $callback, ARRAY_FILTER_USE_BOTH);
        return $this;
    }

    /**
     * returns assoc array of parameters present on URL
     * @param ...$reqParamNames string[] Restricts returned assoc to only specified params.
     * May be provided as an array or ...rest params.
     * If no arguments are provided, all params are returned.
     */
    public function getParams(...$reqParamNames)
    {
        $reqParamNames = is_array($reqParamNames[0] ?? null)
            ? $reqParamNames = $reqParamNames[0]
            : ($reqParamNames ?: array_keys($this->url['query'] ?? []));
        return array_intersect_key($this->url['query'] ?? [], array_flip($reqParamNames));
    }

    /**
     * same as getParams() except that it returns a concatenated string of:
     * "<input type='hidden' name='$k' value='$v' />"
     */
    public function getParamsAsHiddenInputs()
    {
        $params = call_user_func_array([$this, 'getParams'], func_get_args());
        $inputs = explode('&', urldecode(http_build_query($params)));
        $out    = '';
        foreach ($inputs as $kv) {
            [$k, $v] = explode('=', $kv);
            $k       = htmlentities($k);
            $v       = htmlentities($v);
            $out .= "<input type='hidden' name='$k' value='$v' />";
        }
        return $out;
    }

    public function getParam($paramName)
    {
        return $this->url['query'][$paramName];
    }

    public function hasParamRegex($re)
    {
        foreach ($this->url['query'] as $k => $v) {
            if (preg_match($re, $k))
                return true;
        }
    }

    public function rmParamRegex($re): URL
    {
        foreach ($this->url['query'] as $k => $v) {
            if (preg_match($re, $k))
                unset($this->url['query'][$k]);
        }
        return $this;
    }

    public function resetQuery($query = []): URL
    {
        $this->url['query'] = (array)($query ?: []);
        return $this;
    }

    public function setPath($path): URL
    {
        $this->url['path'] = ltrim($path, '/');
        return $this;
    }

    /**
     * change current path as if it were 'cd' on the command line
     * supose we are on "p/a/t/h"
     * - cdPath("foo") => "p/a/t/h/foo"
     * - cdPath("../foo") => "p/a/t/foo"
     * - cdPath("../../foo") => "p/a/foo"
     * - cdPath("/foo") => "foo"
     */
    public function cdPath($relativePath): URL
    {
        $currentPath = $relativePath[0] == '/' ? []
            : ($this->url['path'] ? explode('/', $this->url['path']) : []);
        foreach (explode('/', $relativePath) as $step) {
            if ($step == '..')
                array_pop($currentPath);
            else
                array_push($currentPath, $step);
        }
        $this->url['path'] = implode('/', $currentPath);
        return $this;
    }

    public function setRelativePath($path): URL
    {
        return $this->cdPath("../$path");
    }

    public function getPath()
    {
        return $this->url['path'] ?? '';
    }

    public function addSlash(): URL
    {
        if (substr($this->url['path'], -1) !== '/')
            $this->url['path'] .= '/';
        return $this;
    }

    public function setHost($host): URL
    {
        $this->url['host'] = $host;
        return $this;
    }

    public function getHost()
    {
        return $this->url['host'];
    }

    public function setRelative($path = null): URL
    {
        if ($path !== null) $this->setRelativePath($path);
        return $this->setScheme(null)->setHost(null)->setPort(null);
    }

    // if $ssl is provided, sets $this->ssl, otherwise returns current ssl state
    public function ssl($ssl = null)
    {
        if ($ssl === null)
            return $this->url['scheme'] == 'https';
        $this->url['scheme'] = 'http' . ($ssl ? 's' : '');
        return $this;
    }

    // if $fragment is provided, sets $this->url[fragment], otherwise returns current fragment
    public function fragment($fragment = null): URL
    {
        if ($fragment === null)
            return $this->url['fragment'];
        $this->url['fragment'] = $fragment;
        return $this;
    }

    // set[Scheme|Host|Path|Query|Fragment]
    public function __call($call, $params)
    {
        if (!preg_match('/^(get|set)(scheme|port|host|path|query|fragment)/i', $call, $matches)) {
            trigger_error("$call is not a method of class " . __CLASS__);
            return null;
        }
        list(, $verb, $part) = $matches;

        if (!preg_match('/scheme|port|host|path|query|fragment/', ($part = strtolower($part)))) {
            trigger_error("$call is not a method of class " . __CLASS__);
            return null;
        }

        //debug::print_r(compact('call', 'params', 'matches', 'part'));
        switch ($verb) {
            case 'get':
                return $this->url[$part];
            case 'set':
                if ($part == 'query' && is_string($params[0])) {
                    parse_str($params[0], $this->url[$part]);
                } else {
                    $this->url[$part] = $params[0];
                }
                return $this;
        }
    }

    public function clone(): URL
    {
        return clone $this;
    }

    public function redirect($httpResponseCode = 302)
    {
        $locationHeader = "Location: $this";
        header($locationHeader, true, $httpResponseCode);
        die();
    }

    public function redirectIf($condition, $httpResponseCode = 302)
    {
        if ($condition)
            $this->redirect($httpResponseCode);
    }

    /**
     * encode to base64 in a URL-compatible way
     * '+' and '/' characters are converted respectively to '-' and '_'
     */
    static function base64_encode($data)
    {
        return rtrim(strtr(\base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * decode output from URL::base64_encode()
     */
    static function base64_decode($data)
    {
        return \base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    /**
     * returns hash from a ctime or a file path
     * returns 404 if file not found
     */
    public static function getCacheHash($ctime_or_filepath)
    {
        if (!is_numeric($ctime = $filepath = $ctime_or_filepath)) {
            $ctime = file_exists($filepath) ? filectime($filepath) : null;
        }
        return is_null($ctime)
            ? 404
            : self::base64_encode(pack('N', $ctime - strtotime('2011-01-01')));
    }

    /**
     * Assumes $this->url['path'] is a file, and adds this file's
     * cache hash as a parameter to the current URL.
     */
    public function addCacheHash($basePath, $cacheParamName = 't'): URL
    {
        $hash = self::getCacheHash($basePath . $this->url['path']);
        $this->setParam($cacheParamName, $hash);
        return $this;
    }

    public static function sluggify($name)
    {
        return trim(
            iconv(
                'UTF-8',
                'UTF-7//TRANSLIT',
                preg_replace(
                    '/-+/',
                    '-',
                    mb_ereg_replace(
                        '[^-a-z0-9]*',
                        '',
                        preg_replace(
                            '/[- &]+/',
                            '-',
                            mb_strtolower(
                                self::removeAccents(
                                    trim(
                                        $name,
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            '-',
        );
    }

    public static function removeAccents($string)
    {
        $table = array(
            'Š'        => 'S',
            'š'        => 's',
            'Đ'        => 'Dj',
            'đ'        => 'dj',
            'Ž'        => 'Z',
            'ž'        => 'z',
            'Č'        => 'C',
            'č'        => 'c',
            'Ć'        => 'C',
            'ć'        => 'c',
            'À'        => 'A',
            'Á'        => 'A',
            'Â'        => 'A',
            'Ã'        => 'A',
            'Ä'        => 'A',
            'Å'        => 'A',
            'Æ'        => 'A',
            'Ç'        => 'C',
            'È'        => 'E',
            'É'        => 'E',
            'Ê'        => 'E',
            'Ë'        => 'E',
            'Ì'        => 'I',
            'Í'        => 'I',
            'Î'        => 'I',
            'Ï'        => 'I',
            'Ñ'        => 'N',
            'Ò'        => 'O',
            'Ó'        => 'O',
            'Ô'        => 'O',
            'Õ'        => 'O',
            'Ö'        => 'O',
            'Ø'        => 'O',
            'Ù'        => 'U',
            'Ú'        => 'U',
            'Û'        => 'U',
            'Ü'        => 'U',
            'Ý'        => 'Y',
            'Þ'        => 'B',
            'ß'        => 'Ss',
            'à'        => 'a',
            'á'        => 'a',
            'â'        => 'a',
            'ã'        => 'a',
            'ä'        => 'a',
            'å'        => 'a',
            'æ'        => 'a',
            'ç'        => 'c',
            'è'        => 'e',
            'é'        => 'e',
            'ê'        => 'e',
            'ë'        => 'e',
            'ì'        => 'i',
            'í'        => 'i',
            'î'        => 'i',
            'ï'        => 'i',
            'ð'        => 'o',
            'ñ'        => 'n',
            'ò'        => 'o',
            'ó'        => 'o',
            'ô'        => 'o',
            'õ'        => 'o',
            'ö'        => 'o',
            'ø'        => 'o',
            'ù'        => 'u',
            'ú'        => 'u',
            'û'        => 'u',
            'ü'        => 'u',
            'ý'        => 'y',
            'þ'        => 'b',
            'ÿ'        => 'y',
            'Ŕ'        => 'R',
            'ŕ'        => 'r',
            '’'        => '\'',
            '&ccedil;' => 'c',
            '&eacute;' => 'e',
            '&egrave;' => 'e',
            '&ocirc;'  => 'o',
            '&iuml;'   => 'i',
            '&euml;'   => 'e',
            '&uuml;'   => 'u',
            '&ouml;'   => 'o',
            '&icirc;'  => 'i',
        );

        return strtr($string, $table);
    }

    /**********************/

    /**
     * If multiple fields of the same name exist in a query string, CGI standard would read them into
     * an array, but PHP (and built-in parse_str()) silently overwrites them.
     * cgi_parse_str() is a remedy for this.
     */
    static function cgi_parse_str($str, &$arr = null)
    {
        # result array
        if (!$arr || !is_array($arr)) $arr = [];

        # split on outer delimiter
        $pairs = explode('&', $str ?? '');
        # build $pairs while keeping track in $pairsIdx of where we need to suffix '[]'
        $pairsIdxs = [];
        foreach ($pairs as $i => $p) {
            # split into name and value
            [$paramKey, $paramVal] = $pkv = explode('=', "$p=", 3);

            $pairs[$i] = [$paramKey, $paramVal];

            # keep track of values that will end up in arrays
            do {
                if (substr($paramKey, -2) === '[]') $paramKey = trim($paramKey, '[]');
                $pairsIdxs[$paramKey][] = "$pkv[0] => $paramVal";

            } while (preg_match('/^(.+)(\[[^]]*\])$/', $paramKey, $matches) ? ($paramKey = $matches[1]) : false);
        }

        # loop over
        foreach ($pairs as $i => &$nameVal) {
            [$name, $val] = $nameVal;
            if (
                count($pairsIdxs[$name] ?? []) > 1 &&
                substr($name, -2) !== '[]'
            ) {
                $name .= '[]';
            }
            $nameVal = "$name=$val";
        }

        parse_str(implode('&', $pairs), $arr);

        return $arr;
    }

}
