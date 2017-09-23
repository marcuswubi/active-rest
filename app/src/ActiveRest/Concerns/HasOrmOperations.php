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
        HasPost,
        HasPatch,
        HasPut,
        HasDel,
        HasLast,
        HasReplicate;
}