CREATE TABLE cliente ( 
    cod_cliente       VARCHAR(15) NOT NULL,
    nom_cliente         VARCHAR(36) NOT NULL,
    PRIMARY KEY(cod_cliente)
);

CREATE TABLE item ( 
    cod_item             VARCHAR(15) NOT NULL,
    den_item                  VARCHAR(36) NOT NULL,
    PRIMARY KEY(cod_item)
);

CREATE TABLE pedido ( 
    num_pedido               DECIMAL(6,0) NOT NULL,
    cod_cliente              VARCHAR(15) NOT NULL,
    PRIMARY KEY(num_pedido),
    FOREIGN KEY (cod_cliente) REFERENCES cliente(cod_cliente)
);

CREATE TABLE item_pedido ( 
    num_pedido            DECIMAL(6,0) NOT NULL,
    num_seq_item          DECIMAL(9,0) NOT NULL,
    cod_item              VARCHAR(15) NOT NULL,
    qtd_solicitada        DECIMAL(12,3) NOT NULL,
    pre_unitario          DECIMAL(17,6) NOT NULL,
    PRIMARY KEY(num_pedido,num_seq_item),
    FOREIGN KEY (num_pedido) REFERENCES pedido(num_pedido),
    FOREIGN KEY (cod_item) REFERENCES item(cod_item)
);

INSERT INTO cliente (cod_cliente, nom_cliente) VALUES ('1', 'CLIENTE 1'), ('2', 'CLIENTE 2');
INSERT INTO item(cod_item, den_item) VALUES ('1', 'ITEM 1'), ('2', 'ITEM 2');
