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

            if (!empty($obra["AUTORES"])) {
                $authorsArray = [];
                foreach ($obra["AUTORES"] as $author) {
                    $authorsArray[] = $author["NOME-COMPLETO-DO-AUTOR"][0];
                }
                $recordContent[] = 'author = {'.implode(" and ", $authorsArray).'}';
            }


            if (!empty($dadosBasicosDoTrabalho['@attributes']["ANO-DO-TRABALHO"])) {
                $recordContent[] = 'year = {'.$dadosBasicosDoTrabalho['@attributes']["ANO-DO-TRABALHO"].'}';
            }

            if (!empty($dadosBasicosDoTrabalho['@attributes']["DOI"])) {
                $recordContent[] = 'doi = {'.$dadosBasicosDoTrabalho['@attributes']["DOI"].'}';
            }

            $sha256 = hash('sha256', ''.implode("", $recordContent).'');

            $record[] = '@inproceedings{inproceedings'.substr($sha256, 0, 8).',';
            $record[] = implode(",\n", $recordContent);
            $record[] = '}';

            $records_array[] = implode("\\n", $record);

            unset($recordContent);
            unset($sha256);
        
        }
    
    }

    if (isset($curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'ARTIGOS-PUBLICADOS'})) {

        $artigoPublicadoArray = $curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'ARTIGOS-PUBLICADOS'}->{'ARTIGO-PUBLICADO'};
        foreach ($artigoPublicadoArray as $obra) {
            $obra = get_object_vars($obra);
            $dadosBasicosDoTrabalho = get_object_vars($obra["DADOS-BASICOS-DO-ARTIGO"]);
            $detalhamentoDoTrabalho = get_object_vars($obra["DETALHAMENTO-DO-ARTIGO"]);

            if (!empty($dadosBasicosDoTrabalho['@attributes']["TITULO-DO-ARTIGO"])) {
                $recordContent[] = 'title   = {'.$dadosBasicosDoTrabalho['@attributes']["TITULO-DO-ARTIGO"].'}';
            }

            if (!empty($obra["AUTORES"])) {
                $authorsArray = [];
                foreach ($obra["AUTORES"] as $author) {
                    $authorsArray[] = $author["NOME-COMPLETO-DO-AUTOR"][0];
                }
                $recordContent[] = 'author = {'.implode(" and ", $authorsArray).'}';
            }

            if (!empty($dadosBasicosDoTrabalho['@attributes']["ANO-DO-ARTIGO"])) {
                $recordContent[] = 'year = {'.$dadosBasicosDoTrabalho['@attributes']["ANO-DO-ARTIGO"].'}';
            }

            if (!empty($detalhamentoDoTrabalho['@attributes']["TITULO-DO-PERIODICO-OU-REVISTA"])) {
                $recordContent[] = 'journal = {'.$detalhamentoDoTrabalho['@attributes']["TITULO-DO-PERIODICO-OU-REVISTA"].'}';
            }

            if (!empty($detalhamentoDoTrabalho['@attributes']["VOLUME"])) {
                $recordContent[] = 'volume = {'.$detalhamentoDoTrabalho['@attributes']["VOLUME"].'}';
            }

            if (!empty($detalhamentoDoTrabalho['@attributes']["FASCICULO"])) {
                $recordContent[] = 'number = {'.$detalhamentoDoTrabalho['@attributes']["FASCICULO"].'}';
            }

            if (!empty($detalhamentoDoTrabalho['@attributes']["SERIE"])) {
                $recordContent[] = 'number = {'.$detalhamentoDoTrabalho['@attributes']["SERIE"].'}';
            }

            if (!empty($detalhamentoDoTrabalho['@attributes']["PAGINA-INICIAL"])) {
                $recordContent[] = 'pages = {'.$detalhamentoDoTrabalho['@attributes']["PAGINA-INICIAL"].'-'.$detalhamentoDoTrabalho['@attributes']["PAGINA-FINAL"].' }';
            }

            if (!empty($dadosBasicosDoTrabalho['@attributes']["DOI"])) {
                $recordContent[] = 'doi = {'.$dadosBasicosDoTrabalho['@attributes']["DOI"].'}';
            }


            $sha256 = hash('sha256', ''.implode("", $recordContent).'');

            $record[] = '@article{article'.substr($sha256, 0, 8).',';
            $record[] = implode(",\n", $recordContent);
            $record[] = '}';

            $records_array[] = implode("\\n", $record);

            unset($recordContent);
            unset($sha256);


        }
    }

    if (isset($curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'LIVROS-E-CAPITULOS'})) {

        if (isset($curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'LIVROS-E-CAPITULOS'}->{'LIVROS-PUBLICADOS-OU-ORGANIZADOS'})) {
    
            $livrosPublicadoArray = $curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'LIVROS-E-CAPITULOS'}->{'LIVROS-PUBLICADOS-OU-ORGANIZADOS'}->{'LIVRO-PUBLICADO-OU-ORGANIZADO'};
            foreach ($livrosPublicadoArray as $obra) {
                $obra = get_object_vars($obra);
                $dadosBasicosDoTrabalho = get_object_vars($obra["DADOS-BASICOS-DO-LIVRO"]);
                $detalhamentoDoTrabalho = get_object_vars($obra["DETALHAMENTO-DO-LIVRO"]);

                if (!empty($dadosBasicosDoTrabalho['@attributes']["TITULO-DO-LIVRO"])) {
                    $recordContent[] = 'title   = {'.$dadosBasicosDoTrabalho['@attributes']["TITULO-DO-LIVRO"].'}';
                }

                if (!empty($obra["AUTORES"])) {
                    $authorsArray = [];
                    foreach ($obra["AUTORES"] as $author) {
                        $authorsArray[] = $author["NOME-COMPLETO-DO-AUTOR"][0];
                    }
                    $recordContent[] = 'author = {'.implode(" and ", $authorsArray).'}';
                }
    
                if (!empty($dadosBasicosDoTrabalho['@attributes']["ANO"])) {
                    $recordContent[] = 'year = {'.$dadosBasicosDoTrabalho['@attributes']["ANO"].'}';
                }
    
                if (!empty($dadosBasicosDoTrabalho['@attributes']["DOI"])) {
                    $recordContent[] = 'doi = {'.$dadosBasicosDoTrabalho['@attributes']["DOI"].'}';
                }
    
    
                $sha256 = hash('sha256', ''.implode("", $recordContent).'');
    
                $record[] = '@book{book'.substr($sha256, 0, 8).',';
                $record[] = implode(",\n", $recordContent);
                $record[] = '}';
    
                $records_array[] = implode("\\n", $record);
    
                unset($recordContent);
                unset($sha256);
    
            }
        }
    
        if (isset($curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'LIVROS-E-CAPITULOS'}->{'CAPITULOS-DE-LIVROS-PUBLICADOS'})) {
    
            $capitulosPublicadoArray = $curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'LIVROS-E-CAPITULOS'}->{'CAPITULOS-DE-LIVROS-PUBLICADOS'}->{'CAPITULO-DE-LIVRO-PUBLICADO'};
            foreach ($capitulosPublicadoArray as $obra) {
                $obra = get_object_vars($obra);
                $dadosBasicosDoTrabalho = get_object_vars($obra["DADOS-BASICOS-DO-CAPITULO"]);
                $detalhamentoDoTrabalho = get_object_vars($obra["DETALHAMENTO-DO-CAPITULO"]);

                if (!empty($dadosBasicosDoTrabalho['@attributes']["TITULO-DO-CAPITULO-DO-LIVRO"])) {
                    $recordContent[] = 'title   = {'.$dadosBasicosDoTrabalho['@attributes']["TITULO-DO-CAPITULO-DO-LIVRO"].'}';
                }

                if (!empty($obra["AUTORES"])) {
                    $authorsArray = [];
                    foreach ($obra["AUTORES"] as $author) {
                        $authorsArray[] = $author["NOME-COMPLETO-DO-AUTOR"][0];
                    }
                    $recordContent[] = 'author = {'.implode(" and ", $authorsArray).'}';
                }
    
                if (!empty($dadosBasicosDoTrabalho['@attributes']["ANO"])) {
                    $recordContent[] = 'year = {'.$dadosBasicosDoTrabalho['@attributes']["ANO"].'}';
                }
    
                if (!empty($dadosBasicosDoTrabalho['@attributes']["DOI"])) {
                    $recordContent[] = 'doi = {'.$dadosBasicosDoTrabalho['@attributes']["DOI"].'}';
                }
    
    
                $sha256 = hash('sha256', ''.implode("", $recordContent).'');
    
                $record[] = '@inbook{inbook'.substr($sha256, 0, 8).',';
                $record[] = implode(",\n", $recordContent);
                $record[] = '}';
    
                $records_array[] = implode("\\n", $record);
    
                unset($recordContent);
                unset($sha256); 
    
            }
        }
    
    }


    $file="lattes.bib";
    header('Content-type: text/plain');
    header("Content-Disposition: attachment; filename=$file");

    print_r(implode("\n",$record));

} else {

    http_response_code(204);
    exit();

}