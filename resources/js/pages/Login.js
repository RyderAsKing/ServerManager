import React, { useState } from "react";
import { useHistory } from "react-router-dom";
import { ExchangeToken } from "../plugins/ApiCalls";
import {
    ErrorNotification,
    SuccessNotification,
} from "../plugins/Notification";
import PageLayout from "./../components/PageLayout/";

const Login = (props) => {
    const history = useHistory();
    const [submitButton, setSubmitButton] = useState(
        <button
            type="submit"
            className="btn btn-primary text-white"
            style={{ marginTop: "10px" }}
        >
            Login
        </button>
    );
    const [loginInput, setLoginInput] = useState({
        email: "",
        password: "",
        errorMessage: "",
        errorList: [],
    });

    const resetErrors = () => {
        setLoginInput({ ...loginInput, errorMessage: "", errorList: [] });
    };
    const setLoading = () => {
        setSubmitButton(
            <>
                <button
                    type="submit"
                    className="btn btn-primary text-white"
                    style={{ marginTop: "10px" }}
                    disabled
                >
                    <span className="spinner-border"></span>
                </button>
            </>
        );
    };
    const setLogin = () => {
        setSubmitButton(
            <button
                type="submit"
                className="btn btn-primary text-white"
                style={{ marginTop: "10px" }}
            >
                Login
            </button>
        );
    };

    const handleInput = (e) => {
        setLoginInput({
            ...loginInput,
            [e.target.name]: e.target.value,
        });
    };

    const loginSubmit = (e) => {
        resetErrors();
        setLoading();
        e.preventDefault();

        const data = {
            email: loginInput.email,
            password: loginInput.password,
        };
        var response = ExchangeToken(data.email, data.password);
        response.then((response) => {
            if (response.status == 200) {
                localStorage.setItem("api_token", response.api_token);
                localStorage.setItem("name", response.name);
                localStorage.setItem("email", response.email);
                SuccessNotification(response.message);
                props.setIsLoggedIn(true);
                history.push("/dashboard");
            } else {
                var tempErrorMessage = "";
                var tempErrorList = [];
                if (response.error_message != null) {
                    tempErrorMessage = response.error_message;
                    ErrorNotification(response.error_message);
                }
                if (response.validation_errors != null) {
                    tempErrorList = response.validation_errors;
                }
                setLoginInput({
                    ...loginInput,
                    errorMessage: tempErrorMessage,
                    errorList: tempErrorList,
                });
                setLogin();
            }
        });
    };

    return (
        <>
            <PageLayout
                name="Login"
                text="Log into your account on the server manager to manage your servers"
            >
                <form onSubmit={loginSubmit}>
                    <div className="mb-3">
                        <label htmlFor="email" className="form-label">
                            Email address
                        </label>
                        <input
                            type="email"
                            className="form-control"
                            id="email"
                            name="email"
                            onChange={handleInput}
                            value={loginInput.email}
                            placeholder="Enter your email address"
                        />
                        <div id="emailHelp" className="form-text">
                            We'll never share your email with anyone else.
                        </div>
                        <div style={{ color: "red" }}>
                            {loginInput.errorList.email}
                        </div>
                    </div>
                    <div className="mb-3">
                        <label htmlFor="password" className="form-label">
                            Password
                        </label>
                        <input
                            type="password"
                            className="form-control"
                            id="password"
                            name="password"
                            onChange={handleInput}
                            value={loginInput.password}
                            placeholder="Enter your password"
                        />
                        <div style={{ color: "red" }}>
                            {loginInput.errorList.password}
                        </div>
                    </div>
                    {submitButton}
                </form>
            </PageLayout>
        </>
    );
};

export default Login;
