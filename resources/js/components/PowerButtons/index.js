import React from "react";
import { toast } from "react-toastify";
import { PowerActions } from "../../plugins/ApiCalls";
const PowerButtons = (props) => {
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
    return (
        <>
            {props.type == "with_text" ? (
                <>
                    <button
                        className="btn btn-success"
                        data-db_id={props.id}
                        data-action="start"
                        onClick={handlePowerAction}
                    >
                        <i
                            className="fas fa-play text-white"
                            data-db_id={props.id}
                            data-action="start"
                        ></i>{" "}
                        <span
                            style={{ color: "white" }}
                            data-db_id={props.id}
                            data-action="start"
                        >
                            Start
                        </span>
                    </button>
                    <button
                        className="btn btn-danger"
                        data-db_id={props.id}
                        data-action="stop"
                        onClick={handlePowerAction}
                        style={{ marginLeft: "2px" }}
                    >
                        <i
                            className="fas fa-stop text-white"
                            data-db_id={props.id}
                            data-action="stop"
                        ></i>{" "}
                        <span
                            style={{ color: "white" }}
                            data-db_id={props.id}
                            data-action="stop"
                        >
                            Stop
                        </span>
                    </button>
                    <button
                        className="btn btn-warning"
                        data-db_id={props.id}
                        data-action="restart"
                        onClick={handlePowerAction}
                        style={{ marginLeft: "2px" }}
                    >
                        <i
                            className="fas fa-redo text-black"
                            data-db_id={props.id}
                            data-action="restart"
                        ></i>{" "}
                        <span
                            style={{ color: "black" }}
                            data-db_id={props.id}
                            data-action="restart"
                        >
                            Restart
                        </span>
                    </button>
                    <button
                        className="btn btn-danger"
                        data-db_id={props.id}
                        data-action="kill"
                        onClick={handlePowerAction}
                        style={{ marginLeft: "2px" }}
                    >
                        <i
                            className="fas fa-power-off text-white"
                            data-db_id={props.id}
                            data-action="kill"
                        ></i>{" "}
                        <span
                            style={{ color: "white" }}
                            data-db_id={props.id}
                            data-action="kill"
                        >
                            Power Off
                        </span>
                    </button>
                </>
            ) : (
                <>
                    <button
                        className="btn btn-success"
                        data-db_id={props.id}
                        data-action="start"
                        onClick={handlePowerAction}
                    >
                        <i
                            className="fas fa-play text-white"
                            data-db_id={props.id}
                            data-action="start"
                        ></i>
                    </button>
                    <button
                        className="btn btn-danger"
                        data-db_id={props.id}
                        data-action="stop"
                        onClick={handlePowerAction}
                        style={{ marginLeft: "2px" }}
                    >
                        <i
                            className="fas fa-stop text-white"
                            data-db_id={props.id}
                            data-action="stop"
                        ></i>
                    </button>
                    <button
                        className="btn btn-warning"
                        data-db_id={props.id}
                        data-action="restart"
                        onClick={handlePowerAction}
                        style={{ marginLeft: "2px" }}
                    >
                        <i
                            className="fas fa-redo text-black"
                            data-db_id={props.id}
                            data-action="restart"
                        ></i>
                    </button>
                    <button
                        to=""
                        className="btn btn-danger"
                        data-db_id={props.id}
                        data-action="kill"
                        onClick={handlePowerAction}
                        style={{ marginLeft: "2px" }}
                    >
                        <i
                            className="fas fa-power-off text-white"
                            data-db_id={props.id}
                            data-action="kill"
                        ></i>
                    </button>
                </>
            )}
        </>
    );
};

export default PowerButtons;
