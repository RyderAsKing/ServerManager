import React from "react";

const Dashboard = () => {
    return (
        <>
            <div className="container">
                <div
                    className="row align-items-md-stretch"
                    style={{ marginTop: "5%" }}
                >
                    <h3 style={{ textAlign: "center" }}>
                        What would you like to do today?
                    </h3>
                    <div className="col-md-6" style={{ marginTop: "15px" }}>
                        <div
                            className="p-5 text-white bg-dark rounded-3"
                            style={{ border: "1px solid #E3F2FD" }}
                        >
                            <h2>Manage existing servers</h2>
                            <p>
                                Perform powerful one click actions on servers
                                with ease
                            </p>
                            <a href="">
                                <button
                                    className="btn btn-outline-light"
                                    type="button"
                                >
                                    List servers
                                </button>
                            </a>
                        </div>
                    </div>
                    <div className="col-md-6" style={{ marginTop: "15px" }}>
                        <div
                            className="p-5 text-white bg-dark rounded-3"
                            style={{ border: "1px solid #E3F2FD" }}
                        >
                            <h2>Add new servers</h2>
                            <p>
                                Add new servers to our database so that you can
                                perform actions on them.
                            </p>
                            <a href="">
                                <button
                                    className="btn btn-outline-light"
                                    type="button"
                                >
                                    Add servers
                                </button>
                            </a>
                        </div>
                    </div>
                    <div className="col-sm-3"></div>
                    <div className="col-md-6" style={{ marginTop: "15px" }}>
                        <div
                            className="p-5 text-white bg-dark rounded-3"
                            style={{ border: "1px solid #E3F2FD" }}
                        >
                            <h2>Manage API</h2>
                            <p>Add, remove or modify existing API keys.</p>
                            <a href="">
                                <button
                                    className="btn btn-outline-light"
                                    type="button"
                                >
                                    Modify existing API
                                </button>
                            </a>
                            <a href="">
                                <button
                                    className="btn btn-outline-light"
                                    type="button"
                                >
                                    Add new API
                                </button>
                            </a>
                        </div>
                    </div>
                    <div className="col-sm-3"></div>
                </div>
            </div>
        </>
    );
};

export default Dashboard;
