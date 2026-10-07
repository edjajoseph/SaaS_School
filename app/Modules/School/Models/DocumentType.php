<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentType extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'code', 'is_required', 'is_active'];

    public function studentDocuments(): HasMany
    {
        return $this->hasMany(StudentDocument::class);
    }
}