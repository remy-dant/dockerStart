import { useEffect, useState } from 'react';

const apiBaseUrl = import.meta.env.VITE_API_URL || 'http://localhost:3000';

export default function App() {
  const [data, setData] = useState(null);
  const [error, setError] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    let active = true;

    async function loadBackendInfo() {
      try {
        const response = await fetch(`${apiBaseUrl}/`);
        if (!response.ok) {
          throw new Error(`HTTP ${response.status}`);
        }
        const json = await response.json();
        if (active) {
          setData(json);
          setError(null);
        }
      } catch (fetchError) {
        if (active) {
          setError(fetchError.message);
        }
      } finally {
        if (active) {
          setLoading(false);
        }
      }
    }

    loadBackendInfo();

    return () => {
      active = false;
    };
  }, []);

  return (
    <main style={{ fontFamily: 'system-ui, sans-serif', padding: '2rem' }}>
      <h1>Job 08</h1>
      <p>Frontend Vite + backend Node + MySQL.</p>
      <section>
        <h2>État du backend</h2>
        {loading && <p>Chargement...</p>}
        {error && <p style={{ color: 'crimson' }}>Erreur : {error}</p>}
        {data && <pre>{JSON.stringify(data, null, 2)}</pre>}
      </section>
      <section>
        <h2>Rechargement à chaud</h2>
        <p>Modifie <code>backend/server.js</code> ou <code>frontend/src/App.jsx</code> pour voir le rechargement.</p>
        <p>Je suis un changement effectué directement dans le code sans rebuild.</p>
      </section>
    </main>
  );
}
