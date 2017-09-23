<?php

namespace ActiveRest\Contracts;

/**
 * Interface HasDelContract
 *
 * @package ActiveRest\Contracts
 */
interface HasDelContract
{
    /**
     * Método que implementa a exclusão
     * @param array $params
     * @return array
     */
    public function del(array $params = []): array;
}