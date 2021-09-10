import React from "react";
import { Link } from "react-router-dom";
const Home = () => {
    return (
        <div className="container text-center text-white">
            <div className="px-3" style={{ marginTop: "20%" }}>
                <h1>Server manager.</h1>
                <p className="lead">
                    Control your Virtual Private Server with ease, perform one
                    click actions on your virtual private server.
                </p>
                <p className="lead">
                    <Link
                        to="/dadshboard"
                        className="btn btn-lg btn-secondary fw-bold border-white bg-white"
                        style={{ color: "black" }}
                    >
                        Dashboard
                    </Link>
                </p>
            </div>
        </div>
    );
};

export default Home;
