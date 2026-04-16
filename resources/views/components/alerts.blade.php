@php
    $success = session('success');
    $classesSuccess = 'flex bg-cyan-900 text-green-200 px-3 py-3 rounded-md relative';

    $warning = session('warning');
    $classesWarning = 'flex bg-orange-100 text-orange-800 px-3 py-3 rounded-md relative';

    $error = session('custom_error');
    $classesError = 'flex bg-red-800 text-red-200 px-3 py-3 rounded-md relative';

@endphp

@if ($success)
    <div {{ $attributes->merge(['role' => 'alert', 'class' => $classesSuccess ]) }}>
        
        <flux:icon.check-circle variant="solid" name="check" class="inline size-5 mr-1" />
        
        <span class="">{{ $success }}</span>
    </div>
@endif

@if ($warning)
    <div {{ $attributes->merge(['role' => 'alert', 'class' => $classesWarning ]) }}>
        
        <flux:icon.exclamation-triangle variant="solid" name="exclamation-triangle" class="inline size-5 mr-1" />
        
        <span class="">{{ $warning }}</span>
    </div>  
@endif

@if ($error)
    <div {{ $attributes->merge(['role' => 'alert', 'class' => $classesError ]) }}>
        
        <flux:icon.x-circle variant="solid" name="x" class="inline size-5 mr-1" />
        
        <span class="">{{ $error }}</span>
    </div> 
@endif
