<?php

namespace App\Helpers;

class Helper
{

    public static function getFullHour($seconds)
    {
        $negative = $seconds < 0; //Verifica se é um valor negativo

        if ($negative) {
            $seconds = -$seconds; //Converte o negativo para positivo para poder fazer os calculos
        }

        $hours = $seconds / 3600;

        $mins = ($seconds - ($hours * 3600)) / 60;

        //Pega o valor após o ponto flutuante
        $f = fmod($hours, 1);

        //Adiciona minutos se $seconds for quebrado
        if ($f > 0) $mins += 60 * $f;

        $secs = $seconds % 60;

        $sign = $negative ? '-' : ''; //Adiciona o sinal de negativo se necessário

        return $sign . sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
    }
}
