<?php
namespace ActiveRest\Concerns;

/**
 * Trait HasFunctions
 * @package ActiveRest\Concerns
 */
trait HasFunctions
{
    /**
     * Converte um array fornecido como argumento em um array multi-dimensional caso esse não for
     * @param $array
     * @param string $filterType
     */
    protected function simpleArraytoMulti(
        &$array,
        $filterType = 'is_string'
    ) {
        $filteredArray = count(
            array_filter(
                array_keys($array),
                $filterType
            )
        ) > 0 ? [$array] : $array;

        $array = $filteredArray;
    }
}