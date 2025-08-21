<?php

// Remove acentos de caracteres
function removerAcentos($texto) {
    $acentos = [
        'á'=>'a','à'=>'a','ã'=>'a','â'=>'a','ä'=>'a',
        'é'=>'e','è'=>'e','ê'=>'e','ë'=>'e',
        'í'=>'i','ì'=>'i','î'=>'i','ï'=>'i',
        'ó'=>'o','ò'=>'o','õ'=>'o','ô'=>'o','ö'=>'o',
        'ú'=>'u','ù'=>'u','û'=>'u','ü'=>'u',
        'ç'=>'c',
        'Á'=>'A','À'=>'A','Ã'=>'A','Â'=>'A','Ä'=>'A',
        'É'=>'E','È'=>'E','Ê'=>'E','Ë'=>'E',
        'Í'=>'I','Ì'=>'I','Î'=>'I','Ï'=>'I',
        'Ó'=>'O','Ò'=>'O','Õ'=>'O','Ô'=>'O','Ö'=>'O',
        'Ú'=>'U','Ù'=>'U','Û'=>'U','Ü'=>'U',
        'Ç'=>'C'
    ];
    return strtr($texto, $acentos);
}

// Remove pontuação e caracteres especiais
function limparTexto($texto) {
    return preg_replace('/[^\p{L}\p{N}\s]/u', '', $texto);
}

// Remove stopwords e retorna palavras-chave únicas e ordenadas
function extrairPalavrasChave($texto) {
    $stopwords = [
        'a','o','as','os','um','uma','uns','umas',
        'de','do','da','dos','das','em','no','na','nos','nas',
        'por','para','com','sem','sobre','entre','até','desde',
        'que','e','ou','mas','como','porque','quando','onde','se',
        'já','não','sim','também','então','pois','logo','tudo','todo',
        'só','isso','isto','aquele','aquela','aqueles','aquelas'
    ];

    $texto = strtolower(removerAcentos(limparTexto($texto)));
    $palavras = explode(' ', $texto);

    $keywords = array_filter($palavras, function($word) use ($stopwords) {
        return !in_array($word, $stopwords) && strlen($word) > 2;
    });

    $keywords = array_unique($keywords);
    sort($keywords);

    return $keywords;
}