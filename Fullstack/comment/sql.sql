CREATE TABLE IF NOT EXISTS `clientes` ( -- ~ cria a tabela clientes no banco de dados se não existir, com os parametros e campos a seguir
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT, -- ~ cria o campo com nome id_cliente do tipo inteiro que não pode ser nulo e que auto se preenche com o ultimo valor sequencial + 1 
  `nome_cliente` varchar(100) NOT NULL, -- ~ cria o campo do tipo texto com 100 caracteres que não pode ser nulo
  CONSTRAINT pk_cliente PRIMARY KEY (id_cliente) -- ~ cria uma chave primaria da tabela que não pode repetir sendo unica, e indentidica qual campo da tabela vai ser a chave primaria nesse caso id_cliente
); -- ~ indica o final da criação da tabela

CREATE TABLE IF NOT EXISTS `produtos` ( -- ~ cria a tabela produtos no banco de dados se não existir, com os parametros e campos a seguir
  `id_produto` int(11) NOT NULL AUTO_INCREMENT, -- ~ cria o campo com nome id_produto do tipo inteiro que auto incrementa e não pode ser nulo
  `nome_produto` varchar(100) NOT NULL, -- ~ cria o campo do tipo texto com 100 caracteres que não pode ser nulo
  `preco_unitario_produto` float NOT NULL, -- ~ cria o campo do tipo decimal que não pode ser nulo
  `multiplo_produto` int NOT NULL, -- ~ cria o campo do tipo inteiro que não pode ser nulo
  CONSTRAINT pk_produto PRIMARY KEY (id_produto) -- ~ cria uma chave primaria da tabela que não pode repetir sendo unica, e indentidica qual campo da tabela vai ser a chave primaria nesse caso id_produto
); -- ~ indica o final da criação da tabela

CREATE TABLE IF NOT EXISTS `pedido` ( -- ~ cria a tabela pedido no banco de dados se não existir, com os parametros e campos a seguir
  `id_pedido` int(11) NOT NULL AUTO_INCREMENT, -- ~ cria o campo com nome id_pedido do tipo inteiro que auto incrementa e não pode ser nulo
  `id_produto_pedido` int(11) NOT NULL, -- ~ cria o campo do tipo inteiro que não pode ser nulo
  `id_cliente_pedido` int(11) NOT NULL, -- ~ cria o campo do tipo inteiro que não pode ser nulo
  `preco_unitario_pedido` float NOT NULL, -- ~ cria o campo do tipo decimal que não pode ser nulo
  `quantidade_pedido` int NOT NULL, -- ~ cria o campo do tipo inteiro que não pode ser nulo
  `tempo_pedido` datetime DEFAULT CURRENT_TIMESTAMP NOT NULL, -- ~ cria o campo do tipo data com tempo que não pode ser nulo e por padrão vai pegar o tempo atual do banco de dados
  CONSTRAINT pk_pedido PRIMARY KEY (id_pedido), -- ~ cria uma chave primaria da tabela que não pode repetir sendo unica, e indentidica qual campo da tabela vai ser a chave primaria nesse caso id_pedido
  CONSTRAINT fk_produto_pedido FOREIGN KEY (id_produto_pedido) REFERENCES produtos (id_produto),
  CONSTRAINT fk_cliente_pedido FOREIGN KEY (id_cliente_pedido) REFERENCES clientes (id_cliente)
); -- ~ indica o final da criação da tabela

DROP TRIGGER IF EXISTS `validate_insert_pedido`; -- ~ deleta o gatilho se existir com nome validate_insert_pedido
DELIMITER $$ -- ~ Delimita que vai trabalhar com variaveis e consultas
CREATE TRIGGER `validate_insert_pedido` BEFORE INSERT ON `pedido` FOR EACH ROW BEGIN -- ~ cria um gatilho para que sempre antes de uma inserção na tabela pedido ele execute os comandos a seguir
    DECLARE preco_produto Float; -- ~ declara uma variavel chamada preco_produto do tipo decimal
    DECLARE quantidade_produto INT; -- ~ declara uma variavel chamada quantidade_produto do tipo inteiro
    SET preco_produto = (SELECT produtos.preco_unitario_produto FROM produtos WHERE produtos.id_produto = NEW.id_produto_pedido); -- ~ executa uma pesquisa para pegar o preço do produto e adiciona a variavel preco_produto
    SET quantidade_produto = (SELECT produtos.multiplo_produto FROM produtos WHERE produtos.id_produto = NEW.id_produto_pedido); -- ~ executa uma pesquisa para pegar o multiplo do produto e adiciona a variavel quantidade_produto
    IF (NEW.preco_unitario_pedido < (preco_produto*0.9)) THEN -- ~ verifica se o preço da inserção e menor que o preço do produto menos 10%, exemplo preço produto é 100, então ele verifica se o preço da inserção é menor que 100 - 10% resumindo menor que 90
        SET NEW.preco_unitario_pedido = NULL; -- ~ se for menor muda o novo preço para nulo para cancelar o pedido, pois não se pode inserir coisas nulas
    END IF; -- ~ termina a verificação
    IF ((SELECT MOD(NEW.quantidade_pedido, quantidade_produto)) != 0) THEN -- ~ verifica se o resto da divisão entre a quantidade informada na inserção pelo multiplo do pruduto é diferente de 0, resumindo se a divisão dá numero inteiro ele será 0, caso contrario for flacionado ele dá qualquer numero menos 0
        SET NEW.quantidade_pedido = NULL; -- ~ se o resto foi diferente de zero muda o valor da inserção de quantidade para nulo, para cancelar o pedido
    END IF; -- ~ termina a verificação
END -- ~ termina o gatilho
$$ -- ~ termina a delimitação
DELIMITER ; -- ~ termina a delimitação

DROP TRIGGER IF EXISTS `validate_update_pedido`; -- ~ deleta o gatilho se existir com nome validate_update_pedido
DELIMITER $$ -- ~ Delimita que vai trabalhar com variaveis e consultas
CREATE TRIGGER `validate_update_pedido` BEFORE UPDATE ON `pedido` FOR EACH ROW BEGIN -- ~ cria um gatilho para que sempre antes de uma atualização na tabela pedido ele execute os comandos a seguir
    DECLARE preco_produto Float; -- ~ declara uma variavel chamada preco_produto do tipo decimal
    DECLARE quantidade_produto INT; -- ~ declara uma variavel chamada quantidade_produto do tipo inteiro
    SET preco_produto = (SELECT produtos.preco_unitario_produto FROM produtos WHERE produtos.id_produto = NEW.id_produto_pedido);); -- ~ executa uma pesquisa para pegar o preço do produto e adiciona a variavel preco_produto
    SET quantidade_produto = (SELECT produtos.multiplo_produto FROM produtos WHERE produtos.id_produto = NEW.id_produto_pedido); -- ~ executa uma pesquisa para pegar o multiplo do produto e adiciona a variavel quantidade_produto
    IF (NEW.preco_unitario_pedido < (preco_produto*0.9)) THEN  -- ~ verifica se o preço da atualização e menor que o preço do produto menos 10%, exemplo preço produto é 100, então ele verifica se o preço da atualização é menor que 100 - 10% resumindo menor que 90
        SET NEW.preco_unitario_pedido = NULL; -- ~ se for menor muda o novo preço para nulo para cancelar o pedido, pois não se pode atualização coisas nulas
    END IF; -- ~ termina a verificação
    IF ((SELECT MOD(NEW.quantidade_pedido, quantidade_produto)) != 0) THEN -- ~ verifica se o resto da divisão entre a quantidade informada na atualização pelo multiplo do pruduto é diferente de 0, resumindo se a divisão dá numero inteiro ele será 0, caso contrario for flacionado ele dá qualquer numero menos 0
        SET NEW.quantidade_pedido = NULL; -- ~ se o resto foi diferente de zero muda o valor da atualização de quantidade para nulo, para cancelar o pedido
    END IF; -- ~ termina a verificação
END -- ~ termina o gatilho
$$ -- ~ termina a delimitação
DELIMITER ; -- ~ termina a delimitação

INSERT INTO `clientes` (`id_cliente`, `nome_cliente`) VALUES (1, 'Darth Vader'), (2, 'Obi-Wan Kenobi'), (3, 'Luke Skywalker'), (4, 'Imperador Palpatine'), (5, 'Han Solo'); -- ~ insere na tabela clientes os valores informados
INSERT INTO `produtos` (`id_produto`, `nome_produto`, `preco_unitario_produto`, `multiplo_produto`) VALUES (1, 'Millenium Falcon', 550000.00, 1), (2, 'X-Wing', 60000.00, 2), (3, 'Super Star Destroyer', 4570000.00, 1), (4, 'TIE Fighter', 75000.00, 2), (5, 'Lightsaber', 6000.00, 5), (6, 'DLT-19 Heavy Blaster ifle', 5800.00, 1), (7, 'DL-44 Heavy Blaster Pistol', 1500.00, 10); -- ~ insere na tabela produtos os valores informados
