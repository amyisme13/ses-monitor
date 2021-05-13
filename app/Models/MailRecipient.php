<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailRecipient extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['recipient_id', 'mail_id', 'email', 'status', 'resolved_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /**
     * Get whether mail recipient is not resolved.
     *
     * @return bool
     */
    public function getIsNotResolvedAttribute()
    {
        return is_null($this->resolved_at);
    }

    public function mail()
    {
        return $this->belongsTo(Mail::class);
    }

    public function recipient()
    {
        return $this->belongsTo(Recipient::class);
    }
}
