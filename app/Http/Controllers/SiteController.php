<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\KnowledgeArticle;
use App\Models\Post;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        $posts = Post::query()->where('is_published', true)->latest('published_at')->take(3)->get();

        return view('pages.home', compact('posts'));
    }

    public function services(): View
    {
        return view('pages.services');
    }

    public function serviceShow(string $slug): View
    {
        $services = $this->serviceCatalog();
        abort_unless(isset($services[$slug]), 404);

        return view('services.show', [
            'slug' => $slug,
            'service' => $services[$slug],
            'services' => $services,
        ]);
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function contactStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['nullable', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::query()->create($data);

        return back()->with('success', 'Thanks! Clearway will follow up within one business day.');
    }

    public function blog(): View
    {
        $posts = Post::query()->where('is_published', true)->latest('published_at')->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function blogShow(string $slug): View
    {
        $post = Post::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('blog.show', compact('post'));
    }

    public function knowledge(): View
    {
        $articles = KnowledgeArticle::query()->where('is_published', true)->orderBy('category')->orderBy('title')->get()->groupBy('category');

        return view('kb.index', compact('articles'));
    }

    public function knowledgeShow(string $slug): View
    {
        $article = KnowledgeArticle::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('kb.show', compact('article'));
    }

    public function chat(Request $request, AiService $ai)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'session_id' => ['nullable', 'string', 'max:80'],
        ]);

        $result = $ai->chat($data['message'], $data['session_id'] ?? null);

        return response()->json($result);
    }

    /** @return array<string, array<string, string>> */
    protected function serviceCatalog(): array
    {
        return [
            'residential-junk-haul' => [
                'title' => 'Residential Junk Haul',
                'summary' => 'Furniture, mattresses, and room clear-outs loaded by our Austin crew.',
                'body' => 'Clearway arrives with a two-person team, floor protection, and a truck sized for your pile. Couches, dressers, toys, and boxed clutter leave in one visit — you approve the volume price before we load.',
                'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1600&q=80',
            ],
            'garage-attic-cleanout' => [
                'title' => 'Garage & Attic Cleanout',
                'summary' => 'Full-space resets for garages, attics, basements, and storage units.',
                'body' => 'Years of storage do not need a weekend of trips to the landfill. We sort for donation when items are usable, pull metal for recycling, and leave the slab swept so you can park again.',
                'image' => 'https://images.unsplash.com/photo-1600518464441-9154a4dea21b?auto=format&fit=crop&w=1600&q=80',
            ],
            'construction-debris' => [
                'title' => 'Construction Debris Pickup',
                'summary' => 'Post-remodel scrap, cabinets, drywall chunks, and flooring tear-outs.',
                'body' => 'GCs and DIY remodelers across Central Texas use Clearway when a dumpster will not fit the driveway or the timeline. We load debris piles, bagged waste, and fixtures, then invoice against the job in our ERP.',
                'image' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1600&q=80',
            ],
            'appliance-electronics' => [
                'title' => 'Appliance & Electronics Removal',
                'summary' => 'Fridges, washers, TVs, and e-waste hauled with responsible routing.',
                'body' => 'Heavy appliances need straps, dollies, and a clear path down stairs. We disconnect what is safe to move, note Freon-bearing units for proper handling, and keep e-waste out of ordinary landfill loads when facilities allow.',
                'image' => 'https://images.unsplash.com/photo-1556911220-bff31c812dce?auto=format&fit=crop&w=1600&q=80',
            ],
            'estate-cleanout' => [
                'title' => 'Estate & Property Cleanout',
                'summary' => 'Whole-home and multi-unit clears for families, trustees, and landlords.',
                'body' => 'Sensitive timelines need calm crews. Clearway stages rooms, labels keep-vs-haul piles with your point person, and documents the finish so property managers can turn units faster.',
                'image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1600&q=80',
            ],
            'same-day-haul' => [
                'title' => 'Same-Day Haul',
                'summary' => 'Urgent Austin pickups when closings, HOA deadlines, or driveway access cannot wait.',
                'body' => 'Morning photo quotes unlock afternoon windows when trucks are available. South Austin bungalows, Round Rock townhomes, and Cedar Park driveways are routed by crew capacity — earlier calls win earlier slots.',
                'image' => 'https://images.unsplash.com/photo-1566576721346-d4a3b4eaeb55?auto=format&fit=crop&w=1600&q=80',
            ],
        ];
    }
}
