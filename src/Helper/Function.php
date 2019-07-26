<?php
/**
 * Created by PhpStorm.
 * User: k
 * Date: 2019/7/25
 * Time: 10:35
 */
function findInitialTokenPosition($input)
{
    $pos = 0;

    // search for first valid annotation
    while (($pos = strpos($input, '@', $pos)) !== false) {
        $preceding = substr($input, $pos - 1, 1);

        // if the @ is preceded by a space, a tab or * it is valid
        if ($pos === 0 || $preceding === ' ' || $preceding === '*' || $preceding === "\t") {
            return $pos;
        }

        $pos++;
    }

    return null;
}