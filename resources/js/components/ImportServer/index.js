import React, { useState } from "react";
import { AddServer } from "../../plugins/ApiCalls";
import {
    ErrorNotification,
    SuccessNotification,
} from "../../plugins/Notification";
import BorderCard from "../Cards/BorderCard";

const ImportServer = (props) => {
    const [loading, setLoading] = useState(false);
    const [imported, setImported] = useState(props.imported);
    const handleImport = (e) => {
        setLoading(true);
        var response = AddServer(
            e.target.dataset.server_id,
            e.target.dataset.api_id
        );
        response.then((response) => {
            if (response.status == 200) {
                SuccessNotification(response.message);
                setImported(true);
                setLoading(false);
            } else {
                if (response.error_message != null) {
                    ErrorNotification(response.error_message);
                    setLoading(false);
                }
            }
        });
    };

    return (
        <>
            <div className="col-12 col-lg-6" key={props.uuid}>
                <BorderCard>
                    <div className="card-body">
                        <h5 className="card-title">
                            {props.identifier} - <code>{props.name}</code>
                        </h5>
                        <p className="card-text">
                            UUID - <code>{props.uuid}</code>
                        </p>
                        {loading == false ? (
                            imported == true ? (
                                <button
                                    className="btn btn-primary text-white"
                                    disabled={imported}
                                >
                                    <i className="fas fa-download"></i> Imported
                                </button>
                            ) : (
                                <button
                                    className="btn btn-primary text-white"
                                    disabled={imported}
                                    data-server_id={props.identifier}
                                    data-api_id={props.api_id}
                                    onClick={handleImport}
                                >
                                    <i className="fas fa-download"></i> Import
                                    server
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
        </>
    );
};

export default ImportServer;
