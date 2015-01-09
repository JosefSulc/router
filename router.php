<?php

class router {

    private static $place = array();

    public static function route($get) {
        foreach (router::read() as $mask) {
            if (router::match($get, $mask)) {
                $controller = router::assignController($mask);
                self::$place = array_unique(self::$place);
                $controller = router::assignParams($get, $controller);

                return $controller;
            }
        }
    }

    private static function read() {
        $file = explode("\n", file_get_contents('routes.txt'));
        return $file;
    }

    private static function match($get, $mask) {
        if (strpos($mask, '.')) {
            $pathArr = explode('/', preg_replace("/\|.*/", '', $mask));
            $getArr = explode('/', $get);
            $places = array();

            foreach ($pathArr as $key => $value) {
                if (preg_match("/.\d/", $value)) {
                    $getArr[$key] = $value;
                    self::$place = $key;
                }
            }

            return $pathArr === $getArr;
        } elseif ($get == trim(preg_replace("/\|.*/", '', $mask))) {
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

            foreach (self::$place as $place) {
                $controller['params'][] = $getArr[$place];
            }
        }
        return $controller;
    }

}

print_r(router::route('hue/kai/ser'));
