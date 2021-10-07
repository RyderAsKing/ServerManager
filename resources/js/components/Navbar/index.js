import React, { useState, useEffect } from "react";
import { Link, useHistory } from "react-router-dom";
import { toast } from "react-toastify";
const Navbar = (props) => {
    const history = useHistory();
    const [authButtons, setAuthButtons] = useState(null);

    useEffect(() => {
        setupButtons();
    }, [props.isLoggedIn]);

    const logout = () => {
        localStorage.removeItem("api_token");
        localStorage.removeItem("name");
        localStorage.removeItem("email");
        toast.success(`Logged out successfully`, {
            position: "bottom-right",
            autoClose: 5000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true,
            progress: undefined,
        });
        props.setIsLoggedIn(false);
        history.push("/");
    };
    const setupButtons = () => {
        if (props.isLoggedIn == true) {
            setAuthButtons(
                <>
                    <button className="btn btn-outline-danger" onClick={logout}>
                        Logout
                    </button>
                </>
            );
        } else {
            setAuthButtons(
                <>
                    <Link to="/login">
                        <button className="btn btn-outline-primary">
                            Login
                        </button>
                    </Link>
                    <Link to="/register">
                        <button
                            className="btn btn-outline-primary"
                            style={{ marginLeft: "5px" }}
                        >
                            Register
                        </button>
                    </Link>
                </>
            );
        }
    };

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
                    data-bs-target="#mainNav"
                    aria-controls="mainNav"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span className="navbar-toggler-icon"></span>
                </button>
                <div className="collapse navbar-collapse" id="mainNav">
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
                        <li className="nav-item">
                            <Link className="nav-link" to="/dashboard/users">
                                List sub-users
                            </Link>
                        </li>
                    </ul>
                    <div className="ml-auto">{authButtons}</div>
                </div>
            </div>
        </nav>
    );
};

export default Navbar;
