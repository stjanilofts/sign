<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Formable;

class FormableTranslate
{
    protected $formable;

    protected $translations = [];

    protected $locale;

    protected $default_locale;

    // Hvaða tungumál eru í boði?
    protected $locales;

    public function __construct($translations = array(), Formable $formable, $locale)
    {
        $this->locales = config('formable.locales');

        $this->default_locale = $this->locales[0];

        $locale = trim(strtolower($locale));

        if ($locale && (! in_array($locale, $this->locales))) {
            // Verið að reyna að vinna með tungumál sem er ekki í boði
            abort(500, 'Villa: Reynt að nota tungumál "'.$locale.'" sem ekki er stillt til noktunar.');
        }

        $this->translations = $translations ?: [];
        $this->formable = $formable;

        if ($locale) {
            $this->locale = $locale;
        }
    }

    public function add($attribute, $translation = '')
    {
        if (! $this->locale) {
            abort(500, 'Villa: Þarf að taka fram hvaða tungumál á að bæta við þýðingu fyrir.');
        }

        $this->translations[$this->locale][$attribute] = $translation;
        $this->persist();
    }

    public function has($attribute)
    {
        if(is_array($this->translations)) {
            if(array_key_exists($this->locale, $this->translations)) {
                if($this->translations[$this->locale][$attribute] != '') {
                    return true;
                }
            }
        }

        if($this->locale == $this->default_locale && $this->formable->{$attribute} != '') {
            return true;
        }

        return false;
    }

    public function get($attribute)
    {
        if (! $this->locale) {
            // Sækja bara default locale ef það er til...
            $this->locale = config('app.locale');
        }

        if (array_key_exists($this->locale, $this->translations)) {
            if (array_key_exists(strtolower(trim($attribute)), $this->translations[$this->locale])) {
                return $this->translations[$this->locale][strtolower(trim($attribute))];
            }
        } else {
            // Ekki til í translations... skila bara original attribute
            return $this->formable->{$attribute};
        }
    }

    protected function persist()
    {
        return $this->formable->update(['translations' => $this->translations]);
    }
}