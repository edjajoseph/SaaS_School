<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class StaffContract extends Model
{
    use SoftDeletes;

    // Statuts du contrat
    public const STATUS_ACTIVE = 'active';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_SUSPENDED = 'suspended';
    public const STATUS_TERMINATED = 'terminated';

    // Types de contrats
    public const TYPE_CDI = 'CDI';
    public const TYPE_CDD = 'CDD';
    public const TYPE_VACATAIRE = 'VACATAIRE';
    public const TYPE_PRESTATAIRE = 'PRESTATAIRE';
    public const TYPE_STAGE = 'STAGE';

    // Modes de rémunération
    public const PAY_TYPE_MONTHLY = 'monthly';
    public const PAY_TYPE_HOURLY = 'hourly';
    public const PAY_TYPE_FORFAIT = 'forfait';

    protected $fillable = [
        'staff_id',
        'school_id',
        'staff_role_id',
        'job_title',
        'contract_type',
        'start_date',
        'end_date',
        'pay_type',
        'base_salary_or_rate',
        'contract_document_path',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'base_salary_or_rate' => 'decimal:2',
    ];

    /* =========================================================================
     * RELATIONS
     * ========================================================================= */

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(StaffRole::class, 'staff_role_id');
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class, 'staff_contract_id');
    }

    /* =========================================================================
     * SCOPES DE REQUÊTE (Query Scopes)
     * ========================================================================= */

    // Filtre les contrats actifs et non expirés
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE)
                     ->where(function ($q) {
                         $q->whereNull('end_date')
                           ->orWhere('end_date', '>=', now()->startOfDay());
                     });
    }

    // Filtre le personnel permanent (CDI / CDD payés au mois)
    public function scopePermanent(Builder $query): Builder
    {
        return $query->whereIn('contract_type', [self::TYPE_CDI, self::TYPE_CDD])
                     ->where('pay_type', self::PAY_TYPE_MONTHLY);
    }

    // Filtre les intervenants vacataires (Payés à l'heure)
    public function scopeVacataire(Builder $query): Builder
    {
        return $query->where('contract_type', self::TYPE_VACATAIRE)
                     ->where('pay_type', self::PAY_TYPE_HOURLY);
    }

    /* =========================================================================
     * ACCESSEURS ET MÉTHODES MÉTIER
     * ========================================================================= */

    // Libellé lisible du type de contrat
    public function getContractTypeLabelAttribute(): string
    {
        return match($this->contract_type) {
            self::TYPE_CDI => 'Contrat à Durée Indéterminée (CDI)',
            self::TYPE_CDD => 'Contrat à Durée Déterminée (CDD)',
            self::TYPE_VACATAIRE => 'Vacataire / Horaire',
            self::TYPE_PRESTATAIRE => 'Prestataire Externe',
            self::TYPE_STAGE => 'Stage / Immersion',
            default => strtoupper((string) $this->contract_type),
        };
    }

    // Libellé lisible du mode de paie
    public function getPayTypeLabelAttribute(): string
    {
        return match($this->pay_type) {
            self::PAY_TYPE_MONTHLY => 'Salaire Mensuel Fixe',
            self::PAY_TYPE_HOURLY => 'Taux Horaire',
            self::PAY_TYPE_FORFAIT => 'Forfaitaire',
            default => ucfirst((string) $this->pay_type),
        };
    }

    // Vérifie si le contrat est actuellement valide
    public function getIsValidAttribute(): bool
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        if ($this->end_date && $this->end_date->isPast()) {
            return false;
        }

        return true;
    }

    // Indique si le contrat concerne un permanent
    public function getIsPermanentAttribute(): bool
    {
        return in_array($this->contract_type, [self::TYPE_CDI, self::TYPE_CDD], true) 
            && $this->pay_type === self::PAY_TYPE_MONTHLY;
    }

    // Indique si le contrat concerne un vacataire
    public function getIsVacataireAttribute(): bool
    {
        return $this->contract_type === self::TYPE_VACATAIRE 
            || $this->pay_type === self::PAY_TYPE_HOURLY;
    }
}