# Name: psql-app
# Tag: 1.0.0
FROM postgres:16

COPY ./init-db.sh /docker-entrypoint-initdb.d/init-db.sh

ENV POSTGRES_USER root
ENV POSTGRES_PASSWORD 1234
ENV POSTGRES_DB security_cw2

EXPOSE 5432