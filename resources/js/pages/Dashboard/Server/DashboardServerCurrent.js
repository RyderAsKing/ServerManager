import React, { useState, useEffect } from "react";
import { GetServerInformation } from "../../../plugins/ApiCalls";
import { toast } from "react-toastify";
import { PowerActions } from "../../../plugins/ApiCalls";

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
            }
        }
    }, [serverInformation]);

    const handlePowerAction = (e) => {
        const powerNotification = toast.loading("Sending power action", {
            position: "bottom-right",
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true,
            progress: undefined,
        });

        var response = PowerActions(
            e.target.dataset.db_id,
            e.target.dataset.action
        );
        response.then((response) => {
            if (response.status != 200) {
                toast.update(powerNotification, {
                    render: response.error_message,
                    type: "error",
                    isLoading: false,
                    autoClose: 5000,
                });
            } else {
                toast.update(powerNotification, {
                    render: response.message,
                    type: "success",
                    isLoading: false,
                    autoClose: 5000,
                });
            }
        });
    };
    var common;
    var server_type_0;
    var server_type_1;
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
                        <button
                            className="btn btn-success"
                            data-db_id={serverInformation.id}
                            data-action="start"
                            onClick={handlePowerAction}
                        >
                            <i
                                className="fas fa-play text-white"
                                data-db_id={serverInformation.id}
                                data-action="start"
                            ></i>{" "}
                            <span style={{ color: "white" }}>Start</span>
                        </button>
                        <button
                            className="btn btn-danger"
                            data-db_id={serverInformation.id}
                            data-action="stop"
                            onClick={handlePowerAction}
                            style={{ marginLeft: "2px" }}
                        >
                            <i
                                className="fas fa-stop text-white"
                                data-db_id={serverInformation.id}
                                data-action="stop"
                            ></i>{" "}
                            <span style={{ color: "white" }}>Stop</span>
                        </button>
                        <button
                            className="btn btn-warning"
                            data-db_id={serverInformation.id}
                            data-action="restart"
                            onClick={handlePowerAction}
                            style={{ marginLeft: "2px" }}
                        >
                            <i
                                className="fas fa-redo text-black"
                                data-db_id={serverInformation.id}
                                data-action="restart"
                            ></i>{" "}
                            <span style={{ color: "black" }}>Restart</span>
                        </button>
                        <button
                            to=""
                            className="btn btn-danger"
                            data-db_id={serverInformation.id}
                            data-action="kill"
                            onClick={handlePowerAction}
                            style={{ marginLeft: "2px" }}
                        >
                            <i
                                className="fas fa-power-off text-white"
                                data-db_id={serverInformation.id}
                                data-action="kill"
                            ></i>{" "}
                            <span style={{ color: "white" }}>Power Off</span>
                        </button>
                    </div>
                </div>
            </div>
        );

        server_type_0 = (
            <>
                <div className="row">
                    <div className="col-sm-4">
                        <div
                            className="card bg-dark"
                            style={{ margin: "5px", border: "1px solid white" }}
                        >
                            <div className="card-body">
                                <h5 className="card-title">Bandwidth Usage</h5>
                                <p className="card-text">
                                    {serverInformation[0].bandwidth_used} GB
                                </p>
                            </div>
                        </div>
                    </div>
                    <div className="col-sm-4">
                        <div
                            className="card bg-dark"
                            style={{ margin: "5px", border: "1px solid white" }}
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
                            style={{ margin: "5px", border: "1px solid white" }}
                        >
                            <div className="card-body">
                                <h5 className="card-title">Total Storage</h5>
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
                            style={{ margin: "5px", border: "1px solid white" }}
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
                                {serverInformation[0].is_vnc_available == 1 ? (
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
    return (
        <>
            <h3 className="text-center">Manage Server</h3>
            <p className="text-center">
                Perform powerful one click actions on the server with ease
            </p>
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
                        {serverInformation.server_type == 0
                            ? server_type_0
                            : serverInformation.server_type == 1
                            ? server_type_1
                            : server_type_1}
                    </div>
                )}
            </div>
        </>
    );
};

export default DashboardServerCurrent;
