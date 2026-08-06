<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'content',
        'client_id',
        'user_id',
        'updated_at',
    ];

    public function casts(): array
    {
        return [
            'updated_at' => 'date',
            'created_at' => 'date',
        ];
    }
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function formatDate($field): string
    {
        return Carbon::parse($this->attributes[$field])
            ->isoFormat('D MMMM YYYY');
    }
}
