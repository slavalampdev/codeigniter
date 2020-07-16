<?php

namespace App\Models;

use Config\Database;

class User
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['name', 'email'];

    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    private $name;
    private $password;
    private $email;

    public static function registerUser($data)
    {
        $builder = Database::connect()->table('users');
        $builder->insert($data);
        return Database::connect()->insertID();
    }

    public static function getUsers()
    {
        $builder = Database::connect()->table('users');
        $query = $builder->get();
        return $query->getResult();
    }

    public static function checkUser($data)
    {
        $builder = Database::connect()->table('users');
        $builder->select('id');
        $builder->select('password');
        $builder->where('email', $data['email']);
        $query = $builder->get();
        $result = $query->getFirstRow();
        if (password_verify($data['password'], $result->password)) {
            return $result->id;
        }
        return null;
    }
}