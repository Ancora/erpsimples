CREATE TABLE cidade( 
      id number(10)    NOT NULL , 
      descricao CLOB   , 
      uf_id number(10)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE contato( 
      id number(10)    NOT NULL , 
      pessoa_id number(10)   , 
      nome CLOB   , 
      nome_reduzido CLOB   , 
      cargo CLOB   , 
      email CLOB   , 
      tel_celular CLOB   , 
      tel_fixo CLOB   , 
 PRIMARY KEY (id)); 

 CREATE TABLE financeiro( 
      id number(10)    NOT NULL , 
      movimento_id number(10)    NOT NULL , 
      pessoa_id number(10)    NOT NULL , 
      forma_id number(10)   , 
      vlr_total binary_double   , 
      qtd_parcelas number(10)   , 
      usuario_registro number(10)   , 
      data_registro timestamp(0)   , 
      usuario_atualizacao number(10)   , 
      data_atualizacao timestamp(0)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE forma( 
      id number(10)    NOT NULL , 
      descricao CLOB   , 
 PRIMARY KEY (id)); 

 CREATE TABLE grupo( 
      id number(10)    NOT NULL , 
      descricao CLOB   , 
      data_registro timestamp(0)   , 
      usuario_registro number(10)   , 
      data_atualizacao timestamp(0)   , 
      usuario_atualizacao number(10)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE kardex( 
      id number(10)    NOT NULL , 
      data_movimento date   , 
      produtos_movimento_id number(10)   , 
      movimento_pessoa_id number(10)   , 
      movimento_tipo_estoque char  (1)   , 
      qtd binary_double   , 
      vlr_unit binary_double   , 
      custo_medio binary_double   , 
      saldo binary_double   , 
 PRIMARY KEY (id)); 

 CREATE TABLE medida( 
      id number(10)    NOT NULL , 
      descricao CLOB   , 
      sigla CLOB   , 
      data_registro timestamp(0)   , 
      usuario_registro number(10)   , 
      data_atualizacao timestamp(0)   , 
      usuario_atualizacao number(10)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE movimento( 
      id number(10)    NOT NULL , 
      tipo_estoque char  (1)   , 
      tipo_movimento_id number(10)   , 
      pessoa_id number(10)   , 
      tipo_documento_id number(10)   , 
      situacao_id number(10)   , 
      situacao_id_ant number(10)   , 
      numero_documento CLOB   , 
      data_documento date   , 
      data_abertura date   , 
      data_entrega date   , 
      vlr_frete binary_double    DEFAULT 0 , 
      vlr_icms binary_double    DEFAULT 0 , 
      vlr_ipi binary_double    DEFAULT 0 , 
      vlr_total binary_double   , 
      obs CLOB   , 
      data_registro timestamp(0)   , 
      usuario_registro number(10)   , 
      data_atualizacao timestamp(0)   , 
      usuario_atualizacao number(10)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE pais( 
      descricao CLOB   , 
      id number(10)    NOT NULL , 
      sigla char  (2)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE parcelas( 
      id number(10)    NOT NULL , 
      financeiro_id number(10)    NOT NULL , 
      parcela number(10)   , 
      vlr_parcela binary_double   , 
      data_vencimento date   , 
 PRIMARY KEY (id)); 

 CREATE TABLE pessoa( 
      id number(10)    NOT NULL , 
      tipo_pessoa char  (1)   , 
      ativo char  (1)   , 
      nome CLOB   , 
      nome_reduzido CLOB   , 
      sexo char  (1)   , 
      data_aniversario date   , 
      rg CLOB   , 
      orgao_emissor CLOB   , 
      cpf_cnpj CLOB   , 
      insc_municipal CLOB   , 
      insc_estadual CLOB   , 
      logradouro CLOB   , 
      numero CLOB   , 
      complemento CLOB   , 
      bairro CLOB   , 
      cidade_id number(10)   , 
      uf char  (2)   , 
      cep CLOB   , 
      email CLOB   , 
      tel_celular CLOB   , 
      tel_fixo CLOB   , 
      obs CLOB   , 
      data_registro timestamp(0)   , 
      usuario_registro number(10)   , 
      data_atualizacao timestamp(0)   , 
      usuario_atualizacao number(10)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE pessoa_grupo( 
      id number(10)    NOT NULL , 
      pessoa_id number(10)   , 
      grupo_id number(10)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE produto( 
      id number(10)    NOT NULL , 
      tipo_produto_id number(10)   , 
      pessoa_id number(10)   , 
      ativo char  (1)   , 
      descricao CLOB   , 
      codigo_barras CLOB   , 
      estoque_minimo binary_double   , 
      estoque_maximo binary_double   , 
      medida_id number(10)   , 
      obs CLOB   , 
      data_registro timestamp(0)   , 
      usuario_registro number(10)   , 
      data_atualizacao timestamp(0)   , 
      usuario_atualizacao number(10)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE produtos_movimento( 
      id number(10)    NOT NULL , 
      movimento_id number(10)   , 
      produto_id number(10)   , 
      lote CLOB   , 
      data_validade date   , 
      qtd binary_double   , 
      vlr_unitario binary_double   , 
      vlr_icms binary_double   , 
      vlr_ipi binary_double   , 
 PRIMARY KEY (id)); 

 CREATE TABLE saldos( 
      id number(10)    NOT NULL , 
      produto_id number(10)    NOT NULL , 
      qtd_estoque number(10)   , 
      qtd_reservado number(10)   , 
      qtd_aguardando number(10)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE situacao( 
      id number(10)    NOT NULL , 
      descricao CLOB   , 
      sigla char  (3)   , 
      modulo char  (1)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE tipo_documento( 
      id number(10)    NOT NULL , 
      descricao CLOB   , 
      sigla CLOB   , 
      data_registro timestamp(0)   , 
      usuario_registro number(10)   , 
      data_atualizacao timestamp(0)   , 
      usuario_atualizacao number(10)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE tipo_movimento( 
      id number(10)    NOT NULL , 
      descricao CLOB   , 
      sigla CLOB   , 
      tipo_estoque char  (1)   , 
      data_registro timestamp(0)   , 
      usuario_registro number(10)   , 
      data_atualizacao timestamp(0)   , 
      usuario_atualizacao number(10)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE tipo_produto( 
      id number(10)    NOT NULL , 
      descricao CLOB   , 
      sigla CLOB   , 
      data_registro timestamp(0)   , 
      usuario_registro number(10)   , 
      data_atualizacao timestamp(0)   , 
      usuario_atualizacao number(10)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE uf( 
      id number(10)    NOT NULL , 
      descricao CLOB   , 
      sigla char  (2)   , 
      pais_id number(10)    NOT NULL , 
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
 CREATE SEQUENCE cidade_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cidade_id_seq_tr 

BEFORE INSERT ON cidade FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT cidade_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE contato_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER contato_id_seq_tr 

BEFORE INSERT ON contato FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT contato_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE financeiro_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER financeiro_id_seq_tr 

BEFORE INSERT ON financeiro FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT financeiro_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE forma_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER forma_id_seq_tr 

BEFORE INSERT ON forma FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT forma_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE grupo_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER grupo_id_seq_tr 

BEFORE INSERT ON grupo FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT grupo_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE kardex_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER kardex_id_seq_tr 

BEFORE INSERT ON kardex FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT kardex_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE medida_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER medida_id_seq_tr 

BEFORE INSERT ON medida FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT medida_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE movimento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER movimento_id_seq_tr 

BEFORE INSERT ON movimento FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT movimento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE pais_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER pais_id_seq_tr 

BEFORE INSERT ON pais FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT pais_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE parcelas_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER parcelas_id_seq_tr 

BEFORE INSERT ON parcelas FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT parcelas_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE pessoa_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER pessoa_id_seq_tr 

BEFORE INSERT ON pessoa FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT pessoa_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE pessoa_grupo_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER pessoa_grupo_id_seq_tr 

BEFORE INSERT ON pessoa_grupo FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT pessoa_grupo_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE produto_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER produto_id_seq_tr 

BEFORE INSERT ON produto FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT produto_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE produtos_movimento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER produtos_movimento_id_seq_tr 

BEFORE INSERT ON produtos_movimento FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT produtos_movimento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE saldos_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER saldos_id_seq_tr 

BEFORE INSERT ON saldos FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT saldos_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE situacao_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER situacao_id_seq_tr 

BEFORE INSERT ON situacao FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT situacao_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tipo_documento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tipo_documento_id_seq_tr 

BEFORE INSERT ON tipo_documento FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT tipo_documento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tipo_movimento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tipo_movimento_id_seq_tr 

BEFORE INSERT ON tipo_movimento FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT tipo_movimento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tipo_produto_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tipo_produto_id_seq_tr 

BEFORE INSERT ON tipo_produto FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT tipo_produto_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE uf_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER uf_id_seq_tr 

BEFORE INSERT ON uf FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT uf_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
 
  
