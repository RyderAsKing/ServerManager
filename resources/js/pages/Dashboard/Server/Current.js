import React, { useState, useEffect } from "react";
import { GetServerInformation } from "../../../plugins/ApiCalls";
import PageLayout from "../../../components/PageLayout";
import PowerButtons from "../../../components/PowerButtons";
import BorderCard from "../../../components/Cards/BorderCard";
import Console from "../../../components/Console/index";
// Charts
import Memory from "../../../components/Charts/Memory";
import CPU from "../../../components/Charts/Cpu";
import Network from "../../../components/Charts/Network";
// Notification
import { ErrorNotification } from "../../../plugins/Notification";
// Other
import LargeSpinner from "./../../../components/Spinners/LargeSpinner";
const DashboardServerCurrent = (props) => {
    const [loading, setLoading] = useState(true);
    const [serverInformation, setServerInformation] = useState(null);
    const [serverStatus, setServerStatus] = useState(null);
    const [maxResources, setMaxResources] = useState(null);

    const timeformat = (date) => {
        var h = date.getHours();
        var m = date.getMinutes();
        var s = date.getSeconds();
        var x = h >= 12 ? "" : "";
        h = h % 12;
        h = h ? h : 12;
        m = m < 10 ? "0" + m : m;
        var mytime = h + ":" + m + ":" + s;
        return mytime;
    };
    const [chartData, setChartData] = useState({
        memoryData: Array(25).fill(0.01),
        cpuData: Array(25).fill(0.01),
        diskData: Array(25).fill(0.01),
        networkData: { tx: Array(5).fill(0.01), rx: Array(5).fill(0.01) },
        timeData: Array(25).fill(timeformat(new Date())),
    });
    const [consoleLogs, setConsoleLogs] = useState([
        "\u001b[1m\u001b[33mcontainer~/ \u001b[0m" +
            "Server manager (https://github.com/RyderAsKing/ServerManager)",
    ]);
    const [terminalInput, setTerminalInput] = useState("");
    var [websocket, setWebSocket] = useState(null);
    var currentServer = props.match.params.id;

    useEffect(() => {
        if (loading == true && serverInformation == null) {
            var response = GetServerInformation(currentServer);
            response.then((response) => {
                setServerInformation(response);
            });
        }
    }, [loading]);

    useEffect(() => {
        if (serverInformation != null) {
            setLoading(false);
            if (serverInformation.server_type == 0) {
                setServerStatus(serverInformation[0].status);
            }
            if (serverInformation.server_type == 1) {
                setMaxResources({
                    memory: serverInformation[0].memory,
                    cpu: serverInformation[0].cpu,
                    disk: serverInformation[0].disk,
                });
                var apiToken = axios.defaults.headers.common.Authorization;
                var apiToken = apiToken.replace("Bearer ", "");
                const uri = encodeURI(
                    `${websocket_url}/pterodactyl/console/?db_id=${serverInformation.id}&api_token=${apiToken}`
                );
                const websocket = new WebSocket(uri);
                websocket.onopen = (event) => {
                    setWebSocket(websocket);
                };
                websocket.onmessage = (event) => {
                    const data = JSON.parse(event.data);

                    if (data.event == "stats") {
                        // Conver the string into JSON
                        var stats = JSON.parse(data.args[0]);

                        var memory = chartData.memoryData;
                        var cpu = chartData.cpuData;
                        var disk = chartData.diskData;

                        var tx = chartData.networkData.tx;
                        var rx = chartData.networkData.rx;

                        var time = chartData.timeData;
                        if (memory.length > 25) {
                            memory.shift();
                        }
                        if (cpu.length > 25) {
                            cpu.shift();
                        }
                        if (disk.length > 25) {
                            disk.shift();
                        }
                        if (tx.length > 4) {
                            tx.shift();
                        }
                        if (rx.length > 4) {
                            rx.shift();
                        }
                        if (time.length > 25) {
                            time.shift();
                        }
                        memory.push(
                            Math.round(stats.memory_bytes / 1024 / 1024)
                        );
                        cpu.push(Math.round(stats.cpu_absolute));
                        disk.push(Math.round(stats.disk_bytes / 1024 / 1024));
                        tx.push(stats.network.tx_bytes / 1024 / 1024);
                        rx.push(stats.network.rx_bytes / 1024 / 1024);

                        time.push(timeformat(new Date()));
                        var tempChartData = {
                            ...chartData,
                            memoryData: memory,
                            cpuData: cpu,
                            diskData: disk,
                            networkData: { tx: tx, rx: rx },
                            timeData: time,
                        };
                        setChartData(tempChartData);
                    }
                    if (data.event == "console output") {
                        var logs = data.args[0];
                        setConsoleLogs([logs]);
                    }
                    if (data.event == "status") {
                        if (data.args[0] == "running") {
                            setServerStatus(1);
                        } else {
                            setServerStatus(0);
                        }
                        setConsoleLogs([
                            "\u001b[1m\u001b[33mcontainer~/ \u001b[0m" +
                                `Server marked as ${data.args[0]}`,
                        ]);
                    }
                    if (data.event == "error") {
                        ErrorNotification(data.args[0]);
                    }
                };
            }
        }
    }, [serverInformation]);

    const terminalInputHandler = (e) => {
        setTerminalInput(e.target.value);
    };

    const keyDownInputHandler = (e) => {
        if (e.key === "Enter") {
            var message = JSON.stringify({
                event: "send command",
                args: [`${terminalInput}`],
            });
            websocket.send(message);
            setTerminalInput("");
        }
    };

    var common;
    var container;
    if (serverInformation != null) {
        common = (
            <div className="col-sm-12">
                <div
                    className="card bg-dark"
                    style={{
                        margin: "5px",
                        border: "1px solid white",
                    }}
                >
                    <div className="card-body">
                        <h5 className="card-title">
                            {serverStatus != 1 &&
                            serverStatus != 0 &&
                            serverStatus == null ? (
                                <>
                                    ( <span className="loading"></span> )
                                </>
                            ) : serverStatus == 0 ? (
                                <>
                                    ( <span className="offline"></span> )
                                </>
                            ) : (
                                <>
                                    ( <span className="online"></span> )
                                </>
                            )}{" "}
                            {serverInformation.hostname}{" "}
                            {serverInformation.server_id} -{" "}
                            <code>{serverInformation.ipv4}</code>
                        </h5>
                        <p className="card-text"></p>
                        <PowerButtons
                            type="with_text"
                            id={serverInformation.id}
                        ></PowerButtons>
                    </div>
                </div>
            </div>
        );

        if (serverInformation.server_type == 0) {
            container = (
                <>
                    <div
                        className="modal fade"
                        id="changeHostname"
                        role="modal"
                        tabIndex={-1}
                        aria-labelledby="changeHostname"
                        aria-hidden="true"
                    >
                        <div className="modal-dialog text-black">
                            <form method="post">
                                <div className="modal-content">
                                    <div className="modal-header">
                                        <h5
                                            className="modal-title"
                                            id="staticBackdropLabel"
                                        >
                                            Change Hostname
                                        </h5>
                                        <button
                                            type="button"
                                            className="btn-close"
                                            data-bs-dismiss="modal"
                                            aria-label="Close"
                                        />
                                    </div>
                                    <div className="modal-body">
                                        <div className="form-group">
                                            <label htmlFor="hostname">
                                                New Hostname
                                            </label>
                                            <input
                                                type="string"
                                                className="form-control"
                                                id="hostname"
                                                aria-describedby="emailHelp"
                                                placeholder="Enter new hostname"
                                                name="hostname"
                                            />
                                        </div>
                                    </div>
                                    <div className="modal-footer">
                                        <button
                                            type="button"
                                            className="btn btn-secondary"
                                            data-bs-dismiss="modal"
                                        >
                                            Close
                                        </button>
                                        <button
                                            type="submit"
                                            className="btn btn-primary"
                                        >
                                            Change hostname
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div
                        className="modal fade"
                        id="changePassword"
                        role="modal"
                        tabIndex={-1}
                        aria-labelledby="changePassword"
                        aria-hidden="true"
                    >
                        <div className="modal-dialog">
                            <form method="post">
                                <div className="modal-content text-black">
                                    <div className="modal-header">
                                        <h5
                                            className="modal-title"
                                            id="staticBackdropLabel"
                                        >
                                            Change password
                                        </h5>
                                        <button
                                            type="button"
                                            className="btn-close"
                                            data-bs-dismiss="modal"
                                            aria-label="Close"
                                        />
                                    </div>
                                    <div className="modal-body">
                                        <div className="form-group">
                                            <label htmlFor="password">
                                                New Password
                                            </label>
                                            <input
                                                type="string"
                                                className="form-control"
                                                id="password"
                                                placeholder="Enter new password"
                                                name="password"
                                            />
                                        </div>
                                    </div>
                                    <div className="modal-footer">
                                        <button
                                            type="button"
                                            className="btn btn-secondary"
                                            data-bs-dismiss="modal"
                                        >
                                            Close
                                        </button>
                                        <button
                                            type="submit"
                                            className="btn btn-primary text-white"
                                        >
                                            Change password
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-sm-4">
                            <div
                                className="card bg-dark"
                                style={{
                                    margin: "5px",
                                    border: "1px solid white",
                                }}
                            >
                                <div className="card-body">
                                    <h5 className="card-title">
                                        Bandwidth Usage
                                    </h5>
                                    <p className="card-text">
                                        {serverInformation[0].bandwidth_used} GB
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div className="col-sm-4">
                            <div
                                className="card bg-dark"
                                style={{
                                    margin: "5px",
                                    border: "1px solid white",
                                }}
                            >
                                <div className="card-body">
                                    <h5 className="card-title">Total Cores</h5>
                                    <p className="card-text">
                                        {serverInformation[0].cores} Cores
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div className="col-sm-4">
                            <div
                                className="card bg-dark"
                                style={{
                                    margin: "5px",
                                    border: "1px solid white",
                                }}
                            >
                                <div className="card-body">
                                    <h5 className="card-title">
                                        Total Storage
                                    </h5>
                                    <p className="card-text">
                                        {serverInformation[0].storage} GB
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-sm-12">
                            <div
                                className="card bg-dark"
                                style={{
                                    margin: "5px",
                                    border: "1px solid white",
                                }}
                            >
                                <div className="card-body">
                                    <h5 className="card-title">
                                        More actions (Virtualizor specific)
                                    </h5>
                                    <button
                                        type="button"
                                        className="btn btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#changeHostname"
                                    >
                                        <i className="fas fa-file-signature text-white"></i>
                                        <span style={{ color: "white" }}>
                                            {" "}
                                            Change Hostname
                                        </span>
                                    </button>
                                    <button
                                        className="btn btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#changePassword"
                                        style={{ marginLeft: "5px" }}
                                    >
                                        <i className="fas fa-key text-white"></i>{" "}
                                        <span style={{ color: "white" }}>
                                            Change Password
                                        </span>
                                    </button>
                                    {serverInformation[0].is_vnc_available ==
                                    1 ? (
                                        <button
                                            className="btn btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#vncInformation"
                                            style={{ marginLeft: "5px" }}
                                        >
                                            <i className="fas fa-desktop text-white"></i>{" "}
                                            <span style={{ color: "white" }}>
                                                VNC Information
                                            </span>
                                        </button>
                                    ) : (
                                        ""
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </>
            );
        }
        if (serverInformation.server_type == 1) {
            container = (
                <div className="row">
                    {websocket == null ? (
                        <div
                            className="text-center"
                            style={{ marginTop: "5%" }}
                        >
                            <LargeSpinner></LargeSpinner>
                        </div>
                    ) : (
                        <>
                            <div className="col-lg-8 col-md-12">
                                <BorderCard>
                                    <div className="card-body">
                                        <p className="card-text">
                                            CPU -{" "}
                                            <code>
                                                {chartData.cpuData[25]} /{" "}
                                                {maxResources.cpu} %
                                            </code>
                                            <br />
                                            RAM -{" "}
                                            <code>
                                                {chartData.memoryData[25]} /{" "}
                                                {maxResources.memory} MB
                                            </code>
                                            <br />
                                            Disk -{" "}
                                            <code>
                                                {chartData.diskData[25]} /{" "}
                                                {maxResources.disk} MB
                                            </code>
                                        </p>
                                    </div>
                                </BorderCard>
                                <Console
                                    data={consoleLogs}
                                    style={{
                                        border: "1px solid white",
                                        margin: "5px",
                                    }}
                                    height="100%"
                                    width="100%"
                                    inputEvent={terminalInputHandler}
                                    inputValue={terminalInput}
                                    keyEvent={keyDownInputHandler}
                                ></Console>
                            </div>
                            <div className="col-lg-4 col-md-12">
                                <BorderCard>
                                    <Memory
                                        data={chartData.memoryData}
                                        time={chartData.timeData}
                                    ></Memory>
                                </BorderCard>
                                <BorderCard>
                                    <CPU
                                        data={chartData.cpuData}
                                        time={chartData.timeData}
                                    ></CPU>
                                </BorderCard>
                                <BorderCard>
                                    <Network
                                        data={chartData.networkData}
                                    ></Network>
                                </BorderCard>
                            </div>
                        </>
                    )}
                </div>
            );
        }
    }
    return (
        <>
            <PageLayout
                name="Manage Server"
                text="Perform powerful one click actions on the server with ease"
            >
                <div className="container">
                    {loading == true || loading == null ? (
                        <div className="text-center">
                            <LargeSpinner></LargeSpinner>
                        </div>
                    ) : (
                        <div className="container">
                            {common}
                            {container}
                        </div>
                    )}
                </div>
            </PageLayout>
        </>
    );
};

export default DashboardServerCurrent;
