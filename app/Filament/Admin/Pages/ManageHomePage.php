<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Concerns\NormalisesSettingsData;
use App\Models\GalleryPhoto;
use App\Settings\HomePageSettings;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;
use UnitEnum;

class ManageHomePage extends SettingsPage
{
    use NormalisesSettingsData {
        mutateFormDataBeforeSave as protected normaliseSettingsData;
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static string $settings = HomePageSettings::class;

    protected static ?string $navigationLabel = 'Home page';

    protected static ?string $title = 'Home page content';

    protected static string|UnitEnum|null $navigationGroup = 'Page content';

    protected static ?int $navigationSort = 10;

    /**
     * The Order list is built from the live featured videos (not just the saved ids), so a
     * video featured since the last save shows up — at the end — and one un-featured or
     * unpublished drops out.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['videos_order'] = GalleryPhoto::featuredOnHome()
            ->map(fn (GalleryPhoto $video): array => [
                'id' => $video->id,
                'caption' => $video->caption ?: 'Video',
                'category' => $video->category,
                'thumb' => (string) $video->thumbnailUrl(),
            ])
            ->all();

        return $data;
    }

    /**
     * The list's items are display rows; what's stored is just their ids, in order.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['videos_order'] = collect($data['videos_order'] ?? [])
            ->pluck('id')
            ->filter()
            ->map(fn ($id): int => (int) $id)
            ->values()
            ->all();

        return $this->normaliseSettingsData($data);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Hero')
                ->icon('heroicon-o-megaphone')
                ->description('The background slides themselves are managed under Content → Hero slides.')
                ->columns(2)
                ->components([
                    TextInput::make('hero_eyebrow')->maxLength(120),
                    TextInput::make('hero_emphasis')
                        ->label('Headline emphasis')
                        ->helperText('Substring of the headline shown in italics.')
                        ->maxLength(120),
                    Textarea::make('hero_headline')->rows(2)->maxLength(255)->columnSpanFull()
                        ->helperText('A single newline breaks the headline across two lines.'),
                    Textarea::make('hero_lead')->rows(3)->maxLength(600)->columnSpanFull(),
                    TextInput::make('hero_primary_label')->maxLength(60),
                    TextInput::make('hero_primary_url')->maxLength(255),
                    TextInput::make('hero_secondary_label')->maxLength(60),
                    TextInput::make('hero_secondary_url')->maxLength(255),
                ]),

            Section::make('Stats bar')
                ->icon('heroicon-o-chart-bar')
                ->components([
                    Repeater::make('stats')
                        ->hiddenLabel()
                        ->schema([
                            TextInput::make('value')->required()->maxLength(12),
                            TextInput::make('suffix')->maxLength(8)->helperText('e.g. km, %'),
                            TextInput::make('label')->required()->maxLength(120),
                        ])
                        ->columns(3)
                        ->reorderable()
                        ->defaultItems(0)
                        ->addActionLabel('Add stat'),
                ]),

            Section::make('About teaser')
                ->icon('heroicon-o-user')
                ->columns(2)
                ->components([
                    TextInput::make('about_eyebrow')->maxLength(120),
                    TextInput::make('about_cta_label')->maxLength(60),
                    Textarea::make('about_headline')->rows(3)->maxLength(255)->columnSpanFull(),
                    Textarea::make('about_body')->rows(5)->maxLength(1200)->columnSpanFull(),
                    ImageEntry::make('about_image_preview')
                        ->label('Currently live')
                        ->state(fn (): string => app(HomePageSettings::class)->aboutImageUrl())
                        ->imageHeight(120)
                        ->columnSpanFull(),
                    FileUpload::make('about_image')
                        ->label('Portrait')
                        ->helperText('Shown beside the about copy. Leave empty to use the bundled photo above.')
                        ->image()->imageEditor()
                        ->disk('public')->directory('home')->visibility('public')->maxSize(6144)
                        ->columnSpanFull(),
                ]),

            Section::make('Featured video')
                ->icon('heroicon-o-play-circle')
                ->columns(2)
                ->components([
                    TextInput::make('video_eyebrow')->maxLength(120),
                    TextInput::make('video_youtube_id')->label('YouTube video ID')
                        ->helperText('Used only when no video file is uploaded below.')
                        ->maxLength(20),
                    TextInput::make('video_headline')->maxLength(160)->columnSpanFull(),
                    Textarea::make('video_body')->rows(4)->maxLength(800)->columnSpanFull(),
                    Textarea::make('video_quote')->rows(3)->maxLength(400)->columnSpanFull(),
                    TextEntry::make('video_preview')
                        ->label('Currently live')
                        ->html()
                        ->state(function (): HtmlString {
                            $home = app(HomePageSettings::class);
                            $videoUrl = $home->videoUrl();

                            if ($videoUrl !== null) {
                                return new HtmlString(
                                    '<video controls preload="metadata" style="max-width:280px;border-radius:8px" src="'.e($videoUrl).'"></video>'
                                );
                            }

                            // videoUrl() is only null when a YouTube id is set and takes over.
                            $youtubeId = e($home->video_youtube_id);

                            return new HtmlString(
                                '<a href="https://youtu.be/'.$youtubeId.'" target="_blank" rel="noopener">'
                                .'<img src="https://img.youtube.com/vi/'.$youtubeId.'/hqdefault.jpg" style="max-width:280px;border-radius:8px" alt="Currently live: YouTube video">'
                                .'</a>'
                            );
                        })
                        ->columnSpanFull(),
                    FileUpload::make('video_file')
                        ->label('Video file')
                        ->helperText('MP4, up to 128 MB. Takes priority over the YouTube ID. Leave empty to use the bundled clip above.')
                        ->disk('public')->directory('home')->visibility('public')
                        ->acceptedFileTypes(['video/mp4'])->maxSize(131072)
                        ->columnSpanFull(),
                ]),

            Section::make('Home page videos')
                ->icon('heroicon-o-video-camera')
                ->description('The videos in the home page\'s video slider. Feature videos in the Gallery (or with “Add videos by link”); the heading and intro text of this section are under “Section headers” → Videos.')
                ->columnSpanFull()
                ->components([
                    // The featured videos, as a list to drag into order. Nothing is added or
                    // removed here — that's done by featuring/unfeaturing in the Gallery.
                    Text::make('No videos are featured yet, so the home page shows every published video in the Gallery\'s order. Feature some in the Gallery (or with “Add videos by link”) and they will appear here to put in order.')
                        ->color('gray')
                        ->columnSpanFull()
                        ->hidden(fn (Get $get): bool => filled($get('videos_order'))),
                    Repeater::make('videos_order')
                        ->label('Order of the featured videos')
                        ->helperText('Drag a video (or use the arrows) to change where it appears — the top one shows first in the home page slider. Videos you feature later are added at the end.')
                        ->addable(false)
                        ->deletable(false)
                        ->cloneable(false)
                        ->reorderableWithButtons()
                        ->itemLabel(fn (array $state): ?string => $state['caption'] ?? null)
                        ->columnSpanFull()
                        ->hidden(fn (Get $get): bool => blank($get('videos_order')))
                        ->schema([
                            Hidden::make('id'),
                            Flex::make([
                                Image::make(fn (Get $get): string => (string) $get('thumb'), fn (Get $get): string => (string) $get('caption'))
                                    ->imageHeight(72)
                                    ->grow(false),
                                Text::make(fn (Get $get): string => (string) $get('category'))->color('gray'),
                            ]),
                        ]),
                ]),

            Section::make('Section headers')
                ->icon('heroicon-o-bars-3')
                ->columns(2)
                ->collapsed()
                ->columnSpanFull()
                ->components([
                    TextInput::make('projects_eyebrow')->maxLength(120),
                    TextInput::make('projects_headline')->maxLength(160),
                    Textarea::make('projects_lead')->rows(2)->maxLength(400)->columnSpanFull(),
                    TextInput::make('projects_cta_label')->maxLength(60)->columnSpanFull(),

                    TextInput::make('community_eyebrow')->maxLength(120),
                    TextInput::make('community_headline')->maxLength(160),
                    Textarea::make('community_lead')->rows(2)->maxLength(400)->columnSpanFull(),

                    TextInput::make('videos_eyebrow')->maxLength(120),
                    TextInput::make('videos_headline')->maxLength(160),
                    Textarea::make('videos_lead')->rows(2)->maxLength(400)->columnSpanFull(),
                    TextInput::make('videos_cta_label')->maxLength(60)->columnSpanFull()
                        ->helperText('Videos come from the Gallery — publish or feature them there, and put the featured ones in order in “Home page videos” above. If any video is marked “Feature on home page”, only those show here; otherwise all published videos do. The section hides itself while none are published.'),

                    TextInput::make('recognition_eyebrow')->maxLength(120),
                    TextInput::make('recognition_headline')->maxLength(160),
                    Textarea::make('recognition_lead')->rows(2)->maxLength(400)->columnSpanFull(),

                    TextInput::make('milestones_eyebrow')->maxLength(120),
                    TextInput::make('milestones_headline')->maxLength(160),
                    Textarea::make('milestones_lead')->rows(2)->maxLength(400)->columnSpanFull(),
                    TextInput::make('milestones_cta_label')->maxLength(60)->columnSpanFull(),

                    TextInput::make('upcoming_eyebrow')->maxLength(120),
                    TextInput::make('upcoming_headline')->maxLength(160),
                    Textarea::make('upcoming_lead')->rows(2)->maxLength(400)->columnSpanFull(),
                    TextInput::make('upcoming_cta_label')->maxLength(60)->columnSpanFull(),
                ]),

            Section::make('National candidacy')
                ->icon('heroicon-o-flag')
                ->description('The BEN26 campaign logos in this section are fixed brand assets and aren\'t editable here.')
                ->columns(2)
                ->components([
                    TextInput::make('candidacy_eyebrow')->maxLength(120),
                    TextInput::make('candidacy_cta_label')->maxLength(60),
                    Textarea::make('candidacy_headline')->rows(2)->maxLength(255)->columnSpanFull(),
                    Textarea::make('candidacy_body')->rows(4)->maxLength(800)->columnSpanFull(),
                    TextInput::make('candidacy_cta_url')->maxLength(255)->columnSpanFull(),
                ]),

            Section::make('Closing call to action')
                ->icon('heroicon-o-flag')
                ->columns(2)
                ->components([
                    TextInput::make('cta_headline')->maxLength(160)->columnSpanFull(),
                    Textarea::make('cta_lead')->rows(2)->maxLength(400)->columnSpanFull(),
                    TextInput::make('cta_primary_label')->maxLength(60),
                    TextInput::make('cta_primary_url')->maxLength(255),
                    TextInput::make('cta_secondary_label')->maxLength(60),
                    TextInput::make('cta_secondary_url')->maxLength(255),
                ]),
        ]);
    }
}
