@extends('layouts.app')

@section('title', 'About Clearway Junk Co.')
@section('meta_description', 'Meet Clearway Junk Co. — an Austin hauling team built for same-day cleanouts, estate clears, and responsible junk diversion.')

@section('content')
<section class="section">
    <div class="wrap grid-2">
        <div>
            <h2>Built for Austin cleanouts</h2>
            <p class="lede">Clearway started as a two-truck crew clearing South Austin garages and Round Rock remodel piles. Today we run an ERP-backed dispatch stack so quotes, crews, and invoices stay aligned — without losing the neighborhood-service feel.</p>
            <p>We haul furniture, appliances, remodel debris, and full estates. When items are usable, we divert to donation partners. When they are not, we use licensed disposal and keep driveways clear.</p>
            <a class="btn btn-dark" href="{{ route('contact') }}" style="margin-top:1rem">Talk to dispatch</a>
        </div>
        <div>
            <img src="https://images.unsplash.com/photo-1527515637462-cff94eecc1ac?auto=format&fit=crop&w=1400&q=80" alt="Cleanout crew at work" style="width:100%;height:100%;min-height:320px;object-fit:cover;">
        </div>
    </div>
</section>
<section class="section" style="padding-top:0">
    <div class="wrap grid-3">
        <div class="panel"><h3>Service area</h3><p>Austin, Round Rock, Cedar Park, Georgetown, and nearby Central Texas communities.</p></div>
        <div class="panel"><h3>Crew model</h3><p>Two-person minimum teams with dollies, blankets, and floor runners for typical residential paths.</p></div>
        <div class="panel"><h3>Ops stack</h3><p>Filament ERP for customers, jobs, inventory, invoices, appointments, and AI assist tools.</p></div>
    </div>
</section>
@endsection
