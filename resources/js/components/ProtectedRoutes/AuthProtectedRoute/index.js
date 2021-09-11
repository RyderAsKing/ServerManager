import React from "react";
import { Redirect, Route } from "react-router-dom";
const AuthProtectedRoute = ({ component: Component, ...restOfProps }) => {
    var isLoggedIn = restOfProps.isLoggedIn;
    var updateApiToken = restOfProps.updateApiToken;
    if (isLoggedIn == null) {
        return <>Loading...</>;
    }
    if (isLoggedIn == true) {
        updateApiToken(localStorage.getItem("api_token"));
    }

    return (
        <Route
            {...restOfProps}
            render={(props) =>
                isLoggedIn ? <Component {...props} /> : <Redirect to="/login" />
            }
        />
    );
};

export default AuthProtectedRoute;
