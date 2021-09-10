import React from "react";

const Register = () => {
    return (
        <>
            <div className="container text-white">
                <h3>Register</h3>
                <form method="post" action="">
                    <div
                        className="alert alert-danger"
                        role="alert"
                        style={{ marginTop: "5px" }}
                    >
                        if error
                    </div>
                    <div className="mb-3">
                        <label htmlFor="name" className="form-label">
                            Name
                        </label>
                        <input
                            type="text"
                            className="form-control"
                            id="name"
                            name="name"
                            placeholder="Enter your name"
                        />
                    </div>
                    <div style={{ color: "red" }}></div>
                    <div className="mb-3">
                        <label htmlFor="email" className="form-label">
                            Email address
                        </label>
                        <input
                            type="email"
                            className="form-control"
                            id="email"
                            name="email"
                            placeholder="Enter your email address"
                        />
                        <div id="emailHelp" className="form-text">
                            We'll never share your email with anyone else.
                        </div>
                    </div>
                    <div style={{ color: "red" }}></div>
                    <div className="mb-3">
                        <label htmlFor="password" className="form-label">
                            Password
                        </label>
                        <input
                            type="password"
                            className="form-control"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                        />
                    </div>
                    <div className="mb-3">
                        <label
                            htmlFor="confirmpassword_confirmation_password"
                            className="form-label"
                        >
                            Confirm Password
                        </label>
                        <input
                            type="password"
                            className="form-control"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Enter your password again"
                        />
                    </div>
                    <div style={{ color: "red" }}></div>
                    <button
                        type="submit"
                        className="btn btn-primary"
                        style={{ marginTop: "10px" }}
                    >
                        Register
                    </button>
                </form>
            </div>
        </>
    );
};

export default Register;
