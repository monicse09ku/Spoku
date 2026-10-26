<?php

namespace App\Models;

use App\Enums\ApprovalDecision;
use App\Enums\ComplaintStatus;
use App\Enums\UserRole;
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
        'help_requested_lawyers',
        'help_requested_counselors',
        'help_requested_law_enforcers',
        'help_requested_humanitarians',
        'admin_notes',
        'status',
        'assigned_to_lawyers',
        'assigned_to_counselors',
        'assigned_to_law_enforcers',
        'assigned_to_humanitarians',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_anonymous' => 'boolean',
            'help_requested_lawyers' => 'boolean',
            'help_requested_counselors' => 'boolean',
            'help_requested_law_enforcers' => 'boolean',
            'help_requested_humanitarians' => 'boolean',
            'status' => ComplaintStatus::class,
            'assigned_to_lawyers' => 'boolean',
            'assigned_to_counselors' => 'boolean',
            'assigned_to_law_enforcers' => 'boolean',
            'assigned_to_humanitarians' => 'boolean',
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
        return $this->approvals()
            ->where('decision', ApprovalDecision::Approved->value)
            ->count();
    }

    public function rejectedCount(): int
    {
        return $this->approvals()
            ->where('decision', ApprovalDecision::Rejected->value)
            ->count();
    }

    public function submitterDisplayName(): string
    {
        if ($this->is_anonymous) {
            return 'Anonymous';
        }

        return $this->user?->name ?? 'Unknown';
    }

    public function requestedHelpLabels(): array
    {
        $labels = [];

        if ($this->help_requested_lawyers) {
            $labels[] = 'Lawyers';
        }
        if ($this->help_requested_counselors) {
            $labels[] = 'Counselors';
        }
        if ($this->help_requested_law_enforcers) {
            $labels[] = 'Law Enforcers';
        }
        if ($this->help_requested_humanitarians) {
            $labels[] = 'Humanitarians';
        }

        return $labels;
    }

    /**
     * After approver votes, mark ready for admin publish (does not publish).
     */
    public function refreshApproverStatus(): void
    {
        if (! in_array($this->status, [ComplaintStatus::AwaitingApprovers, ComplaintStatus::ReadyToPublish], true)) {
            return;
        }

        $threshold = (int) config('spoku.approval_threshold', 7);
        $approved = $this->approvedCount();
        $rejected = $this->rejectedCount();

        if ($approved >= $threshold) {
            $this->forceFill([
                'status' => ComplaintStatus::ReadyToPublish,
                'published_at' => null,
            ])->save();

            return;
        }

        $approverCount = User::query()
            ->where('role', UserRole::Approver)
            ->where('is_active', true)
            ->count();

        $remaining = max($approverCount - $approved - $rejected, 0);

        if ($approved + $remaining < $threshold) {
            $this->forceFill([
                'status' => ComplaintStatus::Rejected,
                'published_at' => null,
            ])->save();

            return;
        }

        $this->forceFill([
            'status' => ComplaintStatus::AwaitingApprovers,
            'published_at' => null,
        ])->save();
    }
}
