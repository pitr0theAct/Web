<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avito</title>
</head>
<body>
<h2>Добавить объявление</h2>
<form action="submit.php" method="post">
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required><br>

    <label for="title">Заголовок объявления:</label><br>
    <input type="text" id="title" name="title" required><br>

    <label for="text">Текст объявления:</label><br>
    <textarea id="text" name="text" rows="5" required></textarea><br>

    <label for="category">Категория:</label><br>
    <input type="text" id="category" name="category" required><br><br>

    <input type="submit" value="Добавить">
</form>
</body>
</html>

<?php

require_once "vendor/autoload.php";

use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;

// Функция для записи данных в Google Таблицу
function addEntryToGoogleSheet($email, $title, $text, $category)
{
    //Создание объекта Google_Client для инициализации клиента Google Sheets API
    $client = new Google_Client();
    $client->setApplicationName('ApplicationName');
    $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
    $client->setAccessType('offline');
    $client->setAuthConfig(__DIR__ . "/data/credentials.json");


    $service = new Google_Service_Sheets($client);

    $spreadsheetId = '1aMlrDhSvp7J8lUGVSyRUUFOYtqedjwFDpCp35ILukSWM'; // ID таблицы
    $range = 'Sheet1'; // Название листа в таблице

    $values = [
        [$email, $title, $text, $category]
    ];


    $body = new Google_Service_Sheets_ValueRange([
        'values' => $values
    ]);

    $params = [
        'valueInputOption' => 'RAW'
    ];

    $result = $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
    if ($result->getUpdates()->getUpdatedCells() > 0) {
        echo 'Объявление успешно добавлено!';
    } else {
        echo 'Ошибка при добавлении объявления';
    }
}

// Получаем данные из POST запроса
$email = $_POST['email'];
$title = $_POST['title'];
$text = $_POST['text'];
$category = $_POST['category'];

// Добавляем запись в Google Таблицу
addEntryToGoogleSheet($email, $title, $text, $category);