<?php

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;

require '../vendor/autoload.php';

// Arrange - Given / Preparamos o cenário do teste
$leilao = new Leilao('Notebook CCE I5');
$usuario = new Usuario('MOISES');
$usuario2 = new Usuario('Joacas');


$leilao->recebeLance(new Lance($usuario, 100));
$leilao->recebeLance(new Lance($usuario2, 1000));


$leiloeiro = new Avaliador();

// Act - When / Executamos o código a ser testado
$leiloeiro->avalia($leilao);


// Assert - Then / Verificamos se a saída é a esperada
$maiorvalor = $leiloeiro->getMaiorValor();

echo $maiorvalor;