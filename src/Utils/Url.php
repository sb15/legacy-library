<?php

class Utils_Url {

    public static function filterNameForUrl($name)
    {
        //A-Z, a-z, 0-9, -, ., _, ~, :, /, ?, #, [, ], @, !, $, &, ', (, ), *, +, ,, ; and =
        $name = str_replace(" ", "-", $name);
        $name = preg_replace("#[^-A-Za-z0-9А-Яа-яёЁ]#uis", "", $name);
        return $name;
    }

}