# ZeroLegend Agar – additive integration

Questa versione mantiene il renderer e il gameplay originale come base e aggiunge un layer separato.

## Modifiche incluse

- Menu utente con 20 impostazioni persistenti in `localStorage`.
- Tasto `U` o pulsante ⚙ per aprire il menu.
- Dash con `SHIFT`: 10 secondi, cooldown 16 secondi, moltiplicatore del target mouse 2.6x.
- HUD Dash, scia, particelle e profondità 3D simulata tramite canvas overlay.
- Le impostazioni `Profondità 3D`, `Particelle`, `Scia Dash`, `Bagliore Dash`, `Camera dinamica`, `Suoni gioco` e `Grafica ridotta` influenzano direttamente il feedback del Dash.
- `datad23b.js` resta il motore originale; il boost viene applicato al pacchetto di movimento già esistente, senza sostituire WebSocket o renderer.
- `api/config.php` non contiene credenziali reali: usa variabili d'ambiente.

## Nota sul server Railway

Il server di gioco WebSocket non è contenuto nello ZIP originale. Il Dash qui aumenta temporaneamente il target di movimento inviato dal client; il server resta autorevole e può applicare i propri limiti di velocità. Per un Dash con fisica server-side dedicata, aggiungere la corrispondente regola nel server Railway.

## Variabili ambiente API

`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`, `AUTH_SECRET`, `SERVER_KEY`.

## Preview

Le immagini `../preview_settings.png` e `../preview_dash.png` sono state generate durante l'integrazione per verificare visivamente il nuovo layer senza modificare il layout originale.
