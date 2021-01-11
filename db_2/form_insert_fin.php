<?php

$error_flg = false;

// パラメータの一蘭
$validate_params = array('name', 'post', 'address', 'birthday_yy', 'birthday_mm', 'birthday_dd');
// データを取得する
foreach($validate_params as $p) {
    $user_input_data[$p] = (string)@$_POST[$p];

    // 必須チェック
    if('' === $user_input_data[$p]){
        $error_flg = true;
    }
}

// 確認
var_dump($user_input_data);
// 必須チェック
var_dump($error_flg);