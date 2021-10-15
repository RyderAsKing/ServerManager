import React, { useState, useEffect } from "react";
import { useHistory } from "react-router-dom";
import { ListAllServers, RegisterAccount } from "../../../plugins/ApiCalls";
import {
    SuccessNotification,
    ErrorNotification,
} from "../../../plugins/Notification";

import PageLayout from "../../../components/PageLayout";

const DashboardUsersAdd = () => {
    const history = useHistory();
    const [servers, setServers] = useState(null);

    useEffect(() => {
        if (servers === null) {
            ListAllServers().then((response) => {
                setServers(response);
            });
        }
    }, [servers]);

    const [submitButton, setSubmitButton] = useState(
        <button
            type="submit"
            className="btn btn-primary text-white"
            style={{ marginTop: "10px" }}
        >
            Create subuser
        </button>
    );
    const [registerInput, setRegisterInput] = useState({
        name: "",
        email: "",
        password: "",
        servers: [],
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
                Create Subuser
            </button>
        );
    };

    const handleInput = (e) => {
        setRegisterInput({
            ...registerInput,
            [e.target.name]: e.target.value,
        });
    };

    const handleServersInput = (e) => {
        var options = e.target.options;
        var value = [];
        for (var i = 0, l = options.length; i < l; i++) {
            if (options[i].selected) {
                value.push(options[i].value);
            }
        }
        setRegisterInput({
            ...registerInput,
            [e.target.name]: value,
        });
    };

    const registerSubmit = (e) => {
        setLoading();
        e.preventDefault();

        var response = RegisterAccount(
            registerInput.name,
            registerInput.email,
            registerInput.password,
            true,
            registerInput.servers
        );
        response.then((res) => {
            if (res.status == 200) {
                SuccessNotification(res.message);
                history.push("/dashboard/users");
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

    var options;

    if (servers != null && servers.length > 0) {
        options = servers.map((server) => {
            return (
                <option value={server.id} key={server.id}>
                    {server.server_id} | {server.hostname} |{" "}
                    {server.server_type == 0 ? (
                        "Virtualizor"
                    ) : server.server_type == 1 ? (
                        "Pterodactyl"
                    ) : (
                        <></>
                    )}
                </option>
            );
        });
    }

    return (
        <>
            <PageLayout
                name="Manage Sub-users"
                text="Create more subusers to give them access to your resources."
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
                    <div className="mb-3">
                        <label htmlFor="servers" className="form-label">
                            Servers to give access to
                        </label>
                        <select
                            onChange={handleServersInput}
                            className="form-select"
                            multiple
                            aria-label="multiple select example"
                            defaultValue={[]}
                            id="servers"
                            name="servers"
                        >
                            {options}
                        </select>
                    </div>
                    <div style={{ color: "red" }}>
                        {registerInput.errorList.servers}
                    </div>
                    {submitButton}
                </form>
            </PageLayout>
        </>
    );
};

export default DashboardUsersAdd;
