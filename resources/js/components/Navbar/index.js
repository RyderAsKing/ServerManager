import React from "react";
import { Link } from "react-router-dom";

const Navbar = () => {
    return (
        <nav
            className="navbar navbar-expand-lg navbar-light"
            style={{ backgroundColor: "#e3f2fd", marginBottom: "20px" }}
        >
            <div className="container-fluid">
                <Link className="navbar-brand" to="/">
                    Server Manager
                </Link>
                <button
                    className="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarColor03"
                    aria-controls="navbarColor03"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span className="navbar-toggler-icon"></span>
                </button>
                <div className="collapse navbar-collapse" id="navbarColor03">
                    <ul className="navbar-nav me-auto mb-2 mb-lg-0">
                        <li className="nav-item">
                            <Link className="nav-link" to="/dashboard">
                                Dashboard
                            </Link>
                        </li>
                        <li className="nav-item">
                            <Link className="nav-link" to="/dashboard/server">
                                List servers
                            </Link>
                        </li>
                        <li className="nav-item">
                            <Link className="nav-link" to="/dashboard/api">
                                List API's
                            </Link>
                        </li>
                    </ul>
                    <div className="ml-auto">
                        <Link to="">
                            <button className="btn btn-outline-primary">
                                Login
                            </button>
                        </Link>
                        <Link to="">
                            <button
                                className="btn btn-outline-primary"
                                style={{ marginLeft: "5px" }}
                            >
                                Register
                            </button>
                        </Link>

                        <Link to="">
                            <button className="btn btn-outline-danger">
                                Logout
                            </button>
                        </Link>
                    </div>
                </div>
            </div>
        </nav>
    );
};

export default Navbar;
