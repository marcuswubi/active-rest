<?php

namespace ActiveRest\Concerns;

//texception
use Solis\Breaker\Abstractions\TExceptionAbstract;

/**
 * Trait HasPatch
 *
 * @package ActiveRest\Concerns
 */
trait HasPatch
{
    // Mensagens
    protected static $MSG_PATCH_ALL = ' registros foram atualizados e ';
    protected static $MSG_PATCH_SUCCESS = 'Registro atualizado com sucesso';
    protected static $MSG_PATCH_FAIL = 'Falha ao atualizar registro';
    protected static $MSG_BEFORE_PATCH_FAIL = 'Falha ao executar as operacoes pre atualizacao';
    protected static $MSG_AFTER_PATCH_FAIL = 'Falha ao executar as operacoes pos atualizacao';

    /**
     * Método executado antes de Atualizar via Patch um Registro
     * @param $param
     * @return array
     */
    public function beforePatch(
        array $param
    ): array {
        try {
            return $this->before($param);
        } catch (TExceptionAbstract $e) {
            return $e->toArray();
        }
    }

    /**
     * Método executado depois de Atualizar via Patch um Registro
     * @param $param
     * @return array
     */
    public function afterPatch(
        $param
    ): array {
        try {
            return $this->after($param);
        } catch (TExceptionAbstract $e) {
            return $e->toArray();
        }
    }

    /**
     * Método responsável por atualizar via patch 1 registro no database
     * @param array $param
     */
    public function patchOne(
        array $param
    ) {
        // Antes de ATUALIZAR
        $beforePatch = $this->beforePatch($param);

        // Valida se o beforePost retornou sucesso e os parâmetros
        if ($beforePatch['success'] === true && is_array($beforePatch['param'])) {
            //Carrega o Model com os parametros
            $this->setModel(
                call_user_func_array(
                    [get_class($this->getModel()), 'make'],
                    [$beforePatch['param']]
                )
            );

            // Valida a existencia de um metodo custom para atualização do registro
            if (method_exists(
                $this,
                'customPatch'
            )) {
                $patched = $this->customPatch($param);
            } else {
                $patched = $this->getModel()->patch();
            }

            //Valida Atualização
            if (empty($patched)) {
                $this->newFail(self::$MSG_POST_FAIL);
                return;
            }

            // Depois de ATUALIZAR
            $afterPatch = $this->afterPatch($patched);
            if ($afterPatch['success'] === false || empty($afterPatch['param'])) {
                $this->newFail(self::$MSG_AFTER_PATCH_FAIL);
                return;
            }

            // Retorno
            $this->newSuccess(self::$MSG_PATCH_SUCCESS);
        } elseif ($beforePatch['success'] === false || empty($beforePatch['param'])) {
            // Mensagem
            $message = self::$MSG_PATCH_FAIL . ' - ' . self::$MSG_BEFORE_PATCH_FAIL;
            // ADICIONA FALHA via HasPrepareRetorno
            $this->newFail($message);
        }
    }


    /**
     * Método Responsável por Atualizar via Patch 1 ou N Registros
     * @param $params
     * @return array
     */
    public function patch(
        array $params
    ): array {
        try {
            // Se os parâmetros não estão vazios carrega o Model
            if (empty($params)) {
                throw new TException(
                    __CLASS__,
                    __METHOD__,
                    'Metodo: [ ' . __CLASS__ . ' ], da Classe: [ ' . __METHOD__ . ' ], nao pode ser vazio.',
                    400
                );
            }

            // Retorna sempre um array de parametros, independente de fornecer 1 ou N
            $this->simpleArraytoMulti($params);

            // Inicia o retorno a partir do Trait HasPrepareRetorno
            $this->prepareRetorno(
                count($params)
            );

            // Itera sobre os parâmetros inserindo os registros um por um
            foreach ($params as $param) {
                $this->patchOne($param);
            }

            //Retorno
            return $this->getRetornoProcessamento();
        } catch (TExceptionAbstract $e) {
            return $e->toArray();
        }
    }
}