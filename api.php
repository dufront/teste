<?php

error_reporting(0);
set_time_limit(0);
date_default_timezone_set('America/Sao_Paulo');

# ------------ Deletar cookie ------------ #
if (file_exists("cookie.txt")!== false) {unlink("cookie.txt");fopen
  ("cookie.txt", 'w+');fclose
  ("cookie.txt");}else{fopen
  ("cookie.txt", 'w+');fclose
  ("cookie.txt");}


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    extract($_POST);
} elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
    extract($_GET);
}

# ------------ URL ------------ #
extract($_GET);
$lista = str_replace(" " , "", $lista);
$separar = explode("|", $lista);
$email = $separar[0];
$senha = $separar[1];

#$lista = ("$cc|$mes|$ano|$cvv");


# ------------ Bins.csv ------------ #
function bin ($cc)
{
    $contents = file_get_contents("bins.csv");
    $pattern = preg_quote(substr($cc, 0, 6), '/');
    $pattern = "/^.*$pattern.*\$/m";
    if (preg_match_all($pattern, $contents, $matches)) 
{
    $encontrada = implode("\n", $matches[0]);
}
    $pieces = explode(";", $encontrada);
    return "$pieces[1] $pieces[2] $pieces[3] $pieces[4] $pieces[5]";
}

# ------------ Filtro ------------ #
function getStr($string,$start,$end){
    $str = explode($start,$string);
    $str = explode($end,$str[1]);
    return $str[0];
    }
$bin = bin($lista);


$cpf = $_GET['lista'];

# ------------ Requisição ------------ #
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.i-find.dev/?token=1ac77e80-6463-43cf-a243-bd23c7b37d23&cpf='.$cpf);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_ENCODING, "gzip");
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
'accept-language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
'cache-control: max-age=0',
'sec-ch-ua: " Not;A Brand";v="99", "Google Chrome";v="97", "Chromium";v="97"',
'sec-ch-ua-mobile: ?0',
'sec-ch-ua-platform: "Windows"',
'sec-fetch-mode: navigate',
'sec-fetch-site: none',
'sec-fetch-user: ?1',
'upgrade-insecure-requests: 1',
'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36',
));

#site
$cart = curl_exec($ch);

if (strpos($cart, 'Erro')) {
   echo "die: $cpf";
}elseif(strpos($cart, 'erro')){
    echo "die: $cpf";
}else{
    $sexo = getStr($cart,'sexo": "','"');
    if ($sexo == 'MASCULINO') {
      $nome = getStr($cart,'nome": "','"');
    $nasc = getstr($cart, 'dataNascimento": "', '"');
    echo ('#Aprovada": '.$cpf.':'.$nome.':'.$nasc);
    }else{
      echo "Error: $cpf";
    }
}

?>