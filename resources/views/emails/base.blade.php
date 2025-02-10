<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    @php /*
        The CSS stylesheet which will be inlined by fedeisas/laravel-mail-css-inliner :
        <link rel="stylesheet" type="text/css" href="{{ asset('css/mail.css') }}">
        However, tailwind css must be processed by Vite, so any TW stylesheet must be
        provided to laravel-mail-css-inliner via config, like this:
                Config::set('css-inliner.css-contents',
                    [fn() => Vite::content('resources/css/mail.css')],
                );

    */ @endphp

    @if($subject = $attributes->get('subject'))
        <title>{{ $subject }}</title>
    @endif
    <style>
    </style>
</head>

<body>
    <div class="w-full h-full px-8 py-12">
        <header>
            <table>
                <tr>
                    <td>
                        <img src="{{$logoUrl}}" class="logo" alt="Logo ADN Voyage" />
                    </td>
                    <td style="padding: 0 0 0 1em">
                        Rue le Corbusier 8<br>
                        1208 Genève<br>
                        +41 76 304 00 07<br>
                    </td>
                </tr>
            </table>
        </header>
        {{ $slot }}
        <footer>
        </footer>
    </div>
</body>

</html>