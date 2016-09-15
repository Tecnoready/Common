-- Table: app_configuration

-- DROP TABLE app_configuration;

CREATE TABLE app_configuration
(
  key character varying(255) NOT NULL, -- Indice
  value text, -- Valor plano
  name_wrapper character varying(200) NOT NULL, -- Nombre del contenedor de la configuracion
  description character varying(255),
  enabled smallint NOT NULL,
  created_at timestamp without time zone NOT NULL,
  updated_at timestamp without time zone NOT NULL,
  CONSTRAINT app_configuration_pkey PRIMARY KEY (key)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE app_configuration
  OWNER TO postgres;
COMMENT ON TABLE app_configuration
  IS 'Configuracion de clave=valor almacenada en base de datos';
COMMENT ON COLUMN app_configuration.key IS 'Indice';
COMMENT ON COLUMN app_configuration.value IS 'Valor plano';
COMMENT ON COLUMN app_configuration.name_wrapper IS 'Nombre del contenedor de la configuracion';