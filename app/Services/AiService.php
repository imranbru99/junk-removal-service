<?php

namespace App\Services;

use App\Models\ChatLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AiService
{
    /** @var array<int, array{q: string, a: string}> */
    protected array $faq = [
        [
            'q' => 'price cost quote estimate volume truck load',
            'a' => 'Clearway prices by how much of the truck your junk fills — typically a few hundred dollars for a small pile up to full-truck estate loads. Send photos for a remote quote or book an on-site estimate; you approve the price before we load. Call (512) 555-0194.',
        ],
        [
            'q' => 'what haul furniture couch mattress appliance fridge',
            'a' => 'We haul furniture, mattresses, appliances, electronics, garage clutter, yard waste piles, and most remodel debris. Hazardous waste, wet paint, tires, and certain chemicals need special disposal — ask before the crew arrives.',
        ],
        [
            'q' => 'same day schedule appointment austin pickup',
            'a' => 'Same-day windows are often available across Austin, Round Rock, Cedar Park, and Georgetown when trucks have capacity. Morning photo quotes unlock afternoon slots. Call or WhatsApp (512) 555-0194.',
        ],
        [
            'q' => 'garage attic basement cleanout estate',
            'a' => 'Full-space cleanouts are a Clearway specialty. We sort for donation when items are usable, recycle metal when possible, and leave the space swept. Estate and landlord jobs can be staged room-by-room with your point person on site.',
        ],
        [
            'q' => 'donation recycle landfill sort divert',
            'a' => 'Usable goods go to local donation partners when condition allows. Scrap metal and accepted e-waste are routed separately. Everything else goes to licensed disposal — we never leave items on the curb overnight.',
        ],
        [
            'q' => 'prohibited hazardous paint tires batteries asbestos',
            'a' => 'We cannot take hazardous chemicals, wet paint cans, asbestos, sealed drums, or medical waste. Tires and some batteries need specialty vendors. Tell us what is in the pile during quoting so we plan the right path.',
        ],
        [
            'q' => 'prep prepare stairs elevator parking access',
            'a' => 'Clear a path, unlock gates, reserve elevator time in mid-rises, and note walk-up floors. Move keepers out of the haul zone. Our crew brings dollies, blankets, and floor runners for typical residential paths.',
        ],
        [
            'q' => 'construction debris remodel drywall cabinets',
            'a' => 'Remodel debris — cabinets, flooring, drywall chunks, fixtures — is fair game when bagged or piled accessibly. Dense concrete and dirt may need a separate plan. Photo quotes help us send the right truck size.',
        ],
    ];

    public function chat(string $message, ?string $sessionId = null): array
    {
        $reply = null;
        $source = 'demo';

        if ($key = config('services.openai.key')) {
            try {
                $reply = $this->callOpenAi($message, $key);
                $source = 'openai';
            } catch (\Throwable) {
                $reply = null;
            }
        }

        if (! $reply && ($gemini = config('services.gemini.key'))) {
            try {
                $reply = $this->callGemini($message, $gemini);
                $source = 'gemini';
            } catch (\Throwable) {
                $reply = null;
            }
        }

        if (! $reply) {
            $reply = $this->demoReply($message);
            $source = 'demo';
        }

        ChatLog::query()->create([
            'session_id' => $sessionId,
            'message' => $message,
            'reply' => $reply,
            'source' => $source,
        ]);

        return ['reply' => $reply, 'source' => $source];
    }

    public function generate(string $type, string $prompt): string
    {
        $message = match ($type) {
            'blog' => "Write a concise SEO blog post outline and intro for a junk removal company about: {$prompt}",
            'description' => "Write a compelling product/service description for junk removal: {$prompt}",
            'automation' => "Suggest 5 practical AI automations for a junk removal SME ERP related to: {$prompt}",
            'troubleshoot' => "Troubleshoot this junk removal operations issue with clear steps: {$prompt}",
            default => $prompt,
        };

        $result = $this->chat($message);

        return $result['reply'];
    }

    protected function demoReply(string $message): string
    {
        $hay = Str::lower($message);
        $best = null;
        $bestScore = 0;

        foreach ($this->faq as $item) {
            $score = 0;
            foreach (explode(' ', $item['q']) as $word) {
                if ($word !== '' && str_contains($hay, $word)) {
                    $score++;
                }
            }
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $item['a'];
            }
        }

        if ($best && $bestScore > 0) {
            return $best;
        }

        return 'Thanks for reaching Clearway Junk Co. We haul furniture, appliances, garage piles, and remodel debris across Austin and Central Texas. Ask about pricing, same-day pickup, estate cleanouts, or prohibited items — or call (512) 555-0194.';
    }

    protected function callOpenAi(string $message, string $key): string
    {
        $response = Http::withToken($key)
            ->timeout(30)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('services.openai.model', 'gpt-4o-mini'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are the Clearway Junk Co. assistant for an Austin / Central Texas junk hauling company. Be concise, practical, and focused on volume pricing, what can be hauled, estate/garage cleanouts, appliance removal, donation sorting, and same-day scheduling. Never invent illegal claims.',
                    ],
                    ['role' => 'user', 'content' => $message],
                ],
                'temperature' => 0.4,
            ]);

        $response->throw();

        return (string) data_get($response->json(), 'choices.0.message.content', 'Unable to generate a response.');
    }

    protected function callGemini(string $message, string $key): string
    {
        $model = config('services.gemini.model', 'gemini-1.5-flash');
        $response = Http::timeout(30)
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$key}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => "You are Clearway Junk Co. assistant (Austin junk hauling). Answer briefly:\n{$message}"],
                        ],
                    ],
                ],
            ]);

        $response->throw();

        return (string) data_get($response->json(), 'candidates.0.content.parts.0.text', 'Unable to generate a response.');
    }
}
