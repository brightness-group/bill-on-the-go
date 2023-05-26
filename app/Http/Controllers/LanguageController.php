<?php

namespace App\Http\Controllers;

class LanguageController extends Controller
{
    public array $availLocale = ['en' => 'en', 'de' => 'de'];

    public function swap($locale): \Illuminate\Http\RedirectResponse
    {
        // check for existing language
        if (array_key_exists($locale, $this->availLocale)) {
            session()->put('locale', $locale);
            if (auth()->check()) {
                auth()->user()->locale = $locale;
                auth()->user()->save();
            }
        }
        return redirect()->back();
    }
}
