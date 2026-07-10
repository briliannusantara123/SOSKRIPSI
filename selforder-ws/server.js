require('dotenv').config();

const WebSocket = require('ws');
const axios = require('axios');

const PORT = 3001;

const wss = new WebSocket.Server({
    port: PORT
});

console.log("🚀 WebSocket running on port " + PORT);

/* =========================
   HELPER SEND
========================= */
function send(ws, data) {

    if (ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify(data));
    }

}

/* =========================
   CONNECTION
========================= */
wss.on('connection', (ws) => {

    console.log("✅ Client connected");

    /* =========================
       HEARTBEAT
    ========================= */
    ws.isAlive = true;

    ws.on('pong', () => {
        ws.isAlive = true;
    });

    /* =========================
       MESSAGE
    ========================= */
    ws.on('message', async (msg) => {

        console.log("📥 MESSAGE:", msg.toString());

        try {

            const data = JSON.parse(msg);

            /* =========================
               TYPING ON
            ========================= */
            send(ws, {
                type: 'typing',
                status: true
            });

            console.log("📡 CALLING CODEIGNITER API...");

            /* =========================
               CALL CI API
            ========================= */
            const res = await axios.post(
                'http://localhost/soskripsi/index.php/ordermakanan/ai_chat',
                new URLSearchParams({
                    message: data.message,
                    table_id: data.table_id || 0
                }),
                {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    timeout: 20000
                }
            );

            console.log("✅ RESPONSE:", res.data);

            /* =========================
               TYPING OFF
            ========================= */
            send(ws, {
                type: 'typing',
                status: false
            });

            /* =========================
               VALIDASI RESPONSE
            ========================= */
            console.log("RAW RESPONSE:");
            console.log(res.data);

            if (typeof res.data !== 'object') {
                throw new Error("Response bukan JSON");
            }

            const result = res.data;

            if (!result.reply) {
                throw new Error("Reply kosong dari backend");
            }

            /* =========================
               STREAM CHAT
            ========================= */
            const words = result.reply.split(" ");

            for (const word of words) {

                send(ws, {
                    type: 'stream',
                    chunk: word + " "
                });

                await new Promise(r => setTimeout(r, 25));

            }

            /* =========================
               DONE
            ========================= */
            send(ws, {
                type: 'done',
                menus: result.menus || []
            });

        } catch (err) {

            console.error("❌ ERROR:", err.message);

            send(ws, {
                type: 'error',
                message: err.message
            });

        }

    });

    /* =========================
       CLOSE
    ========================= */
    ws.on('close', () => {

        console.log("❌ Client disconnected");

    });

});

/* =========================
   HEARTBEAT CHECK
========================= */
setInterval(() => {

    wss.clients.forEach((ws) => {

        if (!ws.isAlive) {

            console.log("⚠️ Dead connection");

            return ws.terminate();

        }

        ws.isAlive = false;

        ws.ping();

    });

}, 30000);