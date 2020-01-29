CREATE TABLE cidade( 
      id  INT IDENTITY    NOT NULL  , 
      descricao nvarchar(max)   , 
      uf_id int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE contato( 
      id  INT IDENTITY    NOT NULL  , 
      pessoa_id int   , 
      nome nvarchar(max)   , 
      nome_reduzido nvarchar(max)   , 
      cargo nvarchar(max)   , 
      email nvarchar(max)   , 
      tel_celular nvarchar(max)   , 
      tel_fixo nvarchar(max)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE financeiro( 
      id  INT IDENTITY    NOT NULL  , 
      movimento_id int   NOT NULL  , 
      pessoa_id int   NOT NULL  , 
      forma_id int   , 
      vlr_total float   , 
      qtd_parcelas int   , 
      usuario_registro int   , 
      data_registro datetime2   , 
      usuario_atualizacao int   , 
      data_atualizacao datetime2   , 
 PRIMARY KEY (id)); 

 CREATE TABLE forma( 
      id  INT IDENTITY    NOT NULL  , 
      descricao nvarchar(max)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE grupo( 
      id  INT IDENTITY    NOT NULL  , 
      descricao nvarchar(max)   , 
      data_registro datetime2   , 
      usuario_registro int   , 
      data_atualizacao datetime2   , 
      usuario_atualizacao int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE kardex( 
      id  INT IDENTITY    NOT NULL  , 
      data_movimento date   , 
      produtos_movimento_id int   , 
      movimento_pessoa_id int   , 
      movimento_tipo_estoque char  (1)   , 
      qtd float   , 
      vlr_unit float   , 
      custo_medio float   , 
      saldo float   , 
 PRIMARY KEY (id)); 

 CREATE TABLE medida( 
      id  INT IDENTITY    NOT NULL  , 
      descricao nvarchar(max)   , 
      sigla nvarchar(max)   , 
      data_registro datetime2   , 
      usuario_registro int   , 
      data_atualizacao datetime2   , 
      usuario_atualizacao int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE movimento( 
      id  INT IDENTITY    NOT NULL  , 
      tipo_estoque char  (1)   , 
      tipo_movimento_id int   , 
      pessoa_id int   , 
      tipo_documento_id int   , 
      situacao_id int   , 
      situacao_id_ant int   , 
      numero_documento nvarchar(max)   , 
      data_documento date   , 
      data_abertura date   , 
      data_entrega date   , 
      vlr_frete float     DEFAULT 0, 
      vlr_icms float     DEFAULT 0, 
      vlr_ipi float     DEFAULT 0, 
      vlr_total float   , 
      obs nvarchar(max)   , 
      data_registro datetime2   , 
      usuario_registro int   , 
      data_atualizacao datetime2   , 
      usuario_atualizacao int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE pais( 
      descricao nvarchar(max)   , 
      id  INT IDENTITY    NOT NULL  , 
      sigla char  (2)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE parcelas( 
      id  INT IDENTITY    NOT NULL  , 
      financeiro_id int   NOT NULL  , 
      parcela int   , 
      vlr_parcela float   , 
      data_vencimento date   , 
 PRIMARY KEY (id)); 

 CREATE TABLE pessoa( 
      id  INT IDENTITY    NOT NULL  , 
      tipo_pessoa char  (1)   , 
      ativo char  (1)   , 
      nome nvarchar(max)   , 
      nome_reduzido nvarchar(max)   , 
      sexo char  (1)   , 
      data_aniversario date   , 
      rg nvarchar(max)   , 
      orgao_emissor nvarchar(max)   , 
      cpf_cnpj nvarchar(max)   , 
      insc_municipal nvarchar(max)   , 
      insc_estadual nvarchar(max)   , 
      logradouro nvarchar(max)   , 
      numero nvarchar(max)   , 
      complemento nvarchar(max)   , 
      bairro nvarchar(max)   , 
      cidade_id int   , 
      uf char  (2)   , 
      cep nvarchar(max)   , 
      email nvarchar(max)   , 
      tel_celular nvarchar(max)   , 
      tel_fixo nvarchar(max)   , 
      obs nvarchar(max)   , 
      data_registro datetime2   , 
      usuario_registro int   , 
      data_atualizacao datetime2   , 
      usuario_atualizacao int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE pessoa_grupo( 
      id  INT IDENTITY    NOT NULL  , 
      pessoa_id int   , 
      grupo_id int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE produto( 
      id  INT IDENTITY    NOT NULL  , 
      tipo_produto_id int   , 
      pessoa_id int   , 
      ativo char  (1)   , 
      descricao nvarchar(max)   , 
      codigo_barras nvarchar(max)   , 
      estoque_minimo float   , 
      estoque_maximo float   , 
      medida_id int   , 
      obs nvarchar(max)   , 
      data_registro datetime2   , 
      usuario_registro int   , 
      data_atualizacao datetime2   , 
      usuario_atualizacao int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE produtos_movimento( 
      id  INT IDENTITY    NOT NULL  , 
      movimento_id int   , 
      produto_id int   , 
      lote nvarchar(max)   , 
      data_validade date   , 
      qtd float   , 
      vlr_unitario float   , 
      vlr_icms float   , 
      vlr_ipi float   , 
 PRIMARY KEY (id)); 

 CREATE TABLE saldos( 
      id  INT IDENTITY    NOT NULL  , 
      produto_id int   NOT NULL  , 
      qtd_estoque int   , 
      qtd_reservado int   , 
      qtd_aguardando int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE situacao( 
      id  INT IDENTITY    NOT NULL  , 
      descricao nvarchar(max)   , 
      sigla char  (3)   , 
      modulo char  (1)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE tipo_documento( 
      id  INT IDENTITY    NOT NULL  , 
      descricao nvarchar(max)   , 
      sigla nvarchar(max)   , 
      data_registro datetime2   , 
      usuario_registro int   , 
      data_atualizacao datetime2   , 
      usuario_atualizacao int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE tipo_movimento( 
      id  INT IDENTITY    NOT NULL  , 
      descricao nvarchar(max)   , 
      sigla nvarchar(max)   , 
      tipo_estoque char  (1)   , 
      data_registro datetime2   , 
      usuario_registro int   , 
      data_atualizacao datetime2   , 
      usuario_atualizacao int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE tipo_produto( 
      id  INT IDENTITY    NOT NULL  , 
      descricao nvarchar(max)   , 
      sigla nvarchar(max)   , 
      data_registro datetime2   , 
      usuario_registro int   , 
      data_atualizacao datetime2   , 
      usuario_atualizacao int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE uf( 
      id  INT IDENTITY    NOT NULL  , 
      descricao nvarchar(max)   , 
      sigla char  (2)   , 
      pais_id int   NOT NULL  , 
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

  
