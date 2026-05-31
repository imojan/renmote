<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * Persist the requested locale in the session and redirect back.
     */
    public function switch(Request $request, string $locale): RedirectResponse
    {
        $supported = config('app.supported_locales', ['id', 'en']);

        if (in_array($locale, $supported, true)) {
            $request->session()->put('locale', $locale);
        }

        return back();
    }
}
