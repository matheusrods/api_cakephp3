USE [RHHealth_samaritano]
GO
​
SET ANSI_NULLS ON
GO
​
SET QUOTED_IDENTIFIER ON
GO
​
-- USUARIO
-- Cadastro de Usuários
​
CREATE TABLE [dbo].[usuario](
	[codigo] [int] IDENTITY(1,1) NOT NULL,
	[nome] [varchar](256) NOT NULL,
	[apelido] [varchar](256) NOT NULL,
	[senha] [varchar](172) NOT NULL,
	[email] [varchar](256) NULL,
	[numero_conselho] [varchar](25) NULL,
	[ativo] [bit] NULL,
	[data_inclusao] [datetime] NOT NULL,
	[codigo_usuario_inclusao] [int] NOT NULL,
	[codigo_uperfil] [int] NULL,
	[celular] [varchar](12) NULL,
	[data_senha_expiracao] [datetime] NULL,
	[data_alteracao] [datetime] NULL,
	[codigo_usuario_alteracao] [int] NULL,
 CONSTRAINT [pk_usuario__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY]
GO
​
ALTER TABLE [dbo].[usuario] ADD  CONSTRAINT [df_usuario_ativo]  DEFAULT ((1)) FOR [ativo]
GO
​
ALTER TABLE [dbo].[usuario] ADD  CONSTRAINT [df_usuario_data_inclusao]  DEFAULT (getdate()) FOR [data_inclusao]
GO
​
-- Cadastro de perfis
-- ex. Administrador, Médico, Enfermeiro
​
CREATE TABLE [dbo].[uperfis](
	[codigo] [int] IDENTITY(1,1) NOT NULL,
	[descricao] [varchar](128) NOT NULL,
	[data_inclusao] [datetime] NOT NULL,
	[codigo_usuario_inclusao] [int] NOT NULL,
	[codigo_cliente] [int] NULL,
	[perfil_cliente] [bit] NULL,
	[codigo_tipo_perfil] [int] NULL,
	[codigo_pai] [int] NULL,
	[codigo_empresa] [int] NULL,
 CONSTRAINT [pk_uperfis__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY]
GO
​
ALTER TABLE [dbo].[uperfis]  WITH CHECK ADD  CONSTRAINT [fk_uperfis__codigo_pai] FOREIGN KEY([codigo_pai])
REFERENCES [dbo].[uperfis] ([codigo])
GO
​
ALTER TABLE [dbo].[uperfis] CHECK CONSTRAINT [fk_uperfis__codigo_pai]
GO
​
ALTER TABLE [dbo].[usuario]  WITH CHECK ADD  CONSTRAINT [fk_usuario_uperfis] FOREIGN KEY([codigo_uperfil])
REFERENCES [dbo].[uperfis] ([codigo])
GO
​
ALTER TABLE [dbo].[usuario] CHECK CONSTRAINT [fk_usuario_uperfis]
GO
​
​
-- SISTEMA
-- cadastro parametros para aplicação
CREATE TABLE [dbo].[sistema](
	[codigo] [smallint] IDENTITY(1,1) NOT NULL,
    [valor] [varchar](128) NOT NULL,
	[descricao] [varchar](256) NOT NULL,
	[data_inclusao] [datetime] NULL,
	[codigo_usuario_inclusao] [int] NOT NULL,
 CONSTRAINT [pk_sistema__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
​
ALTER TABLE [dbo].[sistema] ADD  CONSTRAINT [df_sistema_data_inclusao]  DEFAULT (getdate()) FOR [data_inclusao]
GO
​
ALTER TABLE [dbo].[sistema]  WITH CHECK ADD  CONSTRAINT [fk_sistema__usuario] FOREIGN KEY([codigo_usuario_inclusao])
REFERENCES [dbo].[usuario] ([codigo])
GO
​
ALTER TABLE [dbo].[sistema] CHECK CONSTRAINT [fk_sistema__usuario]
GO
​
-- PROFISSIONAL
-- usar da base de dados IThealth
​
-- PROFISSIONAL_TIPO
-- profissional tipo
​
CREATE TABLE [dbo].[profissional_tipo](
	[codigo] [smallint] IDENTITY(1,1) NOT NULL,
	[descricao] [varchar](128) NOT NULL,
	[data_inclusao] [datetime] NOT NULL,
	[codigo_usuario_inclusao] [int] NOT NULL,
	[codigo_empresa] [int] NULL,
 CONSTRAINT [pk_profissional_tipo__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
​
ALTER TABLE [dbo].[profissional_tipo] ADD  CONSTRAINT [df_tipo_profissional__data_inclusao]  DEFAULT (getdate()) FOR [data_inclusao]
GO
​
-- CONVENIO_TIPO
-- Tipos de convenios
-- Amil, Medial, Dix Amico, Santa Helena, Seisa, Next, Unimed, Metrus, Allianz
​
CREATE TABLE [dbo].[convenio_tipo](
	[codigo] [smallint] IDENTITY(1,1) NOT NULL,
	[descricao] [varchar](128) NOT NULL,
	[data_inclusao] [datetime] NOT NULL,
	[codigo_usuario_inclusao] [int] NOT NULL,
 CONSTRAINT [pk_convenio_tipo__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
​
ALTER TABLE [dbo].[convenio_tipo] ADD  CONSTRAINT [df_tipo_convenio__data_inclusao]  DEFAULT (getdate()) FOR [data_inclusao]
GO
​
-- CONSELHO_TIPO
-- Tipos de conselhos para Conselho Regional de Medicina
​
CREATE TABLE [dbo].[conselho_tipo](
	[codigo] [smallint] IDENTITY(1,1) NOT NULL,
	[descricao] [varchar](128) NOT NULL,
	[data_inclusao] [datetime] NOT NULL,
	[codigo_usuario_inclusao] [int] NOT NULL,
 CONSTRAINT [pk_conselho_tipo__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
​
ALTER TABLE [dbo].[conselho_tipo] ADD  CONSTRAINT [df_tipo_conselho__data_inclusao]  DEFAULT (getdate()) FOR [data_inclusao]
GO
​
​
-- PACIENTE
-- Cadastro de Pacientes
​
CREATE TABLE [dbo].[paciente](
	[codigo] [int] IDENTITY(1,1) NOT NULL,
	[nome] [varchar](256) NOT NULL,
	[cpf] [varchar](25) NULL,
	[data_nascimento] [datetime] NULL,
	[sexo] [varchar](2) NOT NULL,
	[idade] [varchar](3) NOT NULL,
    [data_admissao_hospital] [datetime] NULL,	
​
	[codigo_convenio] [int] NOT NULL,
	[codigo_mo] [int] NOT NULL,
	[codigo_registro] [int] NOT NULL,
	[codigo_prontuario] [int] NOT NULL,
​
	[telefone_celular] [varchar](12) NULL,
	[telefone_residencial] [varchar](12) NULL,
	[telefone_outro] [varchar](12) NULL,
	[ativo] [bit] NULL,
	[data_inclusao] [datetime] NOT NULL,
	[codigo_usuario_inclusao] [int] NOT NULL,
	[data_senha_expiracao] [datetime] NULL,
	[data_alteracao] [datetime] NULL,
	[codigo_usuario_alteracao] [int] NULL,
 CONSTRAINT [pk_paciente__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY]
GO
​
ALTER TABLE [dbo].[paciente] ADD  CONSTRAINT [df_paciente_ativo]  DEFAULT ((1)) FOR [ativo]
GO
​
ALTER TABLE [dbo].[paciente] ADD  CONSTRAINT [df_paciente_data_inclusao]  DEFAULT (getdate()) FOR [data_inclusao]
GO

-- log usuario
CREATE TABLE [dbo].[usuario_log](
	[codigo] [int] IDENTITY(1,1) NOT NULL,
	[codigo_usuario] [int] NOT NULL,
	[nome] [varchar](256) NOT NULL,
	[apelido] [varchar](256) NOT NULL,
	[senha] [varchar](172) NULL,
	[email] [varchar](1000) NULL,
	[ativo] [bit] NULL,
	[data_inclusao] [datetime] NOT NULL,
	[codigo_usuario_inclusao] [int] NOT NULL,
	[codigo_uperfil] [int] NULL,
	[celular] [varchar](12) NULL,
	[data_senha_expiracao] [datetime] NULL,
	[data_alteracao] [datetime] NULL,
	[codigo_usuario_alteracao] [int] NULL,
	[acao] [int] NULL,
 CONSTRAINT [pk_usuario_log__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY];
GO

CREATE TABLE [dbo].[paciente_log](
	[codigo] [int] IDENTITY(1,1) NOT NULL,
	[codigo_paciente] [int] NOT NULL,
	[nome] [varchar](256) NOT NULL,
	[cpf] [varchar](25) NULL,
	[data_nascimento] [datetime] NULL,
	[sexo] [varchar](2) NOT NULL,
	[idade] [varchar](3) NOT NULL,
    [data_admissao_hospital] [datetime] NULL,​
	[codigo_convenio] [int] NOT NULL,
	[codigo_mo] [int] NOT NULL,
	[codigo_registro] [int] NOT NULL,
	[codigo_prontuario] [int] NOT NULL,
​	[telefone_celular] [varchar](12) NULL,
	[telefone_residencial] [varchar](12) NULL,
	[telefone_outro] [varchar](12) NULL,
	[ativo] [bit] NULL,
	[data_inclusao] [datetime] NOT NULL,
	[codigo_usuario_inclusao] [int] NOT NULL,
	[data_senha_expiracao] [datetime] NULL,
	[data_alteracao] [datetime] NULL,
	[codigo_usuario_alteracao] [int] NULL,
	[acao] [int] NULL,
 CONSTRAINT [pk_paciente_log__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY]
GO


-- status atendimento
CREATE TABLE [dbo].[status_atendimento](
	[codigo] [int] IDENTITY(1,1) NOT NUll,
	[descricao] [varchar](256) NOT NULL,
	[ativo] [int] NULL,	
 CONSTRAINT [pk_status_atendimento__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY];
GO

INSERT INTO RHHealth_samaritano.dbo.status_atendimento (descricao, ativo) VALUES ('Aberta',1),('Reinternação',1),('Concluída',1);
GO

-- atendimento

CREATE TABLE [dbo].[atendimento](
	[codigo] [int] IDENTITY(1,1) NOT NUll,
	[codigo_paciente] [int] NOT NULL,
	[codigo_status_atendimento] [int] NOT NULL,
	[data_atendimento] [date] NULL,
	[etapa] [int] NULL,
	[ativo] [int] NULL,
	[data_inclusao] [datetime] NOT NULL,
	[codigo_usuario_inclusao] [int] NOT NULL,
	[data_alteracao] [datetime] NULL,
	[codigo_usuario_alteracao] [int] NULL,
 CONSTRAINT [pk_atendimento__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY];
GO
ALTER TABLE [dbo].[atendimento]  WITH CHECK ADD  CONSTRAINT [fk_atendimento__paciente] FOREIGN KEY([codigo_paciente])
	REFERENCES [dbo].[paciente] ([codigo]);
GO
ALTER TABLE [dbo].[atendimento]  WITH CHECK ADD  CONSTRAINT [fk_atendimento__status_atendimento] FOREIGN KEY([codigo_status_atendimento])
	REFERENCES [dbo].[status_atendimento] ([codigo]);
GO
ALTER TABLE [dbo].[atendimento]  WITH CHECK ADD  CONSTRAINT [fk_atendimento__usuario] FOREIGN KEY([codigo_usuario_inclusao])
	REFERENCES [dbo].[usuario] ([codigo]);
GO

#########################################################2. Antecedentes Pessoais e da Doença###########################################
-- doenca_coronariana
CREATE TABLE [dbo].[tipo_doenca_coronariana](
	[codigo] [int] IDENTITY(1,1) NOT NUll,
	[descricao] [varchar](256) NOT NULL,
	[ativo] [int] DEFAULT 1 NULL,	
 CONSTRAINT [pk_tipo_doenca_coronariana__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY];
GO

INSERT INTO RHHealth_samaritano.dbo.tipo_doenca_coronariana (descricao) values ('Percutâneo'),('Clínico'),('Trombólise'),('Cirurgia de RM'),('Sem informação');
GO

-- arritmia
CREATE TABLE [dbo].[tipo_arritmia](
	[codigo] [int] IDENTITY(1,1) NOT NUll,
	[descricao] [varchar](256) NOT NULL,
	[ativo] [int] DEFAULT 1 NULL,	
 CONSTRAINT [pk_tipo_arritmia__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY];
GO

INSERT INTO RHHealth_samaritano.dbo.tipo_arritmia (descricao) values ('Fibrilação Atrial'),('Flutter Atrial'),('Outros');
GO

-- antecedentes_pessoais_doencas
CREATE TABLE [dbo].[antecedentes_pessoais_doencas](
	[codigo] [int] IDENTITY(1,1) NOT NUll,
	[codigo_atendimento] [int] NOT NULL,
	[codigo_paciente] [int] NOT NULL,
	[codigo_tipo_doenca_coronariana] [int] NULL,
	[codigo_tipo_arritimia] [int] NULL,
	[doenca_coronariana] [int] NULL,
	[doenca_coronariana_ano] [int],
	[acidente_vascular_cerebral] [int],
	[acidente_vascular_cerebral_sequelas] [int],
	[insuficiencia_renal_cronica] [int],
	[insuficiencia_renal_cronica_dialise] [int],
	[arritimia] [int] NULL,
	[arritimia_outros] [varchar](256) NULL,
	[portador_dispositivo_cardiaco] [int] NULL,
	[qual_dispositivo] [varchar](256) NULL,	
	[diabetes] [int] NULL,
	[hipertensao_arterial] [int] NULL,
	[dislipidemia] [int] NULL,
	[obesidade] [int] NULL,
	[sedentarismo] [int] NULL,
	[dpoc] [int] NULL,
	[tabagista_ativo] [int] NULL,
	[ex_tabagista] [int] NULL,
	[troca_valvar_previa] [int] NULL,
	[cuidado_paliativo] [int] NULL,
	[neoplastia] [int] NULL,
	[tipo_neoplastia] [varchar](256) NULL,
	[outros_diagnosticos] [int] NULL,
	[tipo_outros_diagnosticos] [varchar](256) NULL,
	[ativo] [int] DEFAULT 1 NULL,
	[data_inclusao] [datetime] NOT NULL,
	[codigo_usuario_inclusao] [int] NOT NULL,
	[data_alteracao] [datetime] NULL,
	[codigo_usuario_alteracao] [int] NULL,
 CONSTRAINT [pk_antecedentes_pessoais_doencas__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY];
GO
ALTER TABLE [dbo].[antecedentes_pessoais_doencas]  WITH CHECK ADD  CONSTRAINT [fk_antecedentes_pessoais_doencas__paciente] FOREIGN KEY([codigo_paciente])
	REFERENCES [dbo].[paciente] ([codigo]);
GO
ALTER TABLE [dbo].[antecedentes_pessoais_doencas]  WITH CHECK ADD  CONSTRAINT [fk_antecedentes_pessoais_doencas__atendimento] FOREIGN KEY([codigo_atendimento])
	REFERENCES [dbo].[atendimento] ([codigo]);
GO
ALTER TABLE [dbo].[antecedentes_pessoais_doencas]  WITH CHECK ADD  CONSTRAINT [fk_antecedentes_pessoais_doencas__usuario] FOREIGN KEY([codigo_usuario_inclusao])
	REFERENCES [dbo].[usuario] ([codigo]);
GO
ALTER TABLE [dbo].[antecedentes_pessoais_doencas]  WITH CHECK ADD  CONSTRAINT [fk_antecedentes_pessoais_doencas__tipo_doenca_coronariana] FOREIGN KEY([codigo_tipo_doenca_coronariana])
	REFERENCES [dbo].[tipo_doenca_coronariana] ([codigo]);
GO
ALTER TABLE [dbo].[antecedentes_pessoais_doencas]  WITH CHECK ADD  CONSTRAINT [fk_antecedentes_pessoais_doencas__tipo_arritmia] FOREIGN KEY([codigo_tipo_arritimia])
	REFERENCES [dbo].[tipo_arritmia] ([codigo]);
GO

#########################################################emergencia_heart###########################################
-- emergencia_heart
CREATE TABLE [dbo].[emergencia_heart](
	[codigo] [int] IDENTITY(1,1) NOT NUll,
	[codigo_atendimento] [int] NOT NULL,
	[codigo_paciente] [int] NOT NULL,	
	[pressao_arterial_diastolica] [int] NULL,
	[pressao_arterial_sistolica] [int] NULL,
	[frequencia_cardiaca] [int] NULL,
	[saturacao_o2] [int] NULL,
	[creatinina] [int] NULL,
	[potassio] [int] NULL,
	[diuretico] [int] NULL,
	[ativo] [int] DEFAULT 1 NULL,
	[data_inclusao] [datetime] NOT NULL,
	[codigo_usuario_inclusao] [int] NOT NULL,
	[data_alteracao] [datetime] NULL,
	[codigo_usuario_alteracao] [int] NULL,
 CONSTRAINT [pk_emergencia_heart__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY];
GO
ALTER TABLE [dbo].[emergencia_heart]  WITH CHECK ADD  CONSTRAINT [fk_emergencia_heart__paciente] FOREIGN KEY([codigo_paciente])
	REFERENCES [dbo].[paciente] ([codigo]);
GO
ALTER TABLE [dbo].[emergencia_heart]  WITH CHECK ADD  CONSTRAINT [fk_emergencia_heart__atendimento] FOREIGN KEY([codigo_atendimento])
	REFERENCES [dbo].[atendimento] ([codigo]);
GO
ALTER TABLE [dbo].[emergencia_heart]  WITH CHECK ADD  CONSTRAINT [fk_emergencia_heart__usuario] FOREIGN KEY([codigo_usuario_inclusao])
	REFERENCES [dbo].[usuario] ([codigo]);
GO


#########################################################informacoes da admissao###########################################

-- tipo_insufiencia_cardiaca
CREATE TABLE [dbo].[tipo_insufiencia_cardiaca](
	[codigo] [int] IDENTITY(1,1) NOT NUll,
	[descricao] [varchar](256) NOT NULL,
	[ativo] [int] DEFAULT 1 NULL,	
 CONSTRAINT [pk_tipo_insufiencia_cardiaca__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY];
GO

INSERT INTO RHHealth_samaritano.dbo.tipo_insufiencia_cardiaca (descricao) values ('FE reduzida -<40 %'),('FE moderada - 40-49%'),('FE Preservada - 50%');
GO

-- etiologia_insufiencia_cardiaca
CREATE TABLE [dbo].[etiologia_insufiencia_cardiaca](
	[codigo] [int] IDENTITY(1,1) NOT NUll,
	[descricao] [varchar](256) NOT NULL,
	[ativo] [int] DEFAULT 1 NULL,	
 CONSTRAINT [pk_etiologia_insufiencia_cardiaca__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY];
GO

INSERT INTO RHHealth_samaritano.dbo.etiologia_insufiencia_cardiaca (descricao) values ('À Esclarecer'),('Taquicardiomiopatias'),('Doença de chagas'),('Isquêmica'),('Hipertensiva'),('Miocárdiopatia Dilatada Idiopática'),('Secundária a Quimioterápicos'),('Valvar'),('Outros');
GO

-- causa_descompensacao
CREATE TABLE [dbo].[causa_descompensacao](
	[codigo] [int] IDENTITY(1,1) NOT NUll,
	[descricao] [varchar](256) NOT NULL,
	[ativo] [int] DEFAULT 1 NULL,	
 CONSTRAINT [pk_causa_descompensacao__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY];
GO

INSERT INTO RHHealth_samaritano.dbo.causa_descompensacao (descricao) values ('Quadro Infeccioso'),('Má Adesão'),('Crise Hipertensiva'),('Arritmia'),('Síndrome Coronariana Aguda'),('Evolução da Doença'),('Outros');
GO

-- causa_primaria_internacao
CREATE TABLE [dbo].[causa_primaria_internacao](
	[codigo] [int] IDENTITY(1,1) NOT NUll,
	[descricao] [varchar](256) NOT NULL,
	[ativo] [int] DEFAULT 1 NULL,	
 CONSTRAINT [pk_causa_primaria_internacao__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY];
GO

INSERT INTO RHHealth_samaritano.dbo.causa_primaria_internacao (descricao) values ('Edema Agudo de Pulmão'),('Arritmia Ventricular'),('Pneumonia'),('Baixo Débito'),('Fibrilação Atrial'),('Infarto Agudo do Miocárdio'),('Insuficiência Renal Aguda'),('Insuficiência Cardíaca'),('Outros');
GO

-- classe_funcional_nyha
CREATE TABLE [dbo].[classe_funcional_nyha](
	[codigo] [int] IDENTITY(1,1) NOT NUll,
	[descricao] [varchar](256) NOT NULL,
	[ativo] [int] DEFAULT 1 NULL,	
 CONSTRAINT [pk_classe_funcional_nyha__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY];
GO

INSERT INTO RHHealth_samaritano.dbo.classe_funcional_nyha (descricao) values ('I'),('II'),('III'),('IV'),('Sem informação');
GO

-- escala_fragilidade
CREATE TABLE [dbo].[escala_fragilidade](
	[codigo] [int] IDENTITY(1,1) NOT NUll,
	[descricao] [varchar](256) NOT NULL,
	[ativo] [int] DEFAULT 1 NULL,	
 CONSTRAINT [pk_escala_fragilidade__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY];
GO

INSERT INTO RHHealth_samaritano.dbo.escala_fragilidade (descricao) values ('Em forma'), ('Bem'), ('Vulnerável'), ('Levemente frágil'), ('Moderamente Frágil'), ('Severamente Frágil'), ('Muito Severamente Frágil'), ('Terminal');
GO

-- perfil_hemodinanico
CREATE TABLE [dbo].[perfil_hemodinanico](
	[codigo] [int] IDENTITY(1,1) NOT NUll,
	[descricao] [varchar](256) NOT NULL,
	[ativo] [int] DEFAULT 1 NULL,	
 CONSTRAINT [pk_perfil_hemodinanico__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY];
GO

INSERT INTO RHHealth_samaritano.dbo.perfil_hemodinanico (descricao) values ('A - Quente e Seco'), ('B- Quente e Úmido'), ('C- Frio e Úmido'), ('D-Frio e Seco'), ('Sem informação');
GO

-- info_admissao
CREATE TABLE [dbo].[info_admissao](
	[codigo] [int] IDENTITY(1,1) NOT NUll,
	[codigo_atendimento] [int] NOT NULL,
	[codigo_paciente] [int] NOT NULL,	
	[peso] [decimal] (10,2) NULL,
	[altura] [decimal] (10,2) NULL,
	[imc] [decimal] (10,2) NULL,
	[codigo_tipo_insufiencia_cardiaca] [int] NULL,
	[codigo_etiologia_insufiencia_cardiaca] [int] NULL,
	[etiologia_insufiencia_cardiaca_outros] [varchar](256) NULL,
	[codigo_causa_descompensacao] [int] NULL,
	[causa_descompensacao_outros] [varchar](256) NULL,
	[codigo_causa_primaria_internacao] [int] NULL,
	[causa_primaria_internacao_outros] [varchar](256) NULL,
	[codigo_classe_funcional_nyha] [int] NULL,
	[codigo_escala_fragilidade] [int] NULL,	
	[codigo_perfil_hemodinanico] [int] NULL,
	[ativo] [int] DEFAULT 1 NULL,
	[data_inclusao] [datetime] NOT NULL,
	[codigo_usuario_inclusao] [int] NOT NULL,
	[data_alteracao] [datetime] NULL,
	[codigo_usuario_alteracao] [int] NULL,
 CONSTRAINT [pk_info_admissao__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY];
GO
ALTER TABLE [dbo].[info_admissao]  WITH CHECK ADD  CONSTRAINT [fk_info_admissao__paciente] FOREIGN KEY([codigo_paciente])
	REFERENCES [dbo].[paciente] ([codigo]);
GO
ALTER TABLE [dbo].[info_admissao]  WITH CHECK ADD  CONSTRAINT [fk_info_admissao__atendimento] FOREIGN KEY([codigo_atendimento])
	REFERENCES [dbo].[atendimento] ([codigo]);
GO
ALTER TABLE [dbo].[info_admissao]  WITH CHECK ADD  CONSTRAINT [fk_info_admissao__usuario] FOREIGN KEY([codigo_usuario_inclusao])
	REFERENCES [dbo].[usuario] ([codigo]);
GO
ALTER TABLE [dbo].[info_admissao]  WITH CHECK ADD  CONSTRAINT [fk_info_admissao__tipo_insufiencia_cardiaca] FOREIGN KEY([codigo_tipo_insufiencia_cardiaca])
	REFERENCES [dbo].[tipo_insufiencia_cardiaca] ([codigo]);
GO
ALTER TABLE [dbo].[info_admissao]  WITH CHECK ADD  CONSTRAINT [fk_info_admissao__etiologia_insufiencia_cardiaca] FOREIGN KEY([codigo_etiologia_insufiencia_cardiaca])
	REFERENCES [dbo].[etiologia_insufiencia_cardiaca] ([codigo]);
GO
ALTER TABLE [dbo].[info_admissao]  WITH CHECK ADD  CONSTRAINT [fk_info_admissao__causa_descompensacao] FOREIGN KEY([codigo_causa_descompensacao])
	REFERENCES [dbo].[causa_descompensacao] ([codigo]);
GO
ALTER TABLE [dbo].[info_admissao]  WITH CHECK ADD  CONSTRAINT [fk_info_admissao__causa_primaria_internacao] FOREIGN KEY([codigo_causa_primaria_internacao])
	REFERENCES [dbo].[causa_primaria_internacao] ([codigo]);
GO
ALTER TABLE [dbo].[info_admissao]  WITH CHECK ADD  CONSTRAINT [fk_info_admissao__classe_funcional_nyha] FOREIGN KEY([codigo_classe_funcional_nyha])
	REFERENCES [dbo].[classe_funcional_nyha] ([codigo]);
GO
ALTER TABLE [dbo].[info_admissao]  WITH CHECK ADD  CONSTRAINT [fk_info_admissao__escala_fragilidade] FOREIGN KEY([codigo_escala_fragilidade])
	REFERENCES [dbo].[escala_fragilidade] ([codigo]);
GO
ALTER TABLE [dbo].[info_admissao]  WITH CHECK ADD  CONSTRAINT [fk_info_admissao__perfil_hemodinanico] FOREIGN KEY([codigo_perfil_hemodinanico])
	REFERENCES [dbo].[perfil_hemodinanico] ([codigo]);
GO

-- medicacao
CREATE TABLE [dbo].[medicacao](
	[codigo] [int] IDENTITY(1,1) NOT NUll,
	[descricao] [varchar](256) NOT NULL,
	[ativo] [int] DEFAULT 1 NULL,	
 CONSTRAINT [pk_medicacao__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY];
GO

INSERT INTO RHHealth_samaritano.dbo.medicacao (descricao) values ('Beta-bloqueador'),('IECA/BRA'),('Nitrato/Hidralazina'),('Nitroprussiato'),('Amiodarona'),('Digitálico'),('Dobutamina'),('Espironolactona'),('Furosemida'),('Ivabradina'),('Nitroglicerina'),('Varfarina'),('Sacubitril/Valsartana'),('Outras medicações');
GO

-- info_admissao_medicacao
CREATE TABLE [dbo].[info_admissao_medicacao](
	[codigo] [int] IDENTITY(1,1) NOT NUll,
	[codigo_info_admissao] [int] NOT NULL,
	[codigo_medicacao] [int] NOT NULL,
	[outros] [varchar](256) NOT NULL,
 CONSTRAINT [pk_info_admissao_medicacao__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY];
GO
ALTER TABLE [dbo].[info_admissao_medicacao]  WITH CHECK ADD  CONSTRAINT [fk_info_admissao_medicacao__info_admissao] FOREIGN KEY([codigo_info_admissao])
	REFERENCES [dbo].[info_admissao] ([codigo]);
GO
ALTER TABLE [dbo].[info_admissao_medicacao]  WITH CHECK ADD  CONSTRAINT [fk_info_admissao_medicacao__medicacao] FOREIGN KEY([codigo_medicacao])
	REFERENCES [dbo].[medicacao] ([codigo]);
GO

############################################################5. Exames Registrados##############################################################################

-- grau_disfuncao
CREATE TABLE [dbo].[grau_disfuncao](
	[codigo] [int] IDENTITY(1,1) NOT NUll,
	[descricao] [varchar](256) NOT NULL,
	[ativo] [int] DEFAULT 1 NULL,	
 CONSTRAINT [pk_grau_disfuncao__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY];
GO

INSERT INTO RHHealth_samaritano.dbo.grau_disfuncao (descricao) values ('BetaGrau I'), ('Grau II'), ('Grau III'), ('Sem informação');
GO

-- exames_registrados
CREATE TABLE [dbo].[exames_registrados](
	[codigo] [int] IDENTITY(1,1) NOT NUll,
	[codigo_atendimento] [int] NOT NULL,
	[codigo_paciente] [int] NOT NULL,
	[ecocardiograma] [int] NULL,
	[data_ecocardiograma] [date] NULL,
	[registro_fracao_ejecao] [decimal](10,2) NULL,
	[psap] [int] NULL,
	[disfuncao_diastolica] [int] NULL,
	[codigo_grau_disfuncao] [int] NULL,
	[eletrocardiograma] [int] NULL,
	[data_eletrocardiograma] [date] NULL,
	[ativo] [int] DEFAULT 1 NULL,
	[data_inclusao] [datetime] NOT NULL,
	[codigo_usuario_inclusao] [int] NOT NULL,
	[data_alteracao] [datetime] NULL,
	[codigo_usuario_alteracao] [int] NULL,
 CONSTRAINT [pk_exames_registrados__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY];
GO
ALTER TABLE [dbo].[exames_registrados]  WITH CHECK ADD  CONSTRAINT [fk_exames_registrados__paciente] FOREIGN KEY([codigo_paciente])
	REFERENCES [dbo].[paciente] ([codigo]);
GO
ALTER TABLE [dbo].[exames_registrados]  WITH CHECK ADD  CONSTRAINT [fk_exames_registrados__atendimento] FOREIGN KEY([codigo_atendimento])
	REFERENCES [dbo].[atendimento] ([codigo]);
GO
ALTER TABLE [dbo].[exames_registrados]  WITH CHECK ADD  CONSTRAINT [fk_exames_registrados__usuario] FOREIGN KEY([codigo_usuario_inclusao])
	REFERENCES [dbo].[usuario] ([codigo]);
GO
ALTER TABLE [dbo].[exames_registrados]  WITH CHECK ADD  CONSTRAINT [fk_exames_registrados__grau_disfuncao] FOREIGN KEY([codigo_grau_disfuncao])
	REFERENCES [dbo].[grau_disfuncao] ([codigo]);
GO

-- resultado_eletrocardiograma
CREATE TABLE [dbo].[resultado_eletrocardiograma](
	[codigo] [int] IDENTITY(1,1) NOT NUll,
	[descricao] [varchar](256) NOT NULL,
	[ativo] [int] DEFAULT 1 NULL,	
 CONSTRAINT [pk_resultado_eletrocardiograma__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY];
GO

INSERT INTO RHHealth_samaritano.dbo.resultado_eletrocardiograma (descricao) values ('Sinusal'),('FIbrilação ou Fluter Atrial'),('BCRE'),('QRS>120ms I'), ('Supra ou Infra ST'),('Sem Laudo'),('Sem Informação');
GO

-- exames_registrados_resultado_eletrocardiograma
CREATE TABLE [dbo].[exames_registrados_resultado_eletrocardiograma](
	[codigo] [int] IDENTITY(1,1) NOT NUll,
	[codigo_exames_registrados] [int] NOT NULL,
	[codigo_resultado_eletrocardiograma] [int] NOT NULL,
 CONSTRAINT [pk_exames_registrados_resultado_eletrocardiograma__codigo] PRIMARY KEY CLUSTERED 
(
	[codigo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 70) ON [PRIMARY]
) ON [PRIMARY];
GO
ALTER TABLE [dbo].[exames_registrados_resultado_eletrocardiograma]  WITH CHECK ADD  CONSTRAINT [fk_exames_registrados_resultado_eletrocardiograma__exames_registrados] FOREIGN KEY([codigo_exames_registrados])
	REFERENCES [dbo].[exames_registrados] ([codigo]);
GO
ALTER TABLE [dbo].[exames_registrados_resultado_eletrocardiograma]  WITH CHECK ADD  CONSTRAINT [fk_exames_registrados_resultado_eletrocardiograma__resultado_eletrocardiograma] FOREIGN KEY([codigo_resultado_eletrocardiograma])
	REFERENCES [dbo].[resultado_eletrocardiograma] ([codigo]);
GO



