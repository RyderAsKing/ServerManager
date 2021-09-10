import React, { useState, useEffect } from "react";
import { BrowserRouter as Router, Route, Link } from "react-router-dom";

// Componenets
import CustomSwitch from "./components/CustomSwitch/";
import Navbar from "./components/Navbar";
import { ToastContainer } from "react-toastify";

// Basic
import Home from "./pages/Home";
import Login from "./pages/Login";
import Register from "./pages/Register";

// Dashboard
import Dashboard from "./pages/Dashboard/";

// Dashboaard Server
import DashboardServer from "./pages/Dashboard/Server";
import DashboardServerAdd from "./pages/Dashboard/Server/DashboardServerAdd";
import DashboardServerCurrent from "./pages/Dashboard/Server/DashboardServerCurrent";

// Dashboard API
import DashboardApi from "./pages/Dashboard/Api";
import DashboardApiAdd from "./pages/Dashboard/Api/DashboardApiAdd";

import axios from "axios";

axios.defaults.headers.post["Content-Type"] = "application/json";
axios.defaults.headers.post["Accept"] = "application/json";

const Main = () => {
    const [isLoggedIn, setIsLoggedIn] = useState(false);
    const [apiToken, setApiToken] = useState(null);

    return (
        <>
            <Router>
                <Navbar
                    isLoggedIn={isLoggedIn}
                    setIsLoggedIn={setIsLoggedIn}
                ></Navbar>
                <ToastContainer></ToastContainer>
                <CustomSwitch>
                    {/* Basic */}
                    <Route path="/" component={Home} exact></Route>
                    <Route
                        path="/login"
                        component={() => (
                            <Login
                                isLoggedIn={isLoggedIn}
                                setIsLoggedIn={setIsLoggedIn}
                            ></Login>
                        )}
                        exact
                    ></Route>
                    <Route
                        path="/register"
                        component={() => (
                            <Register
                                isLoggedIn={isLoggedIn}
                                setIsLoggedIn={setIsLoggedIn}
                            ></Register>
                        )}
                        exact
                    ></Route>
                    {/* Dashboard */}
                    <Route
                        path="/dashboard"
                        component={Dashboard}
                        exact
                    ></Route>
                    ;
                    <Route
                        path="/dashboard/server"
                        component={DashboardServer}
                        exact
                    ></Route>
                    <Route
                        path="/dashboard/server/add"
                        component={DashboardServerAdd}
                        exact
                    ></Route>
                    <Route
                        path="/dashboard/server/:id"
                        component={DashboardServerCurrent}
                        exact
                    ></Route>
                    <Route
                        path="/dashboard/api"
                        component={DashboardApi}
                        exact
                    ></Route>
                    <Route
                        path="/dashboard/api/add"
                        component={DashboardApiAdd}
                        exact
                    ></Route>
                </CustomSwitch>
            </Router>
        </>
    );
};

export default Main;
