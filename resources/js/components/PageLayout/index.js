import React from "react";

const PageLayout = (props) => {
    return (
        <>
            <div className="container text-white" style={props.style}>
                <h3 className="text-center">{props.name}</h3>
                <p className="text-center">{props.text}</p>
                {props.children}
            </div>
        </>
    );
};

export default PageLayout;
