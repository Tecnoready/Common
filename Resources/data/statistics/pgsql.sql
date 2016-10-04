begin;
CREATE TABLE "statistics_monthly" (
id bigserial NOT NULL,
"year" int4 NOT NULL,
"month" int4 NOT NULL,
"total" varchar(255) NOT NULL,
"day1" varchar(255) NOT NULL,
"day2" varchar(255) NOT NULL,
"day3" varchar(255) NOT NULL,
"day4" varchar(255) NOT NULL,
"day5" varchar(255) NOT NULL,
"day6" varchar(255) NOT NULL,
"day7" varchar(255) NOT NULL,
"day8" varchar(255) NOT NULL,
"day9" varchar(255) NOT NULL,
"day10" varchar(255) NOT NULL,
"day11" varchar(255) NOT NULL,
"day12" varchar(255) NOT NULL,
"day13" varchar(255) NOT NULL,
"day14" varchar(255) NOT NULL,
"day15" varchar(255) NOT NULL,
"day16" varchar(255) NOT NULL,
"day17" varchar(255) NOT NULL,
"day18" varchar(255) NOT NULL,
"day19" varchar(255) NOT NULL,
"day20" varchar(255) NOT NULL,
"day21" varchar(255) NOT NULL,
"day22" varchar(255) NOT NULL,
"day23" varchar(255) NOT NULL,
"day24" varchar(255) NOT NULL,
"day25" varchar(255) NOT NULL,
"day26" varchar(255) NOT NULL,
"day27" varchar(255) NOT NULL,
"day28" varchar(255) NOT NULL,
"day29" varchar(255) NOT NULL,
"day30" varchar(255) NOT NULL,
"day31" varchar(255) NOT NULL,
"created_at" timestamp NOT NULL,
"updated_at" timestamp NOT NULL,
"deleted_at" timestamp DEFAULT NULL,
"created_from_ip" varchar(45) DEFAULT NULL,
"updated_from_ip" varchar(45) DEFAULT NULL,
"yearEntity_id" bigint NOT NULL,
PRIMARY KEY ("id") 
);

CREATE INDEX "IDX_DA2703A94DFDCFB" ON "statistics_monthly" ("yearEntity_id");

CREATE TABLE "statistics_year" (
id bigserial NOT NULL,
"year" int4 NOT NULL,
"total" varchar(255) NOT NULL,
"total_month_1" varchar(255) NOT NULL,
"total_month_2" varchar(255) NOT NULL,
"total_month_3" varchar(255) NOT NULL,
"total_month_4" varchar(255) NOT NULL,
"total_month_5" varchar(255) NOT NULL,
"total_month_6" varchar(255) NOT NULL,
"total_month_7" varchar(255) NOT NULL,
"total_month_8" varchar(255) NOT NULL,
"total_month_9" varchar(255) NOT NULL,
"total_month_10" varchar(255) NOT NULL,
"total_month_11" varchar(255) NOT NULL,
"total_month_12" varchar(255) NOT NULL,
"created_at" timestamp NOT NULL,
"updated_at" timestamp NOT NULL,
"deleted_at" timestamp DEFAULT NULL,
"created_from_ip" varchar(45) DEFAULT NULL,
"updated_from_ip" varchar(45) DEFAULT NULL,
PRIMARY KEY ("id") 
);


ALTER TABLE "statistics_monthly" ADD CONSTRAINT "FK_DA2703A94DFDCFB" FOREIGN KEY ("yearEntity_id") REFERENCES "statistics_year" ("id");

commit;