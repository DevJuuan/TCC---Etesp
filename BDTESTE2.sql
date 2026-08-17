CREATE DATABASE SUPERCLICK

USE SUPERCLICK

USE master

CREATE TABLE Cliente (
    Id_Cliente INT PRIMARY KEY IDENTITY(1,1),
    Nome VARCHAR(100),
    CPF VARCHAR(11),
    Email VARCHAR(100) unique,
	CEP VARCHAR(9),
	Rua VARCHAR(150),
	Numero VARCHAR(10),
	Bairro VARCHAR(100),
	Cidade VARCHAR(100),
	Estado VARCHAR(2),
	Complemento VARCHAR(100),
    Telefone VARCHAR(20),
	Senha VARCHAR(100)
);

CREATE TABLE Contato(
    Id_Contato INT PRIMARY KEY IDENTITY(1,1),
    Nome VARCHAR(100),
    Email VARCHAR(100),
    Mensagem VARCHAR(700)
)

CREATE TABLE Funcionario (
    Id_Func INT PRIMARY KEY IDENTITY(1,1),
    Nome VARCHAR(100),
    Cargo VARCHAR(50),
	CPF VARCHAR (11) UNIQUE,
    Ativo BIT
);

CREATE TABLE Categoria (
    Id_Categoria INT PRIMARY KEY IDENTITY(1,1),
    Nome VARCHAR(100)
);

CREATE TABLE Produto (
    Id_Produto INT PRIMARY KEY IDENTITY(1,1),
    Nome VARCHAR(100),
    Preco DECIMAL(10,2),
	Qtnd INT,
    Id_Categoria INT,
    FOREIGN KEY (Id_Categoria)
    REFERENCES Categoria(Id_Categoria)
);

CREATE TABLE Pedido (
    Id_Pedido INT PRIMARY KEY IDENTITY(1,1),
    Id_Cliente INT,
    Id_Funcionario INT,
    Data_Pedido DATE,
    Status_Pedido VARCHAR(20),
	Valor_Total DECIMAL(10,2),
    FOREIGN KEY (Id_Cliente)
    REFERENCES Cliente(Id_Cliente),
    FOREIGN KEY (Id_Funcionario)
    REFERENCES Funcionario(Id_Func)
);

CREATE TABLE Pedido_Item (
    Id_Pedido_Item INT PRIMARY KEY IDENTITY(1,1),
    Id_Pedido INT,
    Id_Produto INT,
    Quantidade INT,
    FOREIGN KEY (Id_Pedido)
    REFERENCES Pedido(Id_Pedido),
    FOREIGN KEY (Id_Produto)
    REFERENCES Produto(Id_Produto)
);


SELECT * FROM Cliente;
SELECT * FROM Contato;
SELECT * FROM Produto;
SELECT * FROM Pedido;
SELECT * FROM Pedido_Item;