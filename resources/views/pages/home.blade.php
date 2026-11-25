@extends('layouts.app')

@section('title', 'Clearway Junk Co.')
@section('meta_description', 'Same-day junk hauling, furniture removal, garage cleanouts, and construction debris pickup across Austin and Central Texas by Clearway Junk Co.')

@section('content')
<section class="hero">
    <div class="wrap hero-inner">
        <h1>Clearway<br>Junk Co.</h1>
        <p>We show up, haul it out, and leave the space clear — furniture, appliances, garage piles, and remodel debris across Austin and Central Texas.</p>
        <div class="hero-ctas">
            <a class="btn btn-primary" href="{{ route('contact') }}">Book a same-day haul</a>
            <a class="btn btn-ghost" href="{{ route('services') }}">See what we haul</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <h2>Junk removal services</h2>
        <p class="lede">Labor-inclusive hauling for homes, landlords, and contractors — priced by volume, not by surprise fees at the curb.</p>
        <div class="grid-3">
            @foreach([
                ['residential-junk-haul','Residential Junk Haul','Couches, mattresses, and room clear-outs loaded by our crew.','https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=80'],
                ['garage-attic-cleanout','Garage & Attic Cleanout','Decades of storage hauled in one visit — donate, recycle, landfill.','https://images.unsplash.com/photo-1600518464441-9154a4dea21b?auto=format&fit=crop&w=1200&q=80'],
                ['construction-debris','Construction Debris','Post-remodel scrap, cabinets, flooring, and tear-out piles.','https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1200&q=80'],
            ] as [$slug,$title,$blurb,$img])
                <a class="service-tile" href="{{ route('services.show', $slug) }}">
                    <img src="{{ $img }}" alt="{{ $title }}">
                    <div class="pad">
                        <h3>{{ $title }}</h3>
                        <p>{{ $blurb }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>Why Clearway</h2>
        <p class="lede">Central Texas crews that quote before we load, sort for donation when it makes sense, and protect floors and doorways on the way out.</p>
        <div class="grid-3">
            <div class="panel">
                <h3>Volume-based quotes</h3>
                <p>Send photos or get an on-site estimate — you approve the price before a single item hits the truck.</p>
            </div>
            <div class="panel">
                <h3>Sort & divert</h3>
                <p>Usable goods go to donation partners; metal and e-waste are routed properly whenever facilities allow.</p>
            </div>
            <div class="panel">
                <h3>ERP-run dispatch</h3>
                <p>Jobs, crew calendars, invoices, and inventory live in one ops stack so same-day windows stay honest.</p>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>Ops dashboard preview</h2>
        <p class="lede">A glimpse of the Clearway ERP used to schedule hauls, track equipment, and close cleanout invoices.</p>
        <div class="dash-preview">
            <div>
                <div class="grid-3" style="grid-template-columns:repeat(3,1fr)">
                    <div class="metric"><strong>36</strong> Open jobs</div>
                    <div class="metric"><strong>$142k</strong> Pipeline</div>
                    <div class="metric"><strong>11</strong> Hauls today</div>
                </div>
                <div class="bars">
                    <span style="height:45%"></span><span style="height:70%"></span><span style="height:55%"></span>
                    <span style="height:85%"></span><span style="height:60%"></span><span style="height:92%"></span>
                    <span style="height:68%"></span>
                </div>
            </div>
            <div>
                <h3 style="font-family:var(--font-display);text-transform:uppercase;margin-top:0;font-weight:800">Live modules</h3>
                <ul style="line-height:1.9;padding-left:1.1rem;color:rgba(255,255,255,.86)">
                    <li>Jobs & crew scheduling</li>
                    <li>Equipment / supply inventory</li>
                    <li>Invoices & payments</li>
                    <li>AI content + troubleshooting</li>
                </ul>
                <a class="btn btn-primary" style="margin-top:1rem" href="/admin">Open admin ERP</a>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>From the haul bay</h2>
        <p class="lede">Guides for homeowners, property managers, and remodel crews clearing space across Austin and Central Texas.</p>
        <div class="grid-3">
            @forelse($posts as $post)
                <a class="panel" href="{{ route('blog.show', $post->slug) }}">
                    <h3>{{ $post->title }}</h3>
                    <p>{{ $post->excerpt }}</p>
                </a>
            @empty
                <div class="panel"><p>Blog posts will appear after seeding.</p></div>
            @endforelse
        </div>
    </div>
</section>
@endsection
