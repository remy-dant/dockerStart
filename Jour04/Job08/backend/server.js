const express = require("express");
const cors = require("cors");
const mysql = require("mysql2/promise");
const Redis = require("ioredis");
require("dotenv").config();

const app = express();
app.use(cors());
app.use(express.json());

const port = Number(process.env.PORT || 3000);
const redis = new Redis({
  host: process.env.REDIS_HOST || "redis",
  port: Number(process.env.REDIS_PORT || 6379),
  maxRetriesPerRequest: null,
});

const dbConfig = {
  host: process.env.MYSQL_HOST || "db",
  port: Number(process.env.MYSQL_PORT || 3306),
  user: process.env.MYSQL_USER || "dev",
  password: process.env.MYSQL_PASSWORD || "devpassword",
  database: process.env.MYSQL_DATABASE || "job08_demo",
};

const pool = mysql.createPool({
  ...dbConfig,
  waitForConnections: true,
  connectionLimit: 5,
});

async function waitForDatabase(maxAttempts = 30, delayMs = 2000) {
  for (let attempt = 1; attempt <= maxAttempts; attempt += 1) {
    try {
      const connection = await pool.getConnection();
      connection.release();
      console.log("MySQL est prêt");
      return;
    } catch (error) {
      console.log(`En attente de MySQL (${attempt}/${maxAttempts})...`);
      if (attempt === maxAttempts) {
        throw error;
      }
      await new Promise((resolve) => setTimeout(resolve, delayMs));
    }
  }
}

app.get("/", (req, res) => {
  const cacheKey = "cache:route:/";

  redis
    .get(cacheKey)
    .then((cachedValue) => {
      if (cachedValue) {
        return res.json(JSON.parse(cachedValue));
      }

      const payload = {
        message: "Serveur backend opérationnel",
        horodatage: new Date().toISOString(),
        source: "calcul",
      };

      redis.set(cacheKey, JSON.stringify(payload), "EX", 3600).catch(() => {});
      return res.json(payload);
    })
    .catch(() => {
      res.json({
        message: "Serveur backend opérationnel",
        horodatage: new Date().toISOString(),
        source: "secours",
      });
    });
});

app.get("/db-test", async (req, res) => {
  try {
    const [rows] = await pool.query(
      "SELECT NOW() AS maintenant, DATABASE() AS base_de_donnees",
    );
    res.json({
      succes: true,
      message: "Connexion à MySQL réussie",
      donnees: rows[0],
    });
  } catch (error) {
    res.status(500).json({
      succes: false,
      message: "Connexion à MySQL échouée",
      erreur: error.message,
    });
  }
});

waitForDatabase()
  .then(() => {
    app.listen(port, () => {
      console.log(`Backend listening on port ${port}`);
    });
  })
  .catch((error) => {
    console.error("La base de données n'a pas répondu dans le temps imparti");
    console.error(error);
    process.exit(1);
  });

process.on("SIGINT", () => {
  redis.disconnect();
  process.exit(0);
});

process.on("SIGTERM", () => {
  redis.disconnect();
  process.exit(0);
});
