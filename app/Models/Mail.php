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

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['recipients'];

    /**
     * Scope a query to filter with the given filters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  array  $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $filters)
    {
        $search = $filters['search'] ?? '';
        if (!$search) {
            return $query;
        }

        $field = $filters['field'] ?? 'subject';
        if ($field === 'subject') {
            return $query->where('subject', 'like', "%{$search}%");
        }

        return $query->whereHas('recipients', function ($query) use ($search) {
            return $query->where('email', 'like', "%{$search}%");
        });
    }

    public function recipients()
    {
        return $this->hasMany(MailRecipient::class);
    }
}
