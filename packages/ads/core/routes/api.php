<?php

Route::get('/', function () {
    return response()->error('', ['hello core']);
});
