<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>{{ isset($title) ? $title . ' | ' : '' }}{{ config('app.name', 'CEAF') }}</title>


<link rel="icon"  href="{{ asset('images/ceaflogo.png') }}" sizes="any">
<link rel="icon"  href="{{ asset('images/ceaflogo.png') }}" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
