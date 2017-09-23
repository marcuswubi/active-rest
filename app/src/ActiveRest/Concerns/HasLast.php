<?php

namespace ActiveRest\Concerns;

use Solis\Breaker\TException;

/**
 * Trait HasLast
 *
 * @package ActiveRest\Concerns
 */
trait HasLast
{
    // Mensagens
    protected static $MSG_LAST_FAIL = 'Falha ao encontrar o ultimo registro';

    /**
     * @return array
     */
    public function last(): array
    {
        try {
            $last = $this->getModel()->last();
            if (!empty($last)) {
                if(is_array($last)){
                    return $last[0]->toArray();
                }
                return $last->toArray();
            }

            return [self::$MSG_LAST_FAIL];
        } catch (TException $exception) {
            return $exception->toArray();
        }
    }

}