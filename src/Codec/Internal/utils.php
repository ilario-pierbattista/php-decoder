<?php declare(strict_types=1);

namespace Pybatt\Codec\Internal;

use Pybatt\Codec\Codec;

/**
 * @param non-empty-array<array-key, Codec> $props
 * @return string
 */
function nameFromProps(array $props): string
{
    return sprintf(
        '{%s}',
        implode(
            ', ',
            array_map(
                static function (Codec $t, $k): string {
                    return sprintf(
                        '%s: %s',
                        is_string($k) ? $k : sprintf('[%d]', $k),
                        $t->getName()
                    );
                },
                $props,
                array_keys($props)
            )
        )
    );
}

/**
 * @param mixed $x
 * @return string
 */
function typeof($x): string {
    if(is_object($x)) {
        return get_class($x);
    }

    return gettype($x);
}
