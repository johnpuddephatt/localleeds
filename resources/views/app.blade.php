<!DOCTYPE html>
<html
  lang="{{ str_replace('_', '-', app()->getLocale()) }}"
  class="text-sm md:text-base"
>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Fonts -->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap"
    />

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" />

    <!-- Scripts -->
    @routes
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script>
      window.google_maps_key = "{{ env('GOOGLE_MAPS_API_KEY') }}";
    </script>
    <script
      type="text/javascript"
      src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"
    ></script>

    <script>
      function googleTranslateElementInit() {
        new google.translate.TranslateElement(
          {
            pageLanguage: "en",
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
          },
          "google_translate_element"
        );
      }
    </script>
    <style>
      .goog-te-gadget {
        font-size: 1rem;
        color: inherit;
        font-family: inherit;
        margin-left: 2rem;
      }
      .goog-te-gadget-simple {
        border: none !important;
        font-size: 1rem;
        color: inherit;
        font-family: inherit;
      }
      .goog-te-gadget-simple::before {
        content: "Translate";
        font-weight: 600;
        color: inherit;
        font-family: inherit;
      }
      .goog-te-gadget-simple * {
        display: none;
      }
    </style>

    @inertiaHead
  </head>
  <body class="w-screen overflow-x-hidden font-sans text-blue-300 antialiased">
    @inertia @env('local')


    <script src="http://localhost:3000/browser-sync/browser-sync-client.js"></script>


@endenv
  </body>
</html>
