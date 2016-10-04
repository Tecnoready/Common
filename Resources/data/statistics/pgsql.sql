begin;
CREATE TABLE statistics_year
(
  id bigserial NOT NULL,
  year integer NOT NULL,
  total character varying(255) NOT NULL,
  total_month_1 character varying(255) NOT NULL,
  total_month_2 character varying(255) NOT NULL,
  total_month_3 character varying(255) NOT NULL,
  total_month_4 character varying(255) NOT NULL,
  total_month_5 character varying(255) NOT NULL,
  total_month_6 character varying(255) NOT NULL,
  total_month_7 character varying(255) NOT NULL,
  total_month_8 character varying(255) NOT NULL,
  total_month_9 character varying(255) NOT NULL,
  total_month_10 character varying(255) NOT NULL,
  total_month_11 character varying(255) NOT NULL,
  total_month_12 character varying(255) NOT NULL,
  created_at timestamp without time zone NOT NULL,
  updated_at timestamp without time zone NOT NULL,
  deleted_at timestamp without time zone,
  created_from_ip character varying(45) DEFAULT NULL::character varying,
  updated_from_ip character varying(45) DEFAULT NULL::character varying,
  CONSTRAINT statistics_year_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE statistics_monthly
(
  id bigserial NOT NULL,
  year integer NOT NULL,
  month integer NOT NULL,
  total character varying(255) NOT NULL,
  day1 character varying(255) NOT NULL,
  day2 character varying(255) NOT NULL,
  day3 character varying(255) NOT NULL,
  day4 character varying(255) NOT NULL,
  day5 character varying(255) NOT NULL,
  day6 character varying(255) NOT NULL,
  day7 character varying(255) NOT NULL,
  day8 character varying(255) NOT NULL,
  day9 character varying(255) NOT NULL,
  day10 character varying(255) NOT NULL,
  day11 character varying(255) NOT NULL,
  day12 character varying(255) NOT NULL,
  day13 character varying(255) NOT NULL,
  day14 character varying(255) NOT NULL,
  day15 character varying(255) NOT NULL,
  day16 character varying(255) NOT NULL,
  day17 character varying(255) NOT NULL,
  day18 character varying(255) NOT NULL,
  day19 character varying(255) NOT NULL,
  day20 character varying(255) NOT NULL,
  day21 character varying(255) NOT NULL,
  day22 character varying(255) NOT NULL,
  day23 character varying(255) NOT NULL,
  day24 character varying(255) NOT NULL,
  day25 character varying(255) NOT NULL,
  day26 character varying(255) NOT NULL,
  day27 character varying(255) NOT NULL,
  day28 character varying(255) NOT NULL,
  day29 character varying(255) NOT NULL,
  day30 character varying(255) NOT NULL,
  day31 character varying(255) NOT NULL,
  created_at timestamp without time zone NOT NULL,
  updated_at timestamp without time zone NOT NULL,
  deleted_at timestamp without time zone,
  created_from_ip character varying(45) DEFAULT NULL::character varying,
  updated_from_ip character varying(45) DEFAULT NULL::character varying,
  "yearEntity_id" bigint NOT NULL,
  CONSTRAINT statistics_monthly_pkey PRIMARY KEY (id),
  CONSTRAINT "FK_DA2703A94DFDCFB" FOREIGN KEY ("yearEntity_id")
      REFERENCES statistics_year (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);

-- Index: "IDX_DA2703A94DFDCFB"

-- DROP INDEX "IDX_DA2703A94DFDCFB";

CREATE INDEX "IDX_DA2703A94DFDCFB" ON statistics_monthly USING btree ("yearEntity_id");
commit;