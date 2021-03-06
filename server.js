// If you get a certificate has expired error even if you have one
// process.env.NODE_TLS_REJECT_UNAUTHORIZED = "0";

var express = require("express");
var app = express();
const axios = require("axios");
const fs = require("fs");
// Servers
const http = require("http");
const https = require("https");
require("dotenv").config();
// Websocket (client and server)
var expressWs;
const wsClient = require("ws");

if (process.env.WEBSOCKET_TYPE == "ws://") {
    const httpServer = http.createServer(app);

    httpServer.listen(3000);
    expressWs = require("express-ws")(app, httpServer);
} else {
    const privateKey = fs.readFileSync(process.env.PRIVATE_KEY, "utf8");
    const certificate = fs.readFileSync(process.env.CERTIFICATE, "utf8");
    const ca = fs.readFileSync(process.env.CHAIN, "utf8");
    const credentials = {
        key: privateKey,
        cert: certificate,
        ca: ca,
    };
    const httpsServer = https.createServer(credentials, app);

    httpsServer.listen(3000);
    expressWs = require("express-ws")(app, httpsServer);
}

app.ws("/pterodactyl/console", async (socket, req) => {
    let db_id = req.query.db_id;
    let api_token = req.query.api_token;
    let foreign_socket;
    let foreign_token;
    let foreign_origin;
    let result;

    const getForeignSocket = async (db_id, api_token) => {
        await axios
            .get(`${process.env.APP_URL}/api/server/${db_id}`, {
                headers: {
                    Authorization: `Bearer ${api_token}`,
                },
            })
            .then((res) => {
                if (res.data[0].status == 200) {
                    foreign_socket = res.data["0"].socket;
                    foreign_token = res.data["0"].token;
                    foreign_origin = res.data["0"].origin;
                    result = true;
                } else {
                    var message = JSON.stringify({
                        event: `error`,
                        args: [`${res.data["0"].error_message}`],
                    });
                    socket.send(message);
                    result = false;
                }
            })
            .catch((err) => {
                result = false;
            });
    };

    const returnForeignSocket = (foreign_socket, foreign_origin) => {
        const serverWS = new wsClient(foreign_socket, {
            headers: {
                Origin: foreign_origin,
            },
        });
        return serverWS;
    };

    const useForeignSocket = async (serverWs) => {
        serverWS.on("open", () => {
            serverWS.send(`{"event":"auth","args":["${foreign_token}"]}`);
            setTimeout(() => {
                serverWS.send('{"event":"send logs","args":[null]}');
            }, 1000);
            setInterval(() => {
                serverWS.send('{"event":"send stats","args":[null]}');
            }, 3000);
            socket.on("message", (msg) => {
                let se = JSON.parse(msg.toString());
                var message = JSON.stringify({
                    event: se.event,
                    args: [`${se.args[0]}`],
                });
                serverWS.send(message);
            });
            serverWS.on("message", (data) => {
                let pa = JSON.parse(data.toString());
                if (pa.event == "console output") {
                    var message = JSON.stringify({
                        event: "console output",
                        args: [`${pa.args[0]}`],
                    });
                    socket.send(message);
                }
                if (pa.event == "stats") {
                    var message = JSON.stringify({
                        event: "stats",
                        args: [`${pa.args[0]}`],
                    });
                    socket.send(message);
                }
                if (pa.event == "status") {
                    var message = JSON.stringify({
                        event: "status",
                        args: [`${pa.args[0]}`],
                    });
                    socket.send(message);
                }
                if (pa.event == "token expiring") {
                    async () => {
                        serverWS.close();
                        await getForeignSocket(db_id, api_token);
                        var serverWS = returnForeignSocket(
                            foreign_socket,
                            foreign_origin
                        );
                        useForeignSocket(serverWS);
                    };
                }
                if (pa.event == "jwt error") {
                    var message = JSON.stringify({
                        event: "error",
                        args: [`ERR_JWT_NOT_VALID`],
                    });
                    socket.send(message);
                }
            });
        });
    };

    await getForeignSocket(db_id, api_token);
    if (result == true) {
        var serverWS = returnForeignSocket(foreign_socket, foreign_origin);
        useForeignSocket(serverWS);
    } else {
        socket.terminate();
    }
});
