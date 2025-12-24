@php
    $isActive = function ($patterns = []) {
        foreach ($patterns as $pattern) {
            if (request()->is($pattern)) return true;
        }
        return false;
    };
@endphp

<aside class="sidebar">
    <div style="padding:30px; text-align:center; font-size:1.5rem; font-weight:bold; color:var(--primary)">Super Admin</div>
    @foreach (config('sidebar') as $key => $properties )

    <a href="{{ url($properties['route']) }}">

        <div style="padding:15px 25px; cursor:pointer;

            {{ $isActive($properties['active']) ? 'background: var(--primary); color:white;' : '' }}">
            <i class="{{ $properties['icon'] }}" style="margin-left:10px"></i>

            {{ $properties['label'] }}
        </div>
    </a>

    @endforeach
</aside>
