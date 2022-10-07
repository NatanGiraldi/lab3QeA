<?php
    require_once "DataBase.php";

    $nome = $password = $confirm_password = "";
    $nome_erro = $password_erro = $confirm_password_erro = "";
    //$_SERVER array contendo informações essenciais de servidor, como cabeçalho, path e localização de scripts
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        //usuario
        if(empty(trim($_POST["nome"]))){
            $nome_err = "Informe um nome se usuario.";
        }else if(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["nome"]))){
            $nome_err = "O Nome deve conter apenas letras e numeros.";
        }else{
            //sql stmt_prepare prepara a declaração SQL para execução
            // o "?" é um parametro que será substituido / definido posteriormente
            $sql = "SELECT id FROM users WHERE nome = ?";
            if($stmt = $mysqli->prepare($sql)){
                //interliga as variaveis como parametros na declaração preparada
                $stmt->bind_param("s", $param_nome);
                $param_nome = trim($_POST["nome"]);
            
                // Tenta executar essa declração
                if($stmt->execute()){
                    // Armazena o resultado
                    $stmt->store_result();
                    //Verifica se o usuario já existe, se nao termina de enviar 
                    if($stmt->num_rows == 1){
                        $nome_err = "Usuario já existe!";
                    } else{
                        $nome = trim($_POST["nome"]);
                    }
                } else{
                    echo "Algo deu errado.";
                }
    
                //Fecha a declaração
                $stmt->close();

            }
        
        }
        //Senha
        if(empty(trim($_POST["password"]))){
            $password_err = "Please enter a password.";     
        } elseif(strlen(trim($_POST["password"])) < 6){
            $password_err = "Password must have atleast 6 characters.";
        } else{
            $password = trim($_POST["password"]);
        }
        if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = "Please confirm password.";     
        } else{
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($password_err) && ($password != $confirm_password)){
                $confirm_password_err = "Password did not match.";
            }
        }

        //verifica por erros antes de enviar para o banco, todas as variaveis de erro devem estar vazias.
        if(empty($nome_err) && empty($password_err) && empty($confirm_password_err)){
            //Prepara a declaração de insersão
            $sql = "INSERT INTO users (nome, password) VALUES (?, ?)";

            if($stmt = $mysqli->prepare($sql)){
                //interliga as variaveis como parametros na declaração preparada
                $stmt->bind_param("ss", $param_nome, $param_password);

                $param_nome = $nome;
                $param_password = password_hash($password, PASSWORD_DEFAULT);//Criptografa a senha com HASH
            if($stmt->execute()){
                //redireciona para a pagina de login após tentar execurtar a declaração
                header("location: login.php");
            }else{
                echo "Algo deu errado";
            }
            $stmt->close();
        }
        
        }
        //fecha conexão com banco de dados
        $mysqli->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Criação de conta</h2>
        <p>Preencha o formulario para criar sua conta</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);
        ?>" method="post">
            <div class="form-group">
                <label>nome</label>
                <input type="text" name="nome" class="form-control"
                <?php echo (!empty($nome_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nome; ?>">
                <span class="invalid-feedback"><?php echo $nome_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
        </form>
    </div>    
</body>
</html>