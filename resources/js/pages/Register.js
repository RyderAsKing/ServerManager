import React, { useState } from "react";
import { useHistory } from "react-router-dom";
import { RegisterAccount } from "../plugins/ApiCalls";
import {
    ErrorNotification,
    SuccessNotification,
} from "../plugins/Notification";
import PageLayout from "./../components/PageLayout/";

const Register = (props) => {
    const history = useHistory();
    const [submitButton, setSubmitButton] = useState(
        <button
            type="submit"
            className="btn btn-primary text-white"
            style={{ marginTop: "10px" }}
        >
            Register
        </button>
    );
    const [registerInput, setRegisterInput] = useState({
        name: "",
        email: "",
        password: "",
        errorMessage: "",
        errorList: [],
    });

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
        setRegisterInput({ ...registerInput, errorMessage: "", errorList: [] });
    };
    const setRegister = () => {
        setSubmitButton(
            <button
                type="submit"
                className="btn btn-primary text-white"
                style={{ marginTop: "10px" }}
            >
                Register
            </button>
        );
    };

    const handleInput = (e) => {
        setRegisterInput({
            ...registerInput,
            [e.target.name]: e.target.value,
        });
    };

    const registerSubmit = (e) => {
        setLoading();
        e.preventDefault();

        var response = RegisterAccount(
            registerInput.name,
            registerInput.email,
            registerInput.password
        );
        response.then((res) => {
            if (res.status == 200) {
                localStorage.setItem("api_token", res.api_token);
                localStorage.setItem("name", res.name);
                localStorage.setItem("email", res.email);
                SuccessNotification(res.message);
                props.setIsLoggedIn(true);

                history.push("/dashboard");
            } else {
                var tempErrorMessage = "";
                var tempErrorList = [];
                if (res.error_message != null) {
                    tempErrorMessage = res.error_message;
                    ErrorNotification(res.error_message);
                }
                if (res.validation_errors != null) {
                    tempErrorList = res.validation_errors;
                }
                setRegisterInput({
                    ...registerInput,
                    errorMessage: tempErrorMessage,
                    errorList: tempErrorList,
                });
                setRegister();
            }
        });
    };
    return (
        <>
            <PageLayout
                name="Register"
                text="Register your account on the server manager to manage your servers"
            >
                <form onSubmit={registerSubmit}>
                    <div className="mb-3">
                        <label htmlFor="name" className="form-label">
                            Name
                        </label>
                        <input
                            type="text"
                            onChange={handleInput}
                            value={registerInput.name}
                            className="form-control"
                            id="name"
                            name="name"
                            placeholder="Enter your name"
                        />
                    </div>
                    <div style={{ color: "red" }}>
                        {registerInput.errorList.name}
                    </div>
                    <div className="mb-3">
                        <label htmlFor="email" className="form-label">
                            Email address
                        </label>
                        <input
                            type="email"
                            onChange={handleInput}
                            value={registerInput.email}
                            className="form-control"
                            id="email"
                            name="email"
                            placeholder="Enter your email address"
                        />
                        <div id="emailHelp" className="form-text">
                            We'll never share your email with anyone else.
                        </div>
                    </div>
                    <div style={{ color: "red" }}>
                        {registerInput.errorList.email}
                    </div>
                    <div className="mb-3">
                        <label htmlFor="password" className="form-label">
                            Password
                        </label>
                        <input
                            type="password"
                            onChange={handleInput}
                            value={registerInput.password}
                            className="form-control"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                        />
                    </div>
                    <div style={{ color: "red" }}>
                        {registerInput.errorList.password}
                    </div>
                    {submitButton}
                </form>
            </PageLayout>
        </>
    );
};

export default Register;
