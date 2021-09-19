import React, { useState, useEffect } from "react";
import { useHistory } from "react-router-dom";
import { toast } from "react-toastify";
import { AddServer, ListAllApis } from "../../../plugins/ApiCalls";
import PageLayout from "./../../../components/PageLayout/index";

const DashboardServerAdd = (props) => {
    const history = useHistory();
    const [apis, setApis] = useState(null);

    useEffect(() => {
        console.log(apis);
        if (apis === null) {
            ListAllApis().then((response) => {
                setApis(response);
            });
        }
    }, [apis]);

    const [submitButton, setSubmitButton] = useState(
        <button
            type="submit"
            className="btn btn-primary text-white"
            style={{ marginTop: "10px" }}
        >
            Add Server
        </button>
    );
    const [serverInput, setServerInput] = useState({
        server_id: "",
        api_id: "",
        errorMessage: "",
        errorList: [],
    });

    const resetErrors = () => {
        setServerInput({ ...serverInput, errorMessage: "", errorList: [] });
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

    const setServer = () => {
        setSubmitButton(
            <button
                type="submit"
                className="btn btn-primary text-white"
                style={{ marginTop: "10px" }}
            >
                Add Server
            </button>
        );
    };

    const handleInput = (e) => {
        console.log(e.target.value);
        setServerInput({
            ...serverInput,
            [e.target.name]: e.target.value,
        });
    };

    const serverSubmit = (e) => {
        resetErrors();
        setLoading();
        e.preventDefault();

        const data = {
            server_id: serverInput.server_id,
            api_id: serverInput.api_id,
        };
        var response = AddServer(data.server_id, data.api_id);
        response.then((response) => {
            if (response.status == 200) {
                toast.success(response.message, {
                    position: "bottom-right",
                    autoClose: 5000,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: true,
                    progress: undefined,
                });
                history.push("/dashboard/server");
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
                setServerInput({
                    ...serverInput,
                    errorMessage: tempErrorMessage,
                    errorList: tempErrorList,
                });
                setSubmit();
            }
        });
    };

    var options;

    if (apis != null && apis.length > 0) {
        options = apis.map((api) => {
            return (
                <option value={api.id} key={api.id}>
                    {api.nick} | {api.api} |{" "}
                    {api.type == 0 ? (
                        "Virtualizor"
                    ) : api.type == 1 ? (
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
                name="Add Server"
                text="Add servers to the server manager and perform powerful on click actions on them"
            >
                <form onSubmit={serverSubmit}>
                    <div className="mb-3">
                        <label htmlFor="email" className="form-label">
                            Server ID
                        </label>
                        <input
                            type="text"
                            className="form-control"
                            id="server_id"
                            name="server_id"
                            onChange={handleInput}
                            value={serverInput.server_id}
                            placeholder="Enter your server ID"
                        />
                        <div style={{ color: "red" }}>
                            {serverInput.errorList.server_id}
                        </div>
                    </div>
                    <div className="mb-3">
                        <label htmlFor="api_id" className="form-label">
                            API
                        </label>
                        <select
                            className="form-select"
                            name="api_id"
                            defaultValue={0}
                            onChange={handleInput}
                        >
                            <option value="none">Select one</option>
                            {options}
                        </select>
                        <div style={{ color: "red" }}>
                            {serverInput.errorList.api_id}
                        </div>
                    </div>
                    {submitButton}
                </form>
            </PageLayout>
        </>
    );
};

export default DashboardServerAdd;
