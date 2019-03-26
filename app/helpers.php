<?php
/**
 * Created by PhpStorm.
 * User: ngduy
 * Date: 3/16/19
 * Time: 7:46 PM
 */

if(!function_exists("disableForeignKey")) {
    function disableForeignKey() {
        if(env('DB_CONNECTION') == 'mysql') {
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }
    }
}

if(!function_exists("enableForeignKey")) {
    function enableForeignKey() {
        if(env('DB_CONNECTION') == 'mysql') {
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}

if(!function_exists("getErrorResponse")) {
    function getErrorResponse($message, $detail, $code) {
        return [
            'errors' => [
                'message' => $message,
                'detail' => $detail,
                'code' => $code
            ]
        ];
    }
}