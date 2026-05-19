# Image Python officielle
FROM python:3.11-slim
# Métadonnées
LABEL maintainer="ton.email@example.com"
# Variables d'environnement Python
ENV PYTHONDONTWRITEBYTECODE=1
ENV PYTHONUNBUFFERED=1
# Dossier de travail
WORKDIR /app
# Copier et installer les dépendances
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt
# Copier le code source
COPY . .
# Port
EXPOSE 5000
# Commande de démarrage
CMD ["python", "run.py"]