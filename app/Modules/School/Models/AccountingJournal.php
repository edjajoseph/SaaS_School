<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountingJournal extends Model
{
    use HasFactory;

    protected $table = 'accounting_journals';

    protected $fillable = [
        'code',
        'name',
        'type',
        'default_account_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function defaultAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'default_account_id');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(AccountingEntry::class, 'journal_id');
    }
}