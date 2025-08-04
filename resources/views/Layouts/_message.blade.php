@php
    $alerts = [
        'success' => 'success',
        'error' => 'danger',
        'payment-error' => 'danger',
        'warning' => 'warning',
        'info' => 'info',
        'secondary' => 'secondary',
        'primary' => 'primary',
        'light' => 'light',
    ];
@endphp

@foreach ($alerts as $key => $type)
    @if (session($key))
        <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
            {{ session($key) }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endforeach
