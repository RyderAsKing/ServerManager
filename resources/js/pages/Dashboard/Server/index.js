import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import { toast } from "react-toastify";
import { Paginator } from "react-paginator-responsive";
import { ListServers, PowerActions } from "../../../plugins/ApiCalls";
import PageLayout from "./../../../components/PageLayout/";
import PowerButtons from "./../../../components/PowerButtons/";

const DashboardServer = () => {
    const [pageNumber, setPageNumber] = useState(1);
    const [paginatorValues, setPaginatorValues] = useState({
        itemsPerPage: 0,
        totalPage: 0,
        totalItems: 0,
        items: [],
    });
    const [loading, setLoading] = useState(false);
    const getServers = (pageNumber) => {
        ListServers(pageNumber).then((response) => {
            setPaginatorValues({
                itemsPerPage: response.per_page,
                totalPage: response.last_page,
                totalItems: response.total,
                items: response.data,
            });
            setLoading(false);
        });
    };

    useEffect(() => {
        setLoading(true);
        getServers(pageNumber);
    }, [pageNumber]);

    const handlePageChange = (newPage) => {
        if (newPage == pageNumber) {
            return;
        }
        setPageNumber(newPage);
    };

    const handlePowerAction = (e) => {
        const powerNotification = toast.loading("Sending power action", {
            position: "bottom-right",
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true,
            progress: undefined,
        });

        var response = PowerActions(
            e.target.dataset.db_id,
            e.target.dataset.action
        );
        response.then((response) => {
            if (response.status != 200) {
                toast.update(powerNotification, {
                    render: response.error_message,
                    type: "error",
                    isLoading: false,
                    autoClose: 5000,
                });
            } else {
                toast.update(powerNotification, {
                    render: response.message,
                    type: "success",
                    isLoading: false,
                    autoClose: 5000,
                });
            }
        });
    };

    const styles = {
        hideBackNextButtonText: false,
        backAndNextTextButtonColor: "white",
        paginatorButtonColor: "#3d3c3c",
        paginatorButtonBackgroundColor: "#fff",
        paginatorButtonSelectedColor: "#fff",
        paginatorButtonHoverColor: "#F0F8FF",
        lateralMargin: "0 2rem",
        iconColor: "#fff",
    };

    var items;
    var listEnding;
    var paginator;
    if (paginatorValues.itemsPerPage == 0) {
        if (loading == false) {
            setLoading(true);
        }
    }
    if (paginatorValues.totalPage > 1) {
        paginator = (
            <div className="col-12 col-lg-12">
                <div style={{ float: "right" }}>
                    <Paginator
                        page={pageNumber}
                        pageSize={paginatorValues.itemsPerPage}
                        pageGroupSize={5}
                        totalItems={paginatorValues.totalItems}
                        items={paginatorValues.items}
                        callback={handlePageChange}
                        styles={styles}
                    />
                </div>
            </div>
        );
    }
    if (paginatorValues.items.length > 0) {
        items = paginatorValues.items.map((value) => (
            <div className="col-12 col-lg-6" key={value.id}>
                <div
                    className="card bg-dark"
                    style={{
                        margin: "5px",
                        border: "1px solid white",
                    }}
                >
                    <div className="card-body">
                        <h5 className="card-title">
                            {value.server_id} - <code>{value.hostname}</code>
                        </h5>
                        <p className="card-text">{value.ipv4}</p>
                        <p className="card-text">
                            Type:{" "}
                            {value.server_type == 0
                                ? "Virtualizor"
                                : "Pterodactyl"}
                            <br />
                        </p>

                        <PowerButtons
                            id={value.id}
                            handlePowerAction={handlePowerAction}
                        ></PowerButtons>

                        <Link
                            to={`/dashboard/server/${value.id}`}
                            className="btn btn-primary"
                            style={{ marginLeft: "2px" }}
                        >
                            <i className="fas fa-external-link-alt text-white"></i>
                        </Link>
                    </div>
                </div>
            </div>
        ));
        listEnding = (
            <div className="col-12 col-lg-6">
                <div
                    className="card bg-dark"
                    style={{ margin: "5px", border: "1px solid white" }}
                >
                    <div className="card-body">
                        <h5 className="card-title">Add more servers?</h5>
                        <p className="card-text">
                            Add new servers to our database so that you can
                            perform actions on them.
                        </p>
                        <Link to="/dashboard/server/add">
                            <button
                                className="btn btn-outline-light"
                                type="button"
                            >
                                Add servers
                            </button>
                        </Link>
                    </div>
                </div>
            </div>
        );
    } else {
        items = (
            <div
                className="p-5 text-white bg-dark rounded-3"
                style={{ textAlign: "center", marginTop: "10%" }}
            >
                <h4>Seems like you have no servers, how about adding one?</h4>
                <p>
                    Add new servers to our database so that you can perform
                    actions on them.
                </p>
                <Link to="/dashboard/server/add">
                    <button className="btn btn-outline-light" type="button">
                        Add servers
                    </button>
                </Link>
            </div>
        );
    }

    return (
        <>
            <PageLayout
                name="Manage Servers"
                text="Perform powerful one click actions on servers with ease"
            >
                {loading == true ? (
                    <>
                        <div className="text-center">
                            <div className="spinner-border" role="status">
                                <span className="sr-only">Loading...</span>
                            </div>
                        </div>
                    </>
                ) : (
                    <>
                        <div className="row">
                            {items}
                            {listEnding}
                        </div>
                    </>
                )}
                {paginator}
            </PageLayout>
        </>
    );
};

export default DashboardServer;
