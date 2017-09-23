<?php

namespace ActiveRest\Contracts;

/**
 * Interface HasOrmOperationsContract
 *
 * @package ActiveRest\Contracts
 */
interface HasOrmOperationsContract extends
    HasDelContract,
    HasFindContract,
    HasLastContract,
    HasModelContract,
    HasPostContract,
    HasPatchContract,
    HasReplicateContract
{

}