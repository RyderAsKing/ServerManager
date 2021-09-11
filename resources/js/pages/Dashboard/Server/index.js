import React from "react";
import axios from "axios";
const DashboardServer = () => {
    axios.get("/api/server", (res) => {
        console.log(res);
    });
    return <></>;
};

export default DashboardServer;
