<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Formable;

class FormableExtras
{
    protected $formable;

    protected $extras = [];

    protected $locales;

    public function __construct($extras = array(), Formable $formable, $locale)
    {
        $this->locales = config('formable.locales');

        $locale = trim(strtolower($locale));

        if ($locale && (! in_array($locale, $this->locales))) {
            // Verið að reyna að vinna með tungumál sem er ekki í boði
            abort(500, 'Villa: Reynt að nota tungumál "'.$locale.'" sem ekki er stillt til noktunar.');
        }

        $this->extras = $extras ?: [];
        $this->formable = $formable;

        if ($locale) {
            $this->locale = $locale;
        } else {
            $this->locale = config('app.locale');
        }
    }

    public function add($attribute, $extra = '')
    {
        if (! $this->locale) {
            abort(500, 'Villa: Þarf að taka fram hvaða tungumál á að bæta við þýðingu fyrir.');
        }

        $this->extras[$this->locale][$attribute] = strlen($extra) > 0 ? $extra : null;
        $this->persist();
    }

    public function get($attribute)
    {
        if (! $this->locale) {
            // Sækja bara default locale ef það er til...
            $this->locale = config('app.locale');
        }

        if (array_key_exists($this->locale, $this->extras)) {
            if (array_key_exists(strtolower(trim($attribute)), $this->extras[$this->locale])) {
                return $this->extras[$this->locale][strtolower(trim($attribute))];
            }
        } else {
            // Ekki til í extras... skila bara original attribute
            // return $this->formable->{$attribute};
        }

        return '';
    }

    protected function persist()
    {
        return $this->formable->update(['extras' => $this->extras]);
    }
}