<?php

namespace App\Rules;

use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\Rule;

class isValidPassword implements Rule
{
    /**
     * Determine if the Length Validation Rule passes.
     *
     * @var boolean
     */
    public $lengthPasses = true;

    /**
     * Determine if the Uppercase Validation Rule passes.
     *
     * @var boolean
     */
    public $uppercasePasses = true;

    /**
     * Determine if the Numeric Validation Rule passes.
     *
     * @var boolean
     */
    public $numericPasses = true;

    /**
     * Determine if the Special Character Validation Rule passes.
     *
     * @var boolean
     */
    public $specialCharacterPasses = true;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // $this->lengthPasses = (Str::length($value) >= 8);
        // $this->uppercasePasses = (Str::lower($value) !== $value);
        // $this->numericPasses = ((bool) preg_match('/[0-9]/', $value));
        // $this->specialCharacterPasses = ((bool) preg_match('/[^A-Za-z0-9]/', $value));

        // return ($this->lengthPasses && $this->uppercasePasses && $this->numericPasses && $this->specialCharacterPasses);

        // Numeric n Character
        $this->numericCharacterPasses = ((bool) preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $value));
        return ($this->numericCharacterPasses);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        switch (true) {
            case ! $this->numericCharacterPasses:
                return ':attribute harus minimal 8 karakter dan mengandung setidaknya angka dan karakter.';
            default:
                return ':attribute harus minimal 8 karakter';
        }
    }
}
