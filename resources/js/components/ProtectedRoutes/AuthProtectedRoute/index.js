import React from "react";
import { Redirect, Route } from "react-router-dom";

const AuthProtectedRoute = ({ component: Component, ...restOfProps }) => {
    const isLoggedIn = restOfProps.isLoggedIn;
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