<?php
// возвращает извлеченные из неё код подтверждения, сумму и кошелек
function GetInfoSMSyandex(string $str,&$sum,&$code,&$receiver){
    if (preg_match('/(\d+)р.|(\d+(?:[\.,]\d+))р./u', $str, $matches)==1){
        $sum=$matches[2];
    }
    else
    {
        return $str."-1";
    }

    if (preg_match('/Пароль:\s+(\d{4,7})/u', $str, $matches)==1){
        $code=$matches[1];
    }
    else
    {
        return $str."-2";
    }

    if (preg_match('/([0-9]{13,15})/m', $str, $matches)==1){
        $receiver=$matches[0];
    }
    else
    {
        return $str."-3";
    }
    return "OK";
}


 $str = 'Никому не говорите пароль! Его спрашивают только мошенники.<br />
Пароль: 80738<br />
Перевод на счет 410016663388703<br />
Вы потратите 7653,27р.';

$error=GetInfoSMSyandex($str,$sum,$code,$receiver);

var_dump($error,$sum,$code,$receiver);



