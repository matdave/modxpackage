<?php

namespace MatDave\MODXPackage\Traits;

trait Utils
{
    public function explodeAndClean(string $string, $delimiter = ',', $mapMethod = '', $keepDuplicates = 0, $filterCallback = null): array
    {
        $array = explode($delimiter, $string);
        $array = array_map('trim', $array);

        if (!empty($filterCallback)) {
            $array = array_filter($array, $filterCallback);
        } else {
            $array = array_filter($array);
        }

        if (!empty($mapMethod)) {
            $array = array_map($mapMethod, $array);
        }

        if ($keepDuplicates == 0) {
            $array = array_keys(array_flip($array));
        }

        if (!empty($filterCallback)) {
            return array_filter($array, $filterCallback);
        }

        return array_filter($array);
    }
}
