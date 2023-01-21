<?php
declare(strict_types = 1);

namespace SnoerenDevelopment\CurrencyCasting;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Currency implements CastsAttributes
{
    /**
     * The amount of digits.
     *
     * @var integer
     */
    protected $digits;

    /**
     * Whether to force showing of the number of decimals
     *
     * @var boolean
     */
    protected $forceDecimals;

    /**
     * Constructor
     *
     * @param  integer $digits The amount of digits to handle.
     * @return void
     *
     * @throws \InvalidArgumentException Thrown on invalid input.
     */
    public function __construct(int $digits = 2, bool $forceDecimals = false)
    {
        if ($digits < 1) {
            throw new \InvalidArgumentException('Digits should be a number larger than zero.');
        }

        $this->digits = $digits;
        $this->forceDecimals = $forceDecimals;
    }

    /**
     * Transform the attribute from the underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model      The model object.
     * @param  string                              $key        The property name.
     * @param  mixed                               $value      The property value.
     * @param  array                               $attributes The model attributes array.
     * @return float
     */
    public function get($model, string $key, $value, array $attributes)
    {
        $returnValue = null;
        if($value !== null) {
            if($this->forceDecimals) {
                // make sure the decmials are included in returned values
                $returnValue = number_format(round($value / (10 ** $this->digits), $this->digits), $this->digits, '.', '');
            } else {
                // allow rounding to behave normally
                $returnValue = round($value / (10 ** $this->digits), $this->digits);
            }
        }

        return $returnValue;
    }

    /**
     * Transform the attribute to its underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model      The model object.
     * @param  string                              $key        The property name.
     * @param  mixed                               $value      The property value.
     * @param  array                               $attributes The model attributes array.
     * @return integer
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if(!is_null($value)) {
            return (int) ($value * (10 ** $this->digits));
        }

        return $value;

    }
}
