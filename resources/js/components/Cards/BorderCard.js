import React from "react";

const BorderCard = (props) => {
    return (
        <div
            className="card bg-dark"
            style={{
                margin: "5px",
                border: "1px solid white",
            }}
        >
            {props.children}
        </div>
    );
};

export default BorderCard;
