import axios from "axios";
import React, { useState } from "react";
import { useHistory } from "react-router-dom";
import { toast } from "react-toastify";
import { CreateApi } from "../../../plugins/ApiCalls";
const DashboardApiAdd = (props) => {
    const history = useHistory();
    const [submitButton, setSubmitButton] = useState(
        <button
            type="submit"
            className="btn btn-primary text-white"
            style={{ marginTop: "10px" }}
        >
            Add API
        </button>
    );
    const [createInput, setCreateInput] = useState({
        type: 0,
        api: "",
        api_pass: "",
        name: "",
        hostname: "",
        protocol: 0,
        errorMessage: "",
        errorList: [],
    });
    const resetErrors = () => {
        setCreateInput({
            ...createInput,
            errorMessage: "",
            errorList: [],
        });
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

    const setCreate = () => {
        setSubmitButton(
            <button
                type="submit"
                className="btn btn-primary text-white"
                style={{ marginTop: "10px" }}
            >
                Add API
            </button>
        );
    };

    const handleInput = (e) => {
        setCreateInput({
            ...createInput,
            [e.target.name]: e.target.value,
        });
        console.log(createInput);
    };

    const createSubmit = (e) => {
        setLoading();
        resetErrors();
        e.preventDefault();

        const data = {
            type: createInput.type,
            api: createInput.api,
            api_pass: createInput.api_pass,
            name: createInput.name,
            hostname: createInput.hostname,
            protocol: createInput.protocol,
        };
        var response = CreateApi(
            data.type,
            data.api,
            data.api_pass,
            data.name,
            data.hostname,
            data.protocol
        );

        response.then((response) => {
            if (response.status === 200) {
                toast.success(response.message, {
                    position: "bottom-right",
                    autoClose: 5000,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: true,
                    progress: undefined,
                });
                history.push("/dashboard/api");
            } else {
                var tempErrorMessage = "";
                var tempErrorList = [];
                if (response.error_message != null) {
                    tempErrorMessage = response.error_message;
                    toast.error(response.error_message, {
                        position: "bottom-right",
                        autoClose: 5000,
                        hideProgressBar: false,
                        closeOnClick: true,
                        pauseOnHover: true,
                        draggable: true,
                        progress: undefined,
                    });
                }
                if (response.validation_errors != null) {
                    tempErrorList = response.validation_errors;
                }
                setCreateInput({
                    ...createInput,
                    errorMessage: tempErrorMessage,
                    errorList: tempErrorList,
                });
                setCreate();
            }
        });
    };

    return (
        <>
            <div className="container text-white">
                <h3>Login</h3>
                <form method="post" onSubmit={createSubmit}>
                    <div className="mb-3">
                        <label htmlFor="type" className="form-label">
                            API Type
                        </label>
                        <select
                            className="form-select"
                            name="type"
                            defaultValue={0}
                            onChange={handleInput}
                        >
                            <option value={0}>Virtualizor</option>
                            <option value={1}>Pterodactyl</option>
                        </select>
                    </div>
                    <div style={{ color: "red" }} />
                    <div className="mb-3">
                        <label htmlFor="name" className="form-label">
                            Name
                        </label>
                        <input
                            type="text"
                            className="form-control"
                            id="name"
                            name="name"
                            placeholder="Give your API a cute name"
                            onChange={handleInput}
                        />
                    </div>
                    <div style={{ color: "red" }}>
                        {createInput.errorList.name}
                    </div>
                    <div className="mb-3">
                        <label htmlFor="api" className="form-label">
                            API Key
                        </label>
                        <input
                            type="text"
                            className="form-control"
                            id="api"
                            name="api"
                            placeholder="Enter your API Key"
                            onChange={handleInput}
                        />
                    </div>
                    <div style={{ color: "red" }}>
                        {createInput.errorList.api}
                    </div>
                    <div className="mb-3">
                        <label htmlFor="api_pass" className="form-label">
                            API Pass
                        </label>
                        <input
                            type="text"
                            className="form-control"
                            id="api_pass"
                            name="api_pass"
                            placeholder="Enter your API Pass (if required)"
                            onChange={handleInput}
                        />
                    </div>
                    <div style={{ color: "red" }}>
                        {createInput.errorList.api_pass}
                    </div>
                    <div className="mb-3">
                        <label htmlFor="hostname" className="form-label">
                            Hostname or IP
                        </label>
                        <input
                            type="text"
                            className="form-control"
                            id="hostname"
                            name="hostname"
                            placeholder="Enter the API providers hostname or IP address"
                            onChange={handleInput}
                        />
                    </div>
                    <div style={{ color: "red" }}>
                        {createInput.errorList.hostname}
                    </div>
                    <div className="mb-3">
                        <label htmlFor="protocol" className="form-label">
                            Protocol
                        </label>
                        <select
                            className="form-select"
                            name="protocol"
                            defaultValue={0}
                            onChange={handleInput}
                        >
                            <option value={0}>HTTP</option>
                            <option value={1}>HTTPs</option>
                        </select>
                    </div>
                    <div style={{ color: "red" }}>
                        {createInput.errorList.protocol}
                    </div>
                    {submitButton}
                </form>
            </div>
        </>
    );
};

export default DashboardApiAdd;
