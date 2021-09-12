import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import { toast } from "react-toastify";
import { Paginator } from "react-paginator-responsive";
import { ListApis, DestroyApi } from "../../../plugins/ApiCalls";
const DashboardApi = () => {
    const [pageNumber, setPageNumber] = useState(1);
    const [paginatorValues, setPaginatorValues] = useState({
        itemsPerPage: 0,
        totalPage: 0,
        totalItems: 0,
        items: [],
    });
    const [loading, setLoading] = useState(false);
    const getApis = (pageNumber) => {
        ListApis(pageNumber).then((response) => {
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
        getApis(pageNumber);
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
                <div
                    className="card bg-dark"
                    style={{ margin: "5px", border: "1px solid white" }}
                >
                    <div className="card-body">
                        <h5 className="card-title">Nickname: {value.nick}</h5>
                        <h5 className="card-title">
                            API: <code>{value.api}</code>
                        </h5>
                        <h5 className="card-title">
                            Hostname: <code>{value.hostname}</code>
                        </h5>
                        <p className="card-text">
                            Type:{" "}
                            {value.type == 0 ? "Virtualizor" : "Pterodactyl"}
                            <br /> Created {value.created_at}
                        </p>
                        <a href="">
                            <button
                                className="btn btn-outline-danger"
                                type="button"
                            >
                                Delete
                            </button>
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
                        <h5 className="card-title">Add more API's?</h5>
                        <p className="card-text">
                            Add new API's to our database so that you can add
                            servers and then perform actions on them.
                        </p>
                        <a href="">
                            <button
                                className="btn btn-outline-light"
                                type="button"
                            >
                                Add API
                            </button>
                        </a>
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
                <h4>Seems like you have no API, how about adding one?</h4>
                <p>
                    Add new API's to our database so that you can add servers
                    and then perform actions on them.
                </p>
                <Link to="">
                    <button className="btn btn-outline-light" type="button">
                        Add API
                    </button>
                </Link>
            </div>
        );
    }

    return (
        <>
            <div className="container text-white">
                <h3 className="text-center">Manage API's</h3>
                <p className="text-center">
                    Add API's to our database so that you can add servers and
                    then perform actions on them
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
                    </>
                )}
                {paginator}
            </div>
        </>
    );
};

export default DashboardApi;
