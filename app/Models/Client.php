<?php

namespace App\Models;

use App\Enums\DocumentTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'names',
        'paternal_surname',
        'maternal_surname',
        'document_type',
        'document_number',
        'email',
        'phone',
    ];

    protected $hidden = [
        'deleted_at'
    ];

    protected $casts = [
        'document_type' => DocumentTypeEnum::class,
    ];

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }
}
