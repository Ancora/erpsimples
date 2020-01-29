PRAGMA foreign_keys=OFF; 

CREATE TABLE cidade( 
      id  INTEGER    NOT NULL  , 
      descricao text   , 
      uf_id int   , 
 PRIMARY KEY (id),
FOREIGN KEY(uf_id) REFERENCES uf(id)); 

 CREATE TABLE contato( 
      id  INTEGER    NOT NULL  , 
      pessoa_id int   , 
      nome text   , 
      nome_reduzido text   , 
      cargo text   , 
      email text   , 
      tel_celular text   , 
      tel_fixo text   , 
 PRIMARY KEY (id),
FOREIGN KEY(pessoa_id) REFERENCES pessoa(id)); 

 CREATE TABLE financeiro( 
      id  INTEGER    NOT NULL  , 
      movimento_id int   NOT NULL  , 
      pessoa_id int   NOT NULL  , 
      forma_id int   , 
      vlr_total double   , 
      qtd_parcelas int   , 
      usuario_registro int   , 
      data_registro datetime   , 
      usuario_atualizacao int   , 
      data_atualizacao datetime   , 
 PRIMARY KEY (id),
FOREIGN KEY(pessoa_id) REFERENCES pessoa(id),
FOREIGN KEY(movimento_id) REFERENCES movimento(id),
FOREIGN KEY(forma_id) REFERENCES forma(id)); 

 CREATE TABLE forma( 
      id  INTEGER    NOT NULL  , 
      descricao text   , 
 PRIMARY KEY (id)); 

 CREATE TABLE grupo( 
      id  INTEGER    NOT NULL  , 
      descricao text   , 
      data_registro datetime   , 
      usuario_registro int   , 
      data_atualizacao datetime   , 
      usuario_atualizacao int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE kardex( 
      id  INTEGER    NOT NULL  , 
      data_movimento date   , 
      produtos_movimento_id int   , 
      movimento_pessoa_id int   , 
      movimento_tipo_estoque char  (1)   , 
      qtd double   , 
      vlr_unit double   , 
      custo_medio double   , 
      saldo double   , 
 PRIMARY KEY (id),
FOREIGN KEY(produtos_movimento_id) REFERENCES produtos_movimento(id)); 

 CREATE TABLE medida( 
      id  INTEGER    NOT NULL  , 
      descricao text   , 
      sigla text   , 
      data_registro datetime   , 
      usuario_registro int   , 
      data_atualizacao datetime   , 
      usuario_atualizacao int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE movimento( 
      id  INTEGER    NOT NULL  , 
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
 PRIMARY KEY (id),
FOREIGN KEY(situacao_id) REFERENCES situacao(id),
FOREIGN KEY(tipo_documento_id) REFERENCES tipo_documento(id),
FOREIGN KEY(pessoa_id) REFERENCES pessoa(id),
FOREIGN KEY(tipo_movimento_id) REFERENCES tipo_movimento(id)); 

 CREATE TABLE pais( 
      descricao text   , 
      id  INTEGER    NOT NULL  , 
      sigla char  (2)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE parcelas( 
      id  INTEGER    NOT NULL  , 
      financeiro_id int   NOT NULL  , 
      parcela int   , 
      vlr_parcela double   , 
      data_vencimento date   , 
 PRIMARY KEY (id),
FOREIGN KEY(financeiro_id) REFERENCES financeiro(id)); 

 CREATE TABLE pessoa( 
      id  INTEGER    NOT NULL  , 
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
 PRIMARY KEY (id),
FOREIGN KEY(cidade_id) REFERENCES cidade(id)); 

 CREATE TABLE pessoa_grupo( 
      id  INTEGER    NOT NULL  , 
      pessoa_id int   , 
      grupo_id int   , 
 PRIMARY KEY (id),
FOREIGN KEY(grupo_id) REFERENCES grupo(id),
FOREIGN KEY(pessoa_id) REFERENCES pessoa(id)); 

 CREATE TABLE produto( 
      id  INTEGER    NOT NULL  , 
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
 PRIMARY KEY (id),
FOREIGN KEY(pessoa_id) REFERENCES pessoa(id),
FOREIGN KEY(tipo_produto_id) REFERENCES tipo_produto(id),
FOREIGN KEY(medida_id) REFERENCES medida(id)); 

 CREATE TABLE produtos_movimento( 
      id  INTEGER    NOT NULL  , 
      movimento_id int   , 
      produto_id int   , 
      lote text   , 
      data_validade date   , 
      qtd double   , 
      vlr_unitario double   , 
      vlr_icms double   , 
      vlr_ipi double   , 
 PRIMARY KEY (id),
FOREIGN KEY(produto_id) REFERENCES produto(id),
FOREIGN KEY(movimento_id) REFERENCES movimento(id)); 

 CREATE TABLE saldos( 
      id  INTEGER    NOT NULL  , 
      produto_id int   NOT NULL  , 
      qtd_estoque int   , 
      qtd_reservado int   , 
      qtd_aguardando int   , 
 PRIMARY KEY (id),
FOREIGN KEY(produto_id) REFERENCES produto(id)); 

 CREATE TABLE situacao( 
      id  INTEGER    NOT NULL  , 
      descricao text   , 
      sigla char  (3)   , 
      modulo char  (1)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE tipo_documento( 
      id  INTEGER    NOT NULL  , 
      descricao text   , 
      sigla text   , 
      data_registro datetime   , 
      usuario_registro int   , 
      data_atualizacao datetime   , 
      usuario_atualizacao int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE tipo_movimento( 
      id  INTEGER    NOT NULL  , 
      descricao text   , 
      sigla text   , 
      tipo_estoque char  (1)   , 
      data_registro datetime   , 
      usuario_registro int   , 
      data_atualizacao datetime   , 
      usuario_atualizacao int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE tipo_produto( 
      id  INTEGER    NOT NULL  , 
      descricao text   , 
      sigla text   , 
      data_registro datetime   , 
      usuario_registro int   , 
      data_atualizacao datetime   , 
      usuario_atualizacao int   , 
 PRIMARY KEY (id)); 

 CREATE TABLE uf( 
      id  INTEGER    NOT NULL  , 
      descricao text   , 
      sigla char  (2)   , 
      pais_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(pais_id) REFERENCES pais(id)); 

  
 
  
