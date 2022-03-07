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

        // if (!empty($cursor["_source"]['name'])) {
        //     $recordContent[] = 'title   = {'.$cursor["_source"]['name'].'}';
        // }

        // if (!empty($cursor["_source"]['author'])) {
        //     $authorsArray = [];
        //     foreach ($cursor["_source"]['author'] as $author) {
        //         $authorsArray[] = $author["person"]["name"];
        //     }
        //     $recordContent[] = 'author = {'.implode(" and ", $authorsArray).'}';
        // }

        // if (!empty($cursor["_source"]['datePublished'])) {
        //     $recordContent[] = 'year = {'.$cursor["_source"]['datePublished'].'}';
        // }

        // if (!empty($cursor["_source"]['doi'])) {
        //     $recordContent[] = 'doi = {'.$cursor["_source"]['doi'].'}';
        // }

        // if (!empty($cursor["_source"]['publisher']['organization']['name'])) {
        //     $recordContent[] = 'publisher = {'.$cursor["_source"]['publisher']['organization']['name'].'}';
        // }

        // if (!empty($cursor["_source"]["releasedEvent"])) {
        //     $recordContent[] = 'booktitle   = {'.$cursor["_source"]["releasedEvent"].'}';
        // } else {
        //     if (!empty($cursor["_source"]["isPartOf"]["name"])) {
        //         $recordContent[] = 'journal   = {'.$cursor["_source"]["isPartOf"]["name"].'}';
        //     }
        // }



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


                // $doc["doc"]["type"] = "Work";
                // $doc["doc"]["tipo"] = "Livro publicado ou organizado";
                // $doc["doc"]["source"] = "Base Lattes";
                // $doc["doc"]["lattes_ids"][] = (string)$curriculo->attributes()->{'NUMERO-IDENTIFICADOR'};
                // if (isset($_REQUEST['tag'])) {
                //     $doc["doc"]["tag"][] = $_REQUEST['tag'];
                // } else {
                //     $doc["doc"]["tag"][] = "Lattes";
                // }
                // $doc["doc"]["lattes"]["tipo"] = $dadosBasicosDoTrabalho['@attributes']["TIPO"];
                // $doc["doc"]["datePublished"] = $dadosBasicosDoTrabalho['@attributes']["ANO"];
                // $doc["doc"]["name"] = $dadosBasicosDoTrabalho['@attributes']["TITULO-DO-LIVRO"];
                // $doc["doc"]["lattes"]["natureza"] = $dadosBasicosDoTrabalho['@attributes']['NATUREZA'];
                // $doc["doc"]["country"] = $dadosBasicosDoTrabalho['@attributes']["PAIS-DE-PUBLICACAO"];
                // $doc["doc"]["language"] = $dadosBasicosDoTrabalho['@attributes']["IDIOMA"];
                // $doc["doc"]["lattes"]["meioDeDivulgacao"] = $dadosBasicosDoTrabalho['@attributes']["MEIO-DE-DIVULGACAO"];
                // $doc["doc"]["url"] = $dadosBasicosDoTrabalho['@attributes']["HOME-PAGE-DO-TRABALHO"];
                // $doc["doc"]["lattes"]["flagRelevancia"] = $dadosBasicosDoTrabalho['@attributes']["FLAG-RELEVANCIA"];
                // $testDOI = Testadores::testDOI($dadosBasicosDoTrabalho['@attributes']["DOI"]);
                // if ($testDOI === 1) {
                //     $doc["doc"]["doi"] = $dadosBasicosDoTrabalho['@attributes']["DOI"];
                // }
                // $doc["doc"]["alternateName"] = $dadosBasicosDoTrabalho['@attributes']["TITULO-DO-LIVRO-INGLES"];
                // $doc["doc"]["lattes"]["flagDivulgacaoCientifica"] = $dadosBasicosDoTrabalho['@attributes']["FLAG-DIVULGACAO-CIENTIFICA"];
    
                // $doc["doc"]["detalhamentoDoLivro"]["numeroDeVolumes"] = $detalhamentoDoTrabalho['@attributes']["NUMERO-DE-VOLUMES"];
                // $doc["doc"]["numberOfPages"] = $detalhamentoDoTrabalho['@attributes']["NUMERO-DE-PAGINAS"];
                // $doc["doc"]["isbn"] = $detalhamentoDoTrabalho['@attributes']["ISBN"];
                // $doc["doc"]["bookEdition"] = $detalhamentoDoTrabalho['@attributes']["NUMERO-DA-EDICAO-REVISAO"];
                // $doc["doc"]["detalhamentoDoLivro"]["numeroDaSerie"] = $detalhamentoDoTrabalho['@attributes']["NUMERO-DA-SERIE"];
                // $doc["doc"]["publisher"]["organization"]["location"] = $detalhamentoDoTrabalho['@attributes']["CIDADE-DA-EDITORA"];
                // $doc["doc"]["publisher"]["organization"]["name"] = $detalhamentoDoTrabalho['@attributes']["NOME-DA-EDITORA"];
    
                // if (!empty($obra["AUTORES"])) {
                //     $array_result = processaAutoresLattes($obra["AUTORES"]);
                //     $doc = array_merge_recursive($doc, $array_result);
                // }
    
                // if (isset($obra["PALAVRAS-CHAVE"])) {
                //     $array_result_pc = processaPalavrasChaveLattes($obra["PALAVRAS-CHAVE"]);
                //     if (isset($array_result_pc)) {
                //         $doc = array_merge_recursive($doc, $array_result_pc);
                //     }
                //     unset($array_result_pc);
                // }
    
                // if (isset($obra["AREAS-DO-CONHECIMENTO"])) {
                //     $array_result_ac = processaAreaDoConhecimentoLattes($obra["AREAS-DO-CONHECIMENTO"]);
                //     if (isset($array_result_ac)) {
                //         $doc = array_merge_recursive($doc, $array_result_ac);
                //     }
                //     unset($array_result_ac);
                // }
    
                // // Vinculo
                // $doc["doc"]["vinculo"] = construct_vinculo($_REQUEST, $curriculo);
    
                // // Constroi sha256
                // if (!empty($doc["doc"]["doi"])) {
                //     $sha256 = hash('sha256', ''.$doc["doc"]["doi"].'');
                // } elseif (!empty($doc["doc"]["isbn"])) {
                //     $sha_array[] = $doc["doc"]["lattes_ids"][0];
                //     $sha_array[] = $doc["doc"]["isbn"];
                //     $sha256 = hash('sha256', ''.implode("", $sha_array).'');
                // } else {
                //     $sha_array[] = $doc["doc"]["lattes_ids"][0];
                //     $sha_array[] = $doc["doc"]["tipo"];
                //     $sha_array[] = $doc["doc"]["name"];
                //     $sha_array[] = $doc["doc"]["datePublished"];
                //     $sha_array[] = $doc["doc"]["bookEdition"];
                //     $sha256 = hash('sha256', ''.implode("", $sha_array).'');
                // }
    
    
                // //$doc["doc"]["bdpi"] = DadosExternos::query_bdpi_index($doc["doc"]["name"], $doc["doc"]["datePublished"]);
                // $doc["doc"]["concluido"] = "Não";
                // $doc["doc_as_upsert"] = true;
    
                // // Comparador
                // $resultado = upsert($doc, $sha256);
                // echo "<br/>";
                // print_r($resultado);
                // echo "<br/><br/>";
    
    
                // unset($dadosBasicosDoTrabalho);
                // unset($detalhamentoDoTrabalho);
                // unset($obra);
                // unset($doc);
                // unset($sha_array);
                // unset($sha256);
                // flush();
    
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
    
                // $doc["doc"]["type"] = "Work";
                // $doc["doc"]["tipo"] = "Capítulo de livro publicado";
                // $doc["doc"]["source"] = "Base Lattes";
                // $doc["doc"]["lattes_ids"][] = (string)$curriculo->attributes()->{'NUMERO-IDENTIFICADOR'};
                // if (isset($_REQUEST['tag'])) {
                //     $doc["doc"]["tag"][] = $_REQUEST['tag'];
                // } else {
                //     $doc["doc"]["tag"][] = "Lattes";
                // }
                // $doc["doc"]["lattes"]["tipo"] = $dadosBasicosDoTrabalho['@attributes']["TIPO"];
                // $doc["doc"]["name"] = $dadosBasicosDoTrabalho['@attributes']["TITULO-DO-CAPITULO-DO-LIVRO"];
                // $doc["doc"]["datePublished"] = $dadosBasicosDoTrabalho['@attributes']["ANO"];
                // $doc["doc"]["country"] = $dadosBasicosDoTrabalho['@attributes']["PAIS-DE-PUBLICACAO"];
                // $doc["doc"]["language"] = $dadosBasicosDoTrabalho['@attributes']["IDIOMA"];
                // $doc["doc"]["lattes"]["meioDeDivulgacao"] = $dadosBasicosDoTrabalho['@attributes']["MEIO-DE-DIVULGACAO"];
                // $doc["doc"]["url"] = $dadosBasicosDoTrabalho['@attributes']["HOME-PAGE-DO-TRABALHO"];
                // $doc["doc"]["lattes"]["flagRelevancia"] = $dadosBasicosDoTrabalho['@attributes']["FLAG-RELEVANCIA"];
                // $testDOI = Testadores::testDOI($dadosBasicosDoTrabalho['@attributes']["DOI"]);
                // if ($testDOI === 1) {
                //     $doc["doc"]["doi"] = $dadosBasicosDoTrabalho['@attributes']["DOI"];
                // }
                // $doc["doc"]["alternateName"] = $dadosBasicosDoTrabalho['@attributes']["TITULO-DO-CAPITULO-DO-LIVRO-INGLES"];
                // if (isset($dadosBasicosDoTrabalho['@attributes']["FLAG-DIVULGACAO-CIENTIFICA"])){
                //     $doc["doc"]["lattes"]["flagDivulgacaoCientifica"] = $dadosBasicosDoTrabalho['@attributes']["FLAG-DIVULGACAO-CIENTIFICA"];
                // }
                // $doc["doc"]["isPartOf"]["name"] = $detalhamentoDoTrabalho['@attributes']["TITULO-DO-LIVRO"];
                // $doc["doc"]["pageStart"] = $detalhamentoDoTrabalho['@attributes']["PAGINA-INICIAL"];
                // $doc["doc"]["pageEnd"] = $detalhamentoDoTrabalho['@attributes']["PAGINA-FINAL"];
                // $doc["doc"]["isPartOf"]["isbn"] = $detalhamentoDoTrabalho['@attributes']["ISBN"];
                // $doc["doc"]["isPartOf"]["contributor"] = $detalhamentoDoTrabalho['@attributes']["ORGANIZADORES"];
                // $doc["doc"]["bookEdition"] = $detalhamentoDoTrabalho['@attributes']["NUMERO-DA-EDICAO-REVISAO"];
                // $doc["doc"]["serie"] = $detalhamentoDoTrabalho['@attributes']["NUMERO-DA-SERIE"];
                // $doc["doc"]["publisher"]["organization"]["location"] = $detalhamentoDoTrabalho['@attributes']["CIDADE-DA-EDITORA"];
                // $doc["doc"]["publisher"]["organization"]["name"] = $detalhamentoDoTrabalho['@attributes']["NOME-DA-EDITORA"];
    
                // if (!empty($obra["AUTORES"])) {
                //     $array_result = processaAutoresLattes($obra["AUTORES"]);
                //     $doc = array_merge_recursive($doc, $array_result);
                // }
    
                // if (isset($obra["PALAVRAS-CHAVE"])) {
                //     $array_result_pc = processaPalavrasChaveLattes($obra["PALAVRAS-CHAVE"]);
                //     if (isset($array_result_pc)) {
                //         $doc = array_merge_recursive($doc, $array_result_pc);
                //     }
                //     unset($array_result_pc);
                // }
    
                // if (isset($obra["AREAS-DO-CONHECIMENTO"])) {
                //     $array_result_ac = processaAreaDoConhecimentoLattes($obra["AREAS-DO-CONHECIMENTO"]);
                //     if (isset($array_result_ac)) {
                //         $doc = array_merge_recursive($doc, $array_result_ac);
                //     }
                //     unset($array_result_ac);
                // }
    
                // // Vinculo
                // $doc["doc"]["vinculo"] = construct_vinculo($_REQUEST, $curriculo);
    
                // // Constroi sha256
                // if (!empty($doc["doc"]["doi"])) {
                //     $sha256 = hash('sha256', ''.$doc["doc"]["doi"].'');
                // } else {
                //     $sha_array[] = $doc["doc"]["lattes_ids"][0];
                //     $sha_array[] = $doc["doc"]["tipo"];
                //     $sha_array[] = $doc["doc"]["name"];
                //     $sha_array[] = $doc["doc"]["datePublished"];
                //     $sha_array[] = $doc["doc"]["isPartOf"]["name"];
                //     $sha256 = hash('sha256', ''.implode("", $sha_array).'');
                // }
    
                // //$doc["doc"]["bdpi"] = DadosExternos::query_bdpi_index($doc["doc"]["name"], $doc["doc"]["datePublished"]);
                // $doc["doc"]["concluido"] = "Não";
                // $doc["doc_as_upsert"] = true;
    
                // // Comparador
                // $resultado = upsert($doc, $sha256);
                // echo "<br/>";
                // print_r($resultado);
                // echo "<br/><br/>";
    
    
                // unset($dadosBasicosDoTrabalho);
                // unset($detalhamentoDoTrabalho);
                // unset($obra);
                // unset($doc);
                // unset($sha_array);
                // unset($sha256);
                // flush();
    
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