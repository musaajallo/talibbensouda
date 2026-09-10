<?php

namespace App\Data;

use Spatie\LaravelData\Data;

/**
 * Base DTO for the app. Extend this for typed request payloads, API resources,
 * and value objects so casts and serialization defaults stay consistent.
 *
 * Example:
 *
 *   class CreatePostData extends AppData
 *   {
 *       public function __construct(
 *           public string $title,
 *           public string $body,
 *           public ?Carbon $published_at = null,
 *       ) {}
 *   }
 *
 *   $data = CreatePostData::from($request);
 */
abstract class AppData extends Data {}
