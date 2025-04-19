#!/bin/bash

echo "[INFO] MySQL zaxira jarayoni boshlanyapti..."

now=$(date +%Y-%m-%d_%H-%M-%S)
SCRIPT_DIR="$(dirname "$(realpath "$0")")"

# === REPO_DIR_API aniqlash ===
#REPO_DIR_API="$(dirname "$SCRIPT_DIR")"
ENV_FILE="$REPO_DIR_API/.env"

# === .env yuklash ===
if [ ! -f "$ENV_FILE" ]; then
    echo "[XATO] .env fayli topilmadi: $ENV_FILE"
    exit 1
fi

export $(grep -v '^#' "$ENV_FILE" | xargs)

# === O'zgaruvchilar ===
PROJECT_NAME=${DOCKER_PROJECT_NAME}
DB_NAME=${DOCKER_PROJECT_NAME}
MYSQL_PASSWORD=${DATABASE_PASSWORD}
DOCKERFILE="$REPO_DIR_API/docker-compose.yml"

BACKUP_DIR="$REPO_DIR_API/backup"
SQL_FILE="$BACKUP_DIR/$PROJECT_NAME-$now.sql"
ARCHIVE_FILE="$BACKUP_DIR/$PROJECT_NAME-$now.tar.gz"

# === MySQL zaxiralash ===
docker compose -f "$DOCKERFILE" exec mysql sh -c "mysqldump -uroot -p$MYSQL_PASSWORD $DB_NAME" > "$SQL_FILE"

if [ $? -ne 0 ]; then
    echo "[XATO] Zaxiralashda muammo bo‘ldi."
    exit 1
fi

echo "[INFO] Zaxira olindi: $SQL_FILE"

# === Siqish ===
tar -czf "$ARCHIVE_FILE" "$SQL_FILE" && rm "$SQL_FILE"
echo "[INFO] Fayl siqildi: $ARCHIVE_FILE"

# === Telegramga yuborish ===
API_TOKEN=${TELEGRAM_BOT_TOKEN}
CHAT_ID=${TELEGRAM_CHAT_ID}

FILE_SIZE=$(du -m "$ARCHIVE_FILE" | cut -f1)

if (( FILE_SIZE > 49 )); then
    echo "[INFO] Fayl katta ($FILE_SIZE MB), bo‘linmoqda..."
    split -b 49M "$ARCHIVE_FILE" "${ARCHIVE_FILE}_part_"
    for part in ${ARCHIVE_FILE}_part_*; do
        curl -s -F chat_id="$CHAT_ID" -F document=@"$part" "https://api.telegram.org/bot$API_TOKEN/sendDocument"
        rm "$part"
    done
else
    curl -s -F chat_id="$CHAT_ID" -F document=@"$ARCHIVE_FILE" "https://api.telegram.org/bot$API_TOKEN/sendDocument"
fi

rm "$ARCHIVE_FILE"
echo "[✅] Backup va yuborish yakunlandi."
