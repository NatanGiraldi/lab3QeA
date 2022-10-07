<?php
    //connexão com o mysql
    $banco = mysqli_connect("localhost", "root", "") or
        die('Erro ao conectar!');
    //Criação do banco 
    mysqli_query($banco , "CREATE DATABASE IF NOT EXISTS QeA_Lab3");
    //conexão com o banco criado
    mysqli_select_db($banco , "QeA_Lab3");

    //criação das tabelas
    $sql1 = <<<EOT
        CREATE TABLE IF NOT EXISTS IF NOT EXISTS tb_disciplinas(
            id_disciplina int not null auto_increment,
            disciplina VARCHAR(40),
            primary key(id_disciplina)
        );
        CREATE TABLE IF NOT EXISTS tb_perguntas(
            id_pg int not null auto_increment,
            questao VARCHAR(200),
            alternativa1 VARCHAR(100),
            alternativa2 VARCHAR(100),
            alternativa3 VARCHAR(100),
            alternativa4 VARCHAR(100),
            alt_correta int,
            id_disciplina int,
            PRIMARY KEY(id_pg),
            CONSTRAINT fk_disciplina FOREIGN KEY (id_disciplina) 
            REFERENCES tb_disciplinas(id_disciplina)
        );
        CREATE TABLE IF NOT EXISTS tb_usuarios(
            id_aluno int not null auto_increment,
            nome VARCHAR(40),
            email VARCHAR(100),
            senha VARCHAR(10),
            PRIMARY KEY(id_aluno)
        );
        CREATE TABLE IF NOT EXISTS tb_respostas(
            id_pg int,
            id_aluno int,
            resposta int,
            FOREIGN KEY (id_pg) REFERENCES tb_perguntas(id_pg)
            FOREIGN KEY (id_aluno) REFERENCES tb_usuarios(id_aluno)
        );
    EOT;

    mysqli_query($banco, $sql1);

