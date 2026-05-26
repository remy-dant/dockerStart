/**
 * Import des dépendances
 */

const express = require('express');
const mysql = require('mysql2');


/**
 * Configuration de la base de données
 */

const dbConfig = {
    host: process.env.DB_HOST || 'mysql_container',
    user: process.env.DB_USER || 'root',
    password: process.env.DB_PASSWORD || 'root',
    database: process.env.DB_NAME || 'projetdb',
};

let connection;


/**
 * Fonction de connexion à MySQL
 * avec reconnexion automatique
 */

function connectToDatabase() {

    connection = mysql.createConnection(dbConfig);

    connection.connect((err) => {

        if (err) {

            console.error(
                'Erreur connexion BDD. Nouvelle tentative dans 5 secondes...',
                err
            );

            setTimeout(connectToDatabase, 5000);

        } else {

            console.log('Connecté à la base de données');
        }
    });

    /**
     * Gestion des erreurs de connexion
     */

    connection.on('error', (err) => {

        console.error('Erreur base de données :', err);

        if (err.code === 'PROTOCOL_CONNECTION_LOST') {

            console.log('Reconnexion à la base de données...');
            connectToDatabase();

        } else {

            throw err;
        }
    });
}


/**
 * Initialisation de la connexion BDD
 */

connectToDatabase();


/**
 * Initialisation de l'application Express
 */

const app = express();


/**
 * Route principale
 */

app.get('/', (req, res) => {

    res.send(
        'Bienvenue sur l\'API du backend de votre projet Docker !'
    );
});


/**
 * Route de statut API
 * Vérifie si MySQL répond correctement
 */

app.get('/api/status', (req, res) => {

    connection.query(
        'SELECT NOW() AS currentTime',
        (err, results) => {

            if (err) {

                console.error(
                    'Erreur exécution requête :',
                    err
                );

                res.status(500).send(
                    'Erreur requête base de données'
                );

            } else {

                res.json({
                    status: 'success',
                    currentTime: results[0].currentTime
                });
            }
        }
    );
});


/**
 * Démarrage du serveur backend
 */

const PORT = 3000;

app.listen(PORT, () => {

    console.log(`Backend running on port ${PORT}`);
});
