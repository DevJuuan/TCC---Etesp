CREATE DATABASE SUPERCLICK

USE SUPERCLICK

USE master

CREATE TABLE Cliente (
    Id_Cliente INT PRIMARY KEY IDENTITY(1,1),
    Nome VARCHAR(100),
    CPF VARCHAR(15),
    Email VARCHAR(100) unique,
	CEP VARCHAR(16),
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
    Senha VARCHAR(100),
    Ativo BIT
);


CREATE TABLE Categoria (
    Id_Categoria INT PRIMARY KEY IDENTITY(1,1),
    Nome VARCHAR(100)
);

CREATE TABLE Produto (
    Id_Produto INT PRIMARY KEY IDENTITY(1,1),
    Nome VARCHAR(100),
    Marca VARCHAR(100),
    Preco DECIMAL(10,2),
	Qtnd INT,
    Descricao VARCHAR (200),
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
    Preco_Unitario DECIMAL(10,2),
    FOREIGN KEY (Id_Pedido) REFERENCES Pedido(Id_Pedido),
    FOREIGN KEY (Id_Produto) REFERENCES Produto(Id_Produto)
);

INSERT INTO Categoria VALUES ('Mercearia'),--1
    ('Laticínios'),--2
    ('Doces'),--3
    ('Hortifruti'),--4
    ('Carnes'),--5
    ('Bebidas'),--6
    ('Limpeza'),--7
    ('Medicamentos'),--8
    ('Higiene Pessoal'),--9
    ('Cuidados Bucais'),--10
    ('Primeiros Socorros'),--11
    ('Acessórios de Saúde'),--12
    ('Proteção e Cuidados'),--13
    ('Cabelos');--14

INSERT INTO Produto (Nome, Marca, Preco, Qtnd, Descricao, Id_Categoria)
VALUES 
    ('Café Torrado 500g','Café Pelé', 19.99, 50, 'Sabor intenso e aroma marcante', 1),
    ('Arroz Tipo 1 5kg','Camil', 15.99, 70, 'Grãos selecionados, ideal para o dia a dia', 1),
    ('Feijão Carioca 1kg','Kicaldo', 8.39, 70, 'Cozimento rápido e sabor caseiro', 1),
    ('Açúcar Refinado 1kg','União', 3.19, 40, 'Versátil para receitas e bebidas', 1),
    ('Macarrão Espaguete 500g','Adria', 5.49, 80, 'Durabilidade e textura perfeita', 1),
    ('Leite Integral 1L','Parmalat', 4.99, 34, 'Fonte de proteína e energia', 2),
    ('Iogurte Natural 170g','Vigor', 15.99, 70, 'Mais cremosidade, mais sabor', 2),
    ('Queijo Mussarela 300g','Président', 24.99, 20, 'Fatiado para suas receitas', 2),
    ('Banana Prata kg','Frutas', 5.49, 100, 'Maduras na medida certa', 4),
    ('Tomate Italiano kg','Frutas', 8.79, 23, 'Vermelho, firme e suculento', 4),
    ('Alface Crespa Unidade','Verduras', 5.49, 70, 'Folhas crocantes e frescas', 4),
    ('Frango em Cubos 400g','Sadia', 18.99, 50, 'Prático para o dia a dia', 5),
    ('Peixe Tilápia Filé 500g','Peixaria', 39.99, 50, 'Assa, grelha ou cozinha', 5),
    ('Patinho Moído 500g','Açougue', 29.99, 20, 'Sabor suave e ótimo para receitas', 5),
    ('Detergente Neutro 500ml','Ype', 2.99, 73, 'Remove gordura com eficiência', 7),
    ('Sabão em Pó 1kg','Omo', 28.49, 80, 'Roupas limpas e cheiro agradável', 7),
    ('Álcool 70 1L','Asfer', 10.49, 42, 'Para higiene e limpeza geral', 7),
    ('Sal Refinado 1Kg','Cisne', 2.49, 22, 'Essencial para temperar', 1),
    ('Alho Kg','Temperos', 1.49, 150, 'Tempero indispensável', 4),
    ('Margarina 1 Kg','Qualy', 9.49, 10, 'Leve, prática e cremosa', 2),
    ('Palha de Aço','Bombril', 6.49, 12, 'Brilho para panelas e alumínios', 7),
    ('Amaciante 500ml','Comfort', 12.49, 5, 'Roupas macias e perfumadas', 7),
    ('Nuggets de Frango 275g','Sadia', 14.99, 92, 'Crocante, prático e delicioso', 5),
    ('Bacon em Fatias 250g','Seara', 29.99, 31, 'Defumado, crocante e delicioso', 5),
    ('Refrigerante Cola 2L','Coca-Cola', 7.79, 100, 'Gelado, clássico e refrescante', 6),
    ('Refrigerante Guaraná 2L','Guaraná Antarctica', 5.79, 102, 'Doce, leve e brasileiro', 6),
    ('Suco de Maracujá 1L','Del Valle', 4.49, 82, 'Tropical, leve e marcante', 6),
    ('Biscoito Recheado 90g','Oreo', 3.49, 53, 'Crocante, doce e recheado', 3),
    ('Cookie Chocolate','Toddy', 3.49, 20, 'Crocante, doce e chocolatudo', 3),
    ('Salgadinho de Milho 32g','Doritos', 9.49, 62, 'Crocante, intenso e marcante', 3),
    ('Energético 473ml','Monster', 9.99, 70, 'Sabor intenso para mais disposição', 6),
    ('Cenoura kg','Legumes', 4.99, 60, 'Colorida, fresca e nutritiva', 4),
    ('Molho de Tomate 300g','Pomarola', 3.49, 55, 'Encorpado para pratos caseiros', 1),
    ('Bala de Gelatina 100g','Fini', 6.49, 72, 'Macia, colorida e cheia de sabor', 3),
    ('Limpador Multiuso 500ml','Veja', 6.99, 22, 'Remove sujeiras de várias superfícies', 7),
    ('Requeijão Cremoso 200g','Catupiry', 8.99, 25, 'Textura suave para pães e torradas', 2),
    ('Linguiça Toscana 700g','Seara', 21.99, 62, 'Temperada para churrascos e refeições', 5),
    ('Óleo de Soja 900ml','Liza', 5.99, 7, 'Leve para frituras e preparos diários', 1),
    ('Maçã Fuji kg','Frutas', 8.69, 23, 'Vermelha, crocante e adocicada', 4),
    ('Chocolate ao Leite 90g','Lacta', 6.99, 20, 'Derrete na boca com sabor marcante', 3),
    ('Água Mineral 1,5L','Crystal', 3.29, 14, 'Pura para hidratar a qualquer momento', 6),
    ('Filé de Frango 1kg','Perdigão', 23.99, 21, 'Ideal para grelhados e receitas leves', 5),
    ('Cream Cheese 150g','Philadelphia', 12.99, 70, 'Cremoso para lanches especiais', 2),
    ('Farinha de Trigo 1kg','Dona Benta', 4.79, 46, 'Base ideal para massas fofinhas', 1),
    ('Desinfetante 1L','Pinho Sol', 7.49, 21, 'Perfume agradável com ação de limpeza', 7),
    ('Whisky Combo Maçã Verde','Mansão Maromba', 27.49, 54, 'Whisky sabor energético... SabOOOr', 6),
    ('Batata Inglesa kg','Legumes', 6.49, 72, 'Boa para assar, cozinhar ou fritar', 4),
    ('Termômetro Digital','G-Tech', 24.99, 10, 'Medição simples para cuidados em casa', 12),
    ('Creme Dental 90g','Colgate', 4.99, 70, 'Hálito fresco e dentes limpos', 10),
    ('Sabonete Dove 90g','Dove', 4.49, 89, 'Limpeza suave com toque hidratante', 9),
    ('Condicionador 400ml','Pantene', 18.99, 32, 'Fios macios e fáceis de pentear', 14),
    ('Dipirona Monoidratada 1g','Prati', 15.99, 70, 'Analgésico (para dor) e antitérmico (para febre)', 8),
    ('Soro Fisiológico 500ml','Farmax', 8.49, 22, 'Ideal para limpeza e higiene diária', 11),
    ('Protetor Solar FPS 50 120ml','Neutrogena', 39.99, 12, 'Cuidado diário contra os raios solares', 13),
    ('Desodorante Aerosol 150ml','Rexona', 10.99, 50, 'Proteção para a rotina do dia', 9),
    ('Curativo 40 Unidades','Band-Aid', 16.99, 50, 'Proteção prática para pequenos machucados', 11),
    ('Antigripal 10 Cápsulas','Cimegripe', 8.90, 70, 'Para gripes e resfriados', 8),
    ('Creme de Pentear 300ml','Seda', 8.99, 21, 'Ajuda a desembaraçar e modelar', 14),
    ('Máscara Descartável 10 Unidades','Ever Care', 11.99, 10, 'Uso prático para proteção diária', 12),
    ('Enxaguante Bucal 250ml','Listerine', 14.99, 42, 'Sensação refrescante após a escovação', 10),
    ('Álcool em Gel 70% 500ml','Asseptgel', 9.99, 23, 'Higienização rápida para as mãos', 13),
    ('Algodão Hidrófilo 50g','Apolo', 5.49, 12, 'Macio para higiene e cuidados diários', 11),
    ('Shampoo 300ml','Salon Line', 9.99, 28, 'Cuidado especial para os cabelos', 14),
    ('Creme Hidratante 200ml','Nivea', 16.99, 33, 'Pele macia e bem cuidada', 13),
    ('Escova Dental Macia','Oral-B', 7.99, 44, 'Limpeza confortável para os dentes', 10),
    ('Repelente Spray 100ml','SBP', 15.99, 7, 'Ajuda na proteção contra insetos', 13);




SELECT * FROM Cliente;
SELECT * FROM Contato;
SELECT * FROM Produto;
SELECT * FROM Funcionario;
SELECT * FROM Categoria;
SELECT * FROM Pedido;
SELECT * FROM Pedido_Item;