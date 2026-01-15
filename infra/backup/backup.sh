#!/bin/sh
set -eu

DATE="$(date -u +"%Y-%m-%d_%H-%M-%S")"
FILE="cronwatch_${DATE}.sql.gz"

echo "==> Dump PostgreSQL"
pg_dump "$DATABASE_URL" | gzip > "/tmp/${FILE}"

echo "==> Upload to Backblaze"
export AWS_ACCESS_KEY_ID="${S3_ACCESS_KEY}"
export AWS_SECRET_ACCESS_KEY="${S3_SECRET_KEY}"
export AWS_EC2_METADATA_DISABLED=true

aws s3 cp "/tmp/${FILE}" "s3://${S3_BUCKET}/${FILE}" \
  --endpoint-url "${S3_ENDPOINT}"

echo "==> Cleanup"
rm -f "/tmp/${FILE}"

echo "BACKUP OK: ${FILE}"
