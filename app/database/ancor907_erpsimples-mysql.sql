CREATE TABLE cidade( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      descricao text   , 
      uf_id int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE contato( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      pessoa_id int   , 
      nome text   , 
      nome_reduzido text   , 
      cargo text   , 
      email text   , 
      tel_celular text   , 
      tel_fixo text   , 
 PRIMARY KEY (id)); 

 CREATE TABLE financeiro( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      movimento_id int   NOT NULL  , 
      pessoa_id int   NOT NULL  , 
      forma_id int   , 
      vlr_total double   , 
      qtd_parcelas int   , 
      usuario_registro int   , 
      data_registro datetime   , 
      usuario_atualizacao int   , 
      data_atualizacao datetime   , 
 PRIMARY KEY (id)); 

 CREATE TABLE forma( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      descricao text   , 
 PRIMARY KEY (id)); 

 CREATE TABLE grupo( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      descricao text   , 
      data_registro datetime   , 
      usuario_registro int   , 
      data_atualizacao datetime   , 
      usuario_atualizacao int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE kardex( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      data_movimento date   , 
      produtos_movimento_id int   , 
      movimento_pessoa_id int   , 
      movimento_tipo_estoque char  (1)   , 
      qtd double   , 
      vlr_unit double   , 
      custo_medio double   , 
      saldo double   , 
 PRIMARY KEY (id)); 

 CREATE TABLE medida( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      descricao text   , 
      sigla text   , 
      data_registro datetime   , 
      usuario_registro int   , 
      data_atualizacao datetime   , 
      usuario_atualizacao int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE movimento( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      tipo_estoque char  (1)   , 
      tipo_movimento_id int   , 
      pessoa_id int   , 
      tipo_documento_id int   , 
      situacao_id int   , 
      situacao_id_ant int   , 
      numero_documento text   , 
      data_documento date   , 
      data_abertura date   , 
      data_entrega date   , 
      vlr_frete double     DEFAULT 0, 
      vlr_icms double     DEFAULT 0, 
      vlr_ipi double     DEFAULT 0, 
      vlr_total double   , 
      obs text   , 
      data_registro datetime   , 
      usuario_registro int   , 
      data_atualizacao datetime   , 
      usuario_atualizacao int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE pais( 
      descricao text   , 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      sigla char  (2)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE parcelas( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      financeiro_id int   NOT NULL  , 
      parcela int   , 
      vlr_parcela double   , 
      data_vencimento date   , 
 PRIMARY KEY (id)); 

 CREATE TABLE pessoa( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
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
      cidade_id int   , 
      uf char  (2)   , 
      cep text   , 
      email text   , 
      tel_celular text   , 
      tel_fixo text   , 
      obs text   , 
      data_registro datetime   , 
      usuario_registro int   , 
      data_atualizacao datetime   , 
      usuario_atualizacao int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE pessoa_grupo( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      pessoa_id int   , 
      grupo_id int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE produto( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      tipo_produto_id int   , 
      pessoa_id int   , 
      ativo char  (1)   , 
      descricao text   , 
      codigo_barras text   , 
      estoque_minimo double   , 
      estoque_maximo double   , 
      medida_id int   , 
      obs text   , 
      data_registro datetime   , 
      usuario_registro int   , 
      data_atualizacao datetime   , 
      usuario_atualizacao int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE produtos_movimento( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      movimento_id int   , 
      produto_id int   , 
      lote text   , 
      data_validade date   , 
      qtd double   , 
      vlr_unitario double   , 
      vlr_icms double   , 
      vlr_ipi double   , 
 PRIMARY KEY (id)); 

 CREATE TABLE saldos( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      produto_id int   NOT NULL  , 
      qtd_estoque int   , 
      qtd_reservado int   , 
      qtd_aguardando int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE situacao( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      descricao text   , 
      sigla char  (3)   , 
      modulo char  (1)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE tipo_documento( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      descricao text   , 
      sigla text   , 
      data_registro datetime   , 
      usuario_registro int   , 
      data_atualizacao datetime   , 
      usuario_atualizacao int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE tipo_movimento( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      descricao text   , 
      sigla text   , 
      tipo_estoque char  (1)   , 
      data_registro datetime   , 
      usuario_registro int   , 
      data_atualizacao datetime   , 
      usuario_atualizacao int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE tipo_produto( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      descricao text   , 
      sigla text   , 
      data_registro datetime   , 
      usuario_registro int   , 
      data_atualizacao datetime   , 
      usuario_atualizacao int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE uf( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      descricao text   , 
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

  
