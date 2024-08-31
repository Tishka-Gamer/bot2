<?php

namespace App\models;

use App\helpers\Connection;

class User
{
    private static function connect($config = CONFIG_CONNECTION){
        return Connection::make($config);
    }

    // public static function getUser($phone, $password){
    //     $query = Connection::make()->prepare("SELECT * FROM users WHERE users.phone = :phone");

    //     $query->execute([':phone' => $phone]);

    //     $user = $query->fetch();
       
    //     if(password_verify($password, $user->password)){
    //         return $user;
    //     } else return null;    
    // }

    public static function find($id){
        $query = Connection::make()->prepare("SELECT name FROM users WHERE name = ?");

        $query->execute([$id]);

        return $query->fetch();
    }
    public static function findbalance($id){
        $query = Connection::make()->prepare("SELECT balance FROM users WHERE name = ?");

        $query->execute([$id]);

        return $query->fetch();
    }
    public static function updbalance($id, $balance){
        $query = Connection::make()->prepare("UPDATE users SET balance= ? WHERE name = ?");
        $query->execute([$balance, $id]);
    }
    public static function addoperation($id, $text){
        $query = Connection::make()->prepare("INSERT into operations (user_id, operation) values (?, ?)");
        $query->execute([$id, $text]);
    }
    public static function findid($id){
        $query = Connection::make()->prepare("SELECT id FROM users WHERE name = ?");

        $query->execute([$id]);

        return $query->fetch();
    }
    // public static function getAll()
    // {
    //     $query = Connection::make()->query("SELECT users.id, users.name, users.surname, users.firstname, users.date_birthday, users.phone, users.email, users.photo FROM users");
    //     return $query->fetchAll();
    // }

    

    public static function insert($id)
    {
        $create = Connection::make()->prepare("INSERT into users (name, balance) values (?, ?)");
        return $create->execute([$id, 0.00]);
    }

    public static function delete($id)
    {
        $query = Connection::make()->prepare("DELETE FROM users WHERE id = ?");

        return $query->execute([$id]);
    }

    public static function findNumber($phone){
        $query = Connection::make()->prepare("SELECT users.id, users.name FROM users WHERE users.phone = ?");

        $query->execute([$phone]);
        $res = $query->fetchAll();

        return !empty($res);
        
    }

    // public static function getLogins(){
    //     $query = Connection::make()->query("SELECT users.login FROM users ");
    //     return $query->fetchAll();
    // }
    public static function findEmail($email)
    {
        $query = Connection::make()->prepare("SELECT users.id, users.name, users.phone, users.email FROM users WHERE users.email = ?");

        $query->execute([$email]);
        $res = $query->fetchAll();

        return !empty($res);
    }
    //здесь размещаем все методы для работы с таблицей users
}
