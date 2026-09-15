@component('mail::message')
Hi {{ $name }},

Talib Ahmed Bensouda would be grateful if you'd share a short testimonial about working with him or Kanifing Municipal Council. It only takes a minute, and you can come back and edit it any time before it's reviewed.

@component('mail::button', ['url' => $url])
Share Your Testimonial
@endcomponent

Thanks,<br>
The Talib Bensouda team
@endcomponent
