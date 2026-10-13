#!/usr/bin/env bash
set -e

# Create a second database with the same name but suffixed "_test" for running local tests against a real DB
psql -v ON_ERROR_STOP=1 --username "${POSTGRES_USER}" --dbname "${POSTGRES_DB}" <<-EOSQL
	CREATE DATABASE ${POSTGRES_DB}_test;
	GRANT ALL PRIVILEGES ON DATABASE ${POSTGRES_DB}_test TO ${POSTGRES_USER};
EOSQL
