SET FOREIGN_KEY_CHECKS=0;

-- Configurações gerais do sistema
DROP TABLE IF EXISTS cv;
CREATE TABLE cv (
  id BIGINT NOT NULL AUTO_INCREMENT,
  version INTEGER DEFAULT NULL,
  inserted DATETIME NOT NULL,
  updated DATETIME NOT NULL,
    
  cargos_pretendidos varchar(300) NOT NULL,
    
  nome varchar(100) NOT NULL,
  cpf char(11) NOT NULL,  
  dt_nascimento date NOT NULL,
  naturalidade_id BIGINT NOT NULL,
  
  endereco_atual_logr varchar(300) NOT NULL,
  endereco_atual_numero varchar(6) NOT NULL,
  endereco_atual_compl varchar(50),
  endereco_atual_bairro varchar(300) NOT NULL,
  endereco_atual_cidade varchar(300) NOT NULL,
  endereco_atual_uf char(2) NOT NULL,
  endereco_atual_tempo_resid varchar(50) NOT NULL,
  
  telefone1 varchar(20) NOT NULL,
  telefone1_tipo VARCHAR(50) NOT NULL,  
  telefone2 varchar(20),
  telefone2_tipo VARCHAR(50),
  telefone3 varchar(20),
  telefone3_tipo VARCHAR(50),
  telefone4 varchar(20),
  telefone4_tipo VARCHAR(50),
  telefone5 varchar(20),
  telefone5_tipo VARCHAR(50),
  
  email VARCHAR(50),
  
  estado_civil CHAR(2) NOT NULL,
  conjuge_nome VARCHAR(100),
  conjuge_estado_trabalho CHAR(2),
  conjuge_profissao VARCHAR(100),
  
  filhos CHAR(1) NOT NULL,
  
  pai_nome VARCHAR(100),
  pai_profissao VARCHAR(100),
  pai_estado_trabalho CHAR(2),
  
  mae_nome VARCHAR(100),
  mae_profissao VARCHAR(100),
  mae_estado_trabalho CHAR(2),
  
  referencia1_nome VARCHAR(100),
  referencia1_relacao VARCHAR(100),
  referencia1_telefone1 VARCHAR(50),
  referencia1_telefone2 VARCHAR(50),
  
  referencia2_nome VARCHAR(100),
  referencia2_relacao VARCHAR(100),
  referencia2_telefone1 VARCHAR(50),
  referencia2_telefone2 VARCHAR(50),
  
  ensino_fundamental_status CHAR(1) NOT NULL,
  ensino_fundamental_local VARCHAR(50),
  
  ensino_medio_status CHAR(1) NOT NULL,
  ensino_medio_local VARCHAR(50),
  
  ensino_superior_status CHAR(1) NOT NULL,
  ensino_superior_local VARCHAR(50),
  
  ensino_demais_obs VARCHAR(3000),
    
  conhece_a_empresa_tempo INTEGER,
  eh_nosso_cliente CHAR(1) NOT NULL,
  parente1_ficha_crediario_nome VARCHAR(100),
  parente2_ficha_crediario_nome VARCHAR(100),
  
  conhecido1_trabalhou_na_empresa VARCHAR(100),
  conhecido2_trabalhou_na_empresa VARCHAR(100),
  
  motivos_quer_trabalhar_aqui VARCHAR(3000),
    
  senha varchar(200) DEFAULT NULL,
    
  PRIMARY KEY (id),  
  UNIQUE KEY UK_cv_cpf (cpf)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;



DROP TABLE IF EXISTS cv_filho;
CREATE TABLE cv_filho (
  id BIGINT NOT NULL AUTO_INCREMENT,
  version INTEGER DEFAULT NULL,
  inserted DATETIME NOT NULL,
  updated DATETIME NOT NULL,
  
  cv_id BIGINT,
  nome VARCHAR(100),
  dt_nascimento DATETIME,
  ocupacao VARCHAR(100),
  obs VARCHAR(3000),  
  
  PRIMARY KEY (id),  
  UNIQUE KEY K_cv_filho_01 (cv_id, nome)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;




DROP TABLE IF EXISTS cv_exper_profis;
CREATE TABLE cv_exper_profis (
  id BIGINT NOT NULL AUTO_INCREMENT,
  version INTEGER DEFAULT NULL,
  inserted DATETIME NOT NULL,
  updated DATETIME NOT NULL,
  
  cv_id BIGINT,
  
  nome_empresa VARCHAR(100),
  local_empresa VARCHAR(100),
  nome_superior VARCHAR(100),
  cargo VARCHAR(100),
  horario VARCHAR(100),
  admissao CHAR(6),
  demissao CHAR(6),
  ultimo_salario DECIMAL(10,2),
  beneficios VARCHAR(100),
  motivo_desligamento VARCHAR(3000),
  
  PRIMARY KEY (id),  
  UNIQUE KEY K_cv_filho_01 (cv_id, nome_empresa, admissao)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;



DROP TABLE IF EXISTS cargos;
CREATE TABLE cargos (
  id BIGINT NOT NULL AUTO_INCREMENT,
  version INTEGER DEFAULT NULL,
  inserted DATETIME NOT NULL,
  updated DATETIME NOT NULL,

  cargo VARCHAR(100),

  PRIMARY KEY (id),
  UNIQUE KEY K_cargos (cargo)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;


DROP TABLE IF EXISTS cv_cargos;
CREATE TABLE cv_cargos (
  cv_id BIGINT NOT NULL,
  cargo_id BIGINT NOT NULL,
  PRIMARY KEY (cv_id, cargo_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;