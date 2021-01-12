<?php

/*
 * ユーザからのform情報の取得とDBへのINSERT
 */

// ユーザ入力情報の取得
// --------------------------------------
// バッファリング
ob_start();

// セッションの開始
session_start();

// ユーザ入力情報を保持する配列を準備する
$user_input_data = array();

// 「パラメタの一覧」を把握
$params = array('name', 'post', 'address', 'birthday_yy', 'birthday_mm', 'birthday_dd');
// データを取得する
foreach($params as $p) {
    $user_input_data[$p] = (string)@$_POST[$p];
}
// 確認
//var_dump($user_input_data);

// ユーザ入力のvalidate
// --------------------------------------
//
$error_flg = false;
// エラーの詳細を入れる配列
$error_detail = array();

// 必須チェックを実装
$validate_params = array('name', 'post', 'address', 'birthday_yy', 'birthday_mm', 'birthday_dd');
foreach($validate_params as $p) {
    // 空文字(未入力)なら
    if ('' === $user_input_data[$p]) {
        // エラーフラグを立てる
        $error_flg = true;
        // 必須入力のみ入力エラー
        $error_detail["error_must_{$p}"] = true;
    }
}
// 型チェックを実装
// 郵便番号
/*
    \A: 行頭
    [0-9]{3}： [0から9までのいずれかの文字]を３回繰り返す
    [- ]?： [ハイフン、スペースのいずれかの文字]を０回ないし１回繰り返す
    [0-9]{4}： [0から9までのいずれかの文字]を４回繰り返す
    \z: 行末
*/
if (1 !== preg_match('/\A[0-9]{3}[- ]?[0-9]{4}\z/', $user_input_data['post'])) {
    // エラーフラグを立てる
    $error_flg = true;
    // 郵便番号のフォーマットエラー
    $error_detail["error_format_post"] = true;
}

// 誕生日
// 初めに、誕生日の年月日を「文字列」から「数値」に変換しておく
$int_params = array('birthday_yy', 'birthday_mm', 'birthday_dd');
foreach($int_params as $p) {
    $user_input_data[$p] = intval($user_input_data[$p]);
    //$user_input_data[$p] = (int)$user_input_data[$p]; // こちらの書き方でもよい
}
// PHPの標準関数を使って日付の妥当性をチェックする
if (false === checkdate($user_input_data['birthday_mm'], $user_input_data['birthday_dd'], $user_input_data['birthday_yy'])) {
    // エラーフラグを立てる
    $error_flg = true;
    // 誕生日のフォーマットエラー
    $error_detail["error_format_birthday"] = true;
}

// エラーが出た時、入力ページに遷移する
if(true === $error_flg){

    // エラー情報をセッションに入れて持ち回る
    $_SESSION['output_buffer'] = $error_detail;
    header('Location: ./form_insert.php');
    exit;
}

// 確認
// var_dump($error_flg);

// ダミーのOK
echo 'OK';