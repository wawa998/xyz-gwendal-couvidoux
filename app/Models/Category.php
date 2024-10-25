<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function tracks(): HasMany
    {
        return $this->hasMany(Track::class);
    }

    /**
     * Récupère toutes les catégories.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAllCategories()
    {
        return self::all();
    }

    public static function findById(int $id): ?Category
    {
        return self::find($id);
    }
}
