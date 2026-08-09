<?php

namespace App\Models;

use App\Enums\ApprovalDecision;
use App\Enums\ComplaintStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Complaint extends Model
{
    protected $fillable = [
        'user_id',
        'person_id',
        'title',
        'details',
        'is_anonymous',
        'status',
        'assigned_to_lawyers',
        'assigned_to_counselors',
        'assigned_to_law_enforcers',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_anonymous' => 'boolean',
            'status' => ComplaintStatus::class,
            'assigned_to_lawyers' => 'boolean',
            'assigned_to_counselors' => 'boolean',
            'assigned_to_law_enforcers' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(ComplaintFile::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(ComplaintApproval::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', ComplaintStatus::Published);
    }

    public function approvedCount(): int
    {
        return $this->approvals()->where('decision', ApprovalDecision::Approved)->count();
    }

    public function rejectedCount(): int
    {
        return $this->approvals()->where('decision', ApprovalDecision::Rejected)->count();
    }

    public function submitterDisplayName(): string
    {
        if ($this->is_anonymous) {
            return 'Anonymous';
        }

        return $this->user?->name ?? 'Unknown';
    }

    public function refreshPublicationStatus(): void
    {
        $threshold = config('spoku.approval_threshold', 7);
        $approved = $this->approvedCount();

        if ($approved >= $threshold) {
            $this->update([
                'status' => ComplaintStatus::Published,
                'published_at' => $this->published_at ?? now(),
            ]);

            return;
        }

        $approverCount = User::query()
            ->where('role', \App\Enums\UserRole::Approver)
            ->where('is_active', true)
            ->count();

        $rejected = $this->rejectedCount();
        $remaining = max($approverCount - $approved - $rejected, 0);

        if ($approved + $remaining < $threshold && $rejected > 0) {
            $this->update([
                'status' => ComplaintStatus::Rejected,
                'published_at' => null,
            ]);
        }
    }
}
