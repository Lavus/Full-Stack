CREATE TABLE IF NOT EXISTS `clientes` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `nome_cliente` varchar(100) NOT NULL,
  CONSTRAINT pk_cliente PRIMARY KEY (id_cliente)
);

CREATE TABLE IF NOT EXISTS `produtos` (
  `id_produto` int(11) NOT NULL AUTO_INCREMENT,
  `nome_produto` varchar(100) NOT NULL,
  `preco_unitario_produto` float NOT NULL,
  `multiplo_produto` int NOT NULL,
  CONSTRAINT pk_produto PRIMARY KEY (id_produto)
);

CREATE TABLE IF NOT EXISTS `pedido` (
  `id_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `id_produto_pedido` int(11) NOT NULL,
  `id_cliente_pedido` int(11) NOT NULL,
  `preco_unitario_pedido` float NOT NULL,
  `quantidade_pedido` int NOT NULL,
  `tempo_pedido` datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
  CONSTRAINT pk_pedido PRIMARY KEY (id_pedido),
  CONSTRAINT fk_produto_pedido FOREIGN KEY (id_produto_pedido) REFERENCES produtos (id_produto),
  CONSTRAINT fk_cliente_pedido FOREIGN KEY (id_cliente_pedido) REFERENCES clientes (id_cliente)
);


INSERT INTO `clientes` (`id_cliente`, `nome_cliente`) VALUES (1, 'Darth Vader'), (2, 'Obi-Wan Kenobi'), (3, 'Luke Skywalker'), (4, 'Imperador Palpatine'), (5, 'Han Solo');
INSERT INTO `produtos` (`id_produto`, `nome_produto`, `preco_unitario_produto`, `multiplo_produto`) VALUES (1, 'Millenium Falcon', 550000.00, 1), (2, 'X-Wing', 60000.00, 2), (3, 'Super Star Destroyer', 4570000.00, 1), (4, 'TIE Fighter', 75000.00, 2), (5, 'Lightsaber', 6000.00, 5), (6, 'DLT-19 Heavy Blaster ifle', 5800.00, 1), (7, 'DL-44 Heavy Blaster Pistol', 1500.00, 10);
