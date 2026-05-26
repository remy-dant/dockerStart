# Image Node.js officielle
FROM node:20

# Dossier de travail
WORKDIR /app

# Copie des fichiers package
COPY package.json .

# Installation des dépendances
RUN npm install

# Copie du projet
COPY . .

# Port exposé
EXPOSE 3000

# Lancement du serveur
CMD ["npm", "start"]
