<?php
namespace ActiveRest\Concerns;

//texception
use Solis\Breaker\{
    TException,
    Abstractions\TExceptionAbstract
};

/**
 * Trait HasDel
 *
 * @package ActiveRest\Concerns
 */
trait HasDel
{
    //Mensagens
    protected static $MSG_DEL_ALL = ' registros foram excluidos e ';
    protected static $MSG_DEL_SUCCESS = 'Registro excluido com sucesso';
    protected static $MSG_DEL_FAIL = 'Falha ao excluir o registro';
    protected static $MSG_BEFORE_DEL_FAIL = 'Falha ao executar as operacoes pre exclusao';
    protected static $MSG_AFTER_DEL_FAIL = 'Falha ao executar as operacoes pos exclusao';

    /**
     * Método que é executado antes de Excluir um Registro
     * @param $param
     * @return array
     */
    public function beforeDel(
        array $param
    ): array {
        try {
            return $this->before($param);
        } catch (TExceptionAbstract $e) {
            return $e->toArray();
        }
    }

    /**
     * Método executado depois de Inserir um Registro
     * @param $param
     * @return array
     */
    public function afterDel(
        array $param
    ): array {
        try {
            return $this->after($param);
        } catch (TExceptionAbstract $e) {
            return $e->toArray();
        }
    }



    /**
     * Método responsável por Inserir 1 Registro na Database
     * @param array $param
     */
    public function delOne(
        array $param
    ) {
        // Chamada o método de interceptação beforeDel e valida o insert
        $beforeDel = $this->beforeDel($param);

        // Valida se o beforeDel retornou sucesso e os parâmetros
        if ($beforeDel['success'] === true && is_array($beforeDel['param'])) {
            $instance = call_user_func_array(
                [get_class($this->getModel()), 'make'],
                [$beforeDel['param']]
            );

            $this->setModel($instance);
            $delete = $this->getModel()->delete();

            //Status
            if (empty($delete)) {
                // ADICIONA FALHA via HasPrepareRetorno
                $this->newFail(self::$MSG_DEL_FAIL);
                return;
            }

            // Depois de fazer a Exclusao
            $afterDel = $this->afterDel($beforeDel['param']);
            if ($afterDel['success'] === false || empty($afterDel['param'])) {
                // ADICIONA FALHA via HasPrepareRetorno
                $this->newFail(self::$MSG_AFTER_DEL_FAIL);
                return;
            }

            // Retorno
            $this->newSuccess(self::$MSG_DEL_SUCCESS);
        } elseif ($beforeDel['success'] === false || empty($beforeDel['param'])) {
            // Mensagem
            $message = self::$MSG_DEL_FAIL . ' - ' . self::$MSG_BEFORE_DEL_FAIL;
            // ADICIONA FALHA via HasPrepareRetorno
            $this->newFail($message);
        }
    }

    /**
     * Método que implementa a exclusão dos registros
     * @param array $params
     * @return array
     */
    public function del(
        array $params = []
    ): array {
        try {
            // Se os parâmetros estão vazios e o Model está carregado a operação é um ACTIVE RECORD
            if (empty($params) && !empty($this->getModel())) {
                $bDelete = $this->getModel()->delete();
                return [
                    'status' => $bDelete,
                    'message' => $bDelete ? self::$MSG_DEL_SUCCESS : self::$MSG_DEL_FAIL
                ];
            }

            // Retorna sempre um array de parametros, independente de fornecer 1 ou N
            $this->simpleArraytoMulti($params);

            // Inicia o retorno a partir do Trait HasPrepareRetorno
            $this->prepareRetorno($params);

            // Itera sobre os parametros e executa as operações individualmente
            foreach ($params as $param) {
                $this->delOne($param);
            }

            return $this->getRetornoProcessamento();
        } catch (TException $exception) {
            return $exception->toArray();
        }
    }
}