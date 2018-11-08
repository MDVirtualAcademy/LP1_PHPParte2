<?php
	
    function usuario_listar($conexao)
    {
	    try
        {
            $stmt = $conexao->prepare("SELECT id, nome, idade FROM usuario ORDER BY nome");
            $data = array();
            if ($stmt->execute())
            {
                while($row = $stmt->fetch(PDO::FETCH_OBJ))
                {
                    $data[] = array("id" => $row->id, "nome" => utf8_encode ( $row->nome ), "idade" => ($row->idade == "") ? "--" : $row->idade);
                }
            }
        }
        catch (PDOException $e)
        {
            $data = "";
        }

	    return $data;
    }

    function usuario_porId($conexao, $id)
    {
        try
        {
            $stmt = $conexao->prepare("SELECT id, nome, idade FROM usuario WHERE id = ?");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $resultado = $stmt->execute();
        }
        catch (PDOException $e)
        {
            $resultado = "";
        }

        return $resultado;
    }

    function usuario_excluir($conexao, $id)
    {
        try
        {
            $stmt = $conexao->prepare("DELETE FROM usuario WHERE id = ?");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $resultado = $stmt->execute();
        }
        catch (PDOException $e)
        {
            $resultado = false;
        }

        return $resultado;
    }

    function usuario_cadastrar( $conexao, $usuario_nome, $usuario_idade)
    {
        if( $usuario_nome == "" )
        {
            return false;
        }
        if( $usuario_idade == "" )
        {
            $usuario_idade = 'NULL';
        }
        try
        {
            $stmt = $conexao->prepare("INSERT INTO usuario (nome, idade) VALUES (?, ?)");
            $stmt->bindParam(1, $usuario_nome);
            $stmt->bindParam(2, $usuario_idade);
            $resultado = $stmt->execute();
            return $resultado;
        }
        catch (PDOException $e)
        {
            return false;
        }
    }

    function usuario_alterar( $conexao, $usuario_nome, $usuario_idade, $usuario_id)
    {
        if( $usuario_nome == "" )
        {
            return false;
        }
        if( $usuario_idade == "" )
        {
            $usuario_idade = 'NULL';
        }
        try
        {
            $stmt = $conexao->prepare("UPDATE usuario SET nome = ?, idade = ? WHERE id = ?");
            $stmt->bindParam(1, $usuario_nome);
            $stmt->bindParam(2, $usuario_idade);
            $stmt->bindParam(3, $usuario_id);
            $resultado = $stmt->execute();
        }
        catch (PDOException $e)
        {
            $resultado = false;
        }

        return $resultado;

    }