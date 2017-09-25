<?php

namespace ActiveRest\Concerns;

/**
 * Trait HasOrmOperations
 *
 * @package ActiveRest\Concerns
 */
trait HasOrmOperations
{
    use HasModel,
        HasFunctions,
        HasFind,
        HasFindOne,
        HasPost,
        HasPatch,
        HasPut,
        HasDel,
        HasLast,
        HasReplicate;
}