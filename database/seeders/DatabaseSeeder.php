<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\KnowledgeArticle;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin', 'manager', 'staff'] as $role) {
            Role::findOrCreate($role);
        }

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '(512) 555-0100',
            ]
        );
        $admin->syncRoles(['admin']);

        User::query()->updateOrCreate(
            ['email' => 'manager@clearwayjunk.com'],
            [
                'name' => 'Sofia Reyes',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'phone' => '(512) 555-0101',
            ]
        )->syncRoles(['manager']);

        User::query()->updateOrCreate(
            ['email' => 'dispatch@clearwayjunk.com'],
            [
                'name' => 'Marcus Hale',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'phone' => '(512) 555-0102',
            ]
        )->syncRoles(['staff']);

        $settings = [
            'company_name' => 'Clearway Junk Co.',
            'company_phone' => '(512) 555-0194',
            'company_email' => 'hello@clearwayjunk.com',
            'whatsapp' => '15125550194',
            'service_area' => 'Austin, Round Rock, Cedar Park, Georgetown & Central Texas',
            'tax_rate' => '8.25',
        ];
        foreach ($settings as $key => $value) {
            Setting::setValue($key, $value);
        }

        $products = [
            ['name' => 'Half-Truck Junk Haul', 'sku' => 'HAUL-HALF', 'category' => 'service', 'unit_cost' => 90, 'unit_price' => 249, 'stock_qty' => 999],
            ['name' => 'Full-Truck Junk Haul', 'sku' => 'HAUL-FULL', 'category' => 'service', 'unit_cost' => 140, 'unit_price' => 449, 'stock_qty' => 999],
            ['name' => 'Appliance Pickup (each)', 'sku' => 'APP-1', 'category' => 'service', 'unit_cost' => 25, 'unit_price' => 75, 'stock_qty' => 999],
            ['name' => 'Mattress / Box Spring', 'sku' => 'MAT-1', 'category' => 'service', 'unit_cost' => 18, 'unit_price' => 55, 'stock_qty' => 999],
            ['name' => 'Estate Cleanout Day Rate', 'sku' => 'EST-DAY', 'category' => 'service', 'unit_cost' => 420, 'unit_price' => 980, 'stock_qty' => 999],
            ['name' => 'Moving Blankets (set of 6)', 'sku' => 'ACC-BLK-6', 'category' => 'accessory', 'unit_cost' => 48, 'unit_price' => 0, 'stock_qty' => 40],
            ['name' => 'Floor Protection Runners', 'sku' => 'ACC-FLR-2', 'category' => 'accessory', 'unit_cost' => 32, 'unit_price' => 0, 'stock_qty' => 28],
            ['name' => 'Landfill / Transfer Fee Pass-Through', 'sku' => 'FEE-DISP-1', 'category' => 'fee', 'unit_cost' => 0, 'unit_price' => 65, 'stock_qty' => 999],
        ];
        foreach ($products as $product) {
            Product::query()->updateOrCreate(['sku' => $product['sku']], $product + ['unit' => 'each', 'is_active' => true]);
        }

        $customers = collect([
            ['name' => 'Ava Chen', 'email' => 'ava@example.com', 'phone' => '(512) 555-3301', 'company' => null, 'city' => 'Austin', 'state' => 'TX', 'zip' => '78704', 'address' => '2100 S Lamar Blvd'],
            ['name' => 'Noah Briggs', 'email' => 'noah@hillcountrybuilds.com', 'phone' => '(512) 555-3310', 'company' => 'Hill Country Builds', 'city' => 'Round Rock', 'state' => 'TX', 'zip' => '78664', 'address' => '400 E Palm Valley Blvd'],
            ['name' => 'Priya Raman', 'email' => 'ops@lakelineflats.com', 'phone' => '(512) 555-3399', 'company' => 'Lakeline Flats PM', 'city' => 'Cedar Park', 'state' => 'TX', 'zip' => '78613', 'address' => '1100 N Lakeline Blvd'],
            ['name' => 'Evan Soto', 'email' => 'evan.soto@example.com', 'phone' => '(512) 555-3188', 'company' => null, 'city' => 'Georgetown', 'state' => 'TX', 'zip' => '78626', 'address' => '710 Main St'],
            ['name' => 'Grace Kim', 'email' => 'grace@oakandpine.org', 'phone' => '(512) 555-3177', 'company' => 'Oak & Pine Realty', 'city' => 'Austin', 'state' => 'TX', 'zip' => '78745', 'address' => '5600 Manchaca Rd'],
        ])->map(fn ($c) => Customer::query()->updateOrCreate(['email' => $c['email']], $c));

        $projects = [
            ['customer' => 0, 'title' => 'Garage declutter — half truck', 'service_type' => 'residential', 'status' => 'scheduled', 'sq_ft' => 8, 'quoted_amount' => 249],
            ['customer' => 1, 'title' => 'Kitchen remodel debris pull', 'service_type' => 'construction', 'status' => 'in_progress', 'sq_ft' => 14, 'quoted_amount' => 520],
            ['customer' => 2, 'title' => 'Unit turnover cleanouts (3)', 'service_type' => 'commercial', 'status' => 'quoted', 'sq_ft' => 20, 'quoted_amount' => 1450],
            ['customer' => 3, 'title' => 'Fridge + washer haulaway', 'service_type' => 'appliance', 'status' => 'completed', 'sq_ft' => 2, 'quoted_amount' => 150, 'final_amount' => 150],
            ['customer' => 4, 'title' => 'Estate clear — listing prep', 'service_type' => 'estate', 'status' => 'invoiced', 'sq_ft' => 30, 'quoted_amount' => 2100, 'final_amount' => 2050],
        ];

        $projectModels = [];
        foreach ($projects as $i => $p) {
            $customer = $customers[$p['customer']];
            $projectModels[] = Project::query()->updateOrCreate(
                ['job_number' => 'JOB-26'.str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'customer_id' => $customer->id,
                    'title' => $p['title'],
                    'service_type' => $p['service_type'],
                    'status' => $p['status'],
                    'sq_ft' => $p['sq_ft'],
                    'quoted_amount' => $p['quoted_amount'],
                    'final_amount' => $p['final_amount'] ?? null,
                    'start_date' => now()->subDays(10 - $i)->toDateString(),
                    'site_address' => trim(($customer->address ?? '').', '.$customer->city.', '.$customer->state),
                    'notes' => 'Seeded demo haul for Clearway ERP. sq_ft stores estimated cubic yards.',
                ]
            );
        }

        foreach ([0, 1, 3, 4] as $idx) {
            $project = $projectModels[$idx];
            $invoice = Invoice::query()->updateOrCreate(
                ['invoice_number' => 'INV-26'.str_pad((string) ($idx + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'customer_id' => $project->customer_id,
                    'project_id' => $project->id,
                    'issue_date' => now()->subDays(8 - $idx)->toDateString(),
                    'due_date' => now()->addDays(14)->toDateString(),
                    'subtotal' => $project->final_amount ?? $project->quoted_amount,
                    'tax' => round(($project->final_amount ?? $project->quoted_amount) * 0.0825, 2),
                    'total' => round(($project->final_amount ?? $project->quoted_amount) * 1.0825, 2),
                    'amount_paid' => in_array($project->status, ['completed', 'invoiced'], true) ? round(($project->final_amount ?? $project->quoted_amount) * 1.0825, 2) : 0,
                    'status' => in_array($project->status, ['completed', 'invoiced'], true) ? 'paid' : 'sent',
                ]
            );

            if ($invoice->status === 'paid') {
                Payment::query()->updateOrCreate(
                    ['reference' => 'PMT-'.$invoice->invoice_number],
                    [
                        'invoice_id' => $invoice->id,
                        'amount' => $invoice->total,
                        'paid_at' => now()->subDays(3)->toDateString(),
                        'method' => 'card',
                        'notes' => 'Demo payment',
                    ]
                );
            }
        }

        Appointment::query()->updateOrCreate(
            ['title' => 'Pickup — Chen garage half-truck'],
            [
                'customer_id' => $customers[0]->id,
                'project_id' => $projectModels[0]->id,
                'type' => 'pickup',
                'starts_at' => now()->addDays(2)->setTime(9, 0),
                'ends_at' => now()->addDays(2)->setTime(11, 0),
                'status' => 'scheduled',
                'location' => $customers[0]->address,
            ]
        );
        Appointment::query()->updateOrCreate(
            ['title' => 'Debris pull — Hill Country Builds'],
            [
                'customer_id' => $customers[1]->id,
                'project_id' => $projectModels[1]->id,
                'type' => 'haul',
                'starts_at' => now()->addDays(5)->setTime(13, 0),
                'ends_at' => now()->addDays(5)->setTime(15, 0),
                'status' => 'confirmed',
                'location' => $customers[1]->address,
            ]
        );

        $posts = [
            [
                'title' => 'How Much Does Junk Removal Cost in Austin?',
                'excerpt' => 'Most residential piles are quoted by truck volume — here is how Clearway prices half-truck vs full-truck hauls without surprise landfill fees.',
                'cover' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1400&q=80',
                'body' => "Austin junk removal is usually priced by how much space your items fill in the truck, plus stairs, heavy appliances, and disposal fees.\n\nClearway starts with photos or an on-site look, then quotes a half-truck or full-truck rate so you are not renting a dumpster you still have to load yourself.",
            ],
            [
                'title' => 'Estate Cleanout Checklist Before Listing Day',
                'excerpt' => 'Executors and agents can keep closings on track with a staged haul plan, donation diversion, and a clear keep-vs-haul list.',
                'cover' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=1400&q=80',
                'body' => "Walk each room and mark keepsakes first. Photograph piles for the quote. Unlock gates and note stairs. Clearway can stage multi-day loads so showings stay possible while bulk items leave.\n\nAsk about donation partners early — condition and facility hours matter.",
            ],
            [
                'title' => 'What Not to Put on the Curb for a Junk Crew',
                'excerpt' => 'Hazardous chemicals, wet paint, asbestos, and some Freon appliances need special handling — flag them before the truck arrives.',
                'cover' => 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?auto=format&fit=crop&w=1400&q=80',
                'body' => "Household furniture, boxed clutter, mattresses, and most appliances are fair game. Hazardous waste, wet paint, asbestos materials, and sealed chemicals are not a standard curb pickup.\n\nTell Clearway during booking so we can route special items correctly or decline them safely.",
            ],
        ];
        foreach ($posts as $post) {
            Post::query()->updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                [
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'body' => $post['body'],
                    'cover_image' => $post['cover'],
                    'is_published' => true,
                    'published_at' => now()->subDays(random_int(2, 20)),
                ]
            );
        }

        $articles = [
            ['category' => 'issues', 'title' => 'Pile bigger than the quoted truck volume', 'body' => "1. Pause loading once the quoted volume is reached.\n2. Ask the crew lead for an on-site upcharge to finish.\n3. Or schedule a second stop.\n4. Photos before booking reduce surprises."],
            ['category' => 'issues', 'title' => 'Unexpected disposal fee on invoice', 'body' => "Specialty items (TVs, Freon appliances, tires) may carry facility fees. Compare the job notes to the quote line items. For future hauls, list specialty items during booking."],
            ['category' => 'guides', 'title' => 'How to prepare for a residential junk pickup', 'body' => "Clear a path to the pile. Unlock gates. Move cars from the load zone. Separate keepsakes. Note stairs and HOA quiet hours. Send photos so Clearway brings the right crew size."],
            ['category' => 'guides', 'title' => 'Residential haul timeline from quote to empty curb', 'body' => "Day 0: Photos or on-site quote.\nDay 0–1: Book a window.\nHaul day: Crew loads, sweeps the curb, processes payment.\nSame day: Invoice appears in ERP."],
            ['category' => 'admin', 'title' => 'Admin ERP quick start', 'body' => "Login at /admin with admin@example.com / password.\n1. Add customers in CRM.\n2. Create a Job/Haul with volume estimate and quote.\n3. Schedule pickups.\n4. Convert completed jobs to invoices and record payments.\n5. Use AI Studio for blog drafts and troubleshooting.\n6. Update company Settings for phone, WhatsApp, and tax rate."],
            ['category' => 'admin', 'title' => 'Roles: Admin, Manager, Staff', 'body' => "Admin: full ERP + settings + users.\nManager: jobs, inventory, invoices, scheduling.\nStaff: field-facing records and appointment updates.\nAssign roles on the Users resource."],
            ['category' => 'troubleshoot', 'title' => 'Crew cannot access load zone', 'body' => "Confirm gate codes, parked cars, and elevator reservations. Reschedule with a clearer window or relocate items closer to the curb. Log the failed attempt on the job notes and notify the customer."],
            ['category' => 'troubleshoot', 'title' => 'Chatbot giving demo answers only', 'body' => "Demo mode is expected without API keys. Add OPENAI_API_KEY or GEMINI_API_KEY to .env, then clear config cache. The public chatbot and AI Studio will prefer live models when keys are present."],
        ];
        foreach ($articles as $article) {
            KnowledgeArticle::query()->updateOrCreate(
                ['slug' => Str::slug($article['title'])],
                [
                    'title' => $article['title'],
                    'category' => $article['category'],
                    'body' => $article['body'],
                    'is_published' => true,
                ]
            );
        }
    }
}
