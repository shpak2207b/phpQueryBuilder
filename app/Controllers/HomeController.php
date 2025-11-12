<?php

namespace App\Controllers;
use App\QueryBuilder;
use Delight\Auth\Auth;
use Delight\Auth\Role;
use League\Plates\Engine;
use PDO;

class HomeController
{
    private $templates;
    private $auth;
    private $queryBuilder;

    public function __construct(QueryBuilder $builder, Engine $engine)
    {
        $this->templates = $engine;
        $this->queryBuilder = $builder;
        $db = new PDO("mysql:host=MySQL-8.0;dbname=app2;", "root", "");
        $this->auth = new Auth($db);
    }
    public function index()
    {
        d( $this->queryBuilder); die();
//        $this->auth->admin()->addRoleForUserById(1,Role::ADMIN);
        $db = new QueryBuilder();
        $posts = $db->getAll('posts');
        $templates = new Engine('../app/views');

        echo $templates->render('homepage', ['name' => 'Index', 'posts' => $posts]);
    }

    public function about($vars)
    {
        $db = new PDO("mysql:host=MySQL-8.0;dbname=app2;", "root", "");
        $auth = new Auth($db);
        try {
            $userId = $this->auth->register('shpak@tima.ru', '123', 'zombak2006', function ($selector, $token) {
                echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
                echo '  For emails, consider using the mail(...) function, Symfony Mailer, Swiftmailer, PHPMailer, etc.';
                echo '  For SMS, consider using a third-party service and a compatible SDK';
            });

            echo 'We have signed up a new user with the ID ' . $userId;
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            die('Invalid email address');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Invalid password');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            die('User already exists');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }
    }

    public function emailVerification()
    {
        try {
            $this->auth->confirmEmail('BxmTMKz9hnu5Y4oN', 'U7ZCjgHLzXmfZgfo');

            echo 'Email address has been verified';
        }
        catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            die('Invalid token');
        }
        catch (\Delight\Auth\TokenExpiredException $e) {
            die('Token expired');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            die('Email address already exists');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }
    }

    public function login()
    {
        try {
            $this->auth->login('shpak@tima.ru', '123');

            echo 'User is logged in';
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            die('Wrong email address');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Wrong password');
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            die('Email not verified');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }

    }

}