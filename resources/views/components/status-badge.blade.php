@props(['status'])

@php
    $class = match($status?->value ?? $status) {
        'published' => 'spoku-status-published',
        'ready_to_publish' => 'spoku-status bg-trust-soft text-trust',
        'awaiting_approvers' => 'spoku-status bg-amber-100 text-amber-900',
        'rejected' => 'spoku-status-rejected',
        default => 'spoku-status-pending',
    };
    $label = is_object($status) && method_exists($status, 'label') ? $status->label() : ucfirst((string) $status);
@endphp

<span class="{{ $class }}">{{ $label }}</span>
