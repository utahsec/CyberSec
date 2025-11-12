FROM php:8.2-cli
RUN apt-get update && apt-get install sqlite3
WORKDIR /srv
COPY app .
RUN sqlite3 users.db < init.sql
EXPOSE 5000
CMD ["php", "-S", "0.0.0.0:5000"]
