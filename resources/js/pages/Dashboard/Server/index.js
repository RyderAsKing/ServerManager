import React, { useState, useEffect } from "react";
import axios from "axios";
import { Paginator } from "react-paginator-responsive";

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
        axios.get(`/api/server/?page=${pageNumber}`).then(function (response) {
            setPaginatorValues({
                itemsPerPage: response.data.per_page,
                totalPage: response.data.last_page,
                totalItems: response.data.total,
                items: response.data.data,
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
    const styles = {
        hideBackNextButtonText: false,
        backAndNextTextButtonColor: "white",
        paginatorButtonColor: "#3d3c3c",
        paginatorButtonBackgroundColor: "#fff",
        paginatorButtonSelectedColor: "#fff",
        paginatorButtonHoverColor: "#F0F8FF",
        lateralMargin: "0 2rem",
    };

    var items;
    var listEnding;
    var paginator;
    if (paginatorValues.itemsPerPage == 0) {
        if (loading == false) {
            setLoading(true);
        }
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
                        <a href="" className="btn btn-success">
                            <i className="fas fa-play text-white"></i>
                        </a>
                        <a
                            href=""
                            className="btn btn-danger"
                            style={{ marginLeft: "2px" }}
                        >
                            <i className="fas fa-stop text-white"></i>
                        </a>
                        <a
                            href=""
                            className="btn btn-warning"
                            style={{ marginLeft: "2px" }}
                        >
                            <i className="fas fa-redo text-black"></i>
                        </a>
                        <a
                            href=""
                            className="btn btn-danger"
                            style={{ marginLeft: "2px" }}
                        >
                            <i className="fas fa-power-off text-white"></i>
                        </a>
                        <a
                            href=""
                            className="btn btn-primary"
                            style={{ marginLeft: "2px" }}
                        >
                            <i className="fas fa-external-link-alt text-white"></i>
                        </a>
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
                        <a href="">
                            <button
                                className="btn btn-outline-light"
                                type="button"
                            >
                                Add servers
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        );
        paginator = (
            <div className="col-12 col-lg-12">
                <div style={{ float: "right" }}>
                    <Paginator
                        page={pageNumber}
                        pageSize={paginatorValues.itemsPerPage}
                        totalItems={paginatorValues.totalItems}
                        items={paginatorValues.items}
                        callback={handlePageChange}
                        styles={styles}
                    />
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
                <a href="">
                    <button className="btn btn-outline-light" type="button">
                        Add servers
                    </button>
                </a>
            </div>
        );
    }

    return (
        <>
            <div className="container text-white">
                <h3 className="text-center">Manage Servers</h3>
                <p className="text-center">
                    Perform powerful one click actions on servers with ease
                </p>

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
                        {paginator}{" "}
                    </>
                )}

                {/* @else
                <div
                    className="p-5 text-white bg-dark rounded-3"
                    style={{ textAlign: "center", marginTop: "10%" }}
                >
                    <h4>
                        Seems like you have no servers, how about adding one?
                    </h4>
                    <p>
                        Add new servers to our database so that you can perform
                        actions on them.
                    </p>
                    <a href="">
                        <button className="btn btn-outline-light" type="button">
                            Add servers
                        </button>
                    </a>
                </div>
                @endif */}
            </div>
        </>
    );
};

export default DashboardServer;
