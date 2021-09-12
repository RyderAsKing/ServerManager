import React, { useState, useEffect } from "react";
import { GetServerInformation } from "../../../plugins/ApiCalls";

const DashboardServerCurrent = (props) => {
    const [loading, setLoading] = useState(true);
    const [serverInformation, setServerInformation] = useState(null);
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
        }
    }, [serverInformation]);

    const handlePowerAction = (e) => {
        console.log(e);
    };

    var basic;

    if (serverInformation != null) {
        basic = (
            <div className="col-sm-12">
                <div
                    className="card bg-dark"
                    style={{
                        margin: "5px",
                        border: "1px solid white",
                        marginTop: "5%",
                    }}
                >
                    <div className="card-body">
                        <h5 className="card-title">
                            <span className="offline"></span>
                            <span className="online"></span>
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
                            ></i>
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
                            ></i>
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
                            ></i>
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
                            ></i>
                        </button>
                    </div>
                </div>
            </div>
        );
    }

    return (
        <>
            {loading == true ? (
                <div className="text-center">
                    <div className="spinner-border" role="status">
                        <span className="sr-only">Loading...</span>
                    </div>
                </div>
            ) : (
                basic
            )}
        </>
    );
};

export default DashboardServerCurrent;
