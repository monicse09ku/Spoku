<button {{ $attributes->merge(['type' => 'submit', 'class' => 'spoku-btn-primary']) }}>
    {{ $slot }}
</button>
