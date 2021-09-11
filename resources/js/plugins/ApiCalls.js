const ListServers = (pageNumber = 1) => {
    return axios.get(`/api/server/?page=${pageNumber}`).then((response) => {
        return response.data;
    });
};

const PowerActions = (db_id, action) => {
    if (
        action != "start" &&
        action != "stop" &&
        action != "restart" &&
        action != "kill"
    ) {
        return { error: true, error_message: "Invalid method" };
    } else {
        return axios
            .post(`/api/server/${db_id}/power`, {
                action: action,
            })
            .then((response) => {
                return response.data;
            });
    }
};

export { ListServers, PowerActions };
