import axios from "axios";
import React, { useState } from "react";
import { useHistory } from "react-router-dom";
import { toast } from "react-toastify";
const Register = () => {
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

        const data = {
            name: registerInput.name,
            email: registerInput.email,
            password: registerInput.password,
        };

        axios.post(`/api/user/register`, data).then((res) => {
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
                if (res.data.error_message != null) {
                    tempErrorMessage = res.data.error_message;
                }
                setRegisterInput({
                    ...registerInput,
                    errorList: res.data.validation_errors,
                    errorMessage: tempErrorMessage,
                });
                setRegister();
            }
        });
    };
    return (
        <>
            <div className="container">
                <h3>Register</h3>
                <form onSubmit={registerSubmit}>
                    {registerInput.errorMessage.length > 0 ? (
                        <div
                            className="alert alert-danger"
                            role="alert"
                            style={{ marginTop: "5px" }}
                        >
                            {registerInput.errorMessage}
                        </div>
                    ) : (
                        ""
                    )}

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
            </div>
        </>
    );
};

export default Register;
