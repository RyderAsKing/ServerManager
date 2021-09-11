import React, { useEffect, useState } from "react";
import { Switch, useLocation } from "react-router-dom";
import TopBarProgress from "react-topbar-progress-indicator";

TopBarProgress.config({
    barColors: {
        0: "#fff",
        "1.0": "#212529",
    },
    shadowBlur: 5,
});

const CustomSwitch = ({ children }) => {
    const [progress, setProgress] = useState(false);
    const [prevLoc, setPrevLoc] = useState("");
    const location = useLocation();

    useEffect(() => {
        setPrevLoc(location.pathname);
        setProgress(true);
        if (location.pathname === prevLoc) {
            setPrevLoc("");
        }
    }, [location]);

    useEffect(() => {
        setProgress(false);
    }, [prevLoc]);

    return (
        <>
            {progress && <TopBarProgress />}
            <Switch>{children}</Switch>
        </>
    );
};

export default CustomSwitch;
