<?php


use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{

    public function testLeilaoNaoDeveReceberLancesRepetidos()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Usuario não pode propor 2 lances consecutivos');
        $this->expectExceptionCode(400);

        $leilao = new Leilao('Variante');
        $joacas = new Usuario('joaquim');

        $leilao->recebeLance(new Lance($joacas, 100));
        $leilao->recebeLance(new Lance($joacas, 200));
    }

    public function testLeilaoNaoDeveAceitarMaisde5LancesPorUsuario()
    {

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Usuario não pode propor mais de 5 lances por leilão');
        $this->expectExceptionCode(400);


        $leilao = new Leilao('Variante');
        $joao = new Usuario('joaquim');
        $maria = new Usuario('mariah');

        $leilao->recebeLance(new Lance($joao, 1000));
        $leilao->recebeLance(new Lance($maria, 1500));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 3000));
        $leilao->recebeLance(new Lance($maria, 3500));
        $leilao->recebeLance(new Lance($joao, 4000));
        $leilao->recebeLance(new Lance($maria, 4500));
        $leilao->recebeLance(new Lance($joao, 5000));
        $leilao->recebeLance(new Lance($maria, 5500));
        $leilao->recebeLance(new Lance($joao, 6000));


        $this->assertCount(10, $leilao->getLances());
        $this->assertEquals(5500, $leilao->getLances()[array_key_last($leilao->getLances())]->getValor());
    }

    /**
     * @dataProvider geraLances
     */
    public function testLeilaoDeveReceberLances(int $qtdLances, Leilao $leilao, array $valores)
    {
        $this->assertCount($qtdLances, $leilao->getLances());
        foreach ($valores as $i => $valor){
            $this->assertEquals($valor, $leilao->getLances()[$i]->getValor());
        }
    }
    public function geraLances()
    {
        $usuario = new Usuario('MOISES');
        $usuario2 = new Usuario('Joacas');
        
        $leilaoCom2Lances = new Leilao('Bola de ping pong');
        $leilaoCom2Lances->recebeLance(new Lance($usuario, 20));
        $leilaoCom2Lances->recebeLance(new Lance($usuario2, 30));

        $leilaoCom1Lance = new Leilao('Dentadura postiça');
        $leilaoCom1Lance->recebeLance(new Lance(new Usuario('Zé'), 200));

        return [
            '2-lances' => [2, $leilaoCom2Lances, [20, 30]],
            '1-lance' => [1, $leilaoCom1Lance, [200]]
        ];
    }
}