<?php

namespace src\config;

class config {

    public static function get($param){

        $list=[

            //this object main key
            'mainFunctionMethod'   =>'get'

        ];

        return self::configOut($param,$list);

    }

    public static  function configOut($param,$list){


        if(array_key_exists($param,$list)){
            return $list[$param];
        }
        else
        {
            return 'config param key not found';
        }
    }
}
