<?php

namespace ActiveRest\Contracts;

/**
 * Class HasLastContract
 *
 * @package ActiveRest\Contracts
 */
interface HasLastContract
{
    /**
     * @return mixed
     */
    public function last(): array;
}