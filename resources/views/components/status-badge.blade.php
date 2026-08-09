@props(['status'])

@php
    $class = match($status?->value ?? $status) {
        'published' => 'spoku-status-published',
        'rejected' => 'spoku-status-rejected',
        default => 'spoku-status-pending',
    };
    $label = is_object($status) && method_exists($status, 'label') ? $status->label() : ucfirst((string) $status);
@endphp

<span class="{{ $class }}">{{ $label }}</span>
