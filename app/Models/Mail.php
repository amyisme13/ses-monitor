<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

class Mail extends Model
{
    use HasFactory, HasTags;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['message_id', 'subject', 'source_email', 'sent_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function recipients()
    {
        return $this->hasMany(MailRecipient::class);
    }
}
