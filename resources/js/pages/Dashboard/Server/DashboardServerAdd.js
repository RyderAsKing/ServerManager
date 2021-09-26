import React, { useState, useEffect } from "react";
import { useHistory } from "react-router-dom";
import { toast } from "react-toastify";
import {
    AddServer,
    ListAllApis,
    ListServersFromApi,
} from "../../../plugins/ApiCalls";
import PageLayout from "./../../../components/PageLayout/";
import BorderCard from "./../../../components/Cards/BorderCard";

const DashboardServerAdd = (props) => {
    const history = useHistory();
    const [apis, setApis] = useState(null);
    const [importList, setImportList] = useState({
        api_id: null,
        list: {},
        buttonLoading: false,
    });
    const [loading, setIsLoading] = useState(false);

    useEffect(() => {
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

    const getServerList = (api_id) => {
        setIsLoading(true);
        var response = ListServersFromApi(api_id);
        response.then((response) => {
            console.log(response);
            if (response.error != null) {
                setImportList({
                    api_id: api_id,
                    list: {},
                    buttonLoading: false,
                });
            } else {
                setImportList({
                    api_id: api_id,
                    list: response.data,
                    buttonLoading: false,
                });
            }
            setIsLoading(false);
        });
    };

    useEffect(() => {
        if (isNaN(parseInt(serverInput.api_id))) return;
        if (importList.api_id == serverInput.api_id) return;
        getServerList(serverInput.api_id);
    }, [serverInput.api_id]);

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
        setServerInput({
            ...serverInput,
            [e.target.name]: e.target.value,
        });
    };

    const handleImport = (e) => {
        setImportList({ ...importList, buttonLoading: true });
        var response = AddServer(
            e.target.dataset.server_id,
            e.target.dataset.api_id
        );
        response.then((response) => {
            getServerList(e.target.dataset.api_id);
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
            } else {
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
            }
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
                setServer();
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
                <hr />
                <div className="row">
                    <h4 style={{ textAlign: "center" }}>Import List</h4>
                    {importList != null && importList.list.length > 0 ? (
                        importList.list.map((value) => (
                            <div className="col-12 col-lg-6" key={value.uuid}>
                                <BorderCard>
                                    <div className="card-body">
                                        <h5 className="card-title">
                                            {value.identifier} -{" "}
                                            <code>{value.name}</code>
                                        </h5>
                                        <p className="card-text">
                                            {value.uuid}
                                        </p>
                                        <p className="card-text">
                                            Type:{" "}
                                            {value.server_type == 0
                                                ? "Virtualizor"
                                                : "Pterodactyl"}
                                            <br />
                                        </p>
                                        {importList.buttonLoading == false ? (
                                            value.imported == true ? (
                                                <button
                                                    className="btn btn-primary text-white"
                                                    disabled={value.imported}
                                                >
                                                    <i className="fas fa-download"></i>{" "}
                                                    Imported
                                                </button>
                                            ) : (
                                                <button
                                                    className="btn btn-primary text-white"
                                                    disabled={value.imported}
                                                    data-server_id={
                                                        value.identifier
                                                    }
                                                    data-api_id={
                                                        importList.api_id
                                                    }
                                                    onClick={handleImport}
                                                >
                                                    <i className="fas fa-download"></i>{" "}
                                                    Import server
                                                </button>
                                            )
                                        ) : (
                                            <>
                                                <button
                                                    type="submit"
                                                    className="btn btn-primary text-white"
                                                    disabled
                                                >
                                                    <span className="spinner-border"></span>
                                                </button>
                                            </>
                                        )}
                                    </div>
                                </BorderCard>
                            </div>
                        ))
                    ) : loading == true ? (
                        <div className="container spinner-border"></div>
                    ) : (
                        <p style={{ textAlign: "center" }}>
                            Select a API to get its server list
                        </p>
                    )}
                </div>
            </PageLayout>
        </>
    );
};

export default DashboardServerAdd;
