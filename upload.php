<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Testa se foi enviado um arquivo
if ($_FILES['file']['size'] != 0) {

    $curriculo = simplexml_load_file($_FILES['file']['tmp_name']);


    if (isset($curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'TRABALHOS-EM-EVENTOS'})) {

        $trabalhosEmEventosArray = $curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'TRABALHOS-EM-EVENTOS'}->{'TRABALHO-EM-EVENTOS'};
        foreach ($trabalhosEmEventosArray as $obra) {
            $obra = get_object_vars($obra);
            $dadosBasicosDoTrabalho = get_object_vars($obra["DADOS-BASICOS-DO-TRABALHO"]);
            $detalhamentoDoTrabalho = get_object_vars($obra["DETALHAMENTO-DO-TRABALHO"]);

            if (!empty($dadosBasicosDoTrabalho['@attributes']["TITULO-DO-TRABALHO"])) {
                $recordContent[] = 'title   = {'.$dadosBasicosDoTrabalho['@attributes']["TITULO-DO-TRABALHO"].'}';
            }

            $sha256 = hash('sha256', ''.implode("", $recordContent).'');

            $record[] = '@inproceedings{inproceedings'.substr($sha256, 0, 8).',';
            $record[] = implode(",\\n", $recordContent);
            $record[] = '}';

            unset($recordContent);
        
        }

        // $trabalhosEmEventosArray = $curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'TRABALHOS-EM-EVENTOS'}->{'TRABALHO-EM-EVENTOS'};
        // foreach ($trabalhosEmEventosArray as $obra) {
        //     $obra = get_object_vars($obra);
        //     $dadosBasicosDoTrabalho = get_object_vars($obra["DADOS-BASICOS-DO-TRABALHO"]);
        //     $detalhamentoDoTrabalho = get_object_vars($obra["DETALHAMENTO-DO-TRABALHO"]);
        //     $doc["doc"]["type"] = "Work";
        //     $doc["doc"]["tipo"] = "Trabalhos em eventos";
        //     $doc["doc"]["source"] = "Base Lattes";
        //     $doc["doc"]["lattes_ids"][] = (string)$curriculo->attributes()->{'NUMERO-IDENTIFICADOR'};
        //     if (isset($_REQUEST['tag'])) {
        //         $doc["doc"]["tag"][] = $_REQUEST['tag'];
        //     } else {
        //         $doc["doc"]["tag"][] = "Lattes";
        //     }
        //     $doc["doc"]["datePublished"] = $dadosBasicosDoTrabalho['@attributes']["ANO-DO-TRABALHO"];
        //     $doc["doc"]["name"] = $dadosBasicosDoTrabalho['@attributes']["TITULO-DO-TRABALHO"];
        //     $doc["doc"]["lattes"]["natureza"] = $dadosBasicosDoTrabalho['@attributes']['NATUREZA'];
        //     $doc["doc"]["country"] = $dadosBasicosDoTrabalho['@attributes']["PAIS-DO-EVENTO"];
        //     $doc["doc"]["language"] = $dadosBasicosDoTrabalho['@attributes']["IDIOMA"];
        //     $doc["doc"]["lattes"]["meioDeDivulgacao"] = $dadosBasicosDoTrabalho['@attributes']["MEIO-DE-DIVULGACAO"];
        //     $doc["doc"]["url"] = $dadosBasicosDoTrabalho['@attributes']["HOME-PAGE-DO-TRABALHO"];
        //     $doc["doc"]["lattes"]["flagRelevancia"] = $dadosBasicosDoTrabalho['@attributes']["FLAG-RELEVANCIA"];
        //     $testDOI = Testadores::testDOI($dadosBasicosDoTrabalho['@attributes']["DOI"]);
        //     if ($testDOI === 1) {
        //         $doc["doc"]["doi"] = $dadosBasicosDoTrabalho['@attributes']["DOI"];
        //     }
        //     $doc["doc"]["lattes"]["flagDivulgacaoCientifica"] = $dadosBasicosDoTrabalho['@attributes']["FLAG-DIVULGACAO-CIENTIFICA"];
    
        //     $doc["doc"]["detalhamentoDoTrabalho"]["classificacaoDoEvento"] = $detalhamentoDoTrabalho['@attributes']["CLASSIFICACAO-DO-EVENTO"];
        //     $doc["doc"]["EducationEvent"]["name"] = $detalhamentoDoTrabalho['@attributes']["NOME-DO-EVENTO"];
        //     $doc["doc"]["publisher"]["organization"]["location"] = $detalhamentoDoTrabalho['@attributes']["CIDADE-DO-EVENTO"];
        //     $doc["doc"]["detalhamentoDoTrabalho"]["anoDeRealizacao"] = $detalhamentoDoTrabalho['@attributes']["ANO-DE-REALIZACAO"];
        //     $doc["doc"]["isPartOf"]["name"] = $detalhamentoDoTrabalho['@attributes']["TITULO-DOS-ANAIS-OU-PROCEEDINGS"];
        //     $doc["doc"]["pageStart"] = $detalhamentoDoTrabalho['@attributes']["PAGINA-INICIAL"];
        //     $doc["doc"]["pageEnd"] = $detalhamentoDoTrabalho['@attributes']["PAGINA-FINAL"];
        //     $doc["doc"]["isPartOf"]["isbn"] = $detalhamentoDoTrabalho['@attributes']["ISBN"];
        //     $doc["doc"]["publisher"]["organization"]["name"] = $detalhamentoDoTrabalho['@attributes']["NOME-DA-EDITORA"];
        //     $doc["doc"]["detalhamentoDoTrabalho"]["cidadeDaEditora"] = $detalhamentoDoTrabalho['@attributes']["CIDADE-DA-EDITORA"];
        //     $doc["doc"]["detalhamentoDoTrabalho"]["volumeDosAnais"] = $detalhamentoDoTrabalho['@attributes']["VOLUME"];
        //     $doc["doc"]["detalhamentoDoTrabalho"]["fasciculoDosAnais"] = $detalhamentoDoTrabalho['@attributes']["FASCICULO"];
        //     $doc["doc"]["detalhamentoDoTrabalho"]["serieDosAnais"] = $detalhamentoDoTrabalho['@attributes']["SERIE"];
    
        //     if (!empty($obra["AUTORES"])) {
        //         $array_result = processaAutoresLattes($obra["AUTORES"]);
        //         $doc = array_merge_recursive($doc, $array_result);
        //     }
    
        //     if (isset($obra["PALAVRAS-CHAVE"])) {
        //         $array_result_pc = processaPalavrasChaveLattes($obra["PALAVRAS-CHAVE"]);
        //         if (isset($array_result_pc)) {
        //             $doc = array_merge_recursive($doc, $array_result_pc);
        //         }
        //         unset($array_result_pc);
        //     }
    
        //     if (isset($obra["AREAS-DO-CONHECIMENTO"])) {
        //         $array_result_ac = processaAreaDoConhecimentoLattes($obra["AREAS-DO-CONHECIMENTO"]);
        //         if (isset($array_result_ac)) {
        //             $doc = array_merge_recursive($doc, $array_result_ac);
        //         }
        //         unset($array_result_ac);
        //     }
    
        //     // Vinculo
        //     $doc["doc"]["vinculo"] = construct_vinculo($_REQUEST, $curriculo);
    
        //     // Constroi sha256
        //     if (!empty($doc['doc']['doi'])) {
        //         $sha256 = hash('sha256', ''.$doc['doc']['doi'].'');
        //     } else {
        //         $sha_array[] = $doc["doc"]["lattes_ids"][0];
        //         $sha_array[] = $doc["doc"]["tipo"];
        //         $sha_array[] = $doc["doc"]["name"];
        //         $sha_array[] = $doc["doc"]["datePublished"];
        //         $sha_array[] = $doc["doc"]["country"];
        //         $sha_array[] = $doc["doc"]["EducationEvent"]["name"];
        //         $sha_array[] = $doc["doc"]["pageStart"];
        //         $sha_array[] = $doc["doc"]["pageEnd"];
        //         $sha256 = hash('sha256', ''.implode("", $sha_array).'');
        //     }
    
    
        //     //$doc["doc"]["bdpi"] = DadosExternos::query_bdpi_index($doc["doc"]["name"], $doc["doc"]["datePublished"]);
        //     $doc["doc"]["concluido"] = "Não";
        //     $doc["doc_as_upsert"] = true;
    
        //     // Comparador
        //     $resultado = upsert($doc, $sha256);
        //     echo "<br/>";
        //     print_r($resultado);
        //     echo "<br/><br/>";
    
        //     unset($dadosBasicosDoTrabalho);
        //     unset($detalhamentoDoTrabalho);
        //     unset($obra);
        //     unset($doc);
        //     unset($sha_array);
        //     unset($sha256);
        //     flush();
    
    }


    $file="lattes.bib";
    header('Content-type: text/plain');
    header("Content-Disposition: attachment; filename=$file");

    print_r(implode("\\n", $record));

} else {

    http_response_code(204);
    exit();

}