<?php

namespace App\Filament\Admin\Pages;

use App\Models\GalleryPhoto;
use App\Support\YouTube\YouTubeChannelClient;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * Browse/search the campaign's YouTube channel and hand-pick which videos
 * become GalleryPhoto rows (type=video) — see that model's docblock for why
 * they share a table with photos. Nothing here is a Filament Resource
 * because there's no local Eloquent list to back a Table with; the "records"
 * live on YouTube and are fetched live via YouTubeChannelClient.
 */
class ImportYoutubeVideos extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedVideoCamera;

    protected static ?string $navigationLabel = 'Import YouTube Videos';

    protected static ?string $title = 'Import YouTube Videos';

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 22;

    protected string $view = 'filament.admin.pages.import-youtube-videos';

    public string $search = '';

    /** @var list<array{id: string, title: string, published_at: ?string, thumbnail: ?string}> */
    public array $videos = [];

    public ?string $nextPageToken = null;

    public function mount(): void
    {
        $this->loadVideos();
    }

    public function updatedSearch(): void
    {
        $this->loadVideos();
    }

    public function isConfigured(): bool
    {
        return app(YouTubeChannelClient::class)->isConfigured();
    }

    public function loadVideos(): void
    {
        $client = app(YouTubeChannelClient::class);

        if (! $client->isConfigured()) {
            $this->videos = [];
            $this->nextPageToken = null;

            return;
        }

        $result = filled($this->search)
            ? $client->searchVideos($this->search)
            : $client->listVideos();

        $this->videos = $result['videos'];
        $this->nextPageToken = $result['next_page_token'];
    }

    public function loadMore(): void
    {
        if (! $this->nextPageToken) {
            return;
        }

        $client = app(YouTubeChannelClient::class);

        $result = filled($this->search)
            ? $client->searchVideos($this->search, $this->nextPageToken)
            : $client->listVideos($this->nextPageToken);

        $this->videos = [...$this->videos, ...$result['videos']];
        $this->nextPageToken = $result['next_page_token'];
    }

    public function alreadyImported(string $videoId): bool
    {
        return GalleryPhoto::where('youtube_video_id', $videoId)->exists();
    }

    public function addToGalleryAction(): Action
    {
        return Action::make('addToGallery')
            ->label('Add to Gallery')
            ->modalHeading('Add video to the Gallery')
            ->modalSubmitActionLabel('Add to Gallery')
            ->schema([
                Select::make('category')
                    ->options(array_combine(GalleryPhoto::CATEGORIES, GalleryPhoto::CATEGORIES))
                    ->default('Projects')
                    ->required(),
                TextInput::make('caption')
                    ->label('Caption')
                    ->maxLength(255)
                    ->required(),
                Toggle::make('published')
                    ->default(true),
            ])
            ->fillForm(fn (array $arguments): array => [
                'caption' => $arguments['title'] ?? '',
            ])
            ->action(function (array $data, array $arguments): void {
                $videoId = (string) $arguments['videoId'];

                if (GalleryPhoto::where('youtube_video_id', $videoId)->exists()) {
                    Notification::make()
                        ->title('Already in the Gallery')
                        ->warning()
                        ->send();

                    return;
                }

                GalleryPhoto::create([
                    'caption' => $data['caption'],
                    'category' => $data['category'],
                    'type' => GalleryPhoto::TYPE_VIDEO,
                    'youtube_video_id' => $videoId,
                    'published' => $data['published'],
                    'sort_order' => 0,
                ]);

                Notification::make()
                    ->title('Added to the Gallery')
                    ->success()
                    ->send();
            });
    }
}
