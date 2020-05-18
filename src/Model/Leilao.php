<?php

namespace Alura\Leilao\Model;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;
    /*** @var bool */
    private $finalizado;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
        $this->finalizado = false;
    }

    public function recebeLance(Lance $lance)
    {
//        CTRL + ALT + M - Para extrai um método
        if(!empty($this->lances) && $this->eDoUltimoUsuario($lance)){
            throw new \DomainException('Usuario não pode propor 2 lances consecutivos', 400);
        }

        $totalLancesUsuario = $this->quantidadeLancesPorUsuario($lance->getUsuario());

        if($totalLancesUsuario >= 5){
            throw new \DomainException('Usuario não pode propor mais de 5 lances por leilão', 400);
        }

        $this->lances[] = $lance;
    }

    public function finaliza()
    {
        $this->finalizado = true;
    }

    public function estaFinalizado()
    {
        return $this->finalizado;
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
