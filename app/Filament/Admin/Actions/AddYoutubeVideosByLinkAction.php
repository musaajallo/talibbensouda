<?php

namespace App\Filament\Admin\Actions;

use App\Filament\Admin\Resources\GalleryPhotos\GalleryPhotoResource;
use App\Models\GalleryPhoto;
use App\Support\YouTube\AddVideosByLink;
use App\Support\YouTube\YouTubeUrl;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;

/**
 * "Add videos by link": paste YouTube links, get Gallery entries. Used on the
 * YouTube Videos page and the Gallery list, so an editor finds it wherever they
 * look. Titles come from YouTube (see AddVideosByLink) and can be edited after.
 */
class AddYoutubeVideosByLinkAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'addYoutubeVideosByLink';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Add videos by link')
            ->icon(Heroicon::OutlinedLink)
            ->visible(fn (): bool => GalleryPhotoResource::canCreate())
            ->modalHeading('Add YouTube videos by link')
            ->modalDescription('Paste one or more YouTube links — one per line. Each caption is taken from the video\'s YouTube title, and you can edit it afterwards in the Gallery. Works for any public video, not just the campaign\'s own channel.')
            ->modalSubmitActionLabel('Add to Gallery')
            ->modalWidth('2xl')
            ->schema([
                Textarea::make('links')
                    ->label('YouTube links')
                    ->placeholder("https://www.youtube.com/watch?v=…\nhttps://youtu.be/…")
                    ->rows(6)
                    ->required()
                    ->rules([fn (): Closure => $this->linksRule()]),
                Select::make('category')
                    ->options(array_combine(GalleryPhoto::CATEGORIES, GalleryPhoto::CATEGORIES))
                    ->default('Projects')
                    ->required(),
                Grid::make(2)->schema([
                    Toggle::make('published')
                        ->default(true),
                    Toggle::make('featured_on_home')
                        ->label('Feature on home page')
                        ->default(false)
                        ->helperText('Once any video is featured, the home page shows only featured videos.'),
                ]),
            ])
            ->action(function (array $data): void {
                $result = app(AddVideosByLink::class)->handle(
                    (string) $data['links'],
                    (string) $data['category'],
                    (bool) ($data['published'] ?? true),
                    (bool) ($data['featured_on_home'] ?? false),
                );

                $this->notify($result);
            });
    }

    /** At least one recognisable link, and not an unreasonable number. */
    protected function linksRule(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            $parsed = YouTubeUrl::parseMany((string) $value);

            if ($parsed['ids'] === []) {
                $fail('None of that looks like a YouTube link.');
            } elseif (count($parsed['ids']) > AddVideosByLink::MAX_LINKS) {
                $fail('Add at most '.AddVideosByLink::MAX_LINKS.' videos at a time.');
            }
        };
    }

    /**
     * @param  array{added: list<string>, existing: list<string>, promoted: list<string>, invalid: list<string>, unavailable: list<string>, unchecked: list<string>}  $result
     */
    protected function notify(array $result): void
    {
        $lines = [];

        if ($result['existing'] !== []) {
            $lines[] = 'Already in the Gallery ('.count($result['existing']).')'
                .($result['promoted'] !== [] ? ' — now featured: '.$this->list($result['promoted']) : '').'.';
        }
        if ($result['unavailable'] !== []) {
            $lines[] = 'No public, embeddable video found for: '.$this->list($result['unavailable']).' (private, removed, or embedding switched off).';
        }
        if ($result['unchecked'] !== []) {
            $lines[] = 'Couldn\'t reach YouTube to check: '.$this->list($result['unchecked']).' — try again in a moment.';
        }
        if ($result['invalid'] !== []) {
            $lines[] = 'Not a YouTube link: '.$this->list($result['invalid']).'.';
        }

        $added = count($result['added']);
        $problems = $result['unavailable'] !== [] || $result['unchecked'] !== [] || $result['invalid'] !== [];

        $notification = Notification::make()
            ->title($added > 0 ? ($added === 1 ? 'Added 1 video to the Gallery' : "Added {$added} videos to the Gallery") : 'No new videos added')
            ->body($lines === [] ? null : new HtmlString(implode('<br>', array_map('e', $lines))));

        // "Already in the Gallery" is information, not a problem; only links that
        // couldn't be added turn it into a warning. Keep anything with a message
        // on screen until it's dismissed — it names links the editor may need — but
        // let a plain "Added 5 videos" fade like any other toast. (persistent() takes
        // no argument: passing false does NOT switch it off.)
        $problems ? $notification->warning() : $notification->success();

        if ($lines !== []) {
            $notification->persistent();
        }

        $notification->send();
    }

    /**
     * @param  list<string>  $items
     */
    protected function list(array $items): string
    {
        return implode(', ', $items);
    }
}
