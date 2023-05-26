<?php

namespace App\Helpers;

/**
 * Overwrite translator class for overwrite app edition file value.
 */
class Translator extends \Illuminate\Translation\Translator
{
    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        $translation = null;

        // Check for tenant's customer type or else Check for selected customer's customer type.
        $customerType = config('bdgo.customer_type');
        if (!empty($customerType)) {
            $translation = $this->getTranslation($key, $replace, null, $fallback, $customerType);
        }

        // Check for edition.
        if (empty($translation)) {
            $translation = $this->getTranslation($key, $replace, null, $fallback);
        }

        // Check default.
        if (empty($translation)) {
            $translation = $this->getTranslation($key, $replace, null, $fallback, null, null);
        }

        return $translation;
    }

    public function getTranslation($key, array $replace = [], $locale = null, $fallback = true, $customerType = null, $appEdition = APP_EDITION)
    {
        $locale = $locale ?: $this->locale;

        // For JSON translations, there is only one file per locale, so we will simply load
        // that file and then we will be ready to check the array for the key. These are
        // only one level deep so we do not need to do any fancy searching through it.
        $this->load('*', '*', $locale);

        $line = $this->loaded['*']['*'][$locale][$key] ?? null;

        // If we can't find a translation for the JSON key, we will attempt to translate it
        // using the typical translation file. This way developers can always just use a
        // helper such as __ instead of having to pick between trans or __ with views.
        if (! isset($line)) {
            [$namespace, $group, $item] = $this->parseKey($key);

            // Check in customer type and app edition.
            if (!empty($appEdition) && !empty($item)) {
                if (!empty($customerType)) {
                    $group = $appEdition . '/' . $customerType;
                } else {
                    $group = $appEdition . '/' . $appEdition;
                }
            }

            // Here we will get the locale that should be used for the language line. If one
            // was not passed, we will use the default locales which was given to us when
            // the translator was instantiated. Then, we can load the lines and return.
            $locales = $fallback ? $this->localeArray($locale) : [$locale];

            foreach ($locales as $locale) {
                if (! is_null($line = $this->getLine(
                    $namespace, $group, $locale, $item, $replace
                ))) {
                    return $line;
                }
            }
        }

        if (empty($line)) {
            if (!empty($customerType) || !empty($appEdition)) {
                return null;
            }
        }

        // If the line doesn't exist, we will return back the key which was requested as
        // that will be quick to spot in the UI if language keys are wrong or missing
        // from the application's language files. Otherwise we can return the line.
        return $this->makeReplacements($line ?: $key, $replace);
    }

    /**
     * Parse a key into namespace, group, and item.
     * Edit : Add default item if not set for e.g. (__('Site Admin')).
     *
     * @param  string  $key
     * @return array
     */
    /* public function parseKey($key)
    {
        $segments = parent::parseKey($key);

        if (is_null($segments[0])) {
            $segments[0] = '*';
        }

        if (is_null($segments[2])) {
            $segments[2] = $segments[1];

            $segments[1] = "locale";
        }

        return $segments;
    } */
}
