<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    protected array $supportedLocales = ['en', 'id'];

    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (in_array($locale, $this->supportedLocales)) {
            $request->session()->put('locale', $locale);
            App::setLocale($locale);
        }

        return redirect()->back();
    }
}
