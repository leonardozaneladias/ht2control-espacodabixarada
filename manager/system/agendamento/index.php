<?php $varPrefix = 'animal'; ?>
    <div class="row">
    <div class="col-lg-12 bs-callout bs-callout-default">
        <div class="col-lg-10">
            <h4>Animais</h4>
        </div>
        <div class="col-lg-2">
            <a href="painel.php?exe=<?= $varPrefix ?>/create" title="Cadastrar Novo"
               class="btn btn-primary">Agendamento</a>
        </div>
    </div>

    <div class="clearfix"></div>
    <hr style="margin: 8px 0;">
<?php

$idServico = 1;
$tempo = 0;
$servico = new Read;
$servico->ExeRead("app_servicos", "WHERE id IN(:ids)", "ids={$idServico}");
foreach ($servico->getResult() as $dados):
    $tempo+= $dados['tempo'];
endforeach;

//echo $tempo;

$agenda = new Read;
$agenda->ExeRead("app_agenda");
foreach ($agenda->getResult() as $dados):
    $inicio = $dados['hr_inicio'];
    $pausa = $dados['hr_pausa'];
    $termino = $dados['hr_termino'];

    $agendamento_dt_inicio = "2016-10-02 {$inicio}";
    $agendamento_dt_fim = "2016-10-02 {$termino}";

    echo $agendamento_dt_inicio."<hr>";
    echo $agendamento_dt_fim."<hr>";

    $agendamentos = new Read();
    $agendamentos->ExeRead("app_agendamentos", "WHERE data_hora >= '{$agendamento_dt_inicio}' AND data_hora <= '{$agendamento_dt_fim}'");
    var_dump($agendamentos->getResult());

    exit;



    $datatime1 = new DateTime("2016/10/02 $inicio");
    $datatime2 = new DateTime("2016/10/02 $termino");

    $data1  = $datatime1->format('Y-m-d H:i:s');
    $data2  = $datatime2->format('Y-m-d H:i:s');

    $diff = $datatime1->diff($datatime2);
    $horas = $diff->h + ($diff->days * 24);
    $minutos = $horas * 60;
    $for = $minutos / 5;

    for($i=0;$i<=$for;$i++){

    }

    echo "A diferença é de {$minutos} minutos";



    /*

    $horaAtual = strtotime($inicio);


    echo "<hr>Atual= ".$horaAtual;
    $horaNova = strtotime($termino);
    $i = 0;
    while ($horaAtual <= $horaNova){
        $i++;
        $tempo = $tempo * $i;
        $rsHoraNova = strtotime("$inicio + ".$tempo." minutes");

        echo $rsHoraNova;


    }
    */

endforeach;

?>