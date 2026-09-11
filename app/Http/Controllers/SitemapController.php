<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/')->setPriority(1.0)->setChangeFrequency('weekly'))
            ->add(Url::create('/about')->setPriority(0.8)->setChangeFrequency('monthly'))
            ->add(Url::create('/peoples-mayor')->setPriority(0.8)->setChangeFrequency('monthly'))
            ->add(Url::create('/gallery')->setPriority(0.7)->setChangeFrequency('weekly'))
            ->add(Url::create('/events')->setPriority(0.9)->setChangeFrequency('daily'))
            ->add(Url::create('/giving-back')->setPriority(0.7)->setChangeFrequency('monthly'))
            ->add(Url::create('/contact')->setPriority(0.8)->setChangeFrequency('yearly'))
            ->add(Url::create('/privacy')->setPriority(0.3)->setChangeFrequency('yearly'))
            ->add(Url::create('/cookies')->setPriority(0.3)->setChangeFrequency('yearly'))
            ->add(Url::create('/sitemap')->setPriority(0.3)->setChangeFrequency('weekly'));

        Event::orderByDesc('ics_start')->each(function (Event $event) use ($sitemap) {
            $sitemap->add(
                Url::create(route('events.show', $event))
                    ->setPriority(0.8)
                    ->setChangeFrequency('weekly')
            );
        });

        return response($sitemap->render(), 200, ['Content-Type' => 'application/xml']);
    }
}
