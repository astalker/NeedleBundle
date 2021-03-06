<?php

namespace Markup\NeedleBundle\Collator;

/**
 * Typed collator for numbers.
 */
class NumberCollator implements TypedCollatorInterface
{
    const TYPE = 'number';

    /**
     * Compare two values on a linear scale.
     *
     * @param mixed $value1
     * @param mixed $value2
     *
     * @return int Returns 1 if first operand is greater than second, 0 if they are equal, -1 if first operand less than second.
     **/
    public function compare($value1, $value2)
    {
        if ($value1 > $value2) {
            return 1;
        } elseif ($value1 == $value2) {
            return 0;
        }

        return -1;
    }

    /**
     * Gets the type (name) of this collator.
     *
     * @return string
     */
    public function getType()
    {
        return self::TYPE;
    }

    /**
     * Gets whether this collator has the type for the provided value (i.e. whether the value is in the type's domain).
     *
     * @param string $value
     * @return bool
     */
    public function hasTypeFor($value)
    {
        return is_numeric($value);
    }
}
