import React, { useState, useEffect } from "react";
import { Link, useHistory } from "react-router-dom";
import { toast } from "react-toastify";
import { Paginator } from "react-paginator-responsive";
import { ListApis, DestroyApi } from "../../../plugins/ApiCalls";
import PageLayout from "./../../../components/PageLayout/";
import MessageDiv from "./../../../components/MessageDiv/";
import BorderCard from "./../../../components/Cards/BorderCard";
import { ErrorNotification } from "../../../plugins/Notification";

const DashboardApi = () => {
    const history = useHistory();
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
        getApis(pageNumber);
    }, [pageNumber]);

    const handlePageChange = (newPage) => {
        if (newPage == pageNumber) {
            return;
        }
        setPageNumber(newPage);
    };

    const handleDeleteAction = (e) => {
        setLoading(true);
        const deleteNotification = toast.loading("Deleting API", {
            position: "bottom-right",
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true,
            progress: undefined,
        });

        var response = DestroyApi(e.target.dataset.db_id);
        response.then((response) => {
            if (response.status != 200) {
                toast.update(deleteNotification, {
                    render: response.error_message,
                    type: "error",
                    isLoading: false,
                    autoClose: 5000,
                });
            } else {
                toast.update(deleteNotification, {
                    render: response.message,
                    type: "success",
                    isLoading: false,
                    autoClose: 5000,
                });
            }
            getApis(pageNumber);
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
                <div style={{ float: "right", marginBottom: "10px" }}>
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
                        <button
                            className="btn btn-outline-danger"
                            type="button"
                            data-db_id={value.id}
                            onClick={handleDeleteAction}
                        >
                            Delete
                        </button>
                    </div>
                </BorderCard>
            </div>
        ));
        listEnding = (
            <div className="col-12 col-lg-6">
                <BorderCard>
                    <div className="card-body">
                        <h5 className="card-title">Add more API's?</h5>
                        <p className="card-text">
                            Add new API's to our database so that you can add
                            servers and then perform actions on them.
                        </p>
                        <Link to="/dashboard/api/add">
                            <button
                                className="btn btn-outline-light"
                                type="button"
                            >
                                Add API
                            </button>
                        </Link>
                    </div>
                </BorderCard>
            </div>
        );
    } else {
        items = (
            <MessageDiv
                name="Seems like you have no API, how about adding one?"
                text="Add new API's to our database so that you can add servers
                    and then perform actions on them."
                buttonText="Add API"
                buttonUrl="/dashboard/api/add"
            ></MessageDiv>
        );
    }

    return (
        <>
            <PageLayout
                name="Manage APIs"
                text="Add API's to our database so that you can add servers and
                    then perform actions on them"
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

export default DashboardApi;
