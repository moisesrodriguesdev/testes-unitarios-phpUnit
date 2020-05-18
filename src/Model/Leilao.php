<?php

namespace Alura\Leilao\Model;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
    }

    public function recebeLance(Lance $lance)
    {
//        CTRL + ALT + M - Para extrai um método
        if(!empty($this->lances) && $this->eDoUltimoUsuario($lance)){
            return;
        }

        $totalLancesUsuario = $this->quantidadeLancesPorUsuario($lance->getUsuario());

        if($totalLancesUsuario >= 5){
            return;
        }

        $this->lances[] = $lance;
    }

    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }

    /**
     * @param Lance $lance
     * @return bool
     */
    private function eDoUltimoUsuario(Lance $lance): bool
    {
        // CTRL + ALT + V - Para extrai uma variavel
        $lance1 = $this->lances[array_key_last($this->lances)];
        return $lance->getUsuario() == $lance1->getUsuario();
    }

    /**
     * @param Usuario $usuario
     * @return mixed
     */
    private function quantidadeLancesPorUsuario(Usuario $usuario)
    {
        $totalLancesUsuario = array_reduce($this->lances, function (int $totalAcumulado, Lance $lanceAtual) use ($usuario) {
            if ($lanceAtual->getUsuario() == $usuario) {
                return $totalAcumulado + 1;
            }

            return $totalAcumulado;
        }, 0

        );
        return $totalLancesUsuario;
    }
}
