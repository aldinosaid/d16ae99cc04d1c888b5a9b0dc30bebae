-- Adminer 4.8.1 PostgreSQL 14.12 dump

DROP TABLE IF EXISTS "clients";
DROP SEQUENCE IF EXISTS clients_id_seq;
CREATE SEQUENCE clients_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 CACHE 1;

CREATE TABLE "public"."clients" (
    "id" integer DEFAULT nextval('clients_id_seq') NOT NULL,
    "username" text,
    "token" text,
    "pass" text,
    "status" text DEFAULT 'unvalidated',
    "created_at" timestamp,
    "secret_key" text,
    CONSTRAINT "clients_pkey" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "queue";
DROP SEQUENCE IF EXISTS queue_id_seq;
CREATE SEQUENCE queue_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 CACHE 1;

CREATE TABLE "public"."queue" (
    "id" integer DEFAULT nextval('queue_id_seq') NOT NULL,
    "created_at" timestamp NOT NULL,
    "email_args" text NOT NULL,
    "status" text DEFAULT 'pending' NOT NULL,
    "uuid" text,
    CONSTRAINT "queue_pkey" PRIMARY KEY ("id")
) WITH (oids = false);


-- 2024-07-04 20:11:44.191614+00