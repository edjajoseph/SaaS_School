<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'evaluation_id',
        'registration_id',
        'score',
        'is_absent',
        'is_justified',
        'remarks',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'is_absent' => 'boolean',
        'is_justified' => 'boolean',
    ];

    /* --- RELATIONS --- */

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    /* --- ACCESSEURS & HELPERS --- */

    /**
     * Calcule la note ramener sur une base standard de 20 (utilisé pour les synthèses).
     */
    public function getScoreOnTwentyAttribute(): ?float
    {
        if ($this->is_absent || is_null($this->score)) {
            return null;
        }

        $maxScore = $this->evaluation->max_score ?? 20.00;

        if ($maxScore == 0) {
            return 0;
        }

        return round(($this->score / $maxScore) * 20, 2);
    }

    /**
     * Formate l'affichage de la note (ex: "14.50/20", "ABS", "ABS (J)").
     */
    public function getFormattedScoreAttribute(): string
    {
        if ($this->is_absent) {
            return $this->is_justified ? 'ABS (J)' : 'ABS';
        }

        if (is_null($this->score)) {
            return 'N/A';
        }

        return number_format($this->score, 2, ',', ' ');
    }
}