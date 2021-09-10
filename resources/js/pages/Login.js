import axios from "axios";
import React, { useState } from "react";
import { useHistory } from "react-router-dom";
import { toast } from "react-toastify";
const Login = () => {
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
    console.log(loginInput);

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
        setLoginInput({ ...loginInput, errorMessage: "", errorList: [] });
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
        setLoading();
        e.preventDefault();

        const data = {
            email: loginInput.email,
            password: loginInput.password,
        };

        axios.post(`/api/user/login`, data).then((res) => {
            if (res.data.status == 200) {
                localStorage.setItem("api_token", res.data.api_token);
                localStorage.setItem("name", res.data.name);
                localStorage.setItem("email", res.data.email);
                toast.success(res.data.message, {
                    position: "bottom-right",
                    autoClose: 5000,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: true,
                    progress: undefined,
                });
                history.push("/dashboard");
            } else {
                var tempErrorMessage = "";
                var tempErrorList = [];
                if (res.data.error_message != null) {
                    tempErrorMessage = res.data.error_message;
                    toast.error(res.data.error_message, {
                        position: "bottom-right",
                        autoClose: 5000,
                        hideProgressBar: false,
                        closeOnClick: true,
                        pauseOnHover: true,
                        draggable: true,
                        progress: undefined,
                    });
                }
                if (res.data.validation_errors != null) {
                    tempErrorList = res.data.validation_errors;
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
            <div className="container text-white">
                <h3>Login</h3>
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
            </div>
        </>
    );
};

export default Login;
