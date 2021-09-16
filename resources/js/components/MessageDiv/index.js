import React from "react";
import { Link } from "react-router-dom";

const MessageDiv = (props) => {
    return (
        <div
            className="p-5 text-white bg-dark rounded-3"
            style={{ textAlign: "center", marginTop: "10%" }}
        >
            <h4>{props.name}</h4>
            <p>{props.text}</p>
            <Link to={props.buttonUrl}>
                <button className="btn btn-outline-light" type="button">
                    {props.buttonText}
                </button>
            </Link>
        </div>
    );
};

export default MessageDiv;
