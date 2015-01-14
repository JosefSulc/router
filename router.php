<?php

class router {

    public static $path = 'routes.txt';
    private static $place = array();

    public static function route($get) {
        foreach (router::read() as $mask) {
            if (router::match($get, $mask)) {
                $controller = router::assignController($mask);
                return router::assignParams($get, $controller);
            }
        }
    }

    private static function read() {
        return explode("\n", file_get_contents(self::$path));
    }

    private static function match($get, $mask) {
        $mask = trim(preg_replace("/\|.*/", '', $mask));
        if (strpos($mask, '.')) {
            $pathArr = explode('/', $mask);
            $getArr = explode('/', $get);


            foreach ($pathArr as $key => $value) {
                if (strpos($value, '.') === 0) {
                    $getArr[$key] = $value;
                    self::$place[substr($value, 1)] = $key;
                }
            }

            if ($pathArr === $getArr) {
                return true;
            } else {
                self::$place = array();
            }
        } elseif ($get == trim($mask)) {
            return true;
        }
    }

    private static function assignController($mask) {
        $maskArr = explode(',', preg_replace('/\s+/', '', preg_replace("/.*\|/", '', $mask)));
        $controller = array();
        foreach ($maskArr as $value) {
            $pair = explode('=>', $value);
            $controller[$pair[0]] = $pair[1];
        }

        return $controller;
    }

    private static function assignParams($get, $controller) {
        if (!empty(self::$place)) {

            $getArr = explode('/', $get);

            foreach (self::$place as $key => $place) {
                $controller['params'][$key] = $getArr[$place];
            }
        }
        return $controller;
    }

}
