import React from "react";

const PowerButtons = (props) => {
    return (
        <>
            {props.type == "with_text" ? (
                <>
                    <button
                        className="btn btn-success"
                        data-db_id={props.id}
                        data-action="start"
                        onClick={props.handlePowerAction}
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
                        onClick={props.handlePowerAction}
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
                        onClick={props.handlePowerAction}
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
                        onClick={props.handlePowerAction}
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
                        onClick={props.handlePowerAction}
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
                        onClick={props.handlePowerAction}
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
                        onClick={props.handlePowerAction}
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
                        onClick={props.handlePowerAction}
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
