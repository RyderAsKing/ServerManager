var express = require("express");
var app = express();
var expressWs = require("express-ws")(app);
const wsClient = require("ws");
const axios = require("axios");
require("dotenv").config();

app.get("/pterodactyl/console", function (req, res, next) {
    console.log("get route", req.testing);
    res.end();
});

app.ws("/pterodactyl/console", async (socket, req) => {
    let db_id = req.query.db_id;
    let api_token = req.query.api_token;
    let foreign_socket;
    let foreign_token;
    let foreign_origin;

    const getForeignSocket = async (db_id, api_token) => {
        await axios
            .get(`${process.env.APP_URL}/api/server/${db_id}`, {
                headers: {
                    Authorization: `Bearer ${api_token}`,
                },
            })
            .then((res) => {
                if (res.status == 200) {
                    foreign_socket = res.data["0"].socket;
                    foreign_token = res.data["0"].token;
                    foreign_origin = res.data["0"].origin;
                }
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
            socket.on("message", (msg) => {
                let se = JSON.parse(msg.toString());
                serverWS.send(
                    `{"event":"${se.event}","args":["${se.args[0]}"]}`
                );
            });
            serverWS.on("message", (data) => {
                let pa = JSON.parse(data.toString());
                if (pa.event == "console output") {
                    var message = JSON.stringify(
                        `{"event": "console output", "args": ["${pa.args[0]}"]}`
                    );
                    socket.send(message);
                }
                if (pa.event == "stats") {
                    var message = JSON.stringify(
                        `{"event": "stats", "args": ["${pa.args[0]}"]}`
                    );
                    socket.send(message);
                }
                if (pa.event == "status") {
                    var message = JSON.stringify(
                        `{"event": "status", "args": ["${pa.args[0]}"]}`
                    );
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
                    socket.send("ERR_JWT_NOT_VALID");
                }
            });
        });
    };

    await getForeignSocket(db_id, api_token);
    var serverWS = returnForeignSocket(foreign_socket, foreign_origin);
    useForeignSocket(serverWS);
});

app.listen(3000);
