@if(authUser())
<div class="sidebar" data-color="purple" data-background-color="white" data-image="{{ asset('img/sidebar-1.jpg') }}">
    <div class="logo">
        <a href="{{ config('app.url') }}" class="simple-text logo-normal">
            {{ config('app.name') }}
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            @include('layouts.admin-sidebar')
        </ul>
    </div>
</div>
@endif