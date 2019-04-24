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


$mysqli = new mysqli('localhost', 'root', '', 'test');

/*
 * Это "официальный" объектно-ориентированный способ сделать это
 * однако $connect_error не работал вплоть до версий PHP 5.2.9 и 5.3.0.
 */
if ($mysqli->connect_error) {
    die('Ошибка подключения (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}

/*
 * Если нужно быть уверенным в совместимости с версиями до 5.2.9,
 * лучше использовать такой код
 */
if (mysqli_connect_error()) {
    die('Ошибка подключения (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
}


$result = $mysqli->query("SELECT id,request FROM funpay");
printf("Затронутые строки (SELECT): %d\n", $mysqli->affected_rows);
while ($row = $result->fetch_object()){
    $sum='';
    $code='';
    $receiver='';
    $error='';
    $id=$row->id;
    $error=GetInfoSMSyandex($row->request,$sum,$code,$receiver);
    //var_dump("update `funpay` set `receiver_out`=$receiver, `sum_out`=$sum, `code_out`=$code, `descr`='$error' where `id`=$id");
      if ($mysqli->query("update `funpay` set `receiver_out`='$receiver', `sum_out`='$sum', `code_out`='$code', `descr`='$error' where `id`=$id")) {
          printf("успешно.\n");
      }
    
}

/*
// if ($result = $mysqli->query("update `funpay` set `receiver_out`=?, `sum_out`=?, `code_out`=? where `id`=?", [$receiver, $sum,$code,$id])) {
//     printf("успешно.\n");
// }
*/
$mysqli->close();



