<?php
	// verificar se o IP  que está acessando a pagina não está em nossa blocklist
	
	$ipsbloqueados = array("10.0.0.1");
	
	foreach($ipsbloqueados as $ip)
	{
	
		if($ip == $_SERVER['REMOTE_ADDR'])
		{
			// redireciona o usuário para a página de acesso negado
			header("Location: negado.php");
			exit();	
		}
	
	}

	// previne o cache de pagina
	
	header("Expires: Mon, 21, Out 1999 00:00:00 GMT");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");

	// vamos declarar os dados do nosso banco de dados
	//host, username pass, dbname
	$db_host = "127.0.0.1";
	$db_user = "root";
	$db_pass = "";
	$db_name = "exemplo01";

	// vamos receber uma variavel chamada r que significa rota
	
	$r = $_GET['r'];
	
	require_once("funcoes.php");
	require_once($r."/index.php");