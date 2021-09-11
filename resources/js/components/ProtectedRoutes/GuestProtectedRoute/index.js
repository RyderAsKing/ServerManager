import React from "react";
import { Redirect, Route } from "react-router-dom";

const GuestProtectedRoute = ({ component: Component, ...restOfProps }) => {
    const isLoggedIn = restOfProps.isLoggedIn;
    const setIsLoggedIn = restOfProps.setIsLoggedIn;

    if (isLoggedIn == null) {
        return <>Loading...</>;
    }
    return (
        <Route
            {...restOfProps}
            render={(props) =>
                !isLoggedIn ? (
                    <Component {...props} setIsLoggedIn={setIsLoggedIn} />
                ) : (
                    <Redirect to="/login" />
                )
            }
        />
    );
};

export default GuestProtectedRoute;
