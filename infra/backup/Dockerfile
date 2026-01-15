FROM postgres:16-alpine

# Installer aws-cli + supercronic
RUN apk add --no-cache curl aws-cli ca-certificates \
 && curl -fsSL https://github.com/aptible/supercronic/releases/download/v0.2.29/supercronic-linux-amd64 \
    -o /usr/local/bin/supercronic \
 && chmod +x /usr/local/bin/supercronic

WORKDIR /backup

COPY backup.sh /backup/backup.sh
RUN chmod +x /backup/backup.sh

COPY crontab /backup/crontab

CMD ["supercronic", "/backup/crontab"]
