import React from "react";
import { Link } from "react-router-dom";
import PageLayout from "./../../components/PageLayout/";

const Dashboard = () => {
    return (
        <>
            <PageLayout
                name="What would you like to do today?"
                text=""
                style={{ marginTop: "4%" }}
            >
                <div className="row align-items-md-stretch">
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
                            <Link to="/dashboard/server">
                                <button
                                    className="btn btn-outline-light"
                                    type="button"
                                >
                                    List servers
                                </button>
                            </Link>
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
                            <Link to="/dashboard/server/add">
                                <button
                                    className="btn btn-outline-light"
                                    type="button"
                                >
                                    Add servers
                                </button>
                            </Link>
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
                            <Link to="/dashboard/api">
                                <button
                                    className="btn btn-outline-light"
                                    type="button"
                                >
                                    Modify existing API
                                </button>
                            </Link>
                            <Link to="/dashboard/api/add">
                                <button
                                    className="btn btn-outline-light"
                                    type="button"
                                    style={{ marginLeft: "5px" }}
                                >
                                    Add new API
                                </button>
                            </Link>
                        </div>
                    </div>
                    <div className="col-sm-3"></div>
                </div>
            </PageLayout>
        </>
    );
};

export default Dashboard;
