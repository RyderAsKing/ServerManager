import React, { useState, useEffect } from "react";
import { GetServerInformation } from "../../../plugins/ApiCalls";
import { toast } from "react-toastify";
import PageLayout from "./../../../components/PageLayout/";
import PowerButtons from "./../../../components/PowerButtons/";

const DashboardServerCurrent = (props) => {
    const [loading, setLoading] = useState(true);
    const [serverInformation, setServerInformation] = useState(null);
    const [serverStatus, setServerStatus] = useState(null);

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
                // Pterodactyl (do nothing)
                var apiToken = axios.defaults.headers.common.Authorization;
                var apiToken = apiToken.replace("Bearer ", "");
                const uri = encodeURI(
                    `ws://${websocket_url}/pterodactyl/console/?db_id=${serverInformation.id}&api_token=${apiToken}`
                );
                const websocket = new WebSocket(uri);
                websocket.onmessage = (event) => {
                    // TODO
                    const data = JSON.parse(event.data);
                    console.log(data);

                    if (data.event == "stats") {
                    }
                    if (data.event == "console output") {
                    }
                    if (data.event == "status") {
                    }
                };
            }
        }
    }, [serverInformation]);

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
                <>
                    <div
                        className="container"
                        style={{
                            margin: "5px",
                            border: "1px solid white",
                        }}
                    >
                        <div id="terminal-body">
                            <div
                                id="terminal"
                                style={{
                                    maxHeight: "none !important",
                                }}
                            ></div>
                            <div
                                id="terminal_input"
                                className="form-group no-margin"
                            >
                                <div className="input-group">
                                    <div className="input-group-addon terminal_input--prompt">
                                        container:~/$
                                    </div>
                                    <input
                                        type="text"
                                        className="form-control terminal_input--input text-white"
                                        style={{ marginLeft: "5px" }}
                                    />
                                </div>
                            </div>
                            <div
                                id="terminalNotify"
                                className="terminal-notify hidden"
                            >
                                <i className="fa fa-bell"></i>
                            </div>
                        </div>
                    </div>
                </>
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
                            <div className="spinner-border" role="status">
                                <span className="sr-only">Loading...</span>
                            </div>
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
