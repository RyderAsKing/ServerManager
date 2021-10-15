import React, { useState, useEffect } from "react";
import { useHistory } from "react-router-dom";
import {
    AddServer,
    ListAllApis,
    ListServersFromApi,
} from "../../../plugins/ApiCalls";
import PageLayout from "./../../../components/PageLayout/";
import BorderCard from "./../../../components/Cards/BorderCard";
import {
    ErrorNotification,
    SuccessNotification,
} from "../../../plugins/Notification";
import ImportServer from "../../../components/ImportServer";

const DashboardServerAdd = () => {
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
                if (response.error == true) {
                    history.goBack();
                    ErrorNotification(response.error_message);
                } else {
                    setApis(response);
                }
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
                ErrorNotification(response.error_message);
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
                SuccessNotification(response.message);
            } else {
                if (response.error_message != null) {
                    ErrorNotification(response.error_message);
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
                            <ImportServer
                                key={value.uuid}
                                uuid={value.uuid}
                                identifier={value.identifier}
                                name={value.name}
                                imported={value.imported}
                                api_id={importList.api_id}
                            ></ImportServer>
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
