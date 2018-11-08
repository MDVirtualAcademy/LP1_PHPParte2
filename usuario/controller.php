<?php
	$titulo = "Manutenção de Usuários";
	try
    {
        $conexao = new PDO("mysql:host=".$db_host."; dbname=".$db_name, $db_user, $db_pass);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conexao->exec("set names utf8");
    }
    catch (PDOException $e)
    {
        echo "A conexão falhou, erro reportado: ".$e->getMessage();
        exit();
    }

	require("mdl_usuario.php");
	
	// qual será a view a ser carregada
	// p = listar e p = cadastrar e p = excluir
	
	if(isset($_GET['p']))
	{
		$passo = $_GET['p'];
	}
	else
	{
		$passo = null;
	}	
	
	switch($passo)
    {
        case "cadastrar" :
            cadastrarUsuario( $conexao );
			break;
        case "alterar" :
            alterarUsuario( $conexao );
            break;
		case "excluir" :
            $retornoExc = excluirUsuario( $conexao );
            $dados = listarDados($conexao);
            require("view_lista.php");
			break;
		default:
			$dados = listarDados($conexao);
			require("view_lista.php");		
			break;
	}

    @mysqli_close($conexao);

    function listarDados($conexao)
    {
		$resultado = usuario_listar($conexao);		

		return $resultado;
	}

    function excluirUsuario( $conexao )
    {
        $id_usuario = (isset($_GET["codigo"])) ? $_GET["codigo"] : -1;
        $resultado = usuario_excluir($conexao, $id_usuario);
        if($resultado)
        {
            return "Exclusão efetuada com sucesso!";
        }
        else
        {
            return "";
        }
    }

    function cadastrarUsuario( $conexao )
    {
        $titulo = "Cadastro de novo usuário";
        // verificamos se o formulário foi postado
        if( isset($_POST['frmCadUsuario']) )
        {
            // postou o formulário de cadastro
            $usuario = $_POST['txtNomeUsuario'];
            $idade   = $_POST['txtIdadeUsuario'];

            if(usuario_cadastrar( $conexao, $usuario, $idade ))
            {
                $retornoExc = "Usuário cadastrado com sucesso!";
                $dados = listarDados($conexao);
                require("view_lista.php");
            }
            else
            {
                echo "O cadastro falhou, tente novamente!";
                require("view_form_cadastro_novo_usuario.php");
            }

        }
        else
        {
            // mostrar o formulário de cadastro
            require("view_form_cadastro_novo_usuario.php");
        }
    }

    function alterarUsuario( $conexao )
    {
        $titulo = "Alterar usuário";
        if(isset($_POST['idusuario']))
        {
            $usuario = $_POST['txtNomeUsuario'];
            $idade   = $_POST['txtIdadeUsuario'];
            $id  = $_POST['idusuario'];
            if(usuario_alterar( $conexao, $usuario, $idade, $id))
            {
                $retornoExc = "Usuário alterado com sucesso!";
                $dados = listarDados($conexao);
                require("view_lista.php");
                return false;
            }
            else
            {
                echo "A alteração falhou, verifique os dados!";
            }
        }
        if(isset($_POST['idusuario']))
        {
            $id = $_POST['idusuario'];
        }
        else
        {
            $id = $_GET['codigo'];
        }
        $retorno = usuario_porId($conexao, $id);
        if(!$retorno)
        {
            echo "Falha em buscar o usuario por ID";
            return false;
        }
        $dadosUsuario = mysqli_fetch_row($retorno);
        $dados = array("id" => $dadosUsuario[0], "nome" => $dadosUsuario[1], "idade" => $dadosUsuario[2]);
        require("view_form_cadastro_altera_usuario.php");
    }

