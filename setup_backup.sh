#!/bin/bash

# === Sozlamalar ===
SOURCE_FILE="./backup/mk.sh"
TARGET_DIR="/home/backup"
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

TARGET_FILE="$TARGET_DIR/$PROJECT_NAME.sh"

# === Manba fayl mavjudligini tekshirish ===
if [ ! -f "$SOURCE_FILE" ]; then
    echo "[XATO] $SOURCE_FILE topilmadi."
    exit 1
fi

# === REPO_DIR_API yangilash ===
sed -i '/^REPO_DIR_API=/s/^/#/' "$SOURCE_FILE"
sed -i "1i$REPO_VAR_LINE" "$SOURCE_FILE"

# === Target katalogini yaratish ===
mkdir -p "$TARGET_DIR"

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
