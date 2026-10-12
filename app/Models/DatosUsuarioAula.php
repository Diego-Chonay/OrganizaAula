<?php

namespace App\Models;

use CodeIgniter\Model;

class DatosUsuarioAula extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'name',
        'email',
        'password',
        'academic_role',
        'active',
        'administrator',
        'profile_image',
        'verification_key',
        'recovery_key',
        'recovery_expires_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
