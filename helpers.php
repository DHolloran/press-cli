<?php
/**
 * Press CLI Helper Functions
 */

/**
 * Recursively collects an array.
 *
 * @param  array $array
 *
 * @return Illuminate\Support\Collection
 */
function rcollect($array)
{
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $value = rcollect($value);
            $array[$key] = $value;
        }
    }

    return collect($array);
}
