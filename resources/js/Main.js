import React from "react";
import { BrowserRouter as Router, Route, Link } from "react-router-dom";
import CustomSwitch from "./components/CustomSwitch/";

// Basic
import Home from "./pages/Home";
import Login from "./pages/Home";
import Register from "./pages/Register";

// Dashboard
import Dashboard from "./pages/Dashboard/";

// Dashboaard Server
import DashboardServer from "./pages/Dashboard/Server";
import DashboardServerAdd from "./pages/Dashboard/Server/DashboardServerAdd";
import DashboardServerCurrent from "./pages/Dashboard/Server/DashboardServerCurrent";

// Dashboard API
import DashboardApi from "./pages/Dashboard/Api/";
import DashboardApiAdd from "./pages/Dashboard/Api/DashboardApiAdd";

const Main = () => {
    return (
        <>
            <Router>
                <CustomSwitch>
                    {/* Basic */}
                    <Route path="/" component={Home}></Route>
                    <Route path="/login" component={Login}></Route>
                    <Route path="/register" component={Register}></Route>
                    {/* Dashboard */}
                    <Route path="/dashboard" component={Dashboard}></Route>;
                    <Route
                        path="/dashboard/server"
                        component={DashboardServer}
                    ></Route>
                    <Route
                        path="/dashboard/server/add"
                        component={DashboardServerAdd}
                    ></Route>
                    <Route
                        path="/dashboard/server/:id"
                        component={DashboardServerCurrent}
                    ></Route>
                    <Route
                        path="/dashboard/api"
                        component={DashboardApi}
                    ></Route>
                    <Route
                        path="/dashboard/api/add"
                        component={DashboardApiAdd}
                    ></Route>
                </CustomSwitch>
            </Router>
        </>
    );
};

export default Main;
