CREATE TABLE cidade( 
      id  SERIAL    NOT NULL  , 
      descricao text   , 
      uf_id integer   , 
 PRIMARY KEY (id)); 

 CREATE TABLE contato( 
      id  SERIAL    NOT NULL  , 
      pessoa_id integer   , 
      nome text   , 
      nome_reduzido text   , 
      cargo text   , 
      email text   , 
      tel_celular text   , 
      tel_fixo text   , 
 PRIMARY KEY (id)); 

 CREATE TABLE financeiro( 
      id  SERIAL    NOT NULL  , 
      movimento_id integer   NOT NULL  , 
      pessoa_id integer   NOT NULL  , 
      forma_id integer   , 
      vlr_total float   , 
      qtd_parcelas integer   , 
      usuario_registro integer   , 
      data_registro timestamp   , 
      usuario_atualizacao integer   , 
      data_atualizacao timestamp   , 
 PRIMARY KEY (id)); 

 CREATE TABLE forma( 
      id  SERIAL    NOT NULL  , 
      descricao text   , 
 PRIMARY KEY (id)); 

 CREATE TABLE grupo( 
      id  SERIAL    NOT NULL  , 
      descricao text   , 
      data_registro timestamp   , 
      usuario_registro integer   , 
      data_atualizacao timestamp   , 
      usuario_atualizacao integer   , 
 PRIMARY KEY (id)); 

 CREATE TABLE kardex( 
      id  SERIAL    NOT NULL  , 
      data_movimento date   , 
      produtos_movimento_id integer   , 
      movimento_pessoa_id integer   , 
      movimento_tipo_estoque char  (1)   , 
      qtd float   , 
      vlr_unit float   , 
      custo_medio float   , 
      saldo float   , 
 PRIMARY KEY (id)); 

 CREATE TABLE medida( 
      id  SERIAL    NOT NULL  , 
      descricao text   , 
      sigla text   , 
      data_registro timestamp   , 
      usuario_registro integer   , 
      data_atualizacao timestamp   , 
      usuario_atualizacao integer   , 
 PRIMARY KEY (id)); 

 CREATE TABLE movimento( 
      id  SERIAL    NOT NULL  , 
      tipo_estoque char  (1)   , 
      tipo_movimento_id integer   , 
      pessoa_id integer   , 
      tipo_documento_id integer   , 
      situacao_id integer   , 
      situacao_id_ant integer   , 
      numero_documento text   , 
      data_documento date   , 
      data_abertura date   , 
      data_entrega date   , 
      vlr_frete float     DEFAULT 0, 
      vlr_icms float     DEFAULT 0, 
      vlr_ipi float     DEFAULT 0, 
      vlr_total float   , 
      obs text   , 
      data_registro timestamp   , 
      usuario_registro integer   , 
      data_atualizacao timestamp   , 
      usuario_atualizacao integer   , 
 PRIMARY KEY (id)); 

 CREATE TABLE pais( 
      descricao text   , 
      id  SERIAL    NOT NULL  , 
      sigla char  (2)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE parcelas( 
      id  SERIAL    NOT NULL  , 
      financeiro_id integer   NOT NULL  , 
      parcela integer   , 
      vlr_parcela float   , 
      data_vencimento date   , 
 PRIMARY KEY (id)); 

 CREATE TABLE pessoa( 
      id  SERIAL    NOT NULL  , 
      tipo_pessoa char  (1)   , 
      ativo char  (1)   , 
      nome text   , 
      nome_reduzido text   , 
      sexo char  (1)   , 
      data_aniversario date   , 
      rg text   , 
      orgao_emissor text   , 
      cpf_cnpj text   , 
      insc_municipal text   , 
      insc_estadual text   , 
      logradouro text   , 
      numero text   , 
      complemento text   , 
      bairro text   , 
      cidade_id integer   , 
      uf char  (2)   , 
      cep text   , 
      email text   , 
      tel_celular text   , 
      tel_fixo text   , 
      obs text   , 
      data_registro timestamp   , 
      usuario_registro integer   , 
      data_atualizacao timestamp   , 
      usuario_atualizacao integer   , 
 PRIMARY KEY (id)); 

 CREATE TABLE pessoa_grupo( 
      id  SERIAL    NOT NULL  , 
      pessoa_id integer   , 
      grupo_id integer   , 
 PRIMARY KEY (id)); 

 CREATE TABLE produto( 
      id  SERIAL    NOT NULL  , 
      tipo_produto_id integer   , 
      pessoa_id integer   , 
      ativo char  (1)   , 
      descricao text   , 
      codigo_barras text   , 
      estoque_minimo float   , 
      estoque_maximo float   , 
      medida_id integer   , 
      obs text   , 
      data_registro timestamp   , 
      usuario_registro integer   , 
      data_atualizacao timestamp   , 
      usuario_atualizacao integer   , 
 PRIMARY KEY (id)); 

 CREATE TABLE produtos_movimento( 
      id  SERIAL    NOT NULL  , 
      movimento_id integer   , 
      produto_id integer   , 
      lote text   , 
      data_validade date   , 
      qtd float   , 
      vlr_unitario float   , 
      vlr_icms float   , 
      vlr_ipi float   , 
 PRIMARY KEY (id)); 

 CREATE TABLE saldos( 
      id  SERIAL    NOT NULL  , 
      produto_id integer   NOT NULL  , 
      qtd_estoque integer   , 
      qtd_reservado integer   , 
      qtd_aguardando integer   , 
 PRIMARY KEY (id)); 

 CREATE TABLE situacao( 
      id  SERIAL    NOT NULL  , 
      descricao text   , 
      sigla char  (3)   , 
      modulo char  (1)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE tipo_documento( 
      id  SERIAL    NOT NULL  , 
      descricao text   , 
      sigla text   , 
      data_registro timestamp   , 
      usuario_registro integer   , 
      data_atualizacao timestamp   , 
      usuario_atualizacao integer   , 
 PRIMARY KEY (id)); 

 CREATE TABLE tipo_movimento( 
      id  SERIAL    NOT NULL  , 
      descricao text   , 
      sigla text   , 
      tipo_estoque char  (1)   , 
      data_registro timestamp   , 
      usuario_registro integer   , 
      data_atualizacao timestamp   , 
      usuario_atualizacao integer   , 
 PRIMARY KEY (id)); 

 CREATE TABLE tipo_produto( 
      id  SERIAL    NOT NULL  , 
      descricao text   , 
      sigla text   , 
      data_registro timestamp   , 
      usuario_registro integer   , 
      data_atualizacao timestamp   , 
      usuario_atualizacao integer   , 
 PRIMARY KEY (id)); 

 CREATE TABLE uf( 
      id  SERIAL    NOT NULL  , 
      descricao text   , 
      sigla char  (2)   , 
      pais_id integer   NOT NULL  , 
 PRIMARY KEY (id)); 

  
  
 ALTER TABLE cidade ADD CONSTRAINT fk_cidade_uf FOREIGN KEY (uf_id) references uf(id); 
ALTER TABLE contato ADD CONSTRAINT fk_contato_pessoa FOREIGN KEY (pessoa_id) references pessoa(id); 
ALTER TABLE financeiro ADD CONSTRAINT fk_financeiro_pessoa FOREIGN KEY (pessoa_id) references pessoa(id); 
ALTER TABLE financeiro ADD CONSTRAINT fk_financeiro_movimento FOREIGN KEY (movimento_id) references movimento(id); 
ALTER TABLE financeiro ADD CONSTRAINT fk_financeiro_forma FOREIGN KEY (forma_id) references forma(id); 
ALTER TABLE kardex ADD CONSTRAINT fk_kardex_produtos_movimento FOREIGN KEY (produtos_movimento_id) references produtos_movimento(id); 
ALTER TABLE movimento ADD CONSTRAINT fk_movimento_situacao FOREIGN KEY (situacao_id) references situacao(id); 
ALTER TABLE movimento ADD CONSTRAINT fk_movimento_documento FOREIGN KEY (tipo_documento_id) references tipo_documento(id); 
ALTER TABLE movimento ADD CONSTRAINT fk_movimento_pessoa FOREIGN KEY (pessoa_id) references pessoa(id); 
ALTER TABLE movimento ADD CONSTRAINT fk_movimento_tipo FOREIGN KEY (tipo_movimento_id) references tipo_movimento(id); 
ALTER TABLE parcelas ADD CONSTRAINT fk_parcelas_financeiro FOREIGN KEY (financeiro_id) references financeiro(id); 
ALTER TABLE pessoa ADD CONSTRAINT fk_pessoa_cidade FOREIGN KEY (cidade_id) references cidade(id); 
ALTER TABLE pessoa_grupo ADD CONSTRAINT fk_pessoa_grupo_grupo FOREIGN KEY (grupo_id) references grupo(id); 
ALTER TABLE pessoa_grupo ADD CONSTRAINT fk_pessoa_grupo_pessoa FOREIGN KEY (pessoa_id) references pessoa(id); 
ALTER TABLE produto ADD CONSTRAINT fk_produto_pessoa FOREIGN KEY (pessoa_id) references pessoa(id); 
ALTER TABLE produto ADD CONSTRAINT fk_produto_tipo_produto FOREIGN KEY (tipo_produto_id) references tipo_produto(id); 
ALTER TABLE produto ADD CONSTRAINT fk_produto_medida FOREIGN KEY (medida_id) references medida(id); 
ALTER TABLE produtos_movimento ADD CONSTRAINT fk_produtos_movimento_produto FOREIGN KEY (produto_id) references produto(id); 
ALTER TABLE produtos_movimento ADD CONSTRAINT fk_produtos_movimento_movimento FOREIGN KEY (movimento_id) references movimento(id); 
ALTER TABLE saldos ADD CONSTRAINT fk_saldos_produto FOREIGN KEY (produto_id) references produto(id); 
ALTER TABLE uf ADD CONSTRAINT fk_uf_1 FOREIGN KEY (pais_id) references pais(id); 

  
