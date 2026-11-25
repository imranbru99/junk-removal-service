@extends('layouts.app')

@section('title', 'Junk Removal Services')
@section('meta_description', 'Residential junk haul, garage cleanouts, construction debris, appliance removal, estate clears, and same-day pickup by Clearway Junk Co.')

@section('content')
<section class="section">
    <div class="wrap">
        <h2>What we haul</h2>
        <p class="lede">Labor-inclusive junk removal across Austin and Central Texas — choose a service for details or book a photo quote.</p>
        <div class="grid-3">
            @foreach([
                ['residential-junk-haul','Residential Junk Haul','Furniture and room clear-outs.','https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=80'],
                ['garage-attic-cleanout','Garage & Attic Cleanout','Full-space storage resets.','https://images.unsplash.com/photo-1600518464441-9154a4dea21b?auto=format&fit=crop&w=1200&q=80'],
                ['construction-debris','Construction Debris','Remodel scrap and tear-outs.','https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1200&q=80'],
                ['appliance-electronics','Appliance & Electronics','Fridges, washers, TVs, e-waste.','https://images.unsplash.com/photo-1556911220-bff31c812dce?auto=format&fit=crop&w=1200&q=80'],
                ['estate-cleanout','Estate & Property Cleanout','Whole-home and unit clears.','https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1200&q=80'],
                ['same-day-haul','Same-Day Haul','Urgent Austin pickup windows.','https://images.unsplash.com/photo-1566576721346-d4a3b4eaeb55?auto=format&fit=crop&w=1200&q=80'],
            ] as [$slug,$title,$blurb,$img])
                <a class="service-tile" href="{{ route('services.show', $slug) }}">
                    <img src="{{ $img }}" alt="{{ $title }}">
                    <div class="pad"><h3>{{ $title }}</h3><p>{{ $blurb }}</p></div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
