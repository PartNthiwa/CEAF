<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DependentDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'dependent_id',
        'type',
        'file_path',
        'approved',
    ];

    // Relationship to Dependent
    public function dependent()
    {
        return $this->belongsTo(Dependent::class);
    }
}
