<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    use HasUuids;

    protected $primaryKey = 'uuid';
    public $timestamps = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'api_token'
    ];
}
