<?php

require_once "vendor/autoload.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/bootstrap.php";
use App\models\User;
use TelegramBot\Api\BotApi;

// Инициализация бота
$bot = new BotApi('past your api');
$bot->setWebhook('past url webhook');

// Получение данных от Telegram
$update = file_get_contents('php://input');
$result = json_decode($update, true);

// Логирование для отладки
file_put_contents('log.txt', $update, FILE_APPEND);

if (isset($result['message'])) {
    $message = $result['message'];
    $text = $message['text'] ?? null; // Текст сообщения
    $chat_id = $message['chat']['id'] ?? null; // ID чата
    $name = $message['from']['username'] ?? 'User'; // Username

    $pattern = '/^[+-]\d+([.,]\d+)?$/';

    if ($text) {
        if ($text == '/start') {
           

            $find = User::find($chat_id);
            if (!$find) {
                $reply = "Hello, " . $name;
                $bot->sendMessage($chat_id, $reply);
                User::insert($chat_id);
            }
            else
            {
                $balance = User::findbalance($chat_id);
                $balanceValue = $balance->balance;
                $reply = "Hello, " . $name . ". Ваш баланс: " . $balanceValue . "$";
                $bot->sendMessage($chat_id, $reply);
            }
        } elseif (preg_match($pattern, $text)) {
            if (strpos($text, ',') !== false) {
                $text = str_replace(',', '.', $text);
            }
            $number = (float)$text;
            $balance = User::findbalance($chat_id);
            $balanceValue = 0;
            if ($balance !== false) {
                $balanceValue = $balance->balance; 
                $balanceValue += $number;
            }
            else
            {
             $reply = 'Ошибка, попробуйте позже';
            }

            if ($balanceValue < 0) {
                $reply = "Операция невозможна, недостаточно средств";
            } else {
                $id = User::findid($chat_id);
                User::addoperation($id->id, $number);
                $formattedNumber = number_format($balanceValue, 2, '.', '');
                User::updbalance($chat_id, $formattedNumber);
                $balance2 = User::findbalance($chat_id);
                $reply = "Ваш баланс: " . $balance2->balance . "$";
            }

            $bot->sendMessage($chat_id, $reply);
        } else {
            $reply = "Если вы хотите снять или пополнить счет, убедитесь что в тексте сообщения присутствует только знак +/- и цифра";
            $bot->sendMessage($chat_id, $reply);
        }
    }
}
