import React, { useState, useEffect } from "react";
import { BrowserRouter as Router, Route, Redirect } from "react-router-dom";
import axios from "axios";

// Routes
import { BasicRoutes, GuestRoutes, AuthRoutes } from "./Routes";

// Custom Componenets
import CustomSwitch from "./components/CustomSwitch/";
import Navbar from "./components/Navbar";
import Footer from "./components/Footer/";
import { ToastContainer } from "react-toastify";

// Protected Route Componenets
import AuthProtectedRoute from "./components/ProtectedRoutes/AuthProtectedRoute/index";
import GuestProtectedRoute from "./components/ProtectedRoutes/GuestProtectedRoute/index";

axios.defaults.headers.post["Content-Type"] = "application/json";
axios.defaults.headers.post["Accept"] = "application/json";

const Main = () => {
    const [isLoggedIn, setIsLoggedIn] = useState(null);
    const updateApiToken = () => {
        axios.defaults.headers.common = {
            Authorization: `Bearer ${localStorage.getItem("api_token")}`,
        };
    };
    useEffect(() => {
        if (isLoggedIn == null) {
            if (localStorage.getItem("api_token")) {
                setIsLoggedIn(true);
            } else {
                setIsLoggedIn(false);
            }
        }
    });

    useEffect(() => {
        if (isLoggedIn == null) return;
        updateApiToken();
    }, [isLoggedIn]);

    const basicRoutes = BasicRoutes.map(({ path, Component, exact }, index) => (
        <Route
            exact={exact}
            path={path}
            component={Component}
            key={index}
            name={name}
        />
    ));

    const guestRoutes = GuestRoutes.map(
        ({ path, Component, exact, name }, index) => (
            <GuestProtectedRoute
                exact={exact}
                path={path}
                key={index}
                component={Component}
                isLoggedIn={isLoggedIn}
                setIsLoggedIn={setIsLoggedIn}
                name={name}
            ></GuestProtectedRoute>
        )
    );

    const authRoutes = AuthRoutes.map(
        ({ path, Component, exact, name }, index) => (
            <AuthProtectedRoute
                exact={exact}
                path={path}
                key={index}
                component={Component}
                isLoggedIn={isLoggedIn}
                updateApiToken={updateApiToken}
                name={name}
            ></AuthProtectedRoute>
        )
    );

    return (
        <>
            <Router>
                <Navbar
                    isLoggedIn={isLoggedIn}
                    setIsLoggedIn={setIsLoggedIn}
                ></Navbar>
                <ToastContainer></ToastContainer>
                <CustomSwitch>
                    {basicRoutes}
                    {guestRoutes}
                    {authRoutes}
                </CustomSwitch>
                <Footer></Footer>
            </Router>
        </>
    );
};

export default Main;
