<?php

namespace App\Models;
use \Core\Session;

use PDO;

class User extends \Core\Model
{
    public static function login($username, $password) 
    {
        //        $arr = static::select('user', 'username=? AND password=?', ['Joe', '$2y$10$d2BxUUNCV38YHhEowAef0em6fqTtA6iymhR4dLVyTmcW0P6hjhIB6']);
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $arr = static::select('user', 'username=?', [$username]);
            if (password_verify($password, $arr[0]['password'])) {
                echo "Logged In";
                Session::put('user', $arr[0]['userkey']);
            }
        }
    }

    public static function create($stuff) 
    {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $passwordHashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $test = array(
                'username' => $_POST['username'], 
                'password' => $passwordHashed,
                'userkey' => bin2hex(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM))
            );
    
            static::insert('user', $test);    
        }
    }

    public static function getAll()
    {    
        try {
            $db = static::getDB();

            $stmt = $db->query('SELECT id, title, content FROM posts ORDER BY created_at');
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
            
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
