<?php

namespace App\Models;

use App\Enums\ApprovalDecision;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplaintApproval extends Model
{
    protected $fillable = [
        'complaint_id',
        'user_id',
        'decision',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'decision' => ApprovalDecision::class,
        ];
    }

    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
