#!/bin/bash

# === Sozlamalar ===
SOURCE_FILE="mk.sh"
TARGET_DIR="/apps/backup"
ENV_FILE=".env"
REPO_VAR_LINE="REPO_DIR_API=\"$(pwd)\""

# === PROJECT_NAME ni aniqlash ===
if [ -f "$ENV_FILE" ]; then
    PROJECT_NAME=$(grep '^DOCKER_PROJECT_NAME=' "$ENV_FILE" | cut -d '=' -f2)
fi

if [ -z "$PROJECT_NAME" ]; then
    PROJECT_NAME=$(basename "$(pwd)")
    echo "[INFO] .env topilmadi yoki bo‘sh. PROJECT_NAME: $PROJECT_NAME"
else
    echo "[INFO] .env dan PROJECT_NAME: $PROJECT_NAME"
fi

BACKUP_DIR_PATH="/apps/backup"
TARGET_FILE="$TARGET_DIR/$PROJECT_NAME.sh"

# === Manba fayl mavjudligini tekshirish ===
if [ ! -f "$SOURCE_FILE" ]; then
    echo "[XATO] $SOURCE_FILE topilmadi."
    exit 1
fi

# === REPO_DIR_API va BACKUP_DIR ni commentga olish va yangilarini kiritish ===
sed -i '/^REPO_DIR_API=/s/^/#/' "$SOURCE_FILE"
sed -i '/^BACKUP_DIR=/s/^/#/' "$SOURCE_FILE"
sed -i "1iREPO_DIR_API=\"$(pwd)\"" "$SOURCE_FILE"
sed -i "2iBACKUP_DIR=\"$BACKUP_DIR_PATH\"" "$SOURCE_FILE"

# === Target katalogini yaratish ===
mkdir -p "$BACKUP_DIR_PATH"

# === Faylni ko‘chirish va bajariladigan qilish ===
rm -f "$TARGET_FILE"
cp "$SOURCE_FILE" "$TARGET_FILE"
chmod +x "$TARGET_FILE"
echo "[INFO] $TARGET_FILE tayyor."

# === Yangi skriptni ishga tushurish ===
echo "[INFO] Skript ishga tushirilmoqda..."
if "$TARGET_FILE"; then
    echo "[✅] Skript muvaffaqiyatli bajarildi."
else
    echo "[XATO] Skript ishida muammo bo‘ldi."
    exit 1
fi

# === Cron job yangilash ===
CRON_JOB="0 2 * * * $TARGET_FILE"
CRONTAB_TMP=$(mktemp)

crontab -l 2>/dev/null | sed "/$PROJECT_NAME.sh/ s/^/# /" >> "$CRONTAB_TMP"
echo "$CRON_JOB" >> "$CRONTAB_TMP"
crontab "$CRONTAB_TMP"
rm "$CRONTAB_TMP"

echo "[✅] Cron job yangilandi: $CRON_JOB"
