@php
    $success = session('success');
    $classesSuccess = 'flex bg-green-100 text-green-800 dark:bg-cyan-900 dark:text-green-200 px-3 py-3 rounded-md relative';

    $warning = session('warning');
    $classesWarning = 'flex bg-yellow-100 text-yellow-700 dark:bg-waring-400 dark:text-red-900 px-3 py-3 rounded-md relative';

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
