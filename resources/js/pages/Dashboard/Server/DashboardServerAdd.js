import React, { useState, useEffect } from "react";
import { toast } from "react-toastify";
import {
    AddServer,
    ListAllApis,
    ListServersFromApi,
} from "../../../plugins/ApiCalls";
import PageLayout from "./../../../components/PageLayout/";
import BorderCard from "./../../../components/Cards/BorderCard";

const DashboardServerAdd = () => {
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

    const [serverInput, setServerInput] = useState({
        api_id: "",
    });

    const getServerList = (api_id) => {
        setIsLoading(true);
        var response = ListServersFromApi(api_id);
        response.then((response) => {
            if (response.error != null) {
                toast.error(response.error_message, {
                    position: "bottom-right",
                    autoClose: 5000,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: true,
                    progress: undefined,
                });
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
                <form>
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
                    </div>
                </form>
                <hr />
                <div className="row">
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
                                            UUID - <code>{value.uuid}</code>
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
