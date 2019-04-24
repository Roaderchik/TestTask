<?php
// создание нового cURL ресурса
$x = curl_init();

//000 000 01 до 999 999 9999

$receiver='41001'.str_pad(random_int(1,9999999999),8+random_int(0,1));
$sum = rand(0.01, 10000.99);
// echo 'receiver='.$receiver.'&sum='.$sum,
// установка URL и других необходимых параметров
curl_setopt_array($x, [
    CURLOPT_URL => 'https://funpay.ru/yandex/emulator',
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => 'receiver='.$receiver.'&sum='.$sum,
    CURLOPT_ENCODING => true,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_RETURNTRANSFER=> 1,
    CURLOPT_HTTPHEADER => [
        'origin: https://funpay.ru',
        'accept-encoding: gzip, deflate, br',
        'accept-language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
        'c-form-urlencoded; charset=UTF-8',
        'accept: text/html, */*; q=0.01',
        'referer: https://funpay.ru/yandex/emulator',
        'authority: funpay.ru',
        'x-requested-with: XMLHttpRequest',
        'dnt: 1',
    ]
]);


$output = curl_exec($x);
$arr=[$receiver,$sum,$output];

curl_close($x);

$descr=json_encode($arr);

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

// echo 'Соединение установлено... ' . $mysqli->host_info . "\n";

if ($result = $mysqli->query("INSERT INTO `funpay` (`id`, `receiver`, `sum`, `request`, `descr`) VALUES (NULL, '$receiver', '$sum', '$output', '$descr');")) {
    printf("успешно.\n");
}

$mysqli->close();

