import React, { useState, useEffect } from "react";
import { Link, useHistory } from "react-router-dom";
import { Paginator } from "react-paginator-responsive";
import { ListServers } from "../../../plugins/ApiCalls";
import PageLayout from "./../../../components/PageLayout/";
import PowerButtons from "./../../../components/PowerButtons/";
import MessageDiv from "./../../../components/MessageDiv/";
import BorderCard from "./../../../components/Cards/BorderCard";
import { ErrorNotification } from "../../../plugins/Notification";

const DashboardServer = () => {
    const history = useHistory();
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
            if (response.error == true) {
                history.goBack();
                ErrorNotification(response.error_message);
            } else {
                setPaginatorValues({
                    itemsPerPage: response.per_page,
                    totalPage: response.last_page,
                    totalItems: response.total,
                    items: response.data,
                });
                setLoading(false);
            }
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
                <BorderCard>
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

                        <PowerButtons id={value.id}></PowerButtons>

                        <Link
                            to={`/dashboard/server/${value.id}`}
                            className="btn btn-primary"
                            style={{ marginLeft: "2px" }}
                        >
                            <i className="fas fa-external-link-alt text-white"></i>
                        </Link>
                    </div>
                </BorderCard>
            </div>
        ));
        listEnding = (
            <div className="col-12 col-lg-6">
                <BorderCard>
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
                </BorderCard>
            </div>
        );
    } else {
        items = (
            <MessageDiv
                name="Seems like you have no servers, how about adding one?"
                text="Add new servers to our database so that you can perform
                    actions on them."
                buttonText="Add servers"
                buttonUrl="/dashboard/server/add"
            ></MessageDiv>
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
