<?php

# app/Infrastructure/PharmaceuticalForm/Models/PharmaceuticalForm.php

namespace App\Infrastructure\PharmaceuticalForm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmaceuticalForm extends Model
{
    use HasFactory;

    protected $table = 'pharmaceutical_forms';

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}