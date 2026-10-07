<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'default_account_id',
    ];

    public function defaultAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'default_account_id');
    }

    public function entries()
    {
        return $this->hasMany(AccountingEntry::class, 'journal_id');
    }
}