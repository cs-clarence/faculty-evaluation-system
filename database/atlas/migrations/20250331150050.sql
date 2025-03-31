-- Create "llm_responses" table
CREATE TABLE "llm_responses" (
  "id" bigserial NOT NULL,
  "key" text NOT NULL,
  "value" text NOT NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "llm_responses_key_unique" UNIQUE ("key")
);
