@extends('layouts.app')

@section('title', 'Contact Clearway Junk Co.')
@section('meta_description', 'Request a junk removal quote from Clearway Junk Co. in Austin — call, WhatsApp, or send the form.')

@section('content')
<section class="section">
    <div class="wrap grid-2">
        <div>
            <h2>Book a haul</h2>
            <p class="lede">Tell us what needs to go, your ZIP, and preferred window. Photos speed up remote quotes.</p>
            <div class="panel" style="margin-bottom:1rem">
                <p><strong>Phone:</strong> (512) 555-0194</p>
                <p><strong>Email:</strong> hello@clearwayjunk.com</p>
                <p><strong>WhatsApp:</strong> +1 512 555 0194</p>
                <p><strong>Area:</strong> Austin & Central Texas</p>
            </div>
        </div>
        <div>
            @if(session('success'))
                <div class="flash">{{ session('success') }}</div>
            @endif
            <form class="stack panel" method="post" action="{{ route('contact.store') }}">
                @csrf
                <div>
                    <label for="name">Name</label>
                    <input id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')<small style="color:#b91c1c">{{ $message }}</small>@enderror
                </div>
                <div>
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                    @error('email')<small style="color:#b91c1c">{{ $message }}</small>@enderror
                </div>
                <div>
                    <label for="phone">Phone</label>
                    <input id="phone" name="phone" value="{{ old('phone') }}">
                </div>
                <div>
                    <label for="subject">Subject</label>
                    <input id="subject" name="subject" value="{{ old('subject', 'Junk removal quote') }}">
                </div>
                <div>
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                    @error('message')<small style="color:#b91c1c">{{ $message }}</small>@enderror
                </div>
                <button class="btn btn-primary" type="submit">Send message</button>
            </form>
        </div>
    </div>
</section>
@endsection
