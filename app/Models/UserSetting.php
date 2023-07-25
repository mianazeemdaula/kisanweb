<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
    use HasFactory;

    function getValueAttribute($v) {
        switch($this->setting->type){
            case 'integer':
                return (int) $v;
            case 'double':
                return (float) $v;
            case 'boolean':
                return (boolean) $v;
        }
        return $this->setting->type;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setting(): BelongsTo
    {
        return $this->belongsTo(Setting::class);
    }
}
